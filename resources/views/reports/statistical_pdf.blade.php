<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Estadístico de Tendencias</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #333; line-height: 1.4; margin: 15px; }
        .header { border-bottom: 2px solid #4f46e5; padding-bottom: 8px; margin-bottom: 15px; }
        .header table { width: 100%; }
        .title { font-size: 18px; font-weight: bold; color: #1e1b4b; }
        .subtitle { font-size: 10px; color: #6b7280; }
        .chart-box { text-align: center; margin-bottom: 20px; padding: 8px; border: 1px solid #e2e8f0; border-radius: 6px; }
        .chart-box img { max-width: 100%; height: auto; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { background-color: #f1f5f9; color: #334155; font-weight: bold; font-size: 8.5px; text-transform: uppercase; padding: 6px; border: 1px solid #cbd5e1; text-align: left; }
        table.data-table td { padding: 6px; border: 1px solid #cbd5e1; font-size: 9.5px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-up { background-color: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 3px; font-weight: bold; }
        .badge-down { background-color: #d1fae5; color: #065f46; padding: 2px 6px; border-radius: 3px; font-weight: bold; }
        .badge-same { background-color: #e2e8f0; color: #475569; padding: 2px 6px; border-radius: 3px; font-weight: bold; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 4px; }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="title">MillennialLife</div>
                    <div class="subtitle">Análisis Estadístico de Variación y Consumo de Servicios</div>
                </td>
                <td class="text-right">
                    <strong>Usuario:</strong> {{ $user->name }}<br>
                    <strong>Periodo Evaluado:</strong> {{ $fromDate->format('d/m/Y') }} al {{ $toDate->format('d/m/Y') }}<br>
                    <strong>Emisión:</strong> {{ now()->format('d/m/Y H:i') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- 1. GRÁFICA DE TENDENCIAS RENDERIZADA -->
    @if($chartImageBase64)
        <div class="chart-box">
            <img src="{{ $chartImageBase64 }}" alt="Gráfico de Tendencia">
        </div>
    @endif

    <!-- 2. TABLA COMPARATIVA CON CÁLCULO DE INCREMENTO / DECREMENTO -->
    <h4 style="margin: 10px 0 4px 0; font-size: 11px; color: #1e1b4b;">Resumen Comparativo de Variación por Concepto</h4>
    <table class="data-table">
        <thead>
            <tr>
                <th>Servicio</th>
                <th>Proveedor</th>
                <th class="text-right">Primer Gasto</th>
                <th class="text-right">Último Gasto</th>
                <th class="text-right">Gasto Promedio</th>
                <th class="text-right">Total Periodo</th>
                <th class="text-center">Variación</th>
                <th class="text-center">Diagnóstico</th>
            </tr>
        </thead>
        <tbody>
            @forelse($analisisServicios as $item)
                <tr>
                    <td><strong>{{ $item['nombre'] }}</strong></td>
                    <td>{{ $item['proveedor'] }}</td>
                    <td class="text-right">${{ number_format($item['primer_pago'], 2) }}</td>
                    <td class="text-right">${{ number_format($item['ultimo_pago'], 2) }}</td>
                    <td class="text-right">${{ number_format($item['promedio'], 2) }}</td>
                    <td class="text-right font-bold">${{ number_format($item['total'], 2) }}</td>
                    <td class="text-center">
                        @if($item['porcentaje_cambio'] > 0)
                            <span class="badge-up">+{{ $item['porcentaje_cambio'] }}%</span>
                        @elseif($item['porcentaje_cambio'] < 0)
                            <span class="badge-down">{{ $item['porcentaje_cambio'] }}%</span>
                        @else
                            <span class="badge-same">0%</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($item['tendencia'] === 'incremento')
                            <span style="color: #b91c1c; font-weight: bold;">▲ Aumento</span>
                        @elseif($item['tendencia'] === 'decremento')
                            <span style="color: #047857; font-weight: bold;">▼ Ahorro</span>
                        @else
                            <span style="color: #64748b;">— Estable</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 15px; color: #94a3b8;">
                        No se encontraron pagos registrados en el rango de fechas seleccionado.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        MillennialLife &bull; Módulo de Inteligencia Financiera y Análisis de Costos Recurrentes
    </div>

</body>
</html>