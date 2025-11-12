<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConfigRoomService extends Model
{
    protected $table = 'config_room_service';

    protected $fillable = [
        'is_active',
        'service_cost',
    ];
}
