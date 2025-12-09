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

    // GET /medicine-checks (optional API)
    public function index()
    {
        return response()->json(MedicineCheck::paginate(20));
    }

    // POST /medicine-check (generic create – not used by dashboards)
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
            'morning'   => 'boolean',
            'afternoon' => 'boolean',
            'night'     => 'boolean',
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
     * Name:  medicinecheck.saveSingle
     */
    public function saveSingle(Request $request)
    {
        $user = auth()->user();
        $patient = $user->patient;   // requires User::patient() relationship

        if (! $patient) {
            abort(403, 'No patient record linked to this user.');
        }

        $request->validate([
            'morning'   => 'nullable|boolean',
            'afternoon' => 'nullable|boolean',
            'night'     => 'nullable|boolean',
        ]);

        $today = now()->toDateString();

        MedicineCheck::updateOrCreate(
            [
                'patient_id' => $patient->id,
                'date'       => $today,
            ],
            [
                'caregiver_id' => $user->id,
                'morning'      => $request->boolean('morning'),
                'afternoon'    => $request->boolean('afternoon'),
                'night'        => $request->boolean('night'),
            ]
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
        // For now we just pull all patients; you can later filter by caregiver, wing, etc.
        $patients = Patient::with('user')->get();

        // view: resources/views/caregiver.blade.php
        return view('caregiver', compact('patients'));
    }

    /**
     * Caregiver presses OK on patient list.
     * Route: POST /caregiver/save-today
     * Name:  caregiver.saveToday
     */
    public function saveMultiple(Request $request)
    {
        $user = auth()->user();          // caregiver user
        $caregiverId = $user->id;

        // Big array from form: patients[<id>][field]...
        $rows = $request->input('patients', []);

        // If somehow nothing was sent, just return
        if (!is_array($rows) || empty($rows)) {
            return redirect()
                ->route('caregiver.dashboard')
                ->with('success', 'No changes to save.');
        }

        $today = now()->toDateString();

        foreach ($rows as $row) {
            if (empty($row['patient_id'])) {
                continue;
            }

            $patientId = $row['patient_id'];

            // Only hit DB if at least one checkbox for this patient is present
            $hasAny =
                !empty($row['morning']) ||
                !empty($row['afternoon']) ||
                !empty($row['night']) ||
                !empty($row['breakfast'] ?? null) ||
                !empty($row['lunch'] ?? null) ||
                !empty($row['dinner'] ?? null);

            if (! $hasAny) {
                // Skip patients where caregiver didn’t tick anything
                continue;
            }

            MedicineCheck::updateOrCreate(
                [
                    'patient_id' => $patientId,
                    'date'       => $today,
                ],
                [
                    'caregiver_id' => $caregiverId,
                    // checkboxes: present => true, missing => false
                    'morning'   => !empty($row['morning']),
                    'afternoon' => !empty($row['afternoon']),
                    'night'     => !empty($row['night']),
                    // If these columns exist in your DB, you can uncomment:
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
