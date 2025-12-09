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
    public function adminIndex()
    {
        // Grab all patients; eager-load user if you want their email, etc.
        $patients = Patient::with('user')->orderBy('patient_name')->get();

        $rows = $patients->map(function ($patient) {
            // Core charges from your helper
            $charges = $this->buildSummaryForPatient($patient->id);

            // Sum of all payments made so far
            $totalPaid = Payment::where('patient_id', $patient->id)->sum('amount');

            $remaining = max($charges['total'] - $totalPaid, 0);

            return [
                'patient'           => $patient,
                'total'             => $charges['total'],
                'totalPaid'         => $totalPaid,
                'remaining'         => $remaining,
                'days'              => $charges['days'],
                'appointmentsCount' => $charges['appointmentsCount'],
                'doseCount'         => $charges['doseCount'],
            ];
        });

        return view('payments', [
            'rows' => $rows,
        ]);
    }

    /**
     * Admin action: record an in-person cash payment.
     */
    public function recordPayment(Request $request, Patient $patient)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
        ]);

        Payment::create([
            'patient_id'  => $patient->id,
            'amount'      => $data['amount'],
            'description' => 'In-person cash payment',
            'paid_at'     => now(),
        ]);

        return redirect()
            ->route('payments')
            ->with('success', 'Payment recorded for '.$patient->patient_name);
    }
}