<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersDetails extends Model
{
    protected $table = 'order_details';

    protected $fillable = ['id',
                            'order_id',
                            'product_id',
                            'quantity',
                            'unit_price',
                            'subtotal'];

    // Remove created_at timestamp management
    public $timestamps = false;

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }
}
