<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicineCheck;
use Illuminate\Http\Request;

class MedicineCheckController extends Controller
{
    public function index()
    {
        return response()->json(MedicineCheck::paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'caregiver_id' => 'required|exists:users,id',
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',
            'morning' => 'boolean',
            'afternoon' => 'boolean',
            'night' => 'boolean'
        ]);

        $check = MedicineCheck::create($data);
        return response()->json($check, 201);
    }

    public function show($id)
    {
        return response()->json(MedicineCheck::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $check = MedicineCheck::findOrFail($id);
        $data = $request->validate([
            'morning' => 'boolean',
            'afternoon' => 'boolean',
            'night' => 'boolean'
        ]);
        $check->update($data);
        return response()->json($check);
    }

    public function saveForTodayFromDashboard(Request $request)
    {
        $user = auth()->user();
        $patient = $user->patient;   // requires User::patient() relationship

        if (!$patient) {
            abort(403, 'No patient record linked to this user.');
        }

        // Only booleans for checkboxes; they can be missing if unchecked
        $request->validate([
            'morning'   => 'nullable|boolean',
            'afternoon' => 'nullable|boolean',
            'night'     => 'nullable|boolean',
        ]);

        // Create or update today's record for this patient
        $check = MedicineCheck::updateOrCreate(
            [
                'patient_id' => $patient->id,
                'date'       => today(),
            ],
            [
                // You can decide what caregiver_id should be:
                // - current user
                // - or null if this is self-reported
                'caregiver_id' => $user->id,

                'morning'   => $request->boolean('morning'),
                'afternoon' => $request->boolean('afternoon'),
                'night'     => $request->boolean('night'),
            ]
        );

        return back()->with('success', 'Medicine checklist saved.');
    }
}
