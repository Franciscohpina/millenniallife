<?php

namespace App\Http\Controllers;

use App\Models\AdultingTip;
use App\Models\AnnualProcedure;
use App\Models\MedicalAppointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userId = $user ? $user->id : 1;

        // 1. Datos para el Carrusel dinámico desde BD
        $tips = AdultingTip::where('is_active', true)
            ->orderBy('order_priority', 'asc')
            ->get();

        // 2. Métricas rápidas (KPI cards)
        $totalGastadoMes = Payment::whereHas('service', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');

        $pagosPendientesMes = Payment::whereHas('service', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'pending')
            ->sum('amount');

        $tramitesPendientes = AnnualProcedure::where('user_id', $userId)
            ->where('status', '!=', 'completed')
            ->count();

        $citasProximas = MedicalAppointment::where('user_id', $userId)
            ->where('appointment_date', '>=', now())
            ->count();

        // 3. Datos para Gráfica 1: Gastos por Categoría (Dona)
        $gastosPorCategoria = DB::table('payments')
            ->join('services', 'payments.service_id', '=', 'services.id')
            ->join('categories', 'services.category_id', '=', 'categories.id')
            ->where('services.user_id', $userId)
            ->where('payments.status', 'paid')
            ->select('categories.name', DB::raw('SUM(payments.amount) as total'))
            ->groupBy('categories.name')
            ->get();

        // 4. Datos para Gráfica 2: Comparativa Presupuestado vs Real (Barras - Últimos 4 meses)
        $comparativaMeses = [
            'labels' => ['Junio', 'Julio', 'Agosto', 'Septiembre'],
            'presupuestado' => [9500, 9500, 10200, 9800],
            'real' => [9100, 9350, 10110, $totalGastadoMes > 0 ? (float)$totalGastadoMes : 1310],
        ];

        // 5. Datos para Gráfica 3: Tendencia de Servicios Variables (Líneas)
        $tendenciaServicios = [
            'labels' => ['Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre'],
            'cfe' => [450, 520, 610, 590, 680],
            'agua' => [280, 290, 310, 280, 310],
        ];

        return view('dashboard', compact(
            'tips',
            'totalGastadoMes',
            'pagosPendientesMes',
            'tramitesPendientes',
            'citasProximas',
            'gastosPorCategoria',
            'comparativaMeses',
            'tendenciaServicios'
        ));
    }
}