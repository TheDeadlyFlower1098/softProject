<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Roster;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RosterController extends Controller
{
    public function index()
    {
        return response()->json(Roster::orderBy('date','desc')->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'date' => 'required|date',
            'supervisor_id' => 'nullable|exists:users,id',
            'doctor_id' => 'nullable|exists:employees,id',
            'caregiver_1' => 'nullable|exists:users,id',
            'caregiver_2' => 'nullable|exists:users,id',
            'caregiver_3' => 'nullable|exists:users,id',
            'caregiver_4' => 'nullable|exists:users,id',
        ]);

        // Basic uniqueness check: ensure same caregiver not assigned multiple times
        $caregivers = collect([$data['caregiver_1'] ?? null, $data['caregiver_2'] ?? null, $data['caregiver_3'] ?? null, $data['caregiver_4'] ?? null])->filter();
        if ($caregivers->count() !== $caregivers->unique()->count()) {
            return response()->json(['error' => 'A caregiver cannot be assigned to multiple groups for the same roster'], 422);
        }

        $roster = Roster::create($data);
        return response()->json($roster, 201);
    }

    public function show($id)
    {
        return response()->json(Roster::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $roster = Roster::findOrFail($id);
        $data = $request->validate([
            'date' => 'sometimes|date',
            'supervisor_id' => 'nullable|exists:users,id',
            'doctor_id' => 'nullable|exists:employees,id',
            'caregiver_1' => 'nullable|exists:users,id',
            'caregiver_2' => 'nullable|exists:users,id',
            'caregiver_3' => 'nullable|exists:users,id',
            'caregiver_4' => 'nullable|exists:users,id',
        ]);

        $roster->update($data);
        return response()->json($roster);
    }

    public function destroy($id)
    {
        Roster::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
