<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;

class DoctorHomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        // Determine whether user is doctor or patient
        // (Change the numbers if your role IDs are different)
        $isDoctor = $user->role_id == 2;
        $isPatient = $user->role_id == 1;

        if ($isDoctor) {
            // User is a doctor → show appointments where they are the doctor
            $upcomingAppointments = Appointment::where('doctor_id', $userId)
                ->where('date', '>=', now())
                ->orderBy('date')
                ->get();

            $pastAppointments = Appointment::where('doctor_id', $userId)
                ->where('date', '<', now())
                ->orderBy('date', 'desc')
                ->get();
        }
        else {
            // User is a patient → show appointments where they are the patient
            $upcomingAppointments = Appointment::where('patient_id', $userId)
                ->where('date', '>=', now())
                ->orderBy('date')
                ->get();

            $pastAppointments = Appointment::where('patient_id', $userId)
                ->where('date', '<', now())
                ->orderBy('date', 'desc')
                ->get();
        }

        return view('doctorHome', compact('upcomingAppointments', 'pastAppointments'));
    }
    public function appointmentDetails($id)
{
    $appointment = Appointment::with('patient')->findOrFail($id);
    return view('appointmentDetails', compact('appointment'));
}

}
