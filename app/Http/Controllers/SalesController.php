<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class SalesController extends Controller
{
    public function index() {
        $sales = Sales::with(['user', 'order'])->get();
        return Inertia::render('Sales/Index', [
            'sales' => $sales
        ]);
     }

     public function store(Request $request) {
        $sale = new Sales();
        $sale->id = $request->id;
        $sale->order_id = $request->order_id;
        $sale->product_id = $request->product_id;
        $sale->user_id = $request->user_id;
        $sale->cash_audit_id = $request->cash_audit_id;
        $sale->quantity = $request->quantity;
        $sale->unit_price = $request->unit_price;
        $sale->subtotal = $request->subtotal;
        $sale->date_time = Carbon::now();
        $sale->payment_method = $request->payment_method;
        $sale->save();

        $user = \App\Models\User::find($request->user_id);
        $sale->user = $user ? $user->name : 'Desconocido';

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

        return redirect()->route('Sales/Index')->with('success', 'Sale recorded successfully.');
     }
}
