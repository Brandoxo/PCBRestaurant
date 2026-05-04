<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discounts extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'id',
        'name',
        'applies_to',
        'type',
        'value',
        'is_active',
        'created_by',
    ];
}
