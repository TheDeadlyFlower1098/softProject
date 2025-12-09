<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Roster;
use App\Models\Employee;
use App\Models\Patient;

class DoctorHomeController extends Controller
{
    /**
     * Restrict certain actions to Admin + Supervisor only.
     */
    protected function ensureAdminOrSupervisor()
    {
        $user     = auth()->user();
        $roleName = optional($user->role)->name;

        if (! $user || ! in_array($roleName, ['Admin', 'Supervisor'])) {
            abort(403);
        }
    }

    /**
     * Doctor / Patient home page
     * - Doctors see their appointments
     * - Patients see their appointments
     */
    public function index()
    {
        $user   = auth()->user();
        $userId = $user->id;

        // Determine whether user is doctor or patient (existing numeric mapping)
        $isDoctor  = $user->role_id == 2;
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
        } else {
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

    /**
     * Show details for a single appointment, plus prescriptions
     * for this patient written by the logged-in doctor.
     */
    public function appointmentDetails($id)
    {
        // Load appointment including patient + patient->user + doctor
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

    /**
     * Show doctor appointment creation page (Admin + Supervisor only).
     * GET /doctor-appointments
     */
    public function createAppointment(Request $request)
    {
        $this->ensureAdminOrSupervisor();

        $selectedDate = $request->query('date', now()->toDateString());

        $doctors = collect();

        // Find roster for that date and pull out the doctor on duty
        $roster = Roster::whereDate('date', $selectedDate)->first();

        if ($roster && $roster->doctor_id) {
            $employee = Employee::find($roster->doctor_id);
            if ($employee && $employee->name) {
                // We will use Employee model as "doctor" here
                $doctors->push($employee);
            }
        }

        return view('doctor_appointments', [
            'selectedDate' => $selectedDate,
            'doctors'      => $doctors,
            'patientName'  => null,
        ]);
    }

    /**
     * Store a new appointment (Admin + Supervisor only).
     * POST /doctor-appointments
     */
    public function storeAppointment(Request $request)
    {
        $this->ensureAdminOrSupervisor();

        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id'  => 'required|exists:employees,id',
            'date'       => 'required|date',
            'notes'      => 'nullable|string|max:1000', // <-- notes now supported
        ]);

        Appointment::create([
            'patient_id' => $data['patient_id'],
            'doctor_id'  => $data['doctor_id'],
            'date'       => $data['date'],
            'status'     => 'scheduled',
            'notes'      => $data['notes'] ?? null, // <-- save notes
        ]);

        return redirect()
            ->route('doctor.appointments', ['date' => $data['date']])
            ->with('success', 'Appointment created successfully.');
    }

    /**
     * Small JSON endpoint for patient lookup by ID.
     * GET /api/patients/{patient}
     */
    public function lookupPatient(Patient $patient)
    {
        $this->ensureAdminOrSupervisor();

        return response()->json([
            'id'   => $patient->id,
            'name' =>
                $patient->user
                    ? $patient->user->first_name . ' ' . $patient->user->last_name
                    : null,
        ]);
    }
}
