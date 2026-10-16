<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class AssetController extends Controller
{
    // Muestra la vista con el formulario de subida y el listado
    public function create()
    {
        $assets = Asset::latest()->get();
        return view('asset.create', compact('assets'));
    }

    // Procesa y almacena la imagen o video en su respectiva subcarpeta
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi,webm|max:20480', // Máx 20MB
        ]);

        $file = $request->file('file');
        $mime = $file->getMimeType();

        // Determinar si es imagen o video y guardarlo en la carpeta correspondiente
        if (str_starts_with($mime, 'image/')) {
            $type = 'image';
            $path = $file->store('images', 'public'); // Guarda en storage/app/public/images
        } elseif (str_starts_with($mime, 'video/')) {
            $type = 'video';
            $path = $file->store('videos', 'public'); // Guarda en storage/app/public/videos
        } else {
            return back()->with('error', 'El archivo no es una imagen ni un video válido.');
        }

        Asset::create([
            'name'      => $request->name,
            'type'      => $type,
            'file_path' => $path,
        ]);

        return redirect()->route('asset.create')->with('success', 'Archivo subido y registrado exitosamente.');
    }

    // Retorna la imagen desde Storage
    public function getImage($filename)
    {
        $path = 'images/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Imagen no encontrada');
        }

        $fullPath = Storage::disk('public')->path($path);

        return response()->file($fullPath);
    }

    // Retorna el video desde Storage
    public function getVideo($filename)
    {
        $path = 'videos/' . $filename;

        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Video no encontrado');
        }

        $fullPath = Storage::disk('public')->path($path);

        return response()->file($fullPath);
    }
}