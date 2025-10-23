<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shifts extends Model
{
    protected $table = 'shifts';

    public $timestamps = false;

    protected $fillable = [
        'name_shift',
        'start_time',
        'end_time',
        'is_active',
    ];
}
