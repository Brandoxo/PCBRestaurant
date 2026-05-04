<?php

namespace App\Resources\Sales;

use App\Models\Orders;

class GetSales {

    public static function ByID(Orders $order, $id) {
        
        $ordersData = $order::with(['room', 'orderDetails.product', 'orderDetails.product.category'])->where('id', $id)->get();

        /* Filter id, total, date_time,
            orderDetails.quantity, orderDetails.unit_price, orderDetails.subtotal,
            orderDetails.product.name, orderDetails.product.price, 
             fields only
        */
        $ordersData->transform(function ($order) {
            return [
                'id' => $order->id,
                'total' => $order->total,
                'date_time' => $order->date_time,
                'orderDetails' => $order->orderDetails->map(function ($detail) {
                    return [
                        'quantity' => $detail->quantity,
                        'unit_price' => $detail->unit_price,
                        'subtotal' => $detail->subtotal,
                        'product' => [
                            'name' => $detail->product->name,
                            'price' => $detail->product->price,
                        ],
                    ];
                }),
            ];
        });
        
        
        return $ordersData;
    }
}