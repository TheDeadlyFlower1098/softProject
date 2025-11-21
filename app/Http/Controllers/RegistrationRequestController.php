<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegistrationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class RegistrationRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        // 1. Validate frontend inputs
        $validated = $request->validate([
            'Email_Input'               => 'required|email',
            'Password_Input'            => 'required|min:6',
            'first_input'               => 'required|string',
            'last_input'                => 'required|string',
            'dob_input'                 => 'required|date',
            'role_select'               => 'required|integer',
            'emergency_contact'         => 'nullable|string',
            'relation_emergency_contact'=> 'nullable|string',
        ]);

        // 2. Save signup request
        RegistrationRequest::create([
            'first_name'          => $validated['first_input'],
            'last_name'           => $validated['last_input'],
            'email'               => $validated['Email_Input'],
            'password'            => Hash::make($validated['Password_Input']),
            'dob'                 => $validated['dob_input'],
            'role'                => $validated['role_select'],
            'emergency_contact'   => $validated['emergency_contact'] ?? null,
            'relation_to_contact' => $validated['relation_emergency_contact'] ?? null,
            'approved'            => false,
        ]);

        // 3. Redirect back with a flash message
        return redirect()->back()->with('success', 'Signup submitted - pending admin approval.');
    }
}
