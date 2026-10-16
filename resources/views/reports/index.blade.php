<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Centro de Reportes y Comprobantes') }}
            </h2>
            <a href="{{ route('reports.pdf') }}" class="bg-rose-600 hover:bg-rose-700 text-white font-bold py-2.5 px-5 rounded-lg text-sm shadow flex items-center gap-2 transition">
                <span></span>
                <span>Exportar Reporte Ejecutivo PDF</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Tarjetas de Resumen Financiero -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <span class="text-xs uppercase text-gray-400 font-bold">Servicios Pagados</span>
                    <p class="text-2xl font-black text-gray-800 mt-1">${{ number_format($totalServicios, 2) }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <span class="text-xs uppercase text-gray-400 font-bold">Trámites Completados</span>
                    <p class="text-2xl font-black text-gray-800 mt-1">${{ number_format($totalTramites, 2) }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl border border-gray-100 shadow-sm">
                    <span class="text-xs uppercase text-gray-400 font-bold">Gasto en Salud / Consultas</span>
                    <p class="text-2xl font-black text-gray-800 mt-1">${{ number_format($totalSalud, 2) }}</p>
                </div>
                <div class="bg-indigo-50 p-5 rounded-xl border border-indigo-100 shadow-sm">
                    <span class="text-xs uppercase text-indigo-500 font-bold">Inversión Total Registrada</span>
                    <p class="text-2xl font-black text-indigo-900 mt-1">${{ number_format($totalServicios + $totalTramites + $totalSalud, 2) }}</p>
                </div>
            </div>

            <!-- MÓDULO DE REPORTE ESTADÍSTICO DE TENDENCIAS -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm mt-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg"> Reporte Estadístico de Consumos y Tendencias</h3>
                        <p class="text-xs text-gray-500">Analiza el incremento o decremento de tus servicios (Luz, Agua, Gas, etc.) en un periodo de tiempo.</p>
                    </div>
                </div>

                <form action="{{ route('reports.statisticalPdf') }}" method="GET" target="_blank" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha Inicio</label>
                        <input type="date" name="from_date" value="{{ now()->subMonths(6)->startOfMonth()->format('Y-m-d') }}" required
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Fecha Fin</label>
                        <input type="date" name="to_date" value="{{ now()->format('Y-m-d') }}" required
                            class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-4 rounded-lg text-sm shadow flex items-center justify-center gap-2 transition">
                            <span></span>
                            <span>Generar PDF Estadístico</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Historial de Transacciones Stripe -->
            <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-800 text-base mb-4">Transacciones Pasarela Stripe</h3>
                @if($transacciones->isEmpty())
                    <p class="text-sm text-gray-400">Aún no hay transacciones bancarias liquidadas por Stripe.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs border-b">
                                <tr>
                                    <th class="py-2.5 px-4">Folio de Sesión Stripe</th>
                                    <th class="py-2.5 px-4">Fecha de Liquidación</th>
                                    <th class="py-2.5 px-4">Monto Pagado</th>
                                    <th class="py-2.5 px-4 text-center">Estatus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($transacciones as $tx)
                                    <tr>
                                        <td class="py-2.5 px-4 font-mono text-xs text-indigo-600">{{ $tx->stripe_checkout_id }}</td>
                                        <td class="py-2.5 px-4">{{ $tx->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="py-2.5 px-4 font-bold text-gray-800">${{ number_format($tx->amount, 2) }} {{ strtoupper($tx->currency) }}</td>
                                        <td class="py-2.5 px-4 text-center">
                                            <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-0.5 rounded-full font-semibold">Exitoso</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Tablas de Resumen General -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Servicios Liquidados -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Últimos Servicios Liquidados</h3>
                    <div class="divide-y divide-gray-100 text-sm">
                        @forelse($pagosRealizados->take(5) as $pago)
                            <div class="py-2 flex justify-between">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $pago->service->name ?? 'Servicio' }}</span>
                                    <span class="block text-xs text-gray-400">Período: {{ $pago->period }}</span>
                                </div>
                                <span class="font-bold text-emerald-600">${{ number_format($pago->amount, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400">No hay pagos registrados como liquidados.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Citas Médicas -->
                <div class="bg-white p-6 rounded-xl border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Historial de Citas Médicas</h3>
                    <div class="divide-y divide-gray-100 text-sm">
                        @forelse($citas->take(5) as $cita)
                            <div class="py-2 flex justify-between">
                                <div>
                                    <span class="font-medium text-gray-800">{{ $cita->specialty }} - {{ $cita->doctor_name }}</span>
                                    <span class="block text-xs text-gray-400">{{ $cita->appointment_date->format('d/m/Y') }}</span>
                                </div>
                                <span class="font-bold text-gray-700">${{ number_format($cita->cost, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400">No hay consultas médicas en el historial.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>