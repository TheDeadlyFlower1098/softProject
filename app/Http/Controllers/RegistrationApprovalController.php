<?php

namespace App\Http\Controllers;

use App\Models\RegistrationRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Patient;
use App\Models\FamilyMember;

class RegistrationApprovalController extends Controller
{
    /**
     * List all pending requests.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $requests = RegistrationRequest::where('approved', 0)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('role', 'LIKE', "%{$search}%")
                    ->orWhereDate('created_at', $search);
                });
            })
            ->paginate(20)
            ->appends(['search' => $search]);
        return view('registration_approval', compact('requests', 'search'));
    }


    /**
     * Approve a request + create matching user.
     */
    public function approve(Request $request, $id)
    {
        $req = RegistrationRequest::findOrFail($id);

        // Mark as approved
        $req->approved = 1;
        $req->processed_by = $request->user()->id ?? null;
        $req->save();

        // Find matching role
        $role = Role::where('name', $req->role)->first();

        // Create the user
        $user = User::create([
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'email' => $req->email,
            'password' => $req->password,
            'role_id' => $role?->id,
            'approved' => 1,
        ]);

        // -------------------------------------
        // AUTO-ASSIGN PATIENT TO RANDOM GROUP
        // -------------------------------------
        if ($req->role === 'Patient') {
            $groupId = \App\Models\Group::inRandomOrder()->value('id');

            \App\Models\Patient::create([
                'user_id' => $user->id,
                'patient_identifier' => strtoupper('P' . rand(10000,99999)),
                'patient_name' => $req->first_name . ' ' . $req->last_name,
                'group_id' => $groupId,
                'admission_date' => now(),
                'family_code' => $req->family_code ?? strtoupper('FC' . rand(100,999)),
            ]);
        }

        // NEW: link family member to patient based on full name from signup
        if ($req->role === 'Family') {
            // Full name entered on signup (e.g. "John Smith")
            $fullName = $req->linked_patient_identifier;

            // Try to find a patient whose full name matches
            $patient = Patient::where('patient_name', $fullName)->first();

            if (! $patient) {
                // Optional: give the admin helpful feedback instead of silently failing
                return back()->withErrors([
                    'approval' => "No patient found with the name '{$fullName}' for family request {$req->email}. 
                                Make sure the patient is approved first and the name matches exactly."
                ]);
            }

            FamilyMember::create([
                'user_id'    => $user->id,
                'patient_id' => $patient->id,
                'relation'   => null, // or capture this on signup later
                'family_code'=> $patient->family_code,
            ]);
        }


        return redirect()
            ->back()
            ->with('success', "{$req->first_name} {$req->last_name} approved successfully.");
    }


    /**
     * Deny a request and delete it.
     */
    public function deny($id)
    {
        $req = RegistrationRequest::findOrFail($id);

        $name = "{$req->first_name} {$req->last_name}";

        // Remove the request entirely
        $req->delete();

        return redirect()
            ->back()
            ->with('success', "Denied and removed request for {$name}.");
    }
}
