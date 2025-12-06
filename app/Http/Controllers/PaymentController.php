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

    /* ----------------------------------------
     * Helper: core calculation logic
     * --------------------------------------*/
    protected function buildSummaryForPatient(int $patientId): array
    {
        $patient = Patient::findOrFail($patientId);

        // Adjust these field names if yours differ
        $admission = $patient->admission_date
            ? Carbon::parse($patient->admission_date)
            : Carbon::today();

        // If you have discharge_date, use it, otherwise bill up to today
        $endDate = $patient->discharge_date
            ? Carbon::parse($patient->discharge_date)
            : Carbon::today();

        // Number of days (inclusive)
        $days = $admission->diffInDays($endDate) + 1;

        // Appointments for this patient within stay window
        $appointmentsCount = Appointment::where('patient_id', $patientId)
            ->whereBetween('date', [$admission->toDateString(), $endDate->toDateString()])
            ->count();

        // Medicine doses: assumes medicine_checks has boolean/int columns morning/afternoon/night
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
     * API-ish endpoints (JSON)
     * --------------------------------------*/

    public function index()
    {
        return response()->json(Payment::with('patient')->paginate(20));
    }

    public function calculateForPatient($patientId)
    {
        $summary = $this->buildSummaryForPatient((int) $patientId);

        return response()->json([
            'patient_id'       => $summary['patient']->id,
            'days'             => $summary['days'],
            'appointments'     => $summary['appointmentsCount'],
            'medicine_doses'   => $summary['doseCount'],
            'total_due'        => number_format($summary['total'], 2),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'  => 'required|exists:patients,id',
            'amount'      => 'required|numeric',
            'description' => 'nullable|string',
            'paid_at'     => 'nullable|date'
        ]);

        $p = Payment::create($data);

        return response()->json($p, 201);
    }

    /* ----------------------------------------
     * Payment page (Blade) endpoints
     * --------------------------------------*/

    // GET /payments - just show empty form
    public function showForm()
    {
        return view('payments');
    }

    // POST /payments - form submit from your page
    public function calculateFromForm(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
        ]);

        $summary = $this->buildSummaryForPatient((int) $validated['patient_id']);

        Payment::create([
            'patient_id'  => $validated['patient_id'],
            'amount'      => $summary['total'],
            'description' => 'Automated billing calculation',
            'paid_at'     => now(),
        ]);


        // Pass summary array to the Blade view
        return view('payments', compact('summary'));
    }
}
