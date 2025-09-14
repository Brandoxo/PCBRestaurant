<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Orders;
class DashBoardController extends Controller
{
    public function index() {
    $orders = Orders::with(['table', 'orderDetails.product'])->orderBy('date_time', 'desc')->get();
        $totalProducts = \App\Models\Products::count();
        $totalCategories = \App\Models\Categories::count();
        $totalUsers = \App\Models\User::count();
        $totalOrders = Orders::count();
        return inertia('Dashboard/Index', [
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'totalUsers' => $totalUsers,
            'orders' => $orders,
            'totalOrders' => $totalOrders
        ]);
    }

}
