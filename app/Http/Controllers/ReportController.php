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
        if (! $showAll && empty($date)) {
            $date = MedicineCheck::max('date');
        }

        // Base query: start from medicine_checks so we *always* have a caregiver_id (if set)
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
                'mc.breakfast',
                'mc.lunch',
                'mc.dinner',
                'emp.name as caretaker',   // JS reads this as m.caretaker
            ]);

        if (! $showAll && $date) {
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

        /**
         * Normalize DB value -> 'taken' / 'missed' / null
         * Handles:
         *  - strings: 'taken' / 'missed'
         *  - booleans / ints: 1 / 0
         */
        $mapStatus = function ($val) {
            if (is_null($val)) {
                return null;
            }

            // Already strings
            if ($val === 'taken' || $val === 'missed') {
                return $val;
            }

            // Boolean / int / "1"/"0"
            if ($val === true || $val === 1 || $val === '1') {
                return 'taken';
            }

            if ($val === false || $val === 0 || $val === '0') {
                return 'missed';
            }

            // Anything else → unknown
            return null;
        };

        $report  = [];
        $summary = [
            'total_patients'  => 0,
            'fully_missed'    => 0,
            'partial_missed'  => 0,
            'fully_completed' => 0,
        ];

        foreach ($rows as $row) {
            // Map meds
            $morning   = $mapStatus($row->morning);
            $afternoon = $mapStatus($row->afternoon);
            $night     = $mapStatus($row->night);

            // Map meals
            $breakfast = $mapStatus($row->breakfast);
            $lunch     = $mapStatus($row->lunch);
            $dinner    = $mapStatus($row->dinner);

            // ALL 6 slots
            $slots = [
                $morning,
                $afternoon,
                $night,
                $breakfast,
                $lunch,
                $dinner,
            ];

            // Only consider non-null slots for "all taken" / "all missed" tests
            $known = array_values(
                array_filter($slots, fn ($v) => ! is_null($v))
            );

            $hasData   = count($known) > 0;
            $allTaken  = $hasData && collect($known)->every(fn ($v) => $v === 'taken');
            $allMissed = $hasData && collect($known)->every(fn ($v) => $v === 'missed');

            // Display status (used by Blade)
            if ($allTaken) {
                $status = 'complete';          // "All checks done ✅"
            } elseif ($allMissed) {
                $status = 'none';              // "All checks missing"
            } elseif (! $hasData) {
                // No data at all – treat as partial-ish in summary (no full counts)
                $status = 'partial';
            } else {
                // Mix of taken/missed
                $status = 'partial';           // "Some checks missing"
            }

            // Build per-patient record for the frontend
            $report[] = [
                'patient'   => $row->patient,
                'caretaker' => $row->caretaker,
                'status'    => $status,
                'date'      => $row->check_date,

                'morning'   => $morning,
                'afternoon' => $afternoon,
                'night'     => $night,

                'breakfast' => $breakfast,
                'lunch'     => $lunch,
                'dinner'    => $dinner,
            ];

            // ---- SUMMARY COUNTS ----
            $summary['total_patients']++;

            if ($allTaken) {
                $summary['fully_completed']++;
            } elseif ($allMissed) {
                $summary['fully_missed']++;
            } elseif ($hasData) {
                // Has at least some data but not all taken/missed
                $summary['partial_missed']++;
            }
            // If ! $hasData, we don't increment any of the three buckets,
            // just total_patients.
        }

        return response()->json([
            'summary' => $summary,
            'report'  => $report,
        ]);
    }
}
