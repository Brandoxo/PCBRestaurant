<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersDetails extends Model
{
    protected $fillable = ['id', 'order_id', 'product_id', 'quantity', 'unit_price', 'subtotal'];
}
