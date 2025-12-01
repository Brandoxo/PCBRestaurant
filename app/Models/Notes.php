<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notes extends Model
{
    protected $table = 'notes';
    protected $fillable = ['order_id', 'content', 'created_at', 'updated_at'];
}
