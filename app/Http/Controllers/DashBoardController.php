<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashBoardController extends Controller
{
    public function index() {
    $totalProducts = \App\Models\Product::count();
    $totalCategories = \App\Models\Category::count();
    $totalUsers = \App\Models\User::count();
    return inertia('Dashboard', compact('totalProducts','totalCategories','totalUsers'));
}

}
