<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recursos: Imágenes y Videos') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-100 border border-rose-400 text-rose-700 px-4 py-3 rounded-lg text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <!-- 1. FORMULARIO DE SUBIDA -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-base mb-4">Subir Nuevo Archivo Multimedia</h3>
                <form action="{{ route('asset.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre descriptivo</label>
                        <input type="text" name="name" required class="mt-1 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Ej. Foto comprobante o Video tutorial">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Seleccionar Imagen o Video</label>
                        <input type="file" name="file" required class="mt-1 block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg text-sm shadow">
                        Subir Archivo
                    </button>
                </form>
            </div>

            <!-- 2. VISTA DE MOSTRAR IMAGEN Y VIDEO  -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-base mb-4">Galería de archivos almacenados</h3>

                @if($assets->isEmpty())
                    <p class="text-sm text-gray-400 italic">No hay archivos cargados aún en el sistema.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($assets as $asset)
                            @php
                                $filename = basename($asset->file_path);
                            @endphp
                            <div class="border border-gray-200 rounded-lg p-3 bg-gray-50 flex flex-col justify-between">
                                <div class="mb-2">
                                    <span class="text-xs uppercase font-bold px-2 py-0.5 rounded {{ $asset->type === 'image' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                        {{ $asset->type }}
                                    </span>
                                    <p class="font-bold text-gray-800 text-sm mt-1 truncate">{{ $asset->name }}</p>
                                </div>

                                <div class="flex items-center justify-center bg-black/5 rounded-md min-h-[160px] overflow-hidden">
                                    @if($asset->type === 'image')
                                        <!-- Muestra imagen usando la ruta getImage -->
                                        <img src="{{ route('asset.getImage', ['filename' => $filename]) }}" alt="{{ $asset->name }}" class="max-h-48 object-contain rounded">
                                    @elseif($asset->type === 'video')
                                        <!-- Muestra video usando la ruta getVideo -->
                                        <video controls class="w-full max-h-48 rounded">
                                            <source src="{{ route('asset.getVideo', ['filename' => $filename]) }}" type="video/mp4">
                                            Tu navegador no soporta reproducción de video.
                                        </video>
                                    @endif
                                </div>

                                <!-- <span class="text-[11px] text-gray-400 mt-2 block truncate">Ruta: {{ $asset->file_path }}</span> -->
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>