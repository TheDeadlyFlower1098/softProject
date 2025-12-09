<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicineCheck;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    // Static amounts per rules
    protected $rates = [
        'per_day'    => 10.00,
        'appointment'=> 50.00,
        'medicine'   => 5.00,
    ];

    protected function buildSummaryForPatient(int $patientId): array
    {
        $patient = Patient::findOrFail($patientId);

        $admission = $patient->admission_date
            ? Carbon::parse($patient->admission_date)
            : Carbon::today();

        $endDate = $patient->discharge_date
            ? Carbon::parse($patient->discharge_date)
            : Carbon::today();

        $days = $admission->diffInDays($endDate) + 1;

        $appointmentsCount = Appointment::where('patient_id', $patientId)
            ->whereBetween('date', [$admission->toDateString(), $endDate->toDateString()])
            ->count();

        $checks = MedicineCheck::where('patient_id', $patientId)
            ->whereBetween('date', [$admission->toDateString(), $endDate->toDateString()])
            ->get();

        $doseCount = $checks->sum(function ($row) {
            return ($row->morning ? 1 : 0)
                 + ($row->afternoon ? 1 : 0)
                 + ($row->night ? 1 : 0);
        });

        $dailyCharge       = $days * $this->rates['per_day'];
        $appointmentCharge = $appointmentsCount * $this->rates['appointment'];
        $medicineCharge    = $doseCount * $this->rates['medicine'];

        $total = $dailyCharge + $appointmentCharge + $medicineCharge;

        return [
            'patient'           => $patient,
            'days'              => $days,
            'dailyCharge'       => $dailyCharge,
            'appointmentsCount' => $appointmentsCount,
            'appointmentCharge' => $appointmentCharge,
            'doseCount'         => $doseCount,
            'medicineCharge'    => $medicineCharge,
            'total'             => $total,
        ];
    }

    /* ----------------------------------------
 * Payment page (Blade) endpoints
 * --------------------------------------*/

// GET /payments - just show empty form + auto patient if possible
    public function showForm(Request $request)
    {
        $user    = $request->user();
        $patient = null;

        if ($user && $user->role) {
            $roleName = $user->role->name;

            if ($roleName === 'Family') {
                $familyLink = $user->familyMember;

                if (! $familyLink || ! $familyLink->patient) {
                    abort(403, 'No linked patient found for this family account.');
                }

                $patient = $familyLink->patient;

            } elseif ($roleName === 'Patient') {
                $patient = $user->patient;

                if (! $patient) {
                    abort(403, 'No patient record linked to this account.');
                }
            }
        }

        // no summary yet – user will hit "ok" first
        return view('payments', [
            'patient' => $patient,
        ]);
    }

    // POST /payments - calculate summary for this patient
    public function calculateFromForm(Request $request)
    {
        $user = $request->user();
        $patientId = null;

        if ($user && $user->role) {
            $roleName = $user->role->name;

            if ($roleName === 'Family') {
                $familyLink = $user->familyMember;

                if (! $familyLink || ! $familyLink->patient) {
                    abort(403, 'No linked patient found for this family account.');
                }

                $patientId = $familyLink->patient_id;

            } elseif ($roleName === 'Patient') {
                $patient = $user->patient;

                if (! $patient) {
                    abort(403, 'No patient record linked to this account.');
                }

                $patientId = $patient->id;
            }
        }

        // Admin / Supervisor / others: manual patient_id
        if (! $patientId) {
            $validated = $request->validate([
                'patient_id' => ['required', 'integer', 'exists:patients,id'],
            ]);

            $patientId = (int) $validated['patient_id'];
        }

        // Base charges from stays/appointments/meds
        $charges = $this->buildSummaryForPatient($patientId);

        // Payments so far
        $totalPaid = Payment::where('patient_id', $patientId)->sum('amount');
        $remaining = max($charges['total'] - $totalPaid, 0);

        $summary = array_merge($charges, [
            'totalPaid' => $totalPaid,
            'remaining' => $remaining,
        ]);

        $patient = $charges['patient'];

        return view('payments', compact('summary', 'patient'));
    }

    // POST /payments/pay - record a payment and show updated summary
    public function makePayment(Request $request)
    {
        $user = $request->user();
        $patientId = null;

        if ($user && $user->role) {
            $roleName = $user->role->name;

            if ($roleName === 'Family') {
                $familyLink = $user->familyMember;

                if (! $familyLink || ! $familyLink->patient) {
                    abort(403, 'No linked patient found for this family account.');
                }

                $patientId = $familyLink->patient_id;

            } elseif ($roleName === 'Patient') {
                $patient = $user->patient;

                if (! $patient) {
                    abort(403, 'No patient record linked to this account.');
                }

                $patientId = $patient->id;
            }
        }

        // Admin / others can post patient_id from the form
        if (! $patientId) {
            $validatedIds = $request->validate([
                'patient_id' => ['required', 'integer', 'exists:patients,id'],
            ]);

            $patientId = (int) $validatedIds['patient_id'];
        }

        // Validate payment amount
        $validated = $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        $amount = (float) $validated['payment_amount'];

        // Store the payment
        Payment::create([
            'patient_id'  => $patientId,
            'amount'      => $amount,
            'description' => 'Manual payment',
            'paid_at'     => now(),
        ]);

        // Rebuild summary with new payment included
        $charges = $this->buildSummaryForPatient($patientId);
        $totalPaid = Payment::where('patient_id', $patientId)->sum('amount');
        $remaining = max($charges['total'] - $totalPaid, 0);

        $summary = array_merge($charges, [
            'totalPaid' => $totalPaid,
            'remaining' => $remaining,
        ]);

        $patient = $charges['patient'];

        return view('payments', compact('summary', 'patient'))
            ->with('success', 'Payment recorded successfully.');
    }
}