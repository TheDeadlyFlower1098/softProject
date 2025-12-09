<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicineCheck;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Roster;
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
     * Name:  medicinecheck.saveSingle
     */
    public function saveSingle(Request $request)
    {
        $user    = auth()->user();
        $patient = $user->patient;   // assumes User has ->patient relation

        if (! $patient) {
            abort(403, 'No patient record linked to this user.');
        }

        $today = today()->toDateString();

        // Map each checkbox -> 'taken' or 'missed' (or adjust to booleans if your DB uses tinyints)
        $slots = ['morning', 'afternoon', 'night', 'breakfast', 'lunch', 'dinner'];

        $values = [];
        foreach ($slots as $slot) {
            $values[$slot] = $request->has($slot) ? 'taken' : 'missed';
            // or: $values[$slot] = $request->boolean($slot);
        }

        MedicineCheck::updateOrCreate(
            [
                'patient_id' => $patient->id,
                'date'       => $today,
            ],
            array_merge(
                [
                    'caregiver_id' => optional($user->employee)->id ?? $user->id,
                ],
                $values
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
     *
     * Shows the caregiver table with only the patients in this caregiver's
     * roster group for the selected date.
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();

        // Only allow caregivers
        if (! $user || optional($user->role)->name !== 'Caregiver') {
            abort(403);
        }

        // Which date are we looking at? (default: today)
        $selectedDate = $request->query('date', now()->toDateString());

        $patients      = collect();
        $assignedGroup = null;
        $roster        = null;

        // Find the employee row linked to this caregiver
        $employee = Employee::where('user_id', $user->id)->first();

        if ($employee) {
            // Get roster for that date
            $roster = Roster::whereDate('date', $selectedDate)->first();

            if ($roster) {
                // Work out which caregiver slot they occupy → which group
                if ($roster->caregiver_1 == $employee->id) {
                    $assignedGroup = 1;
                } elseif ($roster->caregiver_2 == $employee->id) {
                    $assignedGroup = 2;
                } elseif ($roster->caregiver_3 == $employee->id) {
                    $assignedGroup = 3;
                } elseif ($roster->caregiver_4 == $employee->id) {
                    $assignedGroup = 4;
                }

                // If assigned to a group, load only those patients
                if (! is_null($assignedGroup)) {
                    $patients = Patient::with('user')
                        ->where('group_id', $assignedGroup)
                        ->get();
                }
            }
        }

        // view: resources/views/caregiver.blade.php
        return view('caregiver', [
            'patients'      => $patients,
            'selectedDate'  => $selectedDate,
            'roster'        => $roster,
            'assignedGroup' => $assignedGroup,
        ]);
    }

    /**
     * Caregiver presses OK on patient list.
     * Route: POST /caregiver/save-today
     * Name:  caregiver.saveToday
     */
    public function saveMultiple(Request $request)
    {
        $user       = auth()->user();          // caregiver user
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
                    
                    'breakfast' => !empty($row['breakfast'] ?? null),
                    'lunch'     => !empty($row['lunch'] ?? null),
                    'dinner'    => !empty($row['dinner'] ?? null),
                ]
            );
        }

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Daily report saved.');
    }

}