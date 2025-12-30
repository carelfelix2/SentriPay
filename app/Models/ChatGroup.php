<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Transaction;

class ChatGroup extends Model
{
    protected $fillable = [
        'order_id',
        'seller_id',
        'buyer_id',
        'admin_id',
        'name',
        'status',
        'buyer_confirmed',
        'closed_at'
    ];

    protected $casts = [
        'buyer_confirmed' => 'boolean',
        'closed_at' => 'datetime'
    ];

    /**
     * Get the order associated with the chat group
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the seller user
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the buyer user
     */
    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    /**
     * Get the admin user
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get messages in this chat group
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Close the chat group
     */
    public function close(): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now()
        ]);
    }

    /**
     * Mark buyer as confirmed received account
     */
    public function confirmBuyerReceived(): void
    {
        $this->update([
            'buyer_confirmed' => true
        ]);

        // Close the chat group
        $this->close();

        // Update order status to completed (transaksi selesai)
        $this->order->update(['status' => 'completed', 'delivered_date' => now(), 'completed_date' => now()]);

        // Create transaction record for seller payment
        Transaction::create([
            'transaction_number' => (new Transaction())->generateTransactionNumber(),
            'order_id' => $this->order_id,
            'from_user_id' => $this->buyer_id,      // From buyer
            'to_user_id' => $this->seller_id,       // To seller
            'amount' => $this->order->total_amount,
            'type' => 'release',                    // Dana dilepas ke penjual
            'status' => 'completed',                // Langsung completed
            'description' => "Pembayaran untuk pesanan {$this->order->order_number} - {$this->name}",
            'confirmed_at' => now()
        ]);
    }
}
