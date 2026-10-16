<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Trámites Anuales y Obligaciones') }}
            </h2>
            <a href="{{ route('procedures.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow">
                + Nuevo Trámite
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
                    @if($procedures->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-gray-400 text-base">No tienes trámites anuales programados.</p>
                            <a href="{{ route('procedures.create') }}" class="mt-3 inline-block text-indigo-600 font-semibold hover:underline text-sm">
                                Agenda tu primer trámite (Predial, SAT, Refrendo) →
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600">
                                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs border-b">
                                    <tr>
                                        <th class="py-3 px-4">Trámite / Asunto</th>
                                        <th class="py-3 px-4">Categoría</th>
                                        <th class="py-3 px-4">Costo Aprox.</th>
                                        <th class="py-3 px-4">Fecha Límite</th>
                                        <th class="py-3 px-4 text-center">Estatus</th>
                                        <th class="py-3 px-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($procedures as $proc)
                                        <tr class="hover:bg-gray-50/80 transition">
                                            <td class="py-3 px-4 font-bold text-gray-800">
                                                {{ $proc->title }}
                                                @if($proc->notes)
                                                    <span class="block text-xs font-normal text-gray-400 truncate max-w-xs">{{ $proc->notes }}</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium" 
                                                      style="background-color: {{ $proc->category->color_hex ?? '#e0e7ff' }}20; color: {{ $proc->category->color_hex ?? '#4338ca' }}">
                                                    {{ $proc->category->name ?? 'General' }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 font-bold text-gray-800">${{ number_format($proc->cost, 2) }}</td>
                                            <td class="py-3 px-4 text-gray-700">
                                                {{ \Carbon\Carbon::parse($proc->deadline_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                @if($proc->status === 'completed')
                                                    <span class="bg-emerald-100 text-emerald-700 text-xs px-2.5 py-1 rounded-full font-semibold">Completado</span>
                                                @elseif($proc->status === 'in_progress')
                                                    <span class="bg-amber-100 text-amber-700 text-xs px-2.5 py-1 rounded-full font-semibold">En Proceso</span>
                                                @else
                                                    <span class="bg-rose-100 text-rose-700 text-xs px-2.5 py-1 rounded-full font-semibold">Pendiente</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-center space-x-2">
                                                <a href="{{ route('procedures.edit', $proc) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Editar</a>
                                                <form action="{{ route('procedures.destroy', $proc) }}" method="POST" class="inline" onsubmit="return confirm('¿Deseas eliminar este trámite?')">
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