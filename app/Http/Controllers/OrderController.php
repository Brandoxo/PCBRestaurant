<?php

namespace App\Http\Controllers;
use App\Models\Mesas;
use App\Models\Orders;
use App\Resources\Orders\PostOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    return redirect()->route('dashboard')->with('success', 'Order created successfully.');
    }

    public function update(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->status = $request->input('status');
        $order->save();

        if ($order->status === 'Cancelada') {
            $table = method_exists($order, 'table') ? $order->table : Mesas::find($order->table_id);
            if ($table) {
                $table->status = 'Libre';
                $table->save();
            }
        }

        return redirect()->route('dashboard')->with('success', 'Order updated successfully.');
    }
 }