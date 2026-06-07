<?php

namespace App\Livewire\Depositaire;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Delivery;
use App\Models\DeliveryItem;
use App\Models\DepotStock;
use App\Models\TruckStock;
use App\Models\StockMovement;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CreateDelivery extends Component
{
    public Order $order;
    public $livreur_id = '';
    public $delivery_date;
    public $items = [];
    public $alreadyDelivered = [];
    public $depotStocks = [];
    public $depotId;

    // ── Substitutions ──
    public $substitutions = [];
    public $newSubProductId = '';
    public $newSubQty = 1;

    public function mount(Order $order)
    {
        $this->order = $order->load(['client', 'items.product', 'deliveries.items']);
        $this->delivery_date = now()->format('Y-m-d');
        $this->depotId = auth()->user()->depot_id;

        $this->alreadyDelivered = $this->order->deliveries
            ->where('status', '!=', 'annuler')
            ->flatMap->items
            ->groupBy('product_id')
            ->map->sum('qty_delivered')
            ->toArray();


        $stocks = DepotStock::where('depot_id', $this->depotId)
            ->whereIn('product_id', $this->order->items->pluck('product_id'))
            ->get()
            ->pluck('quantity', 'product_id')
            ->toArray();

        foreach ($this->order->items as $item) {
            $this->items[$item->product_id] = 0;
            $this->depotStocks[$item->product_id] = $stocks[$item->product_id] ?? 0;
        }
    }

    /**
     * Ajouter un produit de substitution
     */
    public function addSubstitution()
    {
        $this->validate([
            'newSubProductId' => 'required|exists:products,id',
            'newSubQty' => 'required|integer|min:1',
        ], [
            'newSubProductId.required' => 'Sélectionnez un produit.',
            'newSubQty.required' => 'Entrez une quantité.',
            'newSubQty.min' => 'Quantité minimum: 1.',
        ]);

        $isOriginal = $this->order->items->contains('product_id', (int)$this->newSubProductId);

        $depotId = auth()->user()->depot_id;
        $depotStock = DepotStock::where('depot_id', $depotId)
            ->where('product_id', $this->newSubProductId)
            ->first();

        if (!$depotStock || $depotStock->quantity < $this->newSubQty) {
            $this->addError('newSubProductId', 'Stock dépôt insuffisant pour ce produit.');
            return;
        }


        $existingIdx = collect($this->substitutions)->search(fn($s) => $s['product_id'] == $this->newSubProductId);
        if ($existingIdx !== false) {
            $this->substitutions[$existingIdx]['qty'] += $this->newSubQty;
        } else {
            $product = Product::find($this->newSubProductId);
            $this->substitutions[] = [
                'product_id' => (int)$this->newSubProductId,
                'product_name' => $product->name,
                'product_sku' => $product->sku ?? '—',
                'qty' => (int)$this->newSubQty,
                'price_ht' => (float)$product->price_ht,
                'tva_rate' => (float)$product->tva_rate,
            ];
        }

        $this->newSubProductId = '';
        $this->newSubQty = 1;
        $this->resetErrorBag(['newSubProductId', 'newSubQty']);
    }

    /**
     * Supprimer une substitution
     */
    public function removeSubstitution($index)
    {
        unset($this->substitutions[$index]);
        $this->substitutions = array_values($this->substitutions);
    }

    public function store()
    {
        $this->validate([
            'livreur_id' => 'required|exists:users,id',
            'delivery_date' => 'required|date',
        ]);

        $livreur = User::with('truck')->findOrFail($this->livreur_id);

        if (!$livreur->truck) {
            return $this->addError('livreur_id', 'Ce livreur n\'a pas de camion.');
        }

        $depotId = auth()->user()->depot_id;
        $hasSubstitution = count($this->substitutions) > 0;

        DB::transaction(function () use ($depotId, $livreur, $hasSubstitution) {
            $delivery = Delivery::create([
                'order_id' => $this->order->id,
                'livreur_id' => $livreur->id,
                'depot_id' => $depotId,
                'status' => $hasSubstitution ? 'proposition' : 'pending',
                'has_substitution' => $hasSubstitution,
                'delivery_date' => $this->delivery_date,
                'total_ht' => 0,
                'total_tva' => 0,
                'total_ttc' => 0,
            ]);

            $totals = ['ht' => 0, 'tva' => 0];

            // ── Items originaux de la commande ──
            foreach ($this->items as $productId => $qty) {
                $qty = (int) $qty;
                if ($qty <= 0) continue;

                $orderItem = $this->order->items->firstWhere('product_id', $productId);
                if (!$orderItem) continue;

                $delivered = $this->alreadyDelivered[$productId] ?? 0;
                $remaining = $orderItem->quantity - $delivered;
                
                $availableInDepot = $this->depotStocks[$productId] ?? 0;

                if ($qty > $remaining) $qty = $remaining;
                if ($qty > $availableInDepot) $qty = $availableInDepot;

                if ($qty <= 0) continue;

                $dItem = new DeliveryItem([
                    'delivery_id' => $delivery->id,
                    'product_id' => $productId,
                    'qty_ordered' => $orderItem->quantity,
                    'qty_delivered' => $qty,
                    'is_substitution' => false,
                    'unit_price_ht' => $orderItem->price_unit_ht,
                    'promo_type' => $orderItem->promo_type,
                    'promo_value' => $orderItem->promo_value,
                    'tva_rate' => $orderItem->tva_rate,
                ]);
                $dItem->calculateTotals();
                $dItem->save();

                $totals['ht'] += $dItem->total_ht;
                $totals['tva'] += $dItem->total_tva;

                DepotStock::where('depot_id', $depotId)
                    ->where('product_id', $productId)
                    ->decrement('quantity', $qty);

                TruckStock::firstOrCreate(
                    ['truck_id' => $livreur->truck->id, 'product_id' => $productId],
                    ['quantity' => 0]
                )->increment('quantity', $qty);

                StockMovement::create([
                    'product_id' => $productId,
                    'depot_id' => $depotId,
                    'user_id' => auth()->id(),
                    'order_id' => $this->order->id,
                    'type' => 'out',
                    'quantity' => $qty,
                    'reason' => 'Livraison Client - BL #' . $delivery->id,
                    'moved_at' => now(),
                ]);
            }

            // ── Items de substitution ──
            foreach ($this->substitutions as $sub) {
                $product = Product::find($sub['product_id']);
                if (!$product) continue;

                $qty = (int)$sub['qty'];

                $dItem = new DeliveryItem([
                    'delivery_id' => $delivery->id,
                    'product_id' => $product->id,
                    'qty_ordered' => $qty, 
                    'qty_delivered' => $qty,
                    'is_substitution' => true,
                    'unit_price_ht' => $product->price_ht,
                    'promo_type' => $product->promo_type,
                    'promo_value' => $product->isPromoActive() ? $product->promo_value : 0,
                    'tva_rate' => $product->tva_rate,
                ]);
                $dItem->calculateTotals();
                $dItem->save();

                $totals['ht'] += $dItem->total_ht;
                $totals['tva'] += $dItem->total_tva;


                DepotStock::where('depot_id', $depotId)
                    ->where('product_id', $product->id)
                    ->decrement('quantity', $qty);

                TruckStock::firstOrCreate(
                    ['truck_id' => $livreur->truck->id, 'product_id' => $product->id],
                    ['quantity' => 0]
                )->increment('quantity', $qty);

                StockMovement::create([
                    'product_id' => $product->id,
                    'depot_id' => $depotId,
                    'user_id' => auth()->id(),
                    'order_id' => $this->order->id,
                    'type' => 'out',
                    'quantity' => $qty,
                    'reason' => 'Substitution proposée - BL #' . $delivery->id,
                    'moved_at' => now(),
                ]);
            }

            $delivery->update([
                'total_ht' => round($totals['ht'], 2),
                'total_tva' => round($totals['tva'], 2),
                'total_ttc' => round($totals['ht'] + $totals['tva'], 2),
            ]);

            if (!$hasSubstitution) {
                $this->order->update(['status' => 'confirmed']);
            }
        });

        return redirect()->route('depositaire.orders.show', $this->order)
            ->with('success', $hasSubstitution
                ? 'Proposition de livraison créée. En attente de la réponse du client.'
                : 'Bon de livraison créé.');
    }

    public function render()
    {
        $livreurs = User::where('role', 'livreur')
            ->whereHas('truck')
            ->with('truck')
            ->get();


        $depotId = auth()->user()->depot_id;
        $availableProducts = Product::whereHas('depotStocks', function ($q) use ($depotId) {
            $q->where('depot_id', $depotId)->where('quantity', '>', 0);
        })->with(['depotStocks' => function ($q) use ($depotId) {
            $q->where('depot_id', $depotId);
        }])->get();

        return view('livewire.depositaire.create-delivery', [
            'livreurs' => $livreurs,
            'availableProducts' => $availableProducts,
        ]);
    }
}
