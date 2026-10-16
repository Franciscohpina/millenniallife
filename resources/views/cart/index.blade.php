<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bandeja de Pagos') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('info') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- COLUMNA IZQUIERDA: Conceptos Activos para pagar hoy -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-lg text-gray-800">
                                Listos para Liquidar Hoy ({{ $cartItems->count() }})
                            </h3>
                            <span class="text-xs text-gray-400">Se cobrarán vía Stripe</span>
                        </div>

                        @if($cartItems->isEmpty())
                            <div class="text-center py-8 border-2 border-dashed border-gray-200 rounded-lg">
                                <p class="text-gray-400 text-sm">No tienes conceptos seleccionados para pagar en este lote.</p>
                                <p class="text-xs text-gray-400 mt-1">Agrega servicios o trámites desde la sección inferior.</p>
                            </div>
                        @else
                            <div class="divide-y divide-gray-100">
                                @foreach($cartItems as $item)
                                    <div class="py-3 flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm">
                                                {{ $item->payable->name ?? $item->payable->title ?? 'Concepto de Pago' }}
                                            </p>
                                            <span class="text-xs text-gray-400">
                                                {{ class_basename($item->payable_type) === 'Payment' ? 'Recibo Mensual' : 'Trámite Anual' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="font-black text-gray-800 text-base">${{ number_format($item->item_amount, 2) }}</span>
                                            
                                            <!-- Botón Stand-by -->
                                            <form action="{{ route('cart.toggle', $item) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold px-2.5 py-1 rounded border border-amber-200" title="Dejar para la siguiente quincena">
                                                     Posponer
                                                </button>
                                            </form>

                                            <!-- Botón Quitar -->
                                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-400 hover:text-rose-500 text-sm font-bold">✕</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- SECCIÓN STAND-BY (Guardados para después) -->
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-200">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-bold text-sm text-slate-700">
                                En Stand-by / Siguiente Quincena ({{ $standbyItems->count() }})
                            </h3>
                            <span class="text-xs text-slate-400">No se cobrarán en este lote</span>
                        </div>

                        @if($standbyItems->isEmpty())
                            <p class="text-xs text-slate-400 italic">No tienes gastos pospuestos.</p>
                        @else
                            <div class="divide-y divide-slate-200">
                                @foreach($standbyItems as $item)
                                    <div class="py-2.5 flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-slate-700 text-sm">
                                                {{ $item->payable->name ?? $item->payable->title ?? 'Concepto' }}
                                            </p>
                                            <span class="text-xs text-slate-400">${{ number_format($item->item_amount, 2) }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <form action="{{ route('cart.toggle', $item) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold px-2.5 py-1 rounded border border-indigo-200">
                                                    ⚡ Mover a pagar hoy
                                                </button>
                                            </form>

                                            <form action="{{ route('cart.remove', $item) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-slate-400 hover:text-rose-500 text-xs">Quitar</button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- AGREGAR RÁPIDO DESDE TUS PENDIENTES -->
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h4 class="font-bold text-sm text-gray-800 mb-3">Agregar Pendientes a la Bandeja</h4>
                        
                        <div class="space-y-2">
                            @forelse($pagosDisponibles as $pago)
                                <div class="flex justify-between items-center p-2.5 bg-gray-50 rounded-lg text-sm">
                                    <span>{{ $pago->service->name ?? 'Servicio' }} - Período: {{ $pago->period }} (${{ number_format($pago->amount, 2) }})</span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="payable_type" value="App\Models\Payment">
                                        <input type="hidden" name="payable_id" value="{{ $pago->id }}">
                                        <input type="hidden" name="item_amount" value="{{ $pago->amount }}">
                                        <button type="submit" class="text-xs bg-gray-200 hover:bg-gray-300 font-semibold px-3 py-1 rounded">
                                            + Agregar
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <p class="text-xs text-gray-400">No hay recibos pendientes de servicios por agregar.</p>
                            @endforelse

                            @foreach($tramitesDisponibles as $tramite)
                                <div class="flex justify-between items-center p-2.5 bg-gray-50 rounded-lg text-sm">
                                    <span>Trámite: {{ $tramite->title }} (${{ number_format($tramite->cost, 2) }})</span>
                                    <form action="{{ route('cart.add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="payable_type" value="App\Models\AnnualProcedure">
                                        <input type="hidden" name="payable_id" value="{{ $tramite->id }}">
                                        <input type="hidden" name="item_amount" value="{{ $tramite->cost }}">
                                        <button type="submit" class="text-xs bg-gray-200 hover:bg-gray-300 font-semibold px-3 py-1 rounded">
                                            + Agregar
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: Resumen de Compra y Botón Stripe -->
                <div class="space-y-4">
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="font-bold text-base text-gray-800 mb-4">Resumen de Liquidación</h3>
                        
                        <div class="space-y-2 text-sm text-gray-600 border-b pb-4">
                            <div class="flex justify-between">
                                <span>Conceptos a pagar</span>
                                <span class="font-semibold">{{ $cartItems->count() }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span class="font-semibold">${{ number_format($totalPagar, 2) }} MXN</span>
                            </div>
                            <div class="flex justify-between text-xs text-gray-400">
                                <span>Comisión de Pasarela</span>
                                <span>$0.00 (Test)</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center py-4">
                            <span class="text-base font-bold text-gray-800">Total a Liquidar</span>
                            <span class="text-2xl font-black text-indigo-600">${{ number_format($totalPagar, 2) }}</span>
                        </div>

                        @if($totalPagar > 0)
                            <form action="{{ route('cart.checkout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-[#635BFF] hover:bg-[#5851df] text-white font-bold py-3 px-4 rounded-xl shadow flex items-center justify-center gap-2 transition">
                                    <span>Pagar con</span>
                                    <span class="font-black tracking-wider uppercase text-sm">Stripe</span>
                                </button>
                            </form>
                            <p class="text-center text-xs text-gray-400 mt-2"> Procesamiento seguro mediante Stripe Sandbox</p>
                        @else
                            <button disabled class="w-full bg-gray-200 text-gray-400 font-bold py-3 px-4 rounded-xl cursor-not-allowed">
                                Bandeja Vacía
                            </button>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>