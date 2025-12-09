<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicineCheck;
use Illuminate\Http\Request;
use App\Models\Prescription;
use Illuminate\Support\Carbon;
use App\Models\User;

class PatientDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // assumes User hasOne Patient relation: $user->patient
        $patient = $user->patient;

        if (! $patient) {
            abort(403, 'No patient record linked to this user.');
        }

        // ---- date filter ----
        $selectedDate = $request->query('date');

        if ($selectedDate) {
            try {
                $selectedDate = Carbon::parse($selectedDate)->toDateString();
            } catch (\Throwable $e) {
                $selectedDate = today()->toDateString();
            }
        } else {
            $selectedDate = today()->toDateString();
        }

        // Today's (or selected date's) medicine check
        $todayMedicineCheck = MedicineCheck::where('patient_id', $patient->id)
            ->whereDate('date', $selectedDate)
            ->first();

        // Caregiver name for that record (if any)
        $caregiverName = null;
        if ($todayMedicineCheck && $todayMedicineCheck->caregiver_id) {
            $caregiverUser = User::find($todayMedicineCheck->caregiver_id);

            if ($caregiverUser) {
                $fullName = trim(
                    ($caregiverUser->first_name ?? '') . ' ' . ($caregiverUser->last_name ?? '')
                );

                $caregiverName = $fullName !== '' ? $fullName : ($caregiverUser->name ?? null);
            }
        }

        // For now: no real appointments/prescriptions wired up
        $todayAppointments  = collect();
        $latestPrescription = null;

        return view('patient_dashboard', [
            'patient'            => $patient,
            'todayMedicineCheck' => $todayMedicineCheck,
            'todayAppointments'  => $todayAppointments,
            'latestPrescription' => $latestPrescription,
            'selectedDate'       => $selectedDate,
            'caregiverName'      => $caregiverName,
        ]);
    }
}