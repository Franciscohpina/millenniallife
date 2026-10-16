<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Historial y Citas Médicas') }}
            </h2>
            <a href="{{ route('appointments.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-sm shadow">
                + Nueva Cita
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
                    @if($appointments->isEmpty())
                        <div class="text-center py-10">
                            <p class="text-gray-400 text-base">No tienes citas médicas registradas.</p>
                            <a href="{{ route('appointments.create') }}" class="mt-3 inline-block text-indigo-600 font-semibold hover:underline text-sm">
                                Agenda una consulta médica o dental →
                            </a>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-gray-600">
                                <thead class="bg-gray-50 text-gray-700 uppercase font-semibold text-xs border-b">
                                    <tr>
                                        <th class="py-3 px-4">Médico / Especialista</th>
                                        <th class="py-3 px-4">Especialidad</th>
                                        <th class="py-3 px-4">Fecha y Hora</th>
                                        <th class="py-3 px-4">Costo Consulta</th>
                                        <th class="py-3 px-4">Diagnóstico</th>
                                        <th class="py-3 px-4 text-center">Receta / Archivos</th>
                                        <th class="py-3 px-4 text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($appointments as $app)
                                        <tr class="hover:bg-gray-50/80 transition">
                                            <td class="py-3 px-4 font-bold text-gray-800">{{ $app->doctor_name }}</td>
                                            <td class="py-3 px-4">
                                                <span class="inline-block bg-teal-50 text-teal-700 px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                                    {{ $app->specialty }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-gray-700">
                                                {{ $app->appointment_date->format('d/m/Y h:i A') }}
                                            </td>
                                            <td class="py-3 px-4 font-bold text-gray-800">${{ number_format($app->cost, 2) }}</td>
                                            <td class="py-3 px-4 text-gray-500 max-w-xs truncate">
                                                {{ $app->diagnosis ?? 'Sin diagnóstico registrado' }}
                                            </td>
                                            <td class="py-3 px-4 text-center">
                                                @if($app->documents->isNotEmpty())
                                                    <div class="flex flex-col items-center gap-1">
                                                        @foreach($app->documents as $doc)
                                                            <a href="{{ route('documents.download', $doc) }}" 
                                                            class="inline-flex items-center gap-1 text-xs bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium px-2 py-0.5 rounded border border-emerald-200" 
                                                            title="{{ $doc->file_name }}">
                                                                <span>📄</span>
                                                                <span class="max-w-[100px] truncate">{{ $doc->file_name }}</span>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-xs text-gray-400 italic">Sin receta</span>
                                                @endif
                                            </td>
                                            <td class="py-3 px-4 text-center space-x-2">
                                                <a href="{{ route('appointments.edit', $app) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">Editar</a>
                                                <form action="{{ route('appointments.destroy', $app) }}" method="POST" class="inline" onsubmit="return confirm('¿Deseas eliminar este registro médico?')">
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