<?php

namespace App\Http\Controllers\Depositaire;

use App\Http\Controllers\Controller;
use App\Models\Product;
class StockController extends Controller
{
    public function index()
    {
        return view('depositaire.stock.index');
    }

    public function movements()
    {
        return view('depositaire.stock.movements');
    }

    public function showProduct(Product $product)
    {
        $depotId = auth()->user()->depot_id;
        
        $movements = $product->stockMovements()
            ->where('depot_id', $depotId)
            ->latest('moved_at')
            ->paginate(10);
            
        return view('depositaire.stock.show', compact('product', 'movements'));
    }
}
