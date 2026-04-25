<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_id',
        'product_id',
        'qty_ordered',
        'qty_delivered',
        'returned_quantity',
        'is_substitution',
        'original_product_id',
        'unit_price_ht',
        'promo_type',
        'promo_value',
        'tva_rate',
        'total_ht',
        'total_tva',
        'total_ttc',
    ];

    public function originalProduct()
    {
        return $this->belongsTo(Product::class, 'original_product_id');
    }

    protected function casts(): array
    {
        return [
            'unit_price_ht'  => 'decimal:2',
            'promo_value'    => 'decimal:2',
            'tva_rate'       => 'decimal:2',
            'total_ht'       => 'decimal:2',
            'total_tva'      => 'decimal:2',
            'total_ttc'      => 'decimal:2',
        ];
    }

    public function calculateTotals(): void
    {
        $base       = (float) $this->unit_price_ht;
        $promoValue = (float) $this->promo_value;
        $promoType  = $this->promo_type;
        $tvaRate    = (float) $this->tva_rate;
        $qty        = (int)   $this->qty_delivered;


        if ($promoType === 'percentage' && $promoValue > 0) {
            $finalUnit = $base * (1 - $promoValue / 100);
        } elseif ($promoType === 'fixed' && $promoValue > 0) {
            $finalUnit = max(0, $base - $promoValue);
        } else {
            $finalUnit = $base;
        }

        $this->total_ht  = round($finalUnit * $qty, 2);
        $this->total_tva = round($this->total_ht * ($tvaRate / 100), 2);
        $this->total_ttc = round($this->total_ht + $this->total_tva, 2);
    }

    public function delivery()
    {
        return $this->belongsTo(Delivery::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function orderItem()
    {
        return $this->hasOneThrough(
            OrderItem::class,
            Delivery::class,
            'id',
            'order_id',
            'delivery_id',
            'order_id'
        )->where('order_items.product_id', $this->product_id);
    }
}
