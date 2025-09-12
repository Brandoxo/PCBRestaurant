<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    public function index() {
        $sales = sales::all();
        return Inertia::render('Sales/Index', [
            'sales' => $sales
        ]);
     }
}
