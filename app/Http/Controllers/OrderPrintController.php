<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderPrintController extends Controller
{
    public function show(Order $order)
    {
        // Load relationships needed for the print view
        $order->load(['client', 'client.region', 'items.product', 'creator.depot']);

        return view('orders.print', compact('order'));
    }
}
