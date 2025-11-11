<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Roster;
use App\Models\Employee;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(Appointment::with(['patient','doctor'])->paginate(20));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:employees,id',
            'date' => 'required|date_format:Y-m-d H:i:s',
            'notes' => 'nullable|string'
        ]);

        $appointment = Appointment::create($data);
        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        return response()->json(Appointment::with(['patient','doctor'])->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $a = Appointment::findOrFail($id);
        $data = $request->validate([
            'date' => 'sometimes|date_format:Y-m-d H:i:s',
            'notes' => 'nullable|string',
            'status' => 'nullable|string'
        ]);
        $a->update($data);
        return response()->json($a);
    }

    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
