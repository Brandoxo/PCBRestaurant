<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name','sku','description','price','cost','tax_rate','is_active'];

    public function category(){ return $this->belongsTo(Categories::class); }
    public function ingredients(){ return $this->belongsToMany(Ingredient::class)->withPivot(['qty','unit']); }
}
