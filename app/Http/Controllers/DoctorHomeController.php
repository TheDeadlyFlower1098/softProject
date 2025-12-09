<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;   // <-- added

class DoctorHomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;

        // Determine whether user is doctor or patient
        $isDoctor = $user->role_id == 2;
        $isPatient = $user->role_id == 1;

        if ($isDoctor) {
            // Doctor → view appointments where they are the doctor
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
            // Patient → view appointments where they are the patient
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
        // Load appointment, patient + patient->user + doctor
        $appointment = Appointment::with(['patient.user', 'doctor'])
            ->findOrFail($id);

        // Logged-in doctor (same value stored in prescriptions.doctor_id)
        $doctorId = auth()->id();

        // Load ONLY prescriptions:
        //  - for this patient
        //  - AND written by this doctor
        $prescriptions = Prescription::where('patient_id', $appointment->patient_id)
            ->where('doctor_id', $doctorId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('appointmentDetails', compact('appointment', 'prescriptions'));
    }
}
