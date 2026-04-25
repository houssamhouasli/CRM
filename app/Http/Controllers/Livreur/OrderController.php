<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        return view('livreur.orders.index');
    }

    public function create()
    {
        return view('livreur.orders.create');
    }

    public function show(Order $order)
    {        
        $order->load(['client', 'items.product']);
        return view('livreur.orders.show', compact('order'));
    }
}
