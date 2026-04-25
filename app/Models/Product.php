<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'sku',
        'price_ht',
        'stock_total',
        'image',
        'unit',
        'weight',
        'tva_rate',
        'promo_type',
        'promo_value',
        'promo_min_qty',
        'promo_start_date',
        'promo_end_date',
        'is_refundable',
    ];

    protected function casts(): array
    {
        return [
            'promo_start_date' => 'datetime',
            'promo_end_date' => 'datetime',
            'price_ht' => 'decimal:2',
            'promo_value' => 'decimal:2',
            'weight' => 'decimal:2',
            'tva_rate' => 'decimal:2',
        ];
    }

    public function isPromoActive(): bool
    {
        $now = now();
        return $this->promo_value > 0 && $this->promo_type !== null
            && (!$this->promo_start_date || $this->promo_start_date <= $now) 
            && (!$this->promo_end_date || $this->promo_end_date >= $now);
    }

    /**
     * Calcule la réduction applicable par unité selon la quantité demandée
     */
    public function calculateDiscountPerUnit(int $quantity): float
    {
        if (!$this->isPromoActive()) {
            return 0.0;
        }

        // Vérifier si la quantité minimum requise est atteinte
        if ($quantity < $this->promo_min_qty) {
            return 0.0;
        }

        if ($this->promo_type === 'percentage') {
            return $this->price_ht * ($this->promo_value / 100);
        }

        if ($this->promo_type === 'fixed') {
            return (float) $this->promo_value;
        }

        return 0.0;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function depotStocks()
    {
        return $this->hasMany(DepotStock::class);
    }

    public function truckStocks()
    {
        return $this->hasMany(TruckStock::class);
    }

    public function deliveryItems()
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}

