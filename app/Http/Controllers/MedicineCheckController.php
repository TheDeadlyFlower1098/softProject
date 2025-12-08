<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicineCheck;
use App\Models\Patient;
use Illuminate\Http\Request;

class MedicineCheckController extends Controller
{
    /* ----------------------------------------------------------------------
     |  API-style endpoints (JSON)
     * ---------------------------------------------------------------------*/

    // GET /api/medicine-checks (if you ever use it)
    public function index()
    {
        return response()->json(MedicineCheck::paginate(20));
    }

    // POST /medicine-check  (generic create – not used by dashboards)
    public function store(Request $request)
    {
        $data = $request->validate([
            'caregiver_id' => 'required|exists:users,id',
            'patient_id'   => 'required|exists:patients,id',
            'date'         => 'required|date',
            'morning'      => 'boolean',
            'afternoon'    => 'boolean',
            'night'        => 'boolean',
        ]);

        $check = MedicineCheck::create($data);

        return response()->json($check, 201);
    }

    // GET /medicine-check/{id}
    public function show($id)
    {
        return response()->json(MedicineCheck::findOrFail($id));
    }

    // PUT/PATCH /medicine-check/{id}
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

    /* ----------------------------------------------------------------------
     |  PATIENT DASHBOARD  (one logged-in patient)
     * ---------------------------------------------------------------------*/

    /**
     * Patient presses "Save" on their own dashboard.
     * Route: POST /patient_dashboard/medicine-check
     * Name:  medicinecheck.saveToday
     */
    public function saveSingle(Request $request)
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

        return redirect()
            ->route('patient.dashboard')
            ->with('success', 'Medicine checklist saved.');
    }

    /* ----------------------------------------------------------------------
     |  CAREGIVER DASHBOARD  (list of many patients)
     * ---------------------------------------------------------------------*/

    /**
     * GET /caregiver
     * Name: caregiver.dashboard
     * Shows the caregiver table with all patients.
     */
    public function dashboard()
    {
        // For now we just pull all patients; later you can filter
        $patients = Patient::with('user')->get();

        // view file: resources/views/caregiver.blade.php
        return view('caregiver', compact('patients'));
    }

    /**
     * Caregiver presses OK on patient list.
     * Route: POST /caregiver/save-today
     * Name:  caregiver.saveToday
     */
    public function saveMultiple(Request $request)
    {
        $user = auth()->user();          // caregiver
        $caregiverId = $user->id;

        // "patients" is the big array from the caregiver form
        $rows = $request->input('patients', []);

        foreach ($rows as $row) {
            if (empty($row['patient_id'])) {
                continue;
            }

            $patientId = $row['patient_id'];

            MedicineCheck::updateOrCreate(
                [
                    'patient_id' => $patientId,
                    'date'       => today(),
                ],
                [
                    'caregiver_id' => $caregiverId,

                    // checkboxes: present => 1, missing => 0
                    'morning'   => !empty($row['morning']),
                    'afternoon' => !empty($row['afternoon']),
                    'night'     => !empty($row['night']),
                    // If you later add meal columns to the table, you can map them here:
                    // 'breakfast' => !empty($row['breakfast'] ?? null),
                    // 'lunch'     => !empty($row['lunch'] ?? null),
                    // 'dinner'    => !empty($row['dinner'] ?? null),
                ]
            );
        }

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Daily report saved.');
    }
}
