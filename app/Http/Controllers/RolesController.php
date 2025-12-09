<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    /**
     * Show the Roles page.
     * - Only admin/supervisor can access.
     * - Show all roles.
     * - Show users whose access_level is lower than the current user.
     */
    public function index()
    {
        $currentUser = Auth::user();
        $this->authorizeAccess($currentUser);

        // All roles, highest level first
        $roles = Role::orderBy('access_level', 'desc')->get();

        // Only users "below" me
        $manageableUsers = User::with('role')
            ->whereHas('role', function ($q) use ($currentUser) {
                $q->where('access_level', '<', $currentUser->accessLevel());
            })
            ->orderBy('name') // change to whatever your name column is (e.g. 'full_name')
            ->get();

        return view('roles', [
            'roles'           => $roles,
            'manageableUsers' => $manageableUsers,
            'currentUser'     => $currentUser,
        ]);
    }

    /**
     * Admin-only: create a new role (NEW ROLE + ACCESS LEVEL fields on the left).
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();

        if ($currentUser->roleName() !== 'admin') {
            abort(403); // supervisors can’t create new role types
        }

        $validated = $request->validate([
            'name'          => 'required|string|max:50|unique:roles,name',
            'access_level'  => 'required|integer|min:1',
        ]);

        Role::create($validated);

        return redirect()
            ->route('roles.index')
            ->with('status', 'Role created successfully.');
    }

    /**
     * Change another user’s role.
     * - Only admin/supervisor.
     * - You can only:
     *    - change users with lower access_level
     *    - assign them to a role with access_level lower than your own
     */
    public function updateUserRole(Request $request, User $user)
    {
        $currentUser = Auth::user();
        $this->authorizeAccess($currentUser);

        $validated = $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        $newRole = Role::findOrFail($validated['role_id']);

        // 1) Can’t change users at or above your level
        if ($user->accessLevel() >= $currentUser->accessLevel()) {
            return back()->withErrors('You cannot change this user’s role.');
        }

        // 2) Can’t assign a role equal/higher than yourself
        if ($newRole->access_level >= $currentUser->accessLevel()) {
            return back()->withErrors('You cannot assign this role.');
        }

        $user->role_id = $newRole->id;
        $user->save();

        return back()->with('status', 'Role updated for '.$user->name.'.');
    }

    /**
     * Basic authorization helper:
     * Only admin + supervisor can ever access this controller.
     */
    protected function authorizeAccess(User $user): void
    {
        if (! in_array($user->roleName(), ['admin', 'supervisor'])) {
            abort(403);
        }
    }
}
