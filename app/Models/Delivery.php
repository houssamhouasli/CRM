<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'livreur_id',
        'depot_id',
        'status',
        'has_substitution',
        'delivery_date',
        'total_ht',
        'total_tva',
        'total_ttc',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date'    => 'date',
            'has_substitution' => 'boolean',
            'total_ht'         => 'decimal:2',
            'total_tva'        => 'decimal:2',
            'total_ttc'        => 'decimal:2',
        ];
    }


    public const STATUS_LABELS = [
        'pending'     => 'En attente',
        'proposition' => 'Proposition',
        'livrer'      => 'Livrée',
        'annuler'     => 'Annulée',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

 
    public function originalItems()
    {
        return $this->hasMany(DeliveryItem::class)->where('is_substitution', false);
    }

    /**
     * Items de substitution uniquement
     */
    public function substitutionItems()
    {
        return $this->hasMany(DeliveryItem::class)->where('is_substitution', true);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function livreur()
    {
        return $this->belongsTo(User::class, 'livreur_id');
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function items()
    {
        return $this->hasMany(DeliveryItem::class);
    }

    public function returns()
    {
        return $this->hasMany(ReturnModel::class, 'delivery_id');
    }
}
