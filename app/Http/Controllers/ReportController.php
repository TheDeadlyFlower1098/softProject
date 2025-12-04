<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MedicineCheck;
use App\Models\Patient;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    //Return missed activities per patient
    public function missedActivities(Request $request)
    {
        $date = $request->get('date');
        $filter = $request->get('filter');
        $showAll = $request->boolean('all', false);

        $patients = Patient::all();
        $report = [];

        foreach ($patients as $p) {
            $checkQuery = MedicineCheck::where('patient_id', $p->id);

            if (!$showAll && $date) {
                $checkQuery->whereDate('date', $date);
            }

            $check = $checkQuery->orderBy('date', 'desc')->first();

            // Determine status
            $status = 'none'; // fully missed
            if ($check) {
                $status = (!$check->morning || !$check->afternoon || !$check->night)
                    ? 'partial'
                    : 'complete';
            }

            // Get caretaker name from Employee table
            $caretakerName = null;
            if ($check && $check->caregiver_id) {
                $caretaker = \App\Models\Employee::where('id', $check->caregiver_id)
                            ->where('role', 'caregiver')
                            ->first();
                $caretakerName = $caretaker ? $caretaker->name : 'Unknown';
            }

            $report[] = [
                'patient' => $p->patient_name,
                'patient_id' => $p->id,
                'caretaker' => $caretakerName,
                'status' => $status,
                'date' => $check ? $check->date->toDateString() : 'No record',
                'morning' => $check ? ($check->morning ? 'taken' : 'missed') : null,
                'afternoon' => $check ? ($check->afternoon ? 'taken' : 'missed') : null,
                'night' => $check ? ($check->night ? 'taken' : 'missed') : null,
                'breakfast' => $check ? ($check->breakfast ?? 'unknown') : null,
                'lunch' => $check ? ($check->lunch ?? 'unknown') : null,
                'dinner' => $check ? ($check->dinner ?? 'unknown') : null,
            ];
        }

        // Apply search filter
        if ($filter) {
            $filter = strtolower($filter);
            $report = array_filter($report, function ($m) use ($filter) {
                return str_contains(strtolower($m['patient']), $filter)
                    || str_contains((string)$m['patient_id'], $filter)
                    || ($m['caretaker'] && str_contains(strtolower($m['caretaker']), $filter))
                    || str_contains(strtolower($m['status']), $filter)
                    || ($m['date'] && str_contains(strtolower($m['date']), $filter))
                    || ($m['morning'] && str_contains(strtolower($m['morning']), $filter))
                    || ($m['afternoon'] && str_contains(strtolower($m['afternoon']), $filter))
                    || ($m['night'] && str_contains(strtolower($m['night']), $filter))
                    || ($m['breakfast'] && str_contains(strtolower($m['breakfast']), $filter))
                    || ($m['lunch'] && str_contains(strtolower($m['lunch']), $filter))
                    || ($m['dinner'] && str_contains(strtolower($m['dinner']), $filter));
            });

            $report = array_values($report);
        }

        // Summary
        $summary = [
            'total_patients' => count($report),
            'fully_missed' => count(array_filter($report, fn($m) => $m['status']==='none')),
            'partial_missed' => count(array_filter($report, fn($m) => $m['status']==='partial')),
            'fully_completed' => count(array_filter($report, fn($m) => $m['status']==='complete')),
        ];

        return response()->json([
            'report' => $report,
            'summary' => $summary
        ]);
    }


    
    /**
     * Render the admin report page
     */
    public function viewReportPage()
    {
        // Get the latest medicine check date
        $latestDate = MedicineCheck::orderBy('date', 'desc')->value('date');

        // Fallback to today if no records exist
        $latestDate = $latestDate ? Carbon::parse($latestDate)->toDateString() : now()->toDateString();

        return view('admin_report', compact('latestDate'));
    }
}
