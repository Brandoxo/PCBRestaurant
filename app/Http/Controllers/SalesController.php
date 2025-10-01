<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index(Request $request) {
    $query = Sales::with(['product', 'user', 'order']);

    if ($request->has('date') && $request->date) {
        $query->whereDate('date_time', $request->date);
    }

    $sales = $query->orderBy('date_time', 'desc')->paginate(80);

    return Inertia::render('Sales/Index', [
        'sales' => $sales,
    ]);
}


public function store(Request $request) {
    $salesData = $request->sales;
    foreach ($salesData as $item) {
        $sale = new Sales();
        $sale->order_id = $request->order_id;
        $sale->product_id = $item['product_id'];
        $sale->user_id = $request->user_id;
        $sale->cash_audit_id = $request->cash_audit_id;
        $sale->quantity = $item['quantity'];
        $sale->unit_price = $item['unit_price'];
        $sale->subtotal = $item['subtotal'];
        $sale->date_time = Carbon::now();
        $sale->payment_method = $request->payment_method;
        $sale->is_courtesy = isset($item['is_courtesy']) ? $item['is_courtesy'] : 0;
        $sale->save();
    }

    $order = \App\Models\Orders::find($request->order_id);
    if ($order) {
        $order->status = 'Completada';
        $order->save();
        $mesa = \App\Models\Mesas::find($order->mesa_id);
        if ($mesa) {
            $mesa->status = 'Libre';
            $mesa->save();
        }
    }

    return redirect()->route('Sales/Index')->with('success', 'Todas las ventas registradas correctamente.');
}

public function getSalesForCutOff(Request $request)
{
    $date = $request->input('date');
    $shift = $request->input('shift');

    $query = Sales::with(['product', 'user', 'order']);

    if ($date) {
        $query->whereDate('date_time', $date);
    }

    if ($shift) {
        if ($shift === 'Matutino') {
            $query->whereTime('date_time', '>=', '07:00:00')->whereTime('date_time', '<', '15:00:00');
        } elseif ($shift === 'Vespertino') {
            $query->whereTime('date_time', '>=', '15:00:00')->whereTime('date_time', '<', '23:00:00');
        }
    }

    return response()->json($query->get());
}

}
