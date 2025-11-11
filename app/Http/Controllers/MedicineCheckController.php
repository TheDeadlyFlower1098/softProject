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
}
