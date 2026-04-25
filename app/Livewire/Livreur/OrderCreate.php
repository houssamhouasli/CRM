<?php

namespace App\Livewire\Livreur;

use Livewire\Component;
use App\Models\Product;
use App\Models\Client;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderCreate extends Component
{
    public $clients = [];
    public $client_id = '';

    public $products = [];
    public $cart = [];
    protected $rules = [
        'client_id' => 'required|exists:clients,id',
        'cart' => 'required|array|min:1',
    ];
    public $search = '';

    public $total_ht = 0;
    public $total_tva = 0;
    public $total_ttc = 0;

    public function mount()
    {
        $this->clients = Client::orderBy('company_name')->get();
        $this->loadProducts();
    }

    public function updatedSearch()
    {
        $this->loadProducts();
    }

    public function loadProducts()
    {
        $this->products = Product::with(['category', 'truckStocks'])
            ->where('name', 'like', '%' . $this->search . '%')
            ->orWhere('sku', 'like', '%' . $this->search . '%')
            ->get();
    }

    public function addToCart($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }
        $this->calculateTotals();
    }

    public function removeFromCart($productId)
    {
        unset($this->cart[$productId]);
        $this->calculateTotals();
    }

    public function incrementQty($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]++;
        } else {
            $this->cart[$productId] = 1;
        }
        $this->calculateTotals();
    }

    public function decrementQty($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]--;
            if ($this->cart[$productId] <= 0) {
                unset($this->cart[$productId]);
            }
        }
        $this->calculateTotals();
    }

    public function updateQuantity($productId, $qty)
    {
        if ($qty > 0) {
            $this->cart[$productId] = $qty;
        } else {
            unset($this->cart[$productId]);
        }
        $this->calculateTotals();
    }

    public function calculateTotals()
    {
        $this->total_ht = 0;
        $this->total_tva = 0;
        $this->total_ttc = 0;

        foreach ($this->cart as $id => $qty) {
            $product = Product::find($id);
            if ($product) {
                $unit_price = $product->price_ht;
                $promo_value_per_unit = $product->calculateDiscountPerUnit($qty);
                $unit_price -= $promo_value_per_unit;

                $line_ht = $unit_price * $qty;
                $line_tva = $line_ht * ($product->tva_rate / 100);

                $this->total_ht += $line_ht;
                $this->total_tva += $line_tva;
                $this->total_ttc += ($line_ht + $line_tva);
            }
        }
    }

    public function submitOrder()
    {
        try {
            $this->validate([
                'client_id' => 'required|exists:clients,id',
                'cart' => 'required|array|min:1'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            session()->flash('error', 'Validation failed: ' . json_encode($e->errors()));
            return;
        }

        try {
            DB::beginTransaction();

            $order = Order::create([
                'type' => 'sale',
                'client_id' => $this->client_id,
                'created_by' => auth()->id(),
                'status' => 'pending',
                'total_ht' => $this->total_ht,
                'total_tva' => $this->total_tva,
                'total_ttc' => $this->total_ttc,
                'order_date' => now(),
            ]);

            foreach ($this->cart as $id => $qty) {
                $product = Product::find($id);

                $unit_price = $product->price_ht;
                $promo_amount = $product->calculateDiscountPerUnit($qty);
                $unit_price -= $promo_amount;

                $line_ht = $unit_price * $qty;
                $line_tva = $line_ht * ($product->tva_rate / 100);


                $promoApplied = $promo_amount > 0;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $qty,
                    'price_unit_ht' => $product->price_ht,
                    'promo_type' => $promoApplied ? $product->promo_type : null,
                    'promo_value' => $promoApplied ? $product->promo_value : 0,
                    'discount_amount' => $promo_amount * $qty,
                    'final_price_ht' => $unit_price,
                    'tva_rate' => $product->tva_rate,
                    'total_ht' => $line_ht,
                    'total_tva' => $line_tva,
                    'total_ttc' => $line_ht + $line_tva,
                ]);
            }

            DB::commit();

            session()->flash('success', 'Commande créée avec succès pour ce client.');
            return redirect()->route('livreur.orders.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', "Erreur lors de la création : " . $e->getMessage());
        }
    }

    public function getCartItems()
    {
        $cartItems = [];
        foreach ($this->cart as $id => $qty) {
            $product = Product::find($id);
            if (!$product) continue;
            
            $unitPriceHt = $product->price_ht;
            $discountPerUnit = $product->calculateDiscountPerUnit($qty);
            $finalUnitPriceHt = $unitPriceHt - $discountPerUnit;
            $unitPriceTtc = $finalUnitPriceHt * (1 + $product->tva_rate/100);
            $lineTotalHt = $finalUnitPriceHt * $qty;
            $lineTotalTtc = $unitPriceTtc * $qty;
            $totalDiscount = $discountPerUnit * $qty;
            $originalPriceTtc = $product->price_ht * (1 + $product->tva_rate/100);
            
            $unitPriceTva = $finalUnitPriceHt * ($product->tva_rate/100);
            $lineTotalTva = $unitPriceTva * $qty;
            
            $cartItems[] = [
                'product' => $product,
                'qty' => $qty,
                'unitPriceHt' => $unitPriceHt,
                'unitPriceTva' => $unitPriceTva,
                'unitPriceTtc' => $unitPriceTtc,
                'discountPerUnit' => $discountPerUnit,
                'totalDiscount' => $totalDiscount,
                'lineTotalHt' => $lineTotalHt,
                'lineTotalTva' => $lineTotalTva,
                'lineTotalTtc' => $lineTotalTtc,
                'originalPriceTtc' => $originalPriceTtc,
                'hasPromo' => $product->isPromoActive(),
                'promoApplied' => $discountPerUnit > 0,
            ];
        }
        return $cartItems;
    }

    public function render()
    {
        return view('livewire.livreur.order-create', [
            'cartItems' => $this->getCartItems()
        ]);
    }
}
