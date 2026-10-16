<?php

namespace App\Http\Controllers;

use App\Models\AnnualProcedure;
use App\Models\MedicalAppointment;
use App\Models\Payment;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\Service;

class ReportController extends Controller
{
    // Vista previa de reportes disponibles en pantalla
    public function index()
    {
        $userId = Auth::id();

        $pagosRealizados = Payment::with('service')
            ->whereHas('service', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->get();

        $tramites = AnnualProcedure::with('category')
            ->where('user_id', $userId)
            ->orderBy('deadline_date', 'asc')
            ->get();

        $citas = MedicalAppointment::where('user_id', $userId)
            ->orderBy('appointment_date', 'desc')
            ->get();

        $transacciones = Transaction::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalServicios = $pagosRealizados->sum('amount');
        $totalTramites = $tramites->where('status', 'completed')->sum('cost');
        $totalSalud = $citas->sum('cost');

        return view('reports.index', compact(
            'pagosRealizados',
            'tramites',
            'citas',
            'transacciones',
            'totalServicios',
            'totalTramites',
            'totalSalud'
        ));
    }

    // Descarga del PDF estructurado
    public function downloadPdf()
    {
        $userId = Auth::id();
        $user = Auth::user();

        $pagosRealizados = Payment::with('service')
            ->whereHas('service', fn($q) => $q->where('user_id', $userId))
            ->where('status', 'paid')
            ->orderBy('paid_at', 'desc')
            ->get();

        $tramites = AnnualProcedure::with('category')
            ->where('user_id', $userId)
            ->get();

        $citas = MedicalAppointment::where('user_id', $userId)
            ->orderBy('appointment_date', 'desc')
            ->get();

        $totalServicios = $pagosRealizados->sum('amount');
        $totalTramites = $tramites->where('status', 'completed')->sum('cost');
        $totalSalud = $citas->sum('cost');
        $granTotal = $totalServicios + $totalTramites + $totalSalud;

        $pdf = Pdf::loadView('reports.pdf', compact(
            'user',
            'pagosRealizados',
            'tramites',
            'citas',
            'totalServicios',
            'totalTramites',
            'totalSalud',
            'granTotal'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Reporte_Adulting_MillennialLife_' . now()->format('Ymd_His') . '.pdf');
    }

    // Descarga del PDF estadístico con análisis de tendencias
    public function statisticalPdf(Request $request)
    {
        $request->validate([
            'from_date' => 'required|date',
            'to_date'   => 'required|date|after_or_equal:from_date',
        ]);

        $userId = Auth::id();
        $fromDate = Carbon::parse($request->from_date)->startOfDay();
        $toDate = Carbon::parse($request->to_date)->endOfDay();

        // 1. Obtener servicios del usuario con sus pagos en el rango seleccionado
        $servicios = Service::where('user_id', $userId)
            ->with(['payments' => function ($q) use ($fromDate, $toDate) {
                $q->where('status', 'paid')
                ->whereBetween('paid_at', [$fromDate, $toDate])
                ->orderBy('paid_at', 'asc');
            }])
            ->get();

        // 2. Procesar análisis de variación por cada servicio
        $analisisServicios = [];
        $graficaDatasets = [];
        $colores = ['#4f46e5', '#06b6d4', '#f59e0b', '#10b981', '#ef4444', '#8b5cf6'];
        $colorIndex = 0;

        // Obtener todos los meses únicos del rango para las etiquetas del eje X
        $period = \Carbon\CarbonPeriod::create($fromDate->copy()->startOfMonth(), '1 month', $toDate->copy()->startOfMonth());
        $mesesLabels = [];
        foreach ($period as $dt) {
            $mesesLabels[] = $dt->translatedFormat('M Y');
        }

        foreach ($servicios as $servicio) {
            $pagos = $servicio->payments;

            if ($pagos->isEmpty()) {
                continue;
            }

            $primerPago = $pagos->first()->amount;
            $ultimoPago = $pagos->last()->amount;
            $totalGastado = $pagos->sum('amount');
            $promedio = $pagos->avg('amount');

            // Cálculo de incremento / decremento porcentual
            $diferencia = $ultimoPago - $primerPago;
            $porcentajeCambio = $primerPago > 0 ? round(($diferencia / $primerPago) * 100, 1) : 0;

            $analisisServicios[] = [
                'nombre'           => $servicio->name,
                'proveedor'        => $servicio->provider,
                'total'            => $totalGastado,
                'promedio'         => $promedio,
                'primer_pago'      => $primerPago,
                'ultimo_pago'      => $ultimoPago,
                'diferencia'       => $diferencia,
                'porcentaje_cambio'=> $porcentajeCambio,
                'tendencia'        => $diferencia > 0 ? 'incremento' : ($diferencia < 0 ? 'decremento' : 'estable'),
            ];

            // Mapear pagos a los meses del gráfico
            $dataPorMes = [];
            foreach ($period as $dt) {
                $montoMes = $pagos->filter(function($p) use ($dt) {
                    return Carbon::parse($p->paid_at)->format('Y-m') === $dt->format('Y-m');
                })->sum('amount');
                $dataPorMes[] = (float)$montoMes;
            }

            $color = $colores[$colorIndex % count($colores)];
            $graficaDatasets[] = [
                'label'           => $servicio->name,
                'data'            => $dataPorMes,
                'borderColor'     => $color,
                'backgroundColor' => $color,
                'fill'            => false,
                'tension'         => 0.3,
            ];
            $colorIndex++;
        }

        // 3. Generar la gráfica en imagen usando la API de QuickChart
        $chartConfig = [
            'type' => 'line',
            'data' => [
                'labels'   => $mesesLabels,
                'datasets' => $graficaDatasets,
            ],
            'options' => [
                'title' => [
                    'display' => true,
                    'text'    => 'Tendencia de Consumo Histórico ($ MXN)',
                ],
                'scales' => [
                    'yAxes' => [[
                        'ticks' => ['beginAtZero' => true]
                    ]]
                ]
            ]
        ];

        // Llamada rápida a QuickChart para obtener la imagen como base64
        $chartUrl = 'https://quickchart.io/chart?w=700&h=300&bkg=white&c=' . urlencode(json_encode($chartConfig));
        $chartImageBase64 = null;

        try {
            $response = Http::timeout(6)->get($chartUrl);
            if ($response->successful()) {
                $chartImageBase64 = 'data:image/png;base64,' . base64_encode($response->body());
            }
        } catch (\Exception $e) {
            $chartImageBase64 = null; // Si no hay internet, no truena el PDF
        }

        $user = Auth::user();

        $pdf = Pdf::loadView('reports.statistical_pdf', compact(
            'user',
            'fromDate',
            'toDate',
            'analisisServicios',
            'chartImageBase64'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('Reporte_Estadistico_Tendencias_' . now()->format('Ymd_His') . '.pdf');
    }
}