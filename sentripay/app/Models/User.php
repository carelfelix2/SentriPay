<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'balance',
        'kyc_verified',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'kyc_verified' => 'boolean',
        ];
    }

    // SentriPay Relationships
    public function productsForSale()
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    public function buyerOrders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sellerOrders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function complaintsRaised()
    {
        return $this->hasMany(Dispute::class, 'complained_by');
    }

    public function complaintsReceived()
    {
        return $this->hasMany(Dispute::class, 'complained_against');
    }

    public function transactionsAsFrom()
    {
        return $this->hasMany(Transaction::class, 'from_user_id');
    }

    public function transactionsAsTo()
    {
        return $this->hasMany(Transaction::class, 'to_user_id');
    }

    public function resolvedDisputes()
    {
        return $this->hasMany(Dispute::class, 'reviewed_by');
    }
}
