<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrdersNotes extends Model
{
    protected $table = 'orders_notes';
    protected $fillable = ['order_id', 'content', 'cancellation_reason', 'created_at', 'updated_at'];
}
