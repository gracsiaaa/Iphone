<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = ['user_id', 'invoice_number', 'status', 'subtotal', 'total', 'customer_name', 'customer_email', 'customer_phone', 'customer_store_name', 'billing_address', 'notes', 'paid_at', 'verified_by', 'verified_at'];

    protected function casts(): array
    {
        return ['status' => OrderStatus::class, 'subtotal' => 'decimal:2', 'total' => 'decimal:2', 'paid_at' => 'datetime', 'verified_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp'.number_format((float) $this->total, 0, ',', '.');
    }
}
