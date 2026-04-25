<?php

namespace App\Http\Controllers\Livreur;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\ReturnModel;
use App\Models\ReturnItem;
use App\Models\DeliveryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        return view('livreur.returns.index');
    }

    public function create(Delivery $delivery)
    {
        if ($delivery->livreur_id !== auth()->id()) {
            abort(403);
        }

        if ($delivery->status !== 'livrer') {
            return back()->with('error', 'Seules les livraisons terminées peuvent faire l\'objet d\'un retour.');
        }

        $availableItems = $this->getAvailableItemsForReturn($delivery);

        if (empty($availableItems)) {
            return back()->with('error', 'Aucun produit disponible pour retour dans cette livraison.');
        }

        return view('livreur.returns.create', compact('delivery', 'availableItems'));
    }

    public function store(Request $request, Delivery $delivery)
    {
        if ($delivery->livreur_id !== auth()->id()) {
            abort(403);
        }

        if ($delivery->status !== 'livrer') {
            return back()->with('error', 'Seules les livraisons terminées peuvent faire l\'objet d\'un retour.');
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.delivery_item_id' => 'required|exists:delivery_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.condition_type' => 'required|in:unsold,damaged,expired',
            'items.*.notes' => 'nullable|string|max:500',
            'reason' => 'nullable|string|max:1000',
        ]);

        try {
            $return = $this->createReturn($delivery, $validated['items'], $validated['reason'] ?? null);

            return redirect()->route('livreur.returns.index')
                ->with('success', 'Demande de retour #' . $return->id . ' créée avec succès et en attente de validation.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(\App\Models\ReturnModel $return)
    {
        if ($return->livreur_id !== auth()->id()) {
            abort(403);
        }

        $return->load(['delivery.order.client', 'returnItems.product', 'validator']);

        return view('livreur.returns.show', compact('return'));
    }

    private function createReturn(Delivery $delivery, array $items, ?string $reason = null): ReturnModel
    {
        return DB::transaction(function () use ($delivery, $items, $reason) {
            $return = ReturnModel::create([
                'delivery_id' => $delivery->id,
                'livreur_id' => $delivery->livreur_id,
                'depot_id' => $delivery->depot_id,
                'status' => 'pending',
                'reason' => $reason,
            ]);

            foreach ($items as $item) {
                $deliveryItem = DeliveryItem::findOrFail($item['delivery_item_id']);
                $product = $deliveryItem->product;

                if (!$product->is_refundable) {
                    throw new \Exception("Le produit {$product->name} n'est pas remboursable.");
                }

                $maxReturnable = $deliveryItem->qty_delivered - $deliveryItem->returned_quantity;
                if ($item['quantity'] > $maxReturnable) {
                    throw new \Exception(
                        "Quantité de retour invalide pour {$product->name}. " .
                        "Maximum retournable: {$maxReturnable}"
                    );
                }

                ReturnItem::create([
                    'return_id' => $return->id,
                    'product_id' => $deliveryItem->product_id,
                    'delivery_item_id' => $deliveryItem->id,
                    'quantity' => $item['quantity'],
                    'condition_type' => $item['condition_type'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            return $return->load('returnItems.product');
        });
    }

    private function getAvailableItemsForReturn(Delivery $delivery): array
    {
        $availableItems = [];

        foreach ($delivery->items as $item) {
            $returnableQty = $item->qty_delivered - $item->returned_quantity;
            if ($returnableQty > 0 && $item->product->is_refundable) {
                $availableItems[] = [
                    'delivery_item_id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'qty_delivered' => $item->qty_delivered,
                    'returned_quantity' => $item->returned_quantity,
                    'returnable_quantity' => $returnableQty,
                    'unit' => $item->product->unit,
                ];
            }
        }

        return $availableItems;
    }
}
