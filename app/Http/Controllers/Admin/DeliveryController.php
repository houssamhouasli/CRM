<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    public function index()
    {
        return view('admin.deliveries.index');
    }

    public function show(Delivery $delivery)
    {
        $delivery->load(['order.client', 'order.items.product', 'livreur', 'depot', 'items.product']);
        return view('admin.deliveries.show', compact('delivery'));
    }
}
