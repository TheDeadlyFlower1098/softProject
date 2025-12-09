<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * Show the list of all patients (with optional search filter).
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $patients = Patient::with(['user', 'group'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $like = '%' . $search . '%';

                    $q->where('patient_name', 'like', $like)
                      ->orWhere('patient_identifier', 'like', $like)
                      ->orWhere('emergency_contact_name', 'like', $like)
                      ->orWhere('emergency_contact_phone', 'like', $like)
                      // optionally search by linked user name as well
                      ->orWhereHas('user', function ($uq) use ($like) {
                          $uq->where('name', 'like', $like);
                      });
                });
            })
            ->orderBy('patient_name')
            ->get();

        return view('patientsList', compact('patients'));
    }

    /**
     * Show the additional information page for a single patient.
     */
    public function additional(Patient $patient)
    {
        // Route model binding: {patient} in the URL becomes $patient here
        return view('patientAdditional', [
            'patient' => $patient,
        ]);
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
