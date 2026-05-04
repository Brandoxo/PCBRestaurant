<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $fillable = ['id',
                            'mesa_id',
                            'room_id',
                            'date_time',
                            'status',
                            'total',
                            'tip'];

    // Remove created_at timestamp management
    public $timestamps = false;

    public function table(){
        return $this->belongsTo(Mesas::class, 'mesa_id');
    }

    public function room(){
        return $this->belongsTo(Rooms::class, 'room_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrdersDetails::class, 'order_id');
    }
}
