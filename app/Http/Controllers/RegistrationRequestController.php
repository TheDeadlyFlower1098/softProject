<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\User;
use App\Models\Role;
use App\Models\Patient;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegistrationRequestController extends Controller
{
    /**
     * Show the approval page with search + pagination.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = RegistrationRequest::query()->orderBy('created_at', 'desc');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        $requests = $query->get();

        return view('registration_approval', compact('requests', 'search'));
    }

    /**
     * Store a new registration request from the signup page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Email_Input'                => 'required|email',
            'Password_Input'             => 'required|min:6',
            'first_input'                => 'required|string',
            'last_input'                 => 'required|string',
            'dob_input'                  => 'required|date',
            'role_select'                => 'required',
            'emergency_contact'          => 'nullable|string',
            'relation_emergency_contact' => 'nullable|string',
        ]);

        RegistrationRequest::create([
            'first_name'          => $validated['first_input'],
            'last_name'           => $validated['last_input'],
            'email'               => $validated['Email_Input'],
            'password'            => Hash::make($validated['Password_Input']),
            'dob'                 => $validated['dob_input'],
            'role'                => $validated['role_select'], // numeric OR string
            'emergency_contact'   => $validated['emergency_contact'] ?? null,
            'relation_to_contact' => $validated['relation_emergency_contact'] ?? null,
            'approved'            => false,
        ]);

        return redirect()->back()->with('success', 'Signup submitted - pending admin approval.');
    }

    /**
     * Approve → Create/Update user + patient/employee + delete request.
     */
    public function approve($id)
    {
        // If missing: return gracefully instead of 404
        $req = RegistrationRequest::find($id);
        if (! $req) {
            return redirect()
                ->route('registration.approval')
                ->with('error', 'Registration request not found.');
        }

        DB::transaction(function () use ($req) {

            /* -----------------------------
             * 1) Resolve role safely
             * ----------------------------- */

            $roleField = $req->role; // Could be "Patient" or 5

            if (is_numeric($roleField)) {
                $role = Role::find((int) $roleField);
            } else {
                $role = Role::where('name', $roleField)->first();
            }

            if (! $role) {
                // Fallback for bad data
                $role = Role::where('name', 'Patient')->first();
            }

            $roleName = strtolower($role->name);


            /* -----------------------------
             * 2) Create or update User
             * ----------------------------- */

            $user = User::firstOrNew(['email' => $req->email]);

            // If it's a new user, assign saved password OR a default if missing
            if (! $user->exists) {
                if (! empty($req->password)) {
                    // Use the password we stored at signup (already hashed)
                    $user->password = $req->password;
                } else {
                    // For old/factory requests that never had a password saved
                    $user->password = Hash::make('password123'); // pick any default test password
                }
            }

            $user->first_name = $req->first_name;
            $user->last_name  = $req->last_name;
            $user->dob        = $req->dob;
            $user->role_id    = $role->id;
            $user->approved   = 1;

            $user->save();


            /* -----------------------------
             * 3) PATIENT creation
             * ----------------------------- */

            if ($roleName === 'patient') {
                Patient::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'patient_identifier'       => 'PAT-' . str_pad((Patient::max('id') ?? 0) + 1, 4, '0', STR_PAD_LEFT),
                        'patient_name'             => "{$user->first_name} {$user->last_name}",
                        'admission_date'           => now(),
                        'emergency_contact_name'   => $req->emergency_contact,
                        'emergency_contact_phone'  => null, // your signup form doesn't collect this yet
                        'family_code'              => $user->family_code,
                    ]
                );
            }


            /* -----------------------------
             * 4) EMPLOYEE (staff) creation
             * ----------------------------- */

            if (in_array($roleName, ['doctor', 'caregiver', 'supervisor'])) {
                Employee::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'emp_identifier' => 'EMP-' . strtoupper(Str::random(6)),
                        'name'           => "{$user->first_name} {$user->last_name}",
                        'role'           => ucfirst($roleName),
                        'salary'         => 0,
                    ]
                );
            }


            /* -----------------------------
             * 5) Mark request processed & delete
             * ----------------------------- */

            $req->approved     = true;
            $req->processed_by = auth()->id();
            $req->save();

            $req->delete();
        });

        return redirect()
            ->route('registration.approval')
            ->with('success', 'User approved and moved into main tables.');
    }

    /**
     * Deny → Simply delete the request.
     */
    public function deny($id)
    {
        $req = RegistrationRequest::find($id);

        if ($req) {
            $req->delete();
        }

        return redirect()
            ->route('registration.approval')
            ->with('success', 'Registration request denied and deleted.');
    }
}
