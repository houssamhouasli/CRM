<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $fillable = [
        'livreur_id',
        'name',
        'capacity',
    ];

    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function stocks()
    {
        return $this->hasMany(TruckStock::class);
    }
}
