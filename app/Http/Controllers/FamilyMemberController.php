<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FamilyDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // get the patient linked to this family member
        $familyLink = $user->familyMember;

        if (!$familyLink) {
            abort(403, 'No patient linked to this family account.');
        }

        $patient = $familyLink->patient; // you can eager load relationships if needed

        return view('family_dashboard', compact('patient'));
    }
}
