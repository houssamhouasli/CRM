<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReturnItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_id',
        'product_id',
        'delivery_item_id',
        'quantity',
        'condition_type',
        'notes',
    ];

    public function returnModel()
    {
        return $this->belongsTo(ReturnModel::class, 'return_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function deliveryItem()
    {
        return $this->belongsTo(DeliveryItem::class);
    }

    public function isUnsold(): bool
    {
        return $this->condition_type === 'unsold';
    }

    public function isDamaged(): bool
    {
        return $this->condition_type === 'damaged';
    }

    public function isExpired(): bool
    {
        return $this->condition_type === 'expired';
    }
}
