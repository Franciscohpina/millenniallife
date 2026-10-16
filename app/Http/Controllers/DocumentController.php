<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\MedicalAppointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document'     => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // Hasta 5MB
            'model_type'   => 'required|in:service,appointment',
            'model_id'     => 'required|integer',
        ]);

        $file = $request->file('document');
        $fileName = $file->getClientOriginalName();
        $mimeType = $file->getClientMimeType();
        $fileSize = $file->getSize();

        // Guardar archivo en storage/app/public/attachments
        $filePath = $file->store('attachments', 'public');

        // 1. Guardar registro en la tabla documents
        $document = Document::create([
            'file_name' => $fileName,
            'file_path' => $filePath,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
        ]);

        // 2. Asociar polimórficamente en documentables
        if ($request->model_type === 'service') {
            $entity = Service::findOrFail($request->model_id);
        } else {
            $entity = MedicalAppointment::findOrFail($request->model_id);
        }

        $entity->documents()->attach($document->id);

        return back()->with('success', 'Documento adjuntado exitosamente.');
    }

        public function download(Document $document)
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            abort(404, 'Archivo no encontrado');
        }

        $fullPath = Storage::disk('public')->path($document->file_path);

        return response()->download($fullPath, $document->file_name);
    }

    public function destroy(Document $document)
    {
        // Borrar el archivo físico
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        // Eliminar registro (el cascade de foreign key limpia la tabla documentables)
        $document->delete();

        return back()->with('success', 'Documento eliminado.');
    }
}