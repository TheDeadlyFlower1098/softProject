<?php

namespace App\Http\Controllers;

use App\Models\Roster;
use App\Models\Employee;
use Illuminate\Http\Request;

class RosterController extends Controller
{
    /**
     * Show the roster dashboard (viewable by everyone who is logged in).
     * Allows filtering by date (?date=YYYY-MM-DD).
     */
    
public function dashboard(Request $request)
{
    $date = $request->input('date', now()->toDateString());

    $roster = Roster::whereDate('date', $date)->first();

    $employeesById = collect();

    if ($roster) {
        $ids = collect([
            $roster->supervisor_id,
            $roster->doctor_id,
            $roster->caregiver_1,
            $roster->caregiver_2,
            $roster->caregiver_3,
            $roster->caregiver_4,
        ])->filter()->unique();

        if ($ids->isNotEmpty()) {
            $employeesById = Employee::whereIn('id', $ids)->get()->keyBy('id');
        }
    }

    // === NEW: figure out what the logged-in user is scheduled as ===
    $currentUser      = auth()->user();
    $currentEmployee  = null;
    $currentAssignment = null;

    if ($currentUser) {
        $currentEmployee = Employee::where('user_id', $currentUser->id)->first();
    }

    if ($roster && $currentEmployee) {
        $empId = $currentEmployee->id;

        if ($roster->supervisor_id == $empId) {
            $currentAssignment = [
                'type'  => 'Supervisor',
                'group' => null,
            ];
        } elseif ($roster->doctor_id == $empId) {
            $currentAssignment = [
                'type'  => 'Doctor',
                'group' => null,
            ];
        } elseif ($roster->caregiver_1 == $empId) {
            $currentAssignment = [
                'type'  => 'Caregiver',
                'group' => 'Group 1',
            ];
        } elseif ($roster->caregiver_2 == $empId) {
            $currentAssignment = [
                'type'  => 'Caregiver',
                'group' => 'Group 2',
            ];
        } elseif ($roster->caregiver_3 == $empId) {
            $currentAssignment = [
                'type'  => 'Caregiver',
                'group' => 'Group 3',
            ];
        } elseif ($roster->caregiver_4 == $empId) {
            $currentAssignment = [
                'type'  => 'Caregiver',
                'group' => 'Group 4',
            ];
        }
    }

    return view('rosterDashboard', [
        'selectedDate'      => $date,
        'roster'            => $roster,
        'employeesById'     => $employeesById,
        'currentEmployee'   => $currentEmployee,
        'currentAssignment' => $currentAssignment,
    ]);
}
    
    public function create(Request $request)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, 'You must be logged in.');
        }

        // Look at the *actual* role name from DB
        $roleName = strtolower(optional($user->role)->name ?? '');
        // TEMPORARY OVERRIDE FOR TESTING
        // $roleName = 'admin';

        // Only Admin & Supervisor may create rosters
        if (! in_array($roleName, ['admin', 'supervisor'], true)) {
            abort(403, "You must be Admin or Supervisor to create rosters. Your role: {$roleName}");
        }

        $date = $request->input('date', now()->toDateString());

        $existingRoster = Roster::whereDate('date', $date)->first();

        $supervisors = Employee::where('role', 'Supervisor')
            ->orderBy('name')
            ->get();

        $doctors = Employee::where('role', 'Doctor')
            ->orderBy('name')
            ->get();

        $caregivers = Employee::where('role', 'Caregiver')
            ->orderBy('name')
            ->get();

        return view('new_roster', [
            'date'        => $date,
            'existing'    => $existingRoster,
            'supervisors' => $supervisors,
            'doctors'     => $doctors,
            'caregivers'  => $caregivers,
        ]);
    }

    /**
     * Store / update a roster for a given date.
     * Only Admin & Supervisor may submit.
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (! $user) {
            abort(403, 'You must be logged in.');
        }

        // TEMPORARY OVERRIDE FOR TESTING
        // $roleName = 'admin';
        $roleName = strtolower(optional($user->role)->name ?? '');

        if (! in_array($roleName, ['admin', 'supervisor'], true)) {
            abort(403, "You must be Admin or Supervisor to save rosters. Your role: {$roleName}");
        }

        $data = $request->validate([
            'date'         => ['required', 'date'],
            'supervisor_id'=> ['nullable', 'exists:employees,id'],
            'doctor_id'    => ['nullable', 'exists:employees,id'],
            'caregiver_1'  => ['nullable', 'exists:employees,id'],
            'caregiver_2'  => ['nullable', 'exists:employees,id'],
            'caregiver_3'  => ['nullable', 'exists:employees,id'],
            'caregiver_4'  => ['nullable', 'exists:employees,id'],
        ]);

        $roster = Roster::updateOrCreate(
            ['date' => $data['date']],
            $data
        );

        return redirect()
            ->route('roster.dashboard', ['date' => $roster->date->toDateString()])
            ->with('status', 'Roster saved successfully.');
    }

}
