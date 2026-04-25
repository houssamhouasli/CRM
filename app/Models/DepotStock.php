<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepotStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'depot_id',
        'product_id',
        'quantity',
    ];

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
