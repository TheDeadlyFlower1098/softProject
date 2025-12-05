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
    // index() ... (unchanged)

    /**
     * Store a new registration request from the signup page.
     */
    public function store(Request $request)
    {
        // 1. Validate base inputs
        $validated = $request->validate([
            'Email_Input'                => 'required|email',
            'Password_Input'             => 'required|min:6',
            'first_input'                => 'required|string',
            'last_input'                 => 'required|string',
            'dob_input'                  => 'required|date',
            'role_select'                => 'required|integer',
            'emergency_contact'          => 'nullable|string',
            'relation_emergency_contact' => 'nullable|string',
            'linked_patient_identifier'  => 'nullable|string',
        ]);

        // Map dropdown numeric value -> role name string
        $roleMap = [
            1 => 'Patient',
            2 => 'Doctor',
            3 => 'Supervisor',
            4 => 'Admin',
            5 => 'Family',
        ];

        $roleSelect = (int) $validated['role_select'];
        $roleName   = $roleMap[$roleSelect] ?? null;

        if (!$roleName) {
            return back()
                ->withErrors(['role_select' => 'Please select a valid role.'])
                ->withInput();
        }

        // 2. Handle the family-specific requirement
        $linkedPatientId = null;

        if ($roleName === 'Family') {
            $request->validate([
                'linked_patient_identifier' => 'required|string',
            ]);

            $linkedPatientId = $request->input('linked_patient_identifier');
        }

        // 3. Save signup request
        RegistrationRequest::create([
            'first_name'                => $validated['first_input'],
            'last_name'                 => $validated['last_input'],
            'email'                     => $validated['Email_Input'],
            'password'                  => Hash::make($validated['Password_Input']),
            'dob'                       => $validated['dob_input'],
            'role'                      => $roleName, // store string
            'emergency_contact'         => $validated['emergency_contact'] ?? null,
            'relation_to_contact'       => $validated['relation_emergency_contact'] ?? null,
            'linked_patient_identifier' => $linkedPatientId,
            'approved'                  => false,
        ]);

        // 4. Redirect back with a flash message
        return redirect()
            ->back()
            ->with('success', 'Signup submitted - pending admin approval.');
    }

    // <-- right here the store() method ends,
    // and the *next* thing should be your approve() docblock:

    /**
     * Approve → Create/Update user + patient/employee + delete request.
     */
    public function approve($id)
    {
        // ... your existing approve logic ...
    }

    // deny() ...
}
