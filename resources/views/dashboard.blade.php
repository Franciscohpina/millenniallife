<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Panel de Control - MillennialLife') }}
        </h2>
    </x-slot>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- 1. CARRUSEL DINÁMICO DESDE BD -->
            @if($tips->isNotEmpty())
                <div x-data="{ 
                        active: 0, 
                        total: {{ $tips->count() }},
                        next() { this.active = (this.active + 1) % this.total },
                        prev() { this.active = (this.active - 1 + this.total) % this.total }
                    }" 
                    x-init="setInterval(() => next(), 6000)"
                    class="relative bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 rounded-2xl p-6 text-white shadow-lg overflow-hidden">
                    
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-xs uppercase tracking-wider text-indigo-300 font-bold">Consejos de Adulting</span>
                        <div class="flex space-x-2">
                            <button @click="prev()" class="p-1 rounded-full bg-white/10 hover:bg-white/20 transition">‹</button>
                            <button @click="next()" class="p-1 rounded-full bg-white/10 hover:bg-white/20 transition">›</button>
                        </div>
                    </div>

                    @foreach($tips as $index => $tip)
                        <div x-show="active === {{ $index }}" 
                             x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-x-4"
                             x-transition:enter-end="opacity-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-200 transform opacity-100"
                             x-transition:leave-end="opacity-0 -translate-x-4">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="bg-indigo-500 text-white text-xs px-2 py-0.5 rounded-full font-semibold">
                                    {{ $tip->badge_text ?? $tip->category }}
                                </span>
                                <h3 class="text-lg font-bold text-white">{{ $tip->title }}</h3>
                            </div>
                            <p class="text-sm text-indigo-100 max-w-2xl">{{ $tip->description }}</p>
                        </div>
                    @endforeach

                    <!-- Indicadores de puntos -->
                    <div class="flex justify-center space-x-2 mt-4">
                        @foreach($tips as $index => $tip)
                            <button @click="active = {{ $index }}" 
                                    :class="active === {{ $index }} ? 'bg-white w-6' : 'bg-white/40 w-2'" 
                                    class="h-1.5 rounded-full transition-all duration-300"></button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- 2. TARJETAS DE RESUMEN (KPIs) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Pagado este mes</p>
                    <p class="text-2xl font-black text-emerald-600 mt-1">${{ number_format($totalGastadoMes, 2) }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Pendiente por Pagar</p>
                    <p class="text-2xl font-black text-rose-600 mt-1">${{ number_format($pagosPendientesMes, 2) }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Trámites en Curso</p>
                    <p class="text-2xl font-black text-amber-500 mt-1">{{ $tramitesPendientes }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-500 font-semibold uppercase">Citas Médicas Próximas</p>
                    <p class="text-2xl font-black text-indigo-600 mt-1">{{ $citasProximas }}</p>
                </div>
            </div>

            <!-- 3. DASHBOARD CON 3 GRÁFICAS (Chart.js) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Gráfica 1: Dona (Gastos por Categoría) -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-sm text-gray-700 mb-3">Distribución por Categoría</h4>
                    <div class="relative h-64">
                        <canvas id="chartCategorias"></canvas>
                    </div>
                </div>

                <!-- Gráfica 2: Barras (Presupuesto vs Real) -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-sm text-gray-700 mb-3">Presupuesto vs. Real (Últimos Meses)</h4>
                    <div class="relative h-64">
                        <canvas id="chartComparativa"></canvas>
                    </div>
                </div>

                <!-- Gráfica 3: Líneas (Histórico Servicios Variables) -->
                <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
                    <h4 class="font-bold text-sm text-gray-700 mb-3">Tendencia: Luz vs. Agua ($ MXN)</h4>
                    <div class="relative h-64">
                        <canvas id="chartTendencia"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Script de Inicialización de Chart.js -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Gráfica 1: Dona
            new Chart(document.getElementById('chartCategorias'), {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($gastosPorCategoria->pluck('name')->toArray() ?: ['Vivienda', 'Servicios', 'Otros']) !!},
                    datasets: [{
                        data: {!! json_encode($gastosPorCategoria->pluck('total')->toArray() ?: [590, 720, 300]) !!},
                        backgroundColor: ['#6366f1', '#10b981', '#f59e0b', '#ef4444'],
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // Gráfica 2: Barras
            new Chart(document.getElementById('chartComparativa'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($comparativaMeses['labels']) !!},
                    datasets: [
                        { label: 'Presupuestado', data: {!! json_encode($comparativaMeses['presupuestado']) !!}, backgroundColor: '#94a3b8' },
                        { label: 'Gasto Real', data: {!! json_encode($comparativaMeses['real']) !!}, backgroundColor: '#4f46e5' }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            // Gráfica 3: Líneas
            new Chart(document.getElementById('chartTendencia'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($tendenciaServicios['labels']) !!},
                    datasets: [
                        { label: 'CFE (Luz)', data: {!! json_encode($tendenciaServicios['cfe']) !!}, borderColor: '#f59e0b', fill: false, tension: 0.3 },
                        { label: 'SIAPA (Agua)', data: {!! json_encode($tendenciaServicios['agua']) !!}, borderColor: '#06b6d4', fill: false, tension: 0.3 }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
</x-app-layout>