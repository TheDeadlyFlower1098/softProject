<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Appointment;   // <-- important
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        return response()->json(Prescription::with(['patient', 'doctor'])->paginate(20));
    }

    /**
     * Store a new prescription for a given appointment.
     *
     * Route: POST /appointments/{appointment}/prescriptions
     */
    public function store(Request $request, Appointment $appointment)
    {
        // Only validate the fields your form actually has
        $validated = $request->validate([
            'content' => 'required|string',
            'notes'   => 'nullable|string',
        ]);

        // Create the prescription
        $prescription = Prescription::create([
            'patient_id'     => $appointment->patient_id,   // from the appointment
            'doctor_id'      => auth()->id(),               // adjust if you use employees table
            'appointment_id' => $appointment->id,
            'content'        => $validated['content'],
            'notes'          => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('appointment.details', $appointment->id)
            ->with('success', 'Prescription saved.');
    }

    public function show($id)
    {
        return response()->json(Prescription::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $p = Prescription::findOrFail($id);
        $data = $request->validate([
            'content' => 'sometimes|required|string',
            'notes'   => 'nullable|string',
        ]);
        $p->update($data);
        return response()->json($p);
    }

    public function destroy($id)
    {
        Prescription::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
