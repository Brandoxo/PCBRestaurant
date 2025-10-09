<?php

namespace App\Http\Controllers;

use App\Models\Mesas;
use Illuminate\Http\Request;
use App\Models\Orders;
class DashBoardController extends Controller
{
    public function index() {
    $orders = Orders::with(['table', 'orderDetails.product'])->orderBy('date_time', 'desc')->get();
        $totalProducts = \App\Models\Products::count();
        $totalCategories = \App\Models\Categories::count();
        $totalUsers = \App\Models\User::count();
        $totalSales = \App\Models\Sales::all();
        $totalOrders = Orders::count();
        return inertia('Dashboard/Index', [
            'totalProducts' => $totalProducts,
            'totalCategories' => $totalCategories,
            'totalUsers' => $totalUsers,
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'sales' => $totalSales
        ]);

       
        
    }

    public function edit($id) {
        $order = Orders::with(['table', 'orderDetails.product'])->findOrFail($id);
        $tables = \App\Models\Mesas::all();
        return inertia('Dashboard/EditOrder', [
            'order' => $order,
            'tables' => $tables
        ]);
    }

        public function update(Request $request, $id)
    {
        return response()->json($request->all());
        $order = Orders::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->status = $request->input('status');

          if ($order->status === 'Cancelada') {
        $order->cancelled_by = $request->input('user_name');
        $table = method_exists($order, 'table') ? $order->table : \App\Models\Mesas::find($order->table_id);
        if ($table) {
            $table->status = 'Libre';
            $table->save();
        }
    }
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
