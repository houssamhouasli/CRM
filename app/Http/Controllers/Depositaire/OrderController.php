<?php

namespace App\Http\Controllers\Depositaire;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\DepotStock;
use App\Models\TruckStock;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('depositaire.orders.index');
    }

    public function show(Order $order)
    {
        $order->load(['client.region', 'items.product', 'deliveries.livreur', 'deliveries.depot']);

        $deliveredQty = $order->deliveries()
            ->where('status', '!=', 'cancelled')
            ->with('items')
            ->get()
            ->flatMap(fn($d) => $d->items ?? collect())
            ->groupBy('product_id')
            ->map(fn($items) => $items->sum('qty_delivered'));

        $order->items->each(fn($item) => $item->delivered = $deliveredQty[$item->product_id] ?? 0);

        return view('depositaire.orders.show', compact('order'));
    }

    public function createDelivery(Order $order)
    {
        if ($order->status === 'livrer') {
            return back()->with('error', 'Cette commande est déjà entièrement livrée.');
        }
        
        if ($order->status === 'annuler') {
            return back()->with('error', 'Cette commande est annulée.');
        }

        return view('depositaire.deliveries.create', compact('order'));
    }

    public function storeDelivery(Request $request, Order $order)
    {
        $request->validate([
            'livreur_id' => 'required|exists:users,id',
            'delivery_date' => 'required|date',
            'items' => 'required|array',
            'items.*.qty' => 'required|integer|min:0',
        ]);

        $depotId = auth()->user()->depot_id;
        $livreur = User::with('truck')->findOrFail($request->livreur_id);

        if (!$livreur->truck) {
             return back()->with('error', "Ce livreur n'a pas de camion assigné.");
        }

        \DB::transaction(function() use ($request, $order, $depotId, $livreur) {
            $delivery = Delivery::create([
                'order_id' => $order->id,
                'livreur_id' => $livreur->id,
                'depot_id' => $depotId,
                'status' => 'pending',
                'delivery_date' => $request->delivery_date,
            ]);

            $allDelivered = true;
            foreach ($request->items as $productId => $data) {
                $qty = (int)$data['qty'];
                if ($qty <= 0) continue;

                $orderItem = $order->items()->where('product_id', $productId)->first();
                

                DeliveryItem::create([
                    'delivery_id' => $delivery->id,
                    'product_id' => $productId,
                    'qty_ordered' => $orderItem->quantity,
                    'qty_delivered' => $qty,
                    'price_unit_ht' => $orderItem->price_unit_ht,
                    'total_ht' => $orderItem->price_unit_ht * $qty,
                    'total_tva' => ($orderItem->price_unit_ht * $qty) * ($orderItem->tva_rate / 100),
                    'total_ttc' => ($orderItem->price_unit_ht * $qty) * (1 + $orderItem->tva_rate / 100),
                ]);


                $depotStock = DepotStock::firstOrCreate(
                    ['depot_id' => $depotId, 'product_id' => $productId],
                    ['quantity' => 0]
                );
                $depotStock->decrement('quantity', $qty);

                \App\Models\StockMovement::create([
                    'product_id' => $productId,
                    'depot_id' => $depotId,
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'type' => 'out',
                    'quantity' => $qty,
                    'reason' => 'Livraison Client - Bon #' . $delivery->id,
                    'moved_at' => now(),
                ]);


                $truckStock = TruckStock::firstOrCreate(
                    ['truck_id' => $livreur->truck->id, 'product_id' => $productId],
                    ['quantity' => 0]
                );
                $truckStock->increment('quantity', $qty);
            }
            

            $order = $order->fresh(['items', 'deliveries.items']);
            
            $totalDeliveredByProduct = $order->deliveries
                ->where('status', '!=', 'cancelled')
                ->flatMap->items
                ->groupBy('product_id')
                ->map->sum('qty_delivered');
            
            $isFullyDelivered = true;
            foreach ($order->items as $orderItem) {
                $delivered = $totalDeliveredByProduct[$orderItem->product_id] ?? 0;
                if ($delivered < $orderItem->quantity) {
                    $isFullyDelivered = false;
                    break;
                }
            }

            if ($isFullyDelivered) {
                $order->update(['status' => 'livrer']);
            } else {
                $order->update(['status' => 'confirmed']);
            }
        });

        return redirect()->route('depositaire.orders.show', $order)
            ->with('success', 'Bon de livraison généré avec succès.');
    }

    public function cancel(Order $order)
    {
        if ($order->status !== 'pending') {
            return back()->with('error', "Cette commande n'est pas en attente.");
        }

        \DB::transaction(function() use ($order) {
            $order->update(['status' => 'annuler']);
            
            $order->deliveries()->where('status', 'pending')->update(['status' => 'annuler']);
        });

        return redirect()->route('depositaire.orders.index')->with('success', 'Commande annulée.');
    }
}
