<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['category_id','name','sku','description','price','cost','tax_rate','is_active'];

    public function category(){ return $this->belongsTo(Category::class); }
    public function ingredients(){ return $this->belongsToMany(Ingredient::class)->withPivot(['qty','unit']); }
}
