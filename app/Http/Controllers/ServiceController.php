<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::with(['category', 'documents'])
            ->where('user_id', Auth::id())
            ->orderBy('due_day', 'asc')
            ->get();

        return view('services.index', compact('services'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('services.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'category_id'      => 'required|exists:categories,id',
            'provider'         => 'required|string|max:100',
            'account_number'   => 'nullable|string|max:50',
            'estimated_amount' => 'required|numeric|min:0',
            'due_day'          => 'required|integer|min:1|max:31',
        ]);

        $validated['user_id'] = Auth::id();

        Service::create($validated);

        return redirect()->route('services.index')->with('success', '¡Servicio registrado exitosamente!');
    }

    public function edit(Service $service)
    {
        // Seguridad: validar que el servicio sea del usuario en sesión
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('services.edit', compact('service', 'categories'));
    }

    public function update(Request $request, Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'             => 'required|string|max:100',
            'category_id'      => 'required|exists:categories,id',
            'provider'         => 'required|string|max:100',
            'account_number'   => 'nullable|string|max:50',
            'estimated_amount' => 'required|numeric|min:0',
            'due_day'          => 'required|integer|min:1|max:31',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')->with('success', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $service)
    {
        if ($service->user_id !== Auth::id()) {
            abort(403);
        }

        $service->delete();

        return redirect()->route('services.index')->with('success', 'Servicio eliminado.');
    }
}