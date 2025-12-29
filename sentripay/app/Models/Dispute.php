<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dispute extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispute_number',
        'order_id',
        'complained_by',
        'complained_against',
        'reason',
        'complaint_description',
        'status',
        'evidence',
        'admin_notes',
        'resolution',
        'reviewed_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function complainedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'complained_by');
    }

    public function complainedAgainst(): BelongsTo
    {
        return $this->belongsTo(User::class, 'complained_against');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function generateDisputeNumber()
    {
        return 'DSP-' . date('Ymd') . '-' . strtoupper(uniqid());
    }
}
