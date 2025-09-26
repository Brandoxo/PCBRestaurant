<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    protected $fillable = ['id', 'order_id', 'product_id', 'user_id', 'cash_audit_id', 'quantity', 'unit_price', 'subtotal', 'date_time', 'is_courtesy'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function order()
    {
        return $this->belongsTo(\App\Models\Orders::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(\App\Models\Products::class, 'product_id');
    }
}
