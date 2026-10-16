<?php

namespace App\Http\Controllers;

use App\Models\AnnualProcedure;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnualProcedureController extends Controller
{
    public function index()
    {
        $procedures = AnnualProcedure::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('deadline_date', 'asc')
            ->get();

        return view('procedures.index', compact('procedures'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('procedures.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'         => 'required|string|max:150',
            'category_id'   => 'required|exists:categories,id',
            'cost'          => 'required|numeric|min:0',
            'deadline_date' => 'required|date',
            'status'        => 'required|in:pending,in_progress,completed',
            'notes'         => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        AnnualProcedure::create($validated);

        return redirect()->route('procedures.index')->with('success', '¡Trámite registrado con éxito!');
    }

    public function edit(AnnualProcedure $procedure)
    {
        if ($procedure->user_id !== Auth::id()) {
            abort(403);
        }

        $categories = Category::all();
        return view('procedures.edit', compact('procedure', 'categories'));
    }

    public function update(Request $request, AnnualProcedure $procedure)
    {
        if ($procedure->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title'         => 'required|string|max:150',
            'category_id'   => 'required|exists:categories,id',
            'cost'          => 'required|numeric|min:0',
            'deadline_date' => 'required|date',
            'status'        => 'required|in:pending,in_progress,completed',
            'notes'         => 'nullable|string',
        ]);

        $procedure->update($validated);

        return redirect()->route('procedures.index')->with('success', 'Trámite actualizado.');
    }

    public function destroy(AnnualProcedure $procedure)
    {
        if ($procedure->user_id !== Auth::id()) {
            abort(403);
        }

        $procedure->delete();

        return redirect()->route('procedures.index')->with('success', 'Trámite eliminado.');
    }
}