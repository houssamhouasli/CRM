<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TruckStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'truck_id',
        'product_id',
        'quantity',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
