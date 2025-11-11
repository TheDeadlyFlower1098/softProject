<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Prescription;
use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function index()
    {
        return response()->json(Prescription::with(['patient','doctor'])->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:employees,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'content' => 'required|string',
            'notes' => 'nullable|string'
        ]);

        $prescription = Prescription::create($data);
        return response()->json($prescription, 201);
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
            'notes' => 'nullable|string',
        ]);
        $p->update($data);
        return response()->json($p);
    }

    public function destroy($id)
    {
        Prescription::findOrFail($id)->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
