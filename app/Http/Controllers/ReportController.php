<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\MedicineCheck;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    /**
     * Show the Admin & Supervisor Report page.
     * We just need the most recent check date for the default picker.
     */
    public function viewReportPage()
    {
        $latestDate = MedicineCheck::max('date'); // e.g. "2025-12-04 00:00:00"

        $latestDate = $latestDate
            ? Carbon::parse($latestDate)->toDateString()    // "2025-12-04"
            : null;

        return view('admin_report', [
            'latestDate' => $latestDate,
        ]);
    }

    /**
     * JSON endpoint used by /admin-report to load missed activities.
     */
    public function missedActivities(Request $request)
    {
        $date    = $request->query('date');
        $filter  = $request->query('filter');
        $showAll = filter_var($request->query('all'), FILTER_VALIDATE_BOOLEAN);

        // If not showing all and no date chosen, default to latest
        if (!$showAll && empty($date)) {
            $date = \App\Models\MedicineCheck::max('date');
        }

        // Base query: start from medicine_checks so we *always* have a caregiver_id
        $query = DB::table('medicine_checks as mc')
            ->join('patients', 'patients.id', '=', 'mc.patient_id')
            ->leftJoin('employees as emp', 'emp.id', '=', 'mc.caregiver_id')
            ->select([
                'patients.id as patient_id',
                'patients.patient_name as patient',
                'mc.date as check_date',
                'mc.morning',
                'mc.afternoon',
                'mc.night',
                'emp.name as caretaker',   // <-- THIS is what JS reads as m.caretaker
            ]);

        if (!$showAll && $date) {
            $dateOnly = substr($date, 0, 10);
            $query->whereDate('mc.date', '=', $dateOnly);
        }


        // Text filter
        if ($filter) {
            $like = '%' . $filter . '%';
            $query->where(function ($q) use ($like) {
                $q->where('patients.patient_name', 'LIKE', $like)
                ->orWhere('emp.name', 'LIKE', $like)
                ->orWhere('mc.date', 'LIKE', $like);
            });
        }

        $rows = $query
            ->orderBy('patients.patient_name')
            ->get();

        // Helper to map DB value -> 'taken'/'missed'/null
        $mapStatus = function ($val) {
            if (is_null($val)) return null;
            return $val ? 'taken' : 'missed';
        };

        $report  = [];
        $summary = [
            'total_patients'  => 0,
            'fully_missed'    => 0,
            'partial_missed'  => 0,
            'fully_completed' => 0,
        ];

        foreach ($rows as $row) {
            $morning   = $mapStatus($row->morning);
            $afternoon = $mapStatus($row->afternoon);
            $night     = $mapStatus($row->night);

            $slots = [$morning, $afternoon, $night];

            if (!array_filter($slots)) {
                $status = 'none';
            } elseif (!in_array('missed', $slots, true)) {
                $status = 'complete';
            } else {
                $status = 'partial';
            }

            $report[] = [
                'patient'   => $row->patient,
                'caretaker' => $row->caretaker,   // <--- name from employees table
                'status'    => $status,
                'date'      => $row->check_date,

                'morning'   => $morning,
                'afternoon' => $afternoon,
                'night'     => $night,

                // still null until you wire meals in
                'breakfast' => null,
                'lunch'     => null,
                'dinner'    => null,
            ];

            $summary['total_patients']++;
            if ($status === 'none') {
                $summary['fully_missed']++;
            } elseif ($status === 'complete') {
                $summary['fully_completed']++;
            } else {
                $summary['partial_missed']++;
            }
        }

        return response()->json([
            'summary' => $summary,
            'report'  => $report,
        ]);
    }

}
