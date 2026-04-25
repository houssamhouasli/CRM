<?php

namespace App\Http\Controllers\Commercial;

use App\Http\Controllers\Controller;
use App\Models\Order;


class OrderController extends Controller
{
    public function index()
    {
        return view('commercial.orders.index');
    }

    public function show(Order $order)
    {
        $order->load(['client.region', 'items.product', 'deliveries.livreur', 'deliveries.depot', 'deliveries.items']);

        $deliveredQty = $order->deliveries
            ->flatMap(fn($d) => $d->items ?? collect())
            ->groupBy('product_id')
            ->map(fn($items) => $items->sum('qty_delivered'));

        $order->items->each(fn($item) => $item->delivered = $deliveredQty[$item->product_id] ?? 0);

        return view('commercial.orders.show', compact('order'));
    }
}
