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

        try {
            DB::transaction(function () use ($delivery, $truck) {
                $delivery->load([
                    'items.product',
                    'order.items',
                    'order.deliveries.items'
                ]);

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

                    $stock = TruckStock::where('truck_id', $truck->id)
                        ->where('product_id', $item->product_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$stock) {
                        throw new \Exception(
                            "Stock introuvable pour le produit ID {$item->product_id}"
                        );
                    }

                    if ($stock->quantity < $item->qty_delivered) {
                        throw new \Exception(
                            "Stock insuffisant pour le produit ID {$item->product_id}"
                        );
                    }

                    $stock->decrement('quantity', $item->qty_delivered);

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

                        $existingOrderItem = OrderItem::where('order_id', $order->id)
                            ->where('product_id', $product->id)
                            ->first();

                        if ($existingOrderItem) {
                            $newQty = $existingOrderItem->quantity + $item->qty_delivered;

                            $unitPrice = (float) $existingOrderItem->price_unit_ht;
                            $promoValue = (float) ($existingOrderItem->promo_value ?? 0);
                            $promoType = $existingOrderItem->promo_type;
                            $tvaRate = (float) $existingOrderItem->tva_rate;

                            $finalUnit = $this->calculateFinalUnitPrice(
                                $unitPrice,
                                $promoType,
                                $promoValue
                            );

                            $newTotalHt = round($finalUnit * $newQty, 2);
                            $newTotalTva = round($newTotalHt * ($tvaRate / 100), 2);
                            $newTotalTtc = round($newTotalHt + $newTotalTva, 2);

                            $existingOrderItem->update([
                                'quantity' => $newQty,
                                'final_price_ht' => $finalUnit,
                                'total_ht' => $newTotalHt,
                                'total_tva' => $newTotalTva,
                                'total_ttc' => $newTotalTtc,
                            ]);
                        } else {
                            $finalUnit = $this->calculateFinalUnitPrice(
                                (float) $item->unit_price_ht,
                                $item->promo_type,
                                (float) ($item->promo_value ?? 0)
                            );

                            $totalHt = round($finalUnit * $item->qty_delivered, 2);
                            $totalTva = round($totalHt * ($item->tva_rate / 100), 2);
                            $totalTtc = round($totalHt + $totalTva, 2);

                            OrderItem::create([
                                'order_id' => $order->id,
                                'product_id' => $product->id,
                                'quantity' => $item->qty_delivered,
                                'price_unit_ht' => $item->unit_price_ht,
                                'promo_type' => $item->promo_type,
                                'promo_value' => $item->promo_value ?? 0,
                                'discount_amount' => 0,
                                'final_price_ht' => $finalUnit,
                                'tva_rate' => $item->tva_rate,
                                'total_ht' => $totalHt,
                                'total_tva' => $totalTva,
                                'total_ttc' => $totalTtc,
                            ]);
                        }
                    }
                }

                $delivery->update([
                    'status' => 'livrer',
                    'total_ht' => $sumHt,
                    'total_tva' => $sumTva,
                    'total_ttc' => $sumTtc,
                ]);

                $order->refresh();
                $order->load(['items', 'deliveries.items']);

                $totalDeliveredByProduct = $order->deliveries
                    ->where('status', 'livrer')
                    ->flatMap->items
                    ->groupBy('product_id')
                    ->map(fn ($group) => $group->sum('qty_delivered'));

                foreach ($order->items as $orderItem) {
                    $delivered = $totalDeliveredByProduct[$orderItem->product_id] ?? 0;

                    if ($delivered > 0) {
                        if ($orderItem->quantity != $delivered) {
                            $unitPrice = (float) $orderItem->price_unit_ht;
                            $promoValue = (float) ($orderItem->promo_value ?? 0);
                            $promoType = $orderItem->promo_type;
                            $tvaRate = (float) $orderItem->tva_rate;

                            $finalUnit = $this->calculateFinalUnitPrice(
                                $unitPrice,
                                $promoType,
                                $promoValue
                            );

                            $newTotalHt = round($finalUnit * $delivered, 2);
                            $newTotalTva = round($newTotalHt * ($tvaRate / 100), 2);
                            $newTotalTtc = round($newTotalHt + $newTotalTva, 2);

                            $orderItem->update([
                                'quantity' => $delivered,
                                'final_price_ht' => $finalUnit,
                                'total_ht' => $newTotalHt,
                                'total_tva' => $newTotalTva,
                                'total_ttc' => $newTotalTtc,
                            ]);
                        }
                    } else {
                        $orderItem->delete();
                    }
                }

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
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

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

        try {
            DB::transaction(function () use ($delivery, $truck) {
                $delivery->load([
                    'items.product',
                    'order.items',
                    'order.deliveries.items'
                ]);

                $depotId = $delivery->depot_id;
                $order = $delivery->order;

                $sumHt = 0;
                $sumTva = 0;
                $sumTtc = 0;

                foreach ($delivery->items as $item) {
                    if ($item->is_substitution) {
                        // ── Retourner les substitutions au dépôt ──
                        $qty = $item->qty_delivered;

                        $stock = TruckStock::where('truck_id', $truck->id)
                            ->where('product_id', $item->product_id)
                            ->lockForUpdate()
                            ->first();

                        if ($stock && $stock->quantity >= $qty) {
                            $stock->decrement('quantity', $qty);
                        }

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
                        // ── Livrer les produits originaux ──
                        $item->calculateTotals();
                        $item->save();

                        $sumHt += $item->total_ht;
                        $sumTva += $item->total_tva;
                        $sumTtc += $item->total_ttc;

                        $stock = TruckStock::where('truck_id', $truck->id)
                            ->where('product_id', $item->product_id)
                            ->lockForUpdate()
                            ->first();

                        if (!$stock) {
                            throw new \Exception(
                                "Stock introuvable pour le produit ID {$item->product_id}"
                            );
                        }

                        if ($stock->quantity < $item->qty_delivered) {
                            throw new \Exception(
                                "Stock insuffisant pour le produit ID {$item->product_id}"
                            );
                        }

                        $stock->decrement('quantity', $item->qty_delivered);

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

                // ── Recalculer les totaux de la commande ──
                $order->refresh();
                $order->load(['items', 'deliveries.items']);

                $totalDeliveredByProduct = $order->deliveries
                    ->where('status', 'livrer')
                    ->flatMap->items
                    ->groupBy('product_id')
                    ->map(fn ($group) => $group->sum('qty_delivered'));

                $isFullyDelivered = true;
                foreach ($order->items as $orderItem) {
                    $delivered = $totalDeliveredByProduct[$orderItem->product_id] ?? 0;
                    if ($delivered < $orderItem->quantity) {
                        $isFullyDelivered = false;
                        break;
                    }
                }

                // ── Recalculer order totals ──
                $orderTotalHt = $order->items->sum('total_ht');
                $orderTotalTva = $order->items->sum('total_tva');
                $orderTotalTtc = $order->items->sum('total_ttc');

                $order->update([
                    'status' => $isFullyDelivered ? 'livrer' : 'confirmed',
                    'total_ht' => $orderTotalHt,
                    'total_tva' => $orderTotalTva,
                    'total_ttc' => $orderTotalTtc,
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

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

        try {
            DB::transaction(function() use ($delivery, $truck, $quantities) {
                $delivery->load(['items.product', 'order.items']);
                
                $sumHt = 0;
                $sumTva = 0;
                $sumTtc = 0;

                foreach ($delivery->items as $item) {
                    $originalQty = $item->qty_delivered;
                    $deliveredQty = isset($quantities[$item->id]) ? (int) $quantities[$item->id] : $originalQty;

                    if ($deliveredQty < 0 || $deliveredQty > $originalQty) {
                        throw new \Exception(
                            "La quantité livrée pour le produit {$item->product->name} doit être comprise entre 0 et {$originalQty}."
                        );
                    }

                    $item->qty_delivered = $deliveredQty;
                    $item->calculateTotals();
                    $item->save();

                    $sumHt += $item->total_ht;
                    $sumTva += $item->total_tva;
                    $sumTtc += $item->total_ttc;

                    $truckStock = TruckStock::where('truck_id', $truck->id)
                        ->where('product_id', $item->product_id)
                        ->lockForUpdate()
                        ->first();
                    
                    if (!$truckStock) {
                        throw new \Exception(
                            "Stock de camion introuvable pour le produit ID {$item->product_id}"
                        );
                    }

                    if ($truckStock->quantity < $deliveredQty) {
                        throw new \Exception(
                            "Stock de camion insuffisant pour le produit {$item->product->name} (Disponible: {$truckStock->quantity}, Demandé: {$deliveredQty})"
                        );
                    }

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

                $delivery->update([
                    'status' => 'livrer',
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

                // ── Recalculer order totals ──
                $orderTotalHt = $order->items->sum('total_ht');
                $orderTotalTva = $order->items->sum('total_tva');
                $orderTotalTtc = $order->items->sum('total_ttc');

                $order->update([
                    'status' => $isFullyDelivered ? 'livrer' : 'confirmed',
                    'total_ht' => $orderTotalHt,
                    'total_tva' => $orderTotalTva,
                    'total_ttc' => $orderTotalTtc,
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('livreur.deliveries.index')->with('success', 'Livraison validée (Statut: ' . $delivery->status . ').');
    }

    public function cancel(Delivery $delivery)
    {
        if ($delivery->status !== 'pending' && $delivery->status !== 'proposition') {
            return back()->with('error', "Cette livraison ne peut pas être annulée.");
        }

        auth()->user()->load('truck');
        $truck = auth()->user()->truck;
        if (!$truck) {
            return back()->with('error', "Aucun camion assigné.");
        }

        $depotId = $delivery->depot_id;

        try {
            DB::transaction(function () use ($delivery, $truck, $depotId) {
                $delivery->load('items.product');

                foreach ($delivery->items as $item) {
                    $qty = $item->qty_delivered;
                    if ($qty <= 0) continue;

                    $truckStock = TruckStock::where('truck_id', $truck->id)
                        ->where('product_id', $item->product_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$truckStock) {
                        throw new \Exception(
                            "Stock de camion introuvable pour le produit ID {$item->product_id}"
                        );
                    }

                    if ($truckStock->quantity < $qty) {
                        throw new \Exception(
                            "Stock de camion insuffisant pour annuler la livraison du produit {$item->product->name} (Disponible: {$truckStock->quantity}, Requis: {$qty})"
                        );
                    }

                    $truckStock->decrement('quantity', $qty);

                    $depotStock = DepotStock::where('depot_id', $depotId)
                        ->where('product_id', $item->product_id)
                        ->lockForUpdate()
                        ->first();

                    if (!$depotStock) {
                        $depotStock = DepotStock::create([
                            'depot_id' => $depotId,
                            'product_id' => $item->product_id,
                            'quantity' => 0
                        ]);
                    }

                    $depotStock->increment('quantity', $qty);

                    StockMovement::create([
                        'product_id' => $item->product_id,
                        'depot_id' => $depotId,
                        'truck_id' => $truck->id,
                        'user_id' => auth()->id(),
                        'order_id' => $delivery->order_id,
                        'type' => 'in',
                        'quantity' => $qty,
                        'reason' => 'Annulation Livraison (Retour Depot) - BL #' . $delivery->id,
                        'moved_at' => now(),
                    ]);
                }

                $delivery->update(['status' => 'annuler']);

                // ── Recalculer les totaux de la commande ──
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

                $orderTotalHt = $order->items->sum('total_ht');
                $orderTotalTva = $order->items->sum('total_tva');
                $orderTotalTtc = $order->items->sum('total_ttc');

                $order->update([
                    'status' => $isFullyDelivered ? 'livrer' : 'confirmed',
                    'total_ht' => $orderTotalHt,
                    'total_tva' => $orderTotalTva,
                    'total_ttc' => $orderTotalTtc,
                ]);
            });
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('livreur.deliveries.index')->with('success', 'Livraison annulée et stock retourné au dépôt.');
    }

    private function calculateFinalUnitPrice(
        float $price,
        ?string $promoType,
        float $promoValue = 0
    ): float {
        if ($promoType === 'percentage' && $promoValue > 0) {
            return round($price * (1 - $promoValue / 100), 2);
        }

        if ($promoType === 'fixed' && $promoValue > 0) {
            return round(max(0, $price - $promoValue), 2);
        }

        return $price;
    }
}
