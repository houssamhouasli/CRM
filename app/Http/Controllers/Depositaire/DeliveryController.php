<?php

namespace App\Http\Controllers\Depositaire;

use App\Http\Controllers\Controller;
use App\Models\Delivery;

class DeliveryController extends Controller
{
    public function index()
    {
        return view('depositaire.deliveries.index');
    }

    public function show(Delivery $delivery)
    {
        // Check if the delivery belongs to the depositaire's depot
        if (auth()->user()->role === 'depositaire' && $delivery->depot_id !== auth()->user()->depot_id) {
            abort(403, 'Accès non autorisé à ce bon de livraison.');
        }

        $delivery->load(['order.client', 'order.items.product', 'livreur', 'depot', 'items.product']);
        return view('depositaire.deliveries.show', compact('delivery'));
    }
}
