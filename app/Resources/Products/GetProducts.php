<?php

namespace App\Resources\Products;

use App\Models\Products;

class GetProducts {
    public function allProducts() {
        return Products::all();
    }

    public static function ById($id) {
        return Products::find($id);
    }

    public static function WithPagination($perPage = 15) {
        return Products::paginate($perPage);
    }

    public static function GroupedByCategory() {
        return Products::with('category')->get()->groupBy('category.name');
    }

    public static function SearchByName($name) {
        return Products::where('name', 'like', '%' . $name . '%')->get();
    }

    public static function GroupedByCategoryWithPagination($perPage = 15) {
        return Products::with('category')->paginate($perPage);
    }
}