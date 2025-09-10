<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Resources\Products\GetProducts;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return GetProducts::GroupedByCategoryWithPagination(100);
    }
}
