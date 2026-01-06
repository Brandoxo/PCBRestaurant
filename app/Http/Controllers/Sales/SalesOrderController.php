<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SalesOrderController extends Controller
{
    public function getOrderById(Request $request, $id)
    {
        // Assuming GetSales is properly imported
        $ordersData = \App\Resources\Sales\GetSales::ByID(new \App\Models\Orders(), $id);

    if ($ordersData->isEmpty()) {
        return response()->json([
            'message' => 'No orders found for the given ticket ID ' . $id,
            'order' => null,
        ], 404);
    }
    else{
        return response()->json([
            'message' => 'Orders fetched successfully',
            'order' => $ordersData,
        ]);
    }
        
        return response()->json($orderData);
    }
}
