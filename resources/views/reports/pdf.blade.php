<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Ejecutivo - MillennialLife</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #333; line-height: 1.4; margin: 20px; }
        .header { border-bottom: 2px solid #4f46e5; padding-bottom: 12px; margin-bottom: 20px; }
        .header table { width: 100%; }
        .title { font-size: 20px; font-weight: bold; color: #1e1b4b; }
        .subtitle { font-size: 11px; color: #6b7280; }
        .kpi-container { margin-bottom: 20px; width: 100%; }
        .kpi-box { background-color: #f3f4f6; padding: 10px; border-radius: 6px; text-align: center; }
        .kpi-box h4 { margin: 0; font-size: 9px; color: #4b5563; text-transform: uppercase; }
        .kpi-box p { margin: 4px 0 0 0; font-size: 14px; font-weight: bold; color: #111827; }
        .section-title { font-size: 13px; font-weight: bold; color: #312e81; margin: 16px 0 8px 0; border-bottom: 1px solid #e5e7eb; padding-bottom: 4px; }
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        table.data-table th { background-color: #f8fafc; color: #475569; font-weight: bold; font-size: 9px; text-transform: uppercase; padding: 6px 8px; border: 1px solid #e2e8f0; text-align: left; }
        table.data-table td { padding: 6px 8px; border: 1px solid #e2e8f0; font-size: 10px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge { display: inline-block; padding: 2px 6px; font-size: 8px; font-weight: bold; border-radius: 4px; }
        .badge-paid { background-color: #d1fae5; color: #065f46; }
        .badge-pending { background-color: #fee2e2; color: #991b1b; }
        .badge-proc { background-color: #fef3c7; color: #92400e; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 9px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 6px; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">MillennialLife</div>
                    <div class="subtitle">Reporte de Gestión de Vida Adulta y Obligaciones</div>
                </td>
                <td class="text-right">
                    <strong>Usuario:</strong> {{ $user->name }}<br>
                    <strong>Correo:</strong> {{ $user->email }}<br>
                    <strong>Fecha de Emisión:</strong> {{ now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- KPIs Resumen -->
    <table class="kpi-container" cellspacing="5">
        <tr>
            <td class="kpi-box" width="25%">
                <h4>Servicios Liquidados</h4>
                <p>${{ number_format($totalServicios, 2) }}</p>
            </td>
            <td class="kpi-box" width="25%">
                <h4>Trámites Anuales</h4>
                <p>${{ number_format($totalTramites, 2) }}</p>
            </td>
            <td class="kpi-box" width="25%">
                <h4>Consultas Médicas</h4>
                <p>${{ number_format($totalSalud, 2) }}</p>
            </td>
            <td class="kpi-box" width="25%" style="background-color: #e0e7ff;">
                <h4 style="color: #4338ca;">Total General</h4>
                <p style="color: #312e81;">${{ number_format($granTotal, 2) }}</p>
            </td>
        </tr>
    </table>

    <!-- 1. Servicios del Hogar -->
    <div class="section-title">1. Recibos y Servicios del Hogar Pagados</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Proveedor</th>
                <th>Período</th>
                <th>Fecha Pago</th>
                <th class="text-right">Monto</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pagosRealizados as $pago)
                <tr>
                    <td><strong>{{ $pago->service->name ?? 'Servicio' }}</strong></td>
                    <td>{{ $pago->service->provider ?? 'N/A' }}</td>
                    <td>{{ $pago->period }}</td>
                    <td>{{ $pago->paid_at ? \Carbon\Carbon::parse($pago->paid_at)->format('d/m/Y') : 'N/A' }}</td>
                    <td class="text-right">${{ number_format($pago->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay registros de servicios liquidados.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- 2. Trámites y Obligaciones -->
    <div class="section-title">2. Trámites Obligatorios Anuales</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Trámite</th>
                <th>Categoría</th>
                <th>Fecha Límite</th>
                <th class="text-center">Estatus</th>
                <th class="text-right">Costo Estimado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tramites as $tr)
                <tr>
                    <td><strong>{{ $tr->title }}</strong></td>
                    <td>{{ $tr->category->name ?? 'General' }}</td>
                    <td>{{ \Carbon\Carbon::parse($tr->deadline_date)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span class="badge {{ $tr->status === 'completed' ? 'badge-paid' : 'badge-proc' }}">
                            {{ strtoupper($tr->status) }}
                        </span>
                    </td>
                    <td class="text-right">${{ number_format($tr->cost, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay trámites anuales programados.</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- 3. Salud y Citas Médicas -->
    <div class="section-title">3. Historial de Consultas Médicas y Prevención</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Médico</th>
                <th>Especialidad</th>
                <th>Fecha Cita</th>
                <th>Diagnóstico / Comentarios</th>
                <th class="text-right">Costo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($citas as $cita)
                <tr>
                    <td><strong>{{ $cita->doctor_name }}</strong></td>
                    <td>{{ $cita->specialty }}</td>
                    <td>{{ $cita->appointment_date->format('d/m/Y h:i A') }}</td>
                    <td>{{ $cita->diagnosis ?? 'Sin diagnóstico' }}</td>
                    <td class="text-right">${{ number_format($cita->cost, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">No hay citas médicas registradas.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        MillennialLife - Sistema Integral de Gestión Personal y Adulting &bull; Generado de forma automatizada
    </div>

</body>
</html>