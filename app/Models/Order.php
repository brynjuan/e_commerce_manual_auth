<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'shipping_address',
    'payment_method',
    'payment_proof_path',
    'payment_status',
    'verified_by_admin_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany // atau orderItems
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the admin who verified the payment.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_admin_id');
    }
}