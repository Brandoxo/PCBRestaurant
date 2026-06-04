<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Mesas extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['id',
                            'number',
                            'status',
                            'capacity'];

    public function orders()
    {
        return $this->hasMany(Orders::class, 'mesa_id');
    }
}
