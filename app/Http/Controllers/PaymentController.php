<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\MedicineCheck;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    // Calculate static amounts per rules in your doc
    protected $rates = [
        'per_day' => 10.00,
        'appointment' => 50.00,
        'medicine' => 5.00
    ];

    public function index()
    {
        return response()->json(Payment::with('patient')->paginate(20));
    }

    public function calculateForPatient($patientId)
    {
        $patient = Patient::findOrFail($patientId);

        // Here we do a naive calculation; refine as you need
        $admission = $patient->admission_date ? Carbon::parse($patient->admission_date) : null;
        $today = Carbon::today();

        // days chargeable:
        $days = $admission ? $admission->diffInDays($today) : 0;

        $appointments = Appointment::where('patient_id',$patientId)->count();
        $medicineCount = MedicineCheck::where('patient_id',$patientId)
            ->whereDate('date', '<=', $today)->count(); // simple approach

        $total = ($days * $this->rates['per_day']) + ($appointments * $this->rates['appointment']) + ($medicineCount * $this->rates['medicine']);

        return response()->json([
            'patient_id' => $patientId,
            'days' => $days,
            'appointments' => $appointments,
            'medicine_checks' => $medicineCount,
            'total_due' => number_format($total, 2)
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'paid_at' => 'nullable|date'
        ]);

        $p = Payment::create($data);
        return response()->json($p, 201);
    }
}
