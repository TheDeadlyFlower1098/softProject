<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicineCheck;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    // Return missed activities (e.g., any patient without all morning/afternoon/night checks today)
    public function missedActivities(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());

        // simple approach: for each patient check if there is a medicine_check with all booleans true
        $patients = Patient::all();
        $missed = [];

        foreach ($patients as $p) {
            $check = MedicineCheck::where('patient_id', $p->id)->whereDate('date', $date)->first();
            if (!$check || !$check->morning || !$check->afternoon || !$check->night) {
                $missed[] = [
                    'patient' => $p->patient_name,
                    'patient_id' => $p->id,
                    'status' => $check ? 'partial' : 'none'
                ];
            }
        }

        return response()->json(['date' => $date, 'missed' => $missed]);
    }
}
