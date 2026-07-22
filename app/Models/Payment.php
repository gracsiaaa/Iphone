<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    protected $fillable = ['order_id', 'method', 'amount', 'status', 'proof_path', 'confirmed_at', 'verified_at', 'verified_by', 'admin_note'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2', 'confirmed_at' => 'datetime', 'verified_at' => 'datetime'];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getProofUrlAttribute(): ?string
    {
        return $this->proof_path ? Storage::url($this->proof_path) : null;
    }
}
