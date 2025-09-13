<?php

namespace App\Http\Controllers;
use App\Models\Mesas;
use App\Models\Orders;
use App\Resources\Orders\PostOrder;
use Illuminate\Http\Request;    

class OrderController extends Controller
{
    public function index()
    {
        $orders = Orders::with('table')->get();
        $tables = Mesas::all();
        return inertia('Orders/Index', [
            'orders' => $orders,
            'tables' => $tables
        ]);
    }

    public function store(Request $request)
    {
        $order = new PostOrder();

        $order($request);

        return redirect()->back()->with('success', 'Order created successfully.');
    }
}
