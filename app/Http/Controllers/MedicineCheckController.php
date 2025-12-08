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
            'patient_id'   => ['required', 'exists:patients,id'],
            'date'         => ['required', 'date'],
            'morning'      => ['nullable', 'in:taken,missed'],
            'afternoon'    => ['nullable', 'in:taken,missed'],
            'night'        => ['nullable', 'in:taken,missed'],
            'breakfast'    => ['nullable', 'in:taken,missed'],
            'lunch'        => ['nullable', 'in:taken,missed'],
            'dinner'       => ['nullable', 'in:taken,missed'],
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
            'patient_id'   => ['required', 'exists:patients,id'],
            'date'         => ['required', 'date'],
            'morning'      => ['nullable', 'in:taken,missed'],
            'afternoon'    => ['nullable', 'in:taken,missed'],
            'night'        => ['nullable', 'in:taken,missed'],
            'breakfast'    => ['nullable', 'in:taken,missed'],
            'lunch'        => ['nullable', 'in:taken,missed'],
            'dinner'       => ['nullable', 'in:taken,missed'],
        ]);

        $check->update($data);

        return response()->json($check);
    }

    public function saveForTodayFromDashboard(Request $request)
    {
        $user = auth()->user();
        $patient = $user->patient;   // requires User::patient() relationship

        if (! $patient) {
            abort(403, 'No patient record linked to this user.');
        }

        // We only care about the status fields here; patient/date come from context.
        $data = $request->validate([
            'morning'      => ['nullable', 'in:taken,missed'],
            'afternoon'    => ['nullable', 'in:taken,missed'],
            'night'        => ['nullable', 'in:taken,missed'],
            'breakfast'    => ['nullable', 'in:taken,missed'],
            'lunch'        => ['nullable', 'in:taken,missed'],
            'dinner'       => ['nullable', 'in:taken,missed'],
        ]);

        // Create or update today's record for this patient.
        // All status fields are stored as 'taken' / 'missed' / null (for unknown).
        $check = MedicineCheck::updateOrCreate(
            [
                'patient_id' => $patient->id,
                'date'       => today(),
            ],
            array_merge(
                [
                    'caregiver_id' => auth()->id(),
                ],
                [
                    'morning'   => $data['morning']   ?? null,
                    'afternoon' => $data['afternoon'] ?? null,
                    'night'     => $data['night']     ?? null,
                    'breakfast' => $data['breakfast'] ?? null,
                    'lunch'     => $data['lunch']     ?? null,
                    'dinner'    => $data['dinner']    ?? null,
                ]
            )
        );

        return back()->with('success', 'Medicine checklist saved.');
    }
}
