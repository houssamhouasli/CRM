<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::whereHas('order.client', function ($query) {
            $query->where('region_id', auth()->user()->region_id);
        })->with(['order.client', 'livreur', 'depot'])->latest()->paginate(10);

        return view('commercial.deliveries.index', compact('deliveries'));
    }

    public function show(Delivery $delivery)
    {
        if ($delivery->order->client->region_id !== auth()->user()->region_id) {
            abort(403);
        }

        $delivery->load(['order.client', 'order.items.product', 'livreur', 'depot', 'items.product']);
        return view('commercial.deliveries.show', compact('delivery'));
    }
}
