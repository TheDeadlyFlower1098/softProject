<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorHomeController extends Controller
{
    public function index()
    {
        // Your existing logic to get upcoming and past appointments
        $userId = auth()->id(); // example
        $upcomingAppointments = Appointment::where('doctor_id', $userId)
            ->where('date', '>=', now())
            ->orderBy('date')
            ->get();

        $pastAppointments = Appointment::where('doctor_id', $userId)
            ->where('date', '<', now())
            ->orderBy('date', 'desc')
            ->get();

        return view('doctorHome', compact('upcomingAppointments', 'pastAppointments'));
    }

    // <-- Place the new method here inside the same class
    public function appointmentDetails($id)
    {
        $appointment = Appointment::with('patient.user', 'doctor')->findOrFail($id);

        return view('appointmentDetails', compact('appointment'));
    }
}
