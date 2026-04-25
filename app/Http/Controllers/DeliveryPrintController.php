<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;

class DeliveryPrintController extends Controller
{
    public function show(Delivery $delivery)
    {
        // Load relationships needed for the BL print view
        $delivery->load(['order.client', 'order.client.region', 'livreur', 'depot', 'items.product']);

        return view('deliveries.print', compact('delivery'));
    }
}
