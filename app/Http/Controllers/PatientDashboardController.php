<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicineCheck;
use Illuminate\Http\Request;
use App\Models\Prescription;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $patient = $user->patient;

        if (!$patient) {
            abort(403, 'No patient record linked to this user.');
        }

        $todayAppointments = Appointment::with('doctor')
            ->where('patient_id', $patient->id)
            ->whereDate('date', now()->toDateString())
            ->orderBy('date')
            ->get();

        $todayMedicineCheck = MedicineCheck::where('patient_id', $patient->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        // NEW: latest prescription with items
        $latestPrescription = Prescription::with(['items', 'doctor'])
            ->where('patient_id', $patient->id)
            ->latest()
            ->first();

        return view('patient_dashboard', [
            'user'               => $user,
            'patient'            => $patient,
            'todayAppointments'  => $todayAppointments,
            'todayMedicineCheck' => $todayMedicineCheck,
            'latestPrescription' => $latestPrescription,
        ]);
    }
}