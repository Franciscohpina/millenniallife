<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TextAnalysisController extends Controller
{
    // Muestra la vista con el formulario inicial
    public function index()
    {
        return view('analizador-vocales');
    }

    // Procesa el texto enviado
    public function analyze(Request $request)
    {
        // 1. Validación de entrada
        $request->validate([
            'texto' => 'required|string',
        ], [
            'texto.required' => 'Debes ingresar un texto para poder analizarlo.',
        ]);

        $textoOriginal = $request->input('texto');

        // 2. Normalización: convertir a minúsculas con soporte UTF-8
        $textoNormalizado = mb_strtolower($textoOriginal, 'UTF-8');

        // Mapeo manual para sustituir vocales con acento/diéresis a su vocal base
        $reemplazosAcentos = [
            'á' => 'a', 'à' => 'a', 'ä' => 'a', 'â' => 'a',
            'é' => 'e', 'è' => 'e', 'ë' => 'e', 'ê' => 'e',
            'í' => 'i', 'ì' => 'i', 'ï' => 'i', 'î' => 'i',
            'ó' => 'o', 'ò' => 'o', 'ö' => 'o', 'ô' => 'o',
            'ú' => 'u', 'ù' => 'u', 'ü' => 'u', 'û' => 'u',
        ];
        $textoNormalizado = strtr($textoNormalizado, $reemplazosAcentos);

        // 3. Estructura para el conteo individual
        $conteoVocales = [
            'a' => 0,
            'e' => 0,
            'i' => 0,
            'o' => 0,
            'u' => 0,
        ];

        $totalCaracteresSinEspacios = 0;
        $totalVocales = 0;

        // Longitud del texto en caracteres multibyte
        $longitud = mb_strlen($textoNormalizado, 'UTF-8');

        // 4. Recorrido carácter por carácter
        for ($i = 0; $i < $longitud; $i++) {
            $caracter = mb_substr($textoNormalizado, $i, 1, 'UTF-8');

            // Ignorar espacios en blanco para el conteo de caracteres
            if ($caracter === ' ' || $caracter === "\t" || $caracter === "\n" || $caracter === "\r") {
                continue;
            }

            $totalCaracteresSinEspacios++;

            // Verificar si el carácter es una de las vocales base
            if (array_key_exists($caracter, $conteoVocales)) {
                $conteoVocales[$caracter]++;
                $totalVocales++;
            }
        }

        // 5. Cálculo de porcentajes y determinación de la vocal más frecuente
        $porcentajes = [];
        $vocalMasFrecuente = 'Ninguna';
        $maxApariciones = 0;

        if ($totalVocales > 0) {
            foreach ($conteoVocales as $vocal => $cantidad) {
                // Cálculo del porcentaje respecto al total de vocales
                $porcentajes[$vocal] = round(($cantidad / $totalVocales) * 100, 2);

                if ($cantidad > $maxApariciones) {
                    $maxApariciones = $cantidad;
                    $vocalMasFrecuente = strtoupper($vocal);
                }
            }
        } else {
            foreach ($conteoVocales as $vocal => $cantidad) {
                $porcentajes[$vocal] = 0;
            }
        }

        $metricas = [
            'totalCaracteresSinEspacios' => $totalCaracteresSinEspacios,
            'totalVocales'               => $totalVocales,
            'conteoVocales'              => $conteoVocales,
            'porcentajes'                => $porcentajes,
            'vocalMasFrecuente'          => $vocalMasFrecuente,
            'textoOriginal'              => $textoOriginal,
        ];

        return view('analizador-vocales', compact('metricas'));
    }
}