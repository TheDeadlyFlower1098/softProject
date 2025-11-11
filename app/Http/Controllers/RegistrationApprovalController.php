<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\RegistrationRequest;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegistrationApprovalController extends Controller
{
    // list pending registration requests
    public function index()
    {
        return view('admin.registration_requests.index', [
            'requests' => RegistrationRequest::where('approved', false)->paginate(20)
        ]);
    }

    public function approve(Request $request, $id)
    {
        $req = RegistrationRequest::findOrFail($id);
        $req->approved = true;
        $req->processed_by = $request->user()->id;
        $req->save();

        // Create actual user (example)
        $role = Role::where('name', $req->role)->first();
        $user = User::create([
            'first_name' => $req->first_name,
            'last_name' => $req->last_name,
            'email' => $req->email,
            'password' => Hash::make('changeme123'), // inform user to reset password
            'role_id' => $role ? $role->id : null,
            'approved' => true
        ]);

        return redirect()->back()->with('success','Approved and user created. Please reset password for the new user.');
    }

    public function deny($id)
    {
        $req = RegistrationRequest::findOrFail($id);
        $req->delete();
        return redirect()->back()->with('success','Registration denied and removed.');
    }
}
