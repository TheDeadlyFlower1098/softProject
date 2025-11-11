<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $patients = Patient::with('group')->paginate(20);
        return response()->json($patients);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_identifier' => 'required|unique:patients,patient_identifier',
            'patient_name' => 'required|string',
            'group_id' => 'nullable|exists:groups,id',
            'admission_date' => 'nullable|date',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
            'family_code' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $patient = Patient::create($data);
        return response()->json($patient, 201);
    }

    public function show($id)
    {
        $patient = Patient::with(['appointments','group'])->findOrFail($id);
        return response()->json($patient);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $data = $request->validate([
            'patient_name' => 'sometimes|required|string',
            'group_id' => 'nullable|exists:groups,id',
            'admission_date' => 'nullable|date',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string',
        ]);

        $patient->update($data);

        return response()->json($patient);
    }

    public function destroy($id)
    {
        Patient::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
