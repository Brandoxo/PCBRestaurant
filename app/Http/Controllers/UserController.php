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
}
