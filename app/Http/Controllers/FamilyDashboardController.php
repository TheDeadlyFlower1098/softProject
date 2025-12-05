<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FamilyDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Link from user -> family_members table
        $familyLink = $user->familyMember;

        if (!$familyLink) {
            abort(403, 'No patient is linked to this family account.');
        }

        // Get patient associated with this family member
        $patient = $familyLink->patient;

        if (!$patient) {
            abort(404, 'Linked patient not found.');
        }

        return view('family_dashboard', [
            'patient'    => $patient,
            'familyLink' => $familyLink,
        ]);
    }
}
