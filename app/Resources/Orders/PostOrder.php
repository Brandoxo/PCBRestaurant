<?php

namespace App\Resources\Orders;
use App\Models\Orders;  
use Illuminate\Http\Request;

class PostOrder
{
    public function __invoke(Request $request)
    {

        $order = Orders::create([
            'mesa_id' => $request->input('mesa_id'),
            'room_id' => $request->input('room_id'),
            'date_time' => now(),
            'status' => 'En curso',
            'total' => array_reduce($request->input('items'), fn($sum, $item) => $sum + ($item['quantity'] * $item['price']), 0)
        ]);

        if($order){
            $mesa = \App\Models\Mesas::find($request->input('mesa_id'));
            if ($mesa) {
                $mesa->status = 'Ocupada';
                $mesa->save();
            }else {
                $room = \App\Models\Rooms::find($request->input('room_id'));
                if ($room) {
                    $room->status = 'Ocupada';
                    $room->save();
                }
            }

            error_log('Order created successfully with ID: ' . $order->id);
            foreach ($request->input('items') as $item) {
                $order->orderDetails()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'subtotal' => $item['quantity'] * $item['price']
                ]);
            }

            return $order;
        } else {
            error_log('Failed to create order.');
            return response()->json(['error' => 'Failed to create order.'], 500);
        }
    }
}