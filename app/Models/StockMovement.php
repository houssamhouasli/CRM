<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'product_id',
        'depot_id',
        'user_id',
        'order_id',
        'return_id',
        'truck_id',
        'type',
        'quantity',
        'reason',
        'moved_at',
    ];

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    protected function casts(): array
    {
        return [
            'moved_at' => 'datetime',
            'quantity' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function returnModel()
    {
        return $this->belongsTo(ReturnModel::class, 'return_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }
}
