<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analizador de Vocales</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen py-10 px-4">
    <div class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-2 text-center">Analizador de Vocales</h1>
        <p class="text-sm text-gray-500 text-center mb-6">Módulo de análisis métrico de texto y frecuencia de caracteres</p>

        {{-- Formulario --}}
        <form action="{{ route('analizador.procesar') }}" method="POST" class="mb-8">
            @csrf
            <div class="mb-4">
                <label for="texto" class="block text-sm font-medium text-gray-700 mb-1">Ingresa el texto a evaluar:</label>
                <textarea 
                    name="texto" 
                    id="texto" 
                    rows="5" 
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-3 border @error('texto') border-red-500 @enderror"
                    placeholder="Escribe o pega aquí tu texto..."
                >{{ old('texto', $metricas['textoOriginal'] ?? '') }}</textarea>
                
                @error('texto')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-black font-semibold px-6 py-2 rounded-lg transition duration-150">
                    Analizar Texto
                </button>
            </div>
        </form>

        {{-- Resultados --}}
        @isset($metricas)
            <div class="border-t pt-6">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Resultados del Análisis</h2>

                @if($metricas['totalVocales'] === 0)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                        <p class="text-sm text-yellow-700">
                            <strong>Aviso:</strong> El texto ingresado contiene <strong>{{ $metricas['totalCaracteresSinEspacios'] }}</strong> caracteres (sin espacios), pero no se encontró ninguna vocal.
                        </p>
                    </div>
                @else
                    {{-- Tarjetas de métricas generales --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-indigo-50 p-4 rounded-lg text-center">
                            <span class="block text-xs font-semibold text-indigo-500 uppercase">Caracteres (sin espacios)</span>
                            <span class="text-2xl font-bold text-indigo-900">{{ $metricas['totalCaracteresSinEspacios'] }}</span>
                        </div>
                        <div class="bg-emerald-50 p-4 rounded-lg text-center">
                            <span class="block text-xs font-semibold text-emerald-500 uppercase">Total de Vocales</span>
                            <span class="text-2xl font-bold text-emerald-900">{{ $metricas['totalVocales'] }}</span>
                        </div>
                        <div class="bg-amber-50 p-4 rounded-lg text-center">
                            <span class="block text-xs font-semibold text-amber-500 uppercase">Vocal más frecuente</span>
                            <span class="text-2xl font-bold text-amber-900">{{ $metricas['vocalMasFrecuente'] }}</span>
                        </div>
                    </div>

                    {{-- Tabla de desglose por vocal --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border border-gray-200 rounded-lg overflow-hidden text-sm">
                            <thead class="bg-gray-50 text-gray-700 font-semibold border-b">
                                <tr>
                                    <th class="py-2 px-4">Vocal</th>
                                    <th class="py-2 px-4 text-center">Cantidad encontrada</th>
                                    <th class="py-2 px-4 text-center">Porcentaje (% del total)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($metricas['conteoVocales'] as $vocal => $cantidad)
                                    <tr class="hover:bg-gray-50">
                                        <td class="py-2 px-4 font-bold uppercase text-indigo-600">{{ $vocal }}</td>
                                        <td class="py-2 px-4 text-center">{{ $cantidad }}</td>
                                        <td class="py-2 px-4 text-center">{{ $metricas['porcentajes'][$vocal] }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endisset
    </div>
</body>
</html>