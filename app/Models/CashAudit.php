<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashAudit extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'start_date',
        'end_date',
        'total_amount',
        'created_at',
        'updated_at',
        
    ];
}
