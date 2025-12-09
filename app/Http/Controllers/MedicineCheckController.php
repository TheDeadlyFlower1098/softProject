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
        $patient = $user->patient;   // assumes User has ->patient relation

        if (! $patient) {
            abort(403, 'No patient record linked to this user.');
        }

        // We don’t really need patient_id / date from the form, we derive them:
        // if you still send them, you can keep a simple validate, but it's optional.
        // $request->validate([...]) could be removed or simplified.

        // Map each checkbox -> 'taken' or 'missed'
        $slots = ['morning', 'afternoon', 'night', 'breakfast', 'lunch', 'dinner'];

        $values = [];
        foreach ($slots as $slot) {
            // if checkbox is present => taken, else missed (or null if you prefer "unknown")
            $values[$slot] = $request->has($slot) ? 'taken' : 'missed';
        }

        $check = MedicineCheck::updateOrCreate(
            [
                'patient_id' => $patient->id,
                'date'       => today(),
            ],
            array_merge(
                [
                    // if you have an Employee relation, use employee id, otherwise user id is fine
                    'caregiver_id' => optional($user->employee)->id ?? $user->id,
                ],
                $values
            )
        );

        if (! $check) {
            $check = new MedicineCheck();
            $check->patient_id   = $patient->id;
            $check->caregiver_id = $caregiverId;
            $check->date         = now()->toDateString();  // or now() if your column is datetime
        }

        // Set checkbox values (unchecked -> 0)
        $check->morning   = $request->boolean('morning')   ? 1 : 0;
        $check->afternoon = $request->boolean('afternoon') ? 1 : 0;
        $check->night     = $request->boolean('night')     ? 1 : 0;

        $check->save();

        return redirect()
            ->route('dashboard')
            ->with('status', 'Medicine checklist updated for today.');
    }

}