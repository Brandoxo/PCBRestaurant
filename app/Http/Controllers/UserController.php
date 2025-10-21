<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return Inertia("Users/Index", [
            "users" => User::with(['roles.permissions'])->get(),
            "roles" => \Spatie\Permission\Models\Role::with('permissions')->get(),
        ]);
    }

    public function update(Request $request)
    {   
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|exists:roles,name',
        ]);
        $user = User::find($request->user_id);
        $user->syncRoles($request->role);
        return redirect()->back()->with('success', 'Role updated successfully.');
    }
}
