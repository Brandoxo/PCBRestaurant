<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;
    protected $fillable = ['name','unit','cost','stock','min_stock','is_active'];
    public function products(){ return $this->belongsToMany(Product::class)->withPivot(['qty','unit']); }
}
