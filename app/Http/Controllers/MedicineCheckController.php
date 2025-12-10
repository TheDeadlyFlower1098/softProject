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

        $selectedDate = $request->query('date', now()->toDateString());

        $patients      = collect();
        $assignedGroup = null;
        $roster        = null;
        $existingChecks = collect();

        $employee = Employee::where('user_id', $user->id)->first();

        if ($employee) {
            $roster = Roster::whereDate('date', $selectedDate)->first();

            if ($roster) {
                if ($roster->caregiver_1 == $employee->id) {
                    $assignedGroup = 1;
                } elseif ($roster->caregiver_2 == $employee->id) {
                    $assignedGroup = 2;
                } elseif ($roster->caregiver_3 == $employee->id) {
                    $assignedGroup = 3;
                } elseif ($roster->caregiver_4 == $employee->id) {
                    $assignedGroup = 4;
                }

                if (! is_null($assignedGroup)) {
                    $patients = Patient::with('user')
                        ->where('group_id', $assignedGroup)
                        ->get();
                }
            }
        }

        // 🔹 Get any existing medicine checks for these patients on this date
        if ($patients->isNotEmpty()) {
            $existingChecks = MedicineCheck::forDate($selectedDate)
                ->whereIn('patient_id', $patients->pluck('id'))
                ->get()
                ->keyBy('patient_id');   // key by patient_id for quick lookup
        }

        return view('caregiver', [
            'patients'       => $patients,
            'selectedDate'   => $selectedDate,
            'roster'         => $roster,
            'assignedGroup'  => $assignedGroup,
            'existingChecks' => $existingChecks,
        ]);
    }


    /**
     * Caregiver presses OK on patient list.
     * Route: POST /caregiver/save-today
     * Name:  caregiver.saveToday
     */
    public function saveMultiple(Request $request)
    {
        $user = auth()->user();

        // Only caregivers
        if (! $user || optional($user->role)->name !== 'Caregiver') {
            abort(403);
        }

        $employee     = Employee::where('user_id', $user->id)->first();
        $caregiverId  = optional($employee)->id ?? $user->id;

        $rows = $request->input('patients', []);

        if (!is_array($rows) || empty($rows)) {
            return redirect()
                ->route('caregiver.dashboard')
                ->with('success', 'No changes to save.');
        }

        $today = now()->toDateString();
        $slots = ['morning', 'afternoon', 'night', 'breakfast', 'lunch', 'dinner'];

        foreach ($rows as $row) {
            if (empty($row['patient_id'])) {
                continue;
            }

            $patientId = $row['patient_id'];

            // 🔒 Skip if a record already exists for this patient & date
            $alreadyExists = MedicineCheck::forDate($today)
                ->forPatient($patientId)
                ->exists();

            if ($alreadyExists) {
                continue;
            }

            // Did the caregiver tick at least one box?
            $hasAny =
                !empty($row['morning'] ?? null)   ||
                !empty($row['afternoon'] ?? null) ||
                !empty($row['night'] ?? null)     ||
                !empty($row['breakfast'] ?? null) ||
                !empty($row['lunch'] ?? null)     ||
                !empty($row['dinner'] ?? null);

            if (! $hasAny) {
                continue;
            }

            // Map to 'taken' / 'missed' to stay consistent with your model
            $values = [];
            foreach ($slots as $slot) {
                $values[$slot] = !empty($row[$slot] ?? null)
                    ? 'taken'
                    : 'missed';
            }

            MedicineCheck::create(
                array_merge(
                    [
                        'patient_id'   => $patientId,
                        'date'         => $today,
                        'caregiver_id' => $caregiverId,
                    ],
                    $values
                )
            );
        }

        return redirect()
            ->route('caregiver.dashboard')
            ->with('success', 'Daily report saved.');
    }


}