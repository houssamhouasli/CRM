<?php

namespace App\Livewire\Depositaire;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RestockCart extends Component
{
    public $search = '';
    public $selectedProducts = [];
    public $notes = '';

    public function toggleProduct($productId)
    {
        if (isset($this->selectedProducts[$productId])) {
            unset($this->selectedProducts[$productId]);
        } else {
            $this->selectedProducts[$productId] = 1;
        }
    }

    public function updateQuantity($productId, $qty)
    {
        $qty = (int)$qty;
        if ($qty <= 0) {
            unset($this->selectedProducts[$productId]);
        } else {
            $this->selectedProducts[$productId] = $qty;
        }
    }

    public function submitRequest()
    {
        if (empty($this->selectedProducts)) {
            return $this->flashError('Votre panier est vide.');
        }

        if ($error = $this->validatePrices()) {
            return $this->flashError($error);
        }

        DB::transaction(fn () => $this->createOrder());

        session()->flash('success', 'Demande envoyée.');
        return redirect()->route('depositaire.restock.index');
    }

    private function flashError($message)
    {
        session()->flash('error', $message);
        return;
    }

    private function validatePrices()
    {
        foreach ($this->selectedProducts as $productId => $qty) {
            $product = Product::find($productId);
            if (!$product?->price_ht) {
                return 'Produit sans prix: ' . ($product?->name ?? "#$productId");
            }
        }
        return null;
    }

    private function createOrder()
    {
        $order = Order::create([
            'user_id' => auth()->id(),
            'depot_id' => auth()->user()->depot_id,
            'created_by' => auth()->id(),
            'type' => 'restock',
            'status' => 'pending',
            'notes' => $this->notes,
        ]);


        foreach ($this->selectedProducts as $productId => $qty) {
            $p = Product::findOrFail($productId);
            $subHT = $p->price_ht * $qty;
            $subTVA = $subHT * ($p->tva_rate / 100);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $qty,
                'price_unit_ht' => $p->price_ht,
                'tva_rate' => $p->tva_rate,
                'final_price_ht' => 0,
                'total_ht' => 0,
                'total_tva' => 0,
                'total_ttc' => 0,
            ]);
        }

        $order->update([
            'total_ht' => 0,
            'total_tva' => 0,
            'total_ttc' => 0,
        ]);
    }

    public function render()
    {
        $categories = Category::with(['products' => fn($q) =>
            $this->search ? $q->where('name', 'like', "%$this->search%")
                              ->orWhere('sku', 'like', "%$this->search%") : $q
        ])->get();

        $cartItems = collect();

        if ($this->selectedProducts) {
            $cartItems = Product::whereIn('id', array_keys($this->selectedProducts))->get();
        }

        return view('livewire.depositaire.restock-cart', [
            'categories' => $categories,
            'cartItems' => $cartItems,
        ]);
    }
}
