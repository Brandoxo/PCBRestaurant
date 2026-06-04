<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['category_id','name','sku', 'description','price','cost','tax_rate','is_active', 'image'];

    public function category(){ return $this->belongsTo(Categories::class); }
}
