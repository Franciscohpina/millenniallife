<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Servicio: ') . $service->name }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <form action="{{ route('services.update', $service) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre del Servicio</label>
                        <input type="text" name="name" value="{{ old('name', $service->name) }}" required 
                               class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Proveedor</label>
                            <input type="text" name="provider" value="{{ old('provider', $service->provider) }}" required 
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoría</label>
                            <select name="category_id" required class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id', $service->category_id) == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">No. Contrato / Referencia</label>
                            <input type="text" name="account_number" value="{{ old('account_number', $service->account_number) }}" 
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Monto Estimado ($ MXN)</label>
                            <input type="number" step="0.01" name="estimated_amount" value="{{ old('estimated_amount', $service->estimated_amount) }}" required 
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Día de Vence (1 al 31)</label>
                            <input type="number" min="1" max="31" name="due_day" value="{{ old('due_day', $service->due_day) }}" required 
                                   class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('services.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800">Cancelar</a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg text-sm shadow">
                            Actualizar Servicio
                        </button>
                    </div>
                </form>

                <!-- SECCIÓN DE COMPROBANTES Y RECIBOS -->
                <div class="mt-8 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-800 text-base mb-2">Comprobantes y Recibos Adjuntos</h3>
                    
                    <!-- Formulario para subir archivo -->
                    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-3 mb-6 p-4 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                        @csrf
                        <input type="hidden" name="model_type" value="service">
                        <input type="hidden" name="model_id" value="{{ $service->id }}">
                        
                        <input type="file" name="document" required class="text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg text-xs shadow">
                            Subir Archivo
                        </button>
                    </form>

                    <!-- Lista de archivos adjuntos vinculados -->
                    <div class="divide-y divide-gray-100">
                        @forelse($service->documents as $doc)
                            <div class="py-3 flex justify-between items-center text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="text-lg">📎</span>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $doc->file_name }}</p>
                                        <span class="text-xs text-gray-400">{{ round($doc->file_size / 1024, 1) }} KB</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('documents.download', $doc) }}" class="text-xs bg-emerald-50 text-emerald-700 font-semibold px-3 py-1.5 rounded border border-emerald-200 hover:bg-emerald-100">
                                        Descargar
                                    </a>
                                    <form action="{{ route('documents.destroy', $doc) }}" method="POST" onsubmit="return confirm('¿Eliminar este comprobante?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-gray-400 italic">No hay comprobantes ni recibos cargados para este servicio.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>