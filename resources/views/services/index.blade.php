<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Servicios del Hogar') }}
            </h2>
            <a href="{{ route('services.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow">
                + Nuevo Servicio
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 text-gray-900">
                    @if($services->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-gray-400 text-base">Aún no has registrado servicios del hogar.</p>
                            <a href="{{ route('services.create') }}" class="mt-3 inline-block text-indigo-600 font-semibold hover:underline text-sm">
                                Agrega tu primer servicio (Luz, Internet, etc.) →
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600">
                                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs border-b">
                                    <tr>
                                        <th class="py-3 px-4">Servicio</th>
                                        <th class="py-3 px-4">Proveedor</th>
                                        <th class="py-3 px-4">Categoría</th>
                                        <th class="py-3 px-4">Día de Corte/Vence</th>
                                        <th class="py-3 px-4">Monto Estimado</th>
                                        <th class="py-3 px-4 text-center">Comprobantes</th>
                                        <th class="py-3 px-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($services as $service)
                                        <tr class="hover:bg-gray-50/80 transition">
                                            <td class="py-3 px-4 font-bold text-gray-800">
                                                {{ $service->name }}
                                                @if($service->account_number)
                                                    <span class="block text-xs font-normal text-gray-400">Ref: {{ $service->account_number }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">{{ $service->provider }}</td>
                                            <td class="py-3 px-4">
                                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                                      style="background-color: {{ $service->category->color_hex ?? '#e0e7ff' }}20; color: {{ $service->category->color_hex ?? '#4338ca' }}">
                                                    {{ $service->category->name ?? 'General' }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4">Día {{ $service->due_day }} de c/mes</td>
                                            <td class="py-3 px-4 font-bold text-gray-800">${{ number_format($service->estimated_amount, 2) }}</td>
                                            <td class="py-3 px-4 text-center">
                                                @if($service->documents->isNotEmpty())
                                                    <div class="flex flex-col items-center gap-1">
                                                        @foreach($service->documents as $doc)
                                                            <a href="{{ route('documents.download', $doc) }}" 
                                                            class="inline-flex items-center gap-1 text-xs bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-medium px-2 py-0.5 rounded border border-indigo-200" 
                                                            title="{{ $doc->file_name }}">
                                                                <span>📎</span>
                                                                <span class="max-w-[100px] truncate">{{ $doc->file_name }}</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Sin archivos</span>
                                                @endif
                                            </td>  
                                            <td class="py-3 px-4 text-center space-x-2">
                                                <a href="{{ route('services.edit', $service) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Editar</a>
                                                <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline" onsubmit="return confirm('¿Seguro que deseas eliminar este servicio?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>