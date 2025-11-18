<?php

namespace App\Http\Controllers;

use App\Models\Mesas;
use Illuminate\Http\Request;
use App\Models\Orders;
use App\Models\ConfigRoomService;
class DashBoardController extends Controller
{
    public function index() {
    $RoomServiceConfig = ConfigRoomService::select('is_active', 'service_cost')->first();
    view()->share('RoomServiceConfig', $RoomServiceConfig);
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $orders = Orders::with(['table', 'room', 'orderDetails.product'])->whereDate('date_time', $today)->orWhereDate('date_time', $yesterday)->orderBy('date_time', 'desc')->get();
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
            'sales' => $totalSales,
            'RoomServiceConfig' => $RoomServiceConfig
        ]);

       
        
    }

    public function edit($id) {
        $order = Orders::with(['table', 'room', 'orderDetails.product'])->findOrFail($id);
        $tables = \App\Models\Mesas::all();
        return inertia('Dashboard/EditOrder', [
            'order' => $order,
            'tables' => $tables
        ]);
    }

        public function update(Request $request, $id)
    {
        $order = Orders::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->status = $request->input('status');

          if ($order->status === 'Cancelada') {
        $order->cancelled_by = $request->input('user_name');
        $table = method_exists($order, 'table') ? $order->table : \App\Models\Mesas::find($order->table_id);
        $room = method_exists($order, 'room') ? $order->room : \App\Models\Rooms::find($order->room_id);
        if ($room) {
            $room->status = 'Libre';
            $room->save();
        }
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
