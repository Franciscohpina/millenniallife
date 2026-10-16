<x-app-layout>
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100 text-center space-y-4">
                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-3xl font-bold">
                    ✓
                </div>

                <h2 class="text-2xl font-black text-gray-900">¡Pago Procesado con Éxito!</h2>
                <p class="text-sm text-gray-500">Tus compromisos y servicios han sido liquidados y marcados como pagados en el sistema.</p>

                <div class="bg-gray-50 p-4 rounded-xl text-left text-sm space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Monto Liquidado:</span>
                        <span class="font-bold text-gray-800">${{ number_format($transaction->amount, 2) }} {{ strtoupper($transaction->currency) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Folio Stripe:</span>
                        <span class="font-mono text-xs text-gray-700 truncate max-w-xs">{{ $transaction->stripe_checkout_id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Estatus:</span>
                        <span class="text-emerald-600 font-semibold uppercase text-xs">Aprobado</span>
                    </div>
                </div>

                <div class="pt-4 flex justify-center gap-3">
                    <a href="{{ route('dashboard') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-lg text-sm shadow">
                        Volver al Dashboard
                    </a>
                    <a href="{{ route('services.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 px-6 rounded-lg text-sm">
                        Ver Servicios
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>