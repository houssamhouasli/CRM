<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'region_id',
        'depot_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function depot()
    {
        return $this->belongsTo(Depot::class);
    }

    public function truck()
    {
        return $this->hasOne(Truck::class, 'livreur_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'created_by');
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'livreur_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isCommercial(): bool
    {
        return $this->role === 'commercial';
    }

    public function isDepositaire(): bool
    {
        return $this->role === 'depositaire';
    }

    public function isLivreur(): bool
    {
        return $this->role === 'livreur';
    }
}
