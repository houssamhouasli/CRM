<?php

namespace App\Http\Controllers\Depositaire;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DepotStock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class RestockController extends Controller
{
    public function index()
    {
        return view('depositaire.restock.index');
    }

    public function create()
    {
        return view('depositaire.restock.create');
    }

    public function show(Order $order)
    {
        if ($order->type !== 'restock') abort(404);
        $order->load('items.product');
        return view('depositaire.restock.show', compact('order'));
    }

    public function receive(Order $order)
    {
        if ($order->type !== 'restock' || $order->status !== 'confirmed') {
            return back()->with('error', "Cette demande ne peut pas être réceptionnée.");
        }

        DB::transaction(function () use ($order) {
            $depotId = auth()->user()->depot_id;

            foreach ($order->items as $item) {
                $stock = DepotStock::firstOrCreate(
                    ['depot_id' => $depotId, 'product_id' => $item->product_id],
                    ['quantity' => 0]
                );
                $stock->increment('quantity', $item->quantity);

                StockMovement::create([
                    'product_id' => $item->product_id,
                    'depot_id' => $depotId,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'reason' => 'Réception Réapprovisionnement - Demande #' . $order->id,
                    'moved_at' => now(),
                ]);
            }

            $order->update(['status' => 'delivered']);
        });

        return redirect()->route('depositaire.restock.index')->with('success', 'Stock mis à jour avec succès.');
    }
}
