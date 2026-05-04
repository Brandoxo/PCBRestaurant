<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashFloats extends Model
{
    protected $table = 'cash_floats';

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'notes',
    ];
}
