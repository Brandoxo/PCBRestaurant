<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Mesas extends Model
{
    protected $fillable = ['id',
                            'number',
                            'status',
                            'capacity'];

    public function orders()
    {
        return $this->hasMany(Orders::class, 'mesa_id');
    }
}
