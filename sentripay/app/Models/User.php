<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'bank_name',
        'bank_account',
        'account_holder',
        'balance',
        'status',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
        ];
    }

    // Relationships
    public function productsForSale(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function buyerOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sellerOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function complaintsRaised(): HasMany
    {
        return $this->hasMany(Dispute::class, 'complained_by');
    }

    public function complaintsAgainst(): HasMany
    {
        return $this->hasMany(Dispute::class, 'complained_against');
    }

    public function transactionsAsFrom(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_user_id');
    }

    public function transactionsAsTo(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_user_id');
    }

    public function disputesReviewed(): HasMany
    {
        return $this->hasMany(Dispute::class, 'reviewed_by');
    }
}

