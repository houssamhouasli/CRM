<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'promo_applied',
        'promo_type',
        'promo_value',
        'discount_amount',
        'price_unit_ht',
        'final_price_ht',
        'tva_rate',
        'total_ht',
        'total_tva',
        'total_ttc',
    ];

    protected function casts(): array
    {
        return [
            'promo_applied' => 'decimal:2',
            'promo_value' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'price_unit_ht' => 'decimal:2',
            'final_price_ht' => 'decimal:2',
            'tva_rate' => 'decimal:2',
            'total_ht' => 'decimal:2',
            'total_tva' => 'decimal:2',
            'total_ttc' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getDeliveredAttribute(): int
    {
        return DeliveryItem::whereHas('delivery', function ($query) {
            $query->where('order_id', $this->order_id)
                  ->where('status', 'livrer');
        })->where('product_id', $this->product_id)->sum('qty_delivered') ?? 0;
    }

    public function getDeliveredQuantityAttribute(): int
    {
        return DeliveryItem::whereHas('delivery', function ($query) {
            $query->where('order_id', $this->order_id)
                  ->whereIn('status', ['livrer', 'pending']);
        })->where('product_id', $this->product_id)->sum('qty_delivered') ?? 0;
    }
}
