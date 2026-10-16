<?php

namespace App\Http\Controllers;

use App\Models\MedicalAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalAppointmentController extends Controller
{
    public function index()
    {
        $appointments = MedicalAppointment::with('documents')
            ->where('user_id', Auth::id())
            ->orderBy('appointment_date', 'desc')
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        return view('appointments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_name'      => 'required|string|max:100',
            'specialty'        => 'required|string|max:100',
            'appointment_date' => 'required|date',
            'cost'             => 'required|numeric|min:0',
            'diagnosis'        => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();

        MedicalAppointment::create($validated);

        return redirect()->route('appointments.index')->with('success', '¡Cita médica agendada correctamente!');
    }

    public function edit(MedicalAppointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('appointments.edit', compact('appointment'));
    }

    public function update(Request $request, MedicalAppointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'doctor_name'      => 'required|string|max:100',
            'specialty'        => 'required|string|max:100',
            'appointment_date' => 'required|date',
            'cost'             => 'required|numeric|min:0',
            'diagnosis'        => 'nullable|string',
        ]);

        $appointment->update($validated);

        return redirect()->route('appointments.index')->with('success', 'Cita médica actualizada.');
    }

    public function destroy(MedicalAppointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }

        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Cita médica eliminada.');
    }
}