<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return view('commercial.products.index');
    }

    public function show(Product $product)
    {
        $product->load('category');
        $movements = $product->stockMovements()->paginate(12);

        return view('commercial.products.show', compact('product', 'movements'));
    }
}
