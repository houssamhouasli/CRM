<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Delivery;
use App\Models\OrderItem;
use App\Models\TruckStock;
use App\Models\DepotStock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class DeliveryController extends Controller
{
    public function index()
    {
        return view('livreur.deliveries.index');
    }

    public function show(Delivery $delivery)
    {
        $delivery->load(['order.client', 'order.items.product', 'items.product', 'depot']);
        return view('livreur.deliveries.show', compact('delivery'));
    }

    public function acceptProposition(Delivery $delivery)
    {
        if ($delivery->status !== 'proposition') {
            return back()->with('error', "Cette livraison n'est pas une proposition en attente.");
        }

        auth()->user()->load('truck');
        $truck = auth()->user()->truck;
        if (!$truck) {
            return back()->with('error', "Aucun camion assigné.");
        }

        DB::transaction(function () use ($delivery, $truck) {
            $delivery->load(['items.product', 'order.items']);
            $order = $delivery->order;

            $sumHt = 0;
            $sumTva = 0;
            $sumTtc = 0;

            foreach ($delivery->items as $item) {

                $item->calculateTotals();
                $item->save();

                $sumHt += $item->total_ht;
                $sumTva += $item->total_tva;
                $sumTtc += $item->total_ttc;


                TruckStock::where('truck_id', $truck->id)
                    ->where('product_id', $item->product_id)
                    ->decrement('quantity', $item->qty_delivered);


                StockMovement::create([
                    'product_id' => $item->product_id,
                    'truck_id' => $truck->id,
                    'user_id' => auth()->id(),
                    'order_id' => $delivery->order_id,
                    'type' => 'out',
                    'quantity' => $item->qty_delivered,
                    'reason' => 'Livraison Client (Substitution acceptée) - BL #' . $delivery->id,
                    'moved_at' => now(),
                ]);

                if ($item->is_substitution) {
                    $product = $item->product;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $item->qty_delivered,
                        'price_unit_ht' => $item->unit_price_ht,
                        'promo_type' => $item->promo_type,
                        'promo_value' => $item->promo_value ?? 0,
                        'discount_amount' => 0,
                        'final_price_ht' => $item->unit_price_ht,
                        'tva_rate' => $item->tva_rate,
                        'total_ht' => $item->total_ht,
                        'total_tva' => $item->total_tva,
                        'total_ttc' => $item->total_ttc,
                    ]);
                }
            }

            $delivery->update([
                'status' => 'livrer',
                'total_ht' => $sumHt,
                'total_tva' => $sumTva,
                'total_ttc' => $sumTtc,
            ]);

            $order->refresh();
            $order->load('items');
            $newTotalHt = $order->items->sum('total_ht');
            $newTotalTva = $order->items->sum('total_tva');
            $newTotalTtc = $order->items->sum('total_ttc');

            $order->update([
                'status' => 'livrer',
                'total_ht' => $newTotalHt,
                'total_tva' => $newTotalTva,
                'total_ttc' => $newTotalTtc,
            ]);
        });

        return redirect()->route('livreur.deliveries.index')
            ->with('success', 'Le client a accepté la proposition. Livraison validée.');
    }


    public function rejectProposition(Delivery $delivery)
    {
        if ($delivery->status !== 'proposition') {
            return back()->with('error', "Cette livraison n'est pas une proposition en attente.");
        }

        auth()->user()->load('truck');
        $truck = auth()->user()->truck;
        if (!$truck) {
            return back()->with('error', "Aucun camion assigné.");
        }

        DB::transaction(function () use ($delivery, $truck) {
            $delivery->load(['items.product', 'order.items']);
            $depotId = $delivery->depot_id;

            $sumHt = 0;
            $sumTva = 0;
            $sumTtc = 0;

            foreach ($delivery->items as $item) {
                if ($item->is_substitution) {
                    $qty = $item->qty_delivered;

                    TruckStock::where('truck_id', $truck->id)
                        ->where('product_id', $item->product_id)
                        ->decrement('quantity', $qty);

                    DepotStock::firstOrCreate(
                        ['depot_id' => $depotId, 'product_id' => $item->product_id],
                        ['quantity' => 0]
                    )->increment('quantity', $qty);

                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'depot_id' => $depotId,
                        'truck_id' => $truck->id,
                        'user_id' => auth()->id(),
                        'order_id' => $delivery->order_id,
                        'type' => 'in',
                        'quantity' => $qty,
                        'reason' => 'Substitution refusée (Retour Depot) - BL #' . $delivery->id,
                        'moved_at' => now(),
                    ]);

                    $item->delete();
                } else {
                    $item->calculateTotals();
                    $item->save();

                    $sumHt += $item->total_ht;
                    $sumTva += $item->total_tva;
                    $sumTtc += $item->total_ttc;

                    TruckStock::where('truck_id', $truck->id)
                        ->where('product_id', $item->product_id)
                        ->decrement('quantity', $item->qty_delivered);

                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'truck_id' => $truck->id,
                        'user_id' => auth()->id(),
                        'order_id' => $delivery->order_id,
                        'type' => 'out',
                        'quantity' => $item->qty_delivered,
                        'reason' => 'Livraison Client (Originaux uniquement) - BL #' . $delivery->id,
                        'moved_at' => now(),
                    ]);
                }
            }

            $delivery->update([
                'status' => 'livrer',
                'has_substitution' => false,
                'total_ht' => $sumHt,
                'total_tva' => $sumTva,
                'total_ttc' => $sumTtc,
            ]);

            $order = $delivery->order->fresh(['items', 'deliveries.items']);
            $totalDeliveredByProduct = $order->deliveries
                ->where('status', 'livrer')
                ->flatMap->items
                ->groupBy('product_id')
                ->map(fn($group) => $group->sum('qty_delivered'));

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

        return redirect()->route('livreur.deliveries.index')
            ->with('success', 'Proposition refusée. Seuls les produits originaux ont été livrés, les substitutions sont retournées au dépôt.');
    }

    public function complete(Request $request, Delivery $delivery)
    {
        if ($delivery->status !== 'pending') {
            return back()->with('error', "Cette livraison ne peut pas être marquée comme terminée.");
        }

        auth()->user()->load('truck');
        $truck = auth()->user()->truck;
        if (!$truck) {
             return back()->with('error', "Aucun camion assigné.");
        }

        $quantities = $request->input('quantities', []);

        DB::transaction(function() use ($delivery, $truck, $quantities) {
            $delivery->load(['items.product', 'order.items']);
            
            $sumHt = 0;
            $sumTva = 0;
            $sumTtc = 0;

            foreach ($delivery->items as $item) {
                $deliveredQty = $quantities[$item->id] ?? $item->qty_delivered;

                $item->qty_delivered = $deliveredQty;
                $item->calculateTotals();
                $item->save();

                $sumHt += $item->total_ht;
                $sumTva += $item->total_tva;
                $sumTtc += $item->total_ttc;

                $truckStock = TruckStock::where('truck_id', $truck->id)
                    ->where('product_id', $item->product_id)
                    ->first();
                
                if ($truckStock) {
                    $truckStock->decrement('quantity', $deliveredQty);
                    

                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'truck_id' => $truck->id,
                        'user_id' => auth()->id(),
                        'order_id' => $delivery->order_id,
                        'type' => 'out',
                        'quantity' => $deliveredQty,
                        'reason' => 'Livraison Client - BL #' . $delivery->id,
                        'moved_at' => now(),
                    ]);
                }
            }

            $status = 'livrer';
            $delivery->update([
                'status' => $status,
                'total_ht' => $sumHt,
                'total_tva' => $sumTva,
                'total_ttc' => $sumTtc
            ]);

            $order = $delivery->order->fresh(['items', 'deliveries.items']);
            $totalDeliveredByProduct = $order->deliveries
                ->where('status', 'livrer')
                ->flatMap->items
                ->groupBy('product_id')
                ->map(fn($group) => $group->sum('qty_delivered'));

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
            }
        });

        return redirect()->route('livreur.deliveries.index')->with('success', 'Livraison validée (Statut: ' . $delivery->status . ').');
    }

    public function cancel(Delivery $delivery)
    {
        if ($delivery->status !== 'pending' && $delivery->status !== 'proposition') {
            return back()->with('error', "Cette livraison ne peut pas être annulée.");
        }

        $livreur = auth()->user();
        $depotId = $delivery->depot_id;

        DB::transaction(function () use ($delivery, $livreur, $depotId) {
            foreach ($delivery->items as $item) {
                $qty = $item->qty_delivered;
                if ($qty <= 0) continue;

                TruckStock::where('truck_id', $livreur->truck->id)
                    ->where('product_id', $item->product_id)
                    ->decrement('quantity', $qty);

                DepotStock::where('depot_id', $depotId)
                    ->where('product_id', $item->product_id)
                    ->increment('quantity', $qty);


                StockMovement::create([
                    'product_id' => $item->product_id,
                    'depot_id' => $depotId,
                    'truck_id' => $livreur->truck->id,
                    'user_id' => auth()->id(),
                    'order_id' => $delivery->order_id,
                    'type' => 'in',
                    'quantity' => $qty,
                    'reason' => 'Annulation Livraison (Retour Depot) - BL #' . $delivery->id,
                    'moved_at' => now(),
                ]);
            }

            $delivery->update(['status' => 'annuler']);
        });

        return redirect()->route('livreur.deliveries.index')->with('success', 'Livraison annulée et stock retourné au dépôt.');
    }
}
