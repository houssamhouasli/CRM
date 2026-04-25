<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
    ];

    public function stocks()
    {
        return $this->hasMany(DepotStock::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
