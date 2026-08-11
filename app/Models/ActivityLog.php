<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public function getActionLabelAttribute(): string
    {
        return match ($this->action) {
            'auth.login' => 'Login berhasil',
            'auth.logout' => 'Logout',
            'auth.register' => 'Akun reseller dibuat',
            'order.created' => 'Pesanan dibuat',
            'payment.submitted' => 'Bukti pembayaran dikirim',
            'order.approved' => 'Pembayaran disetujui',
            'order.rejected' => 'Pembayaran ditolak',
            'order.completed' => 'Pesanan diselesaikan',
            default => Str::headline(str_replace('.', ' ', $this->action)),
        };
    }

    public function getCategoryAttribute(): string
    {
        return match (true) {
            str_starts_with($this->action, 'auth.') => 'auth',
            $this->action === 'payment.submitted' => 'payment',
            in_array($this->action, ['order.approved', 'order.rejected'], true) => 'payment',
            str_starts_with($this->action, 'order.') => 'order',
            default => 'system',
        };
    }

    public function getBrowserLabelAttribute(): string
    {
        $userAgent = Str::lower((string) $this->user_agent);

        if ($userAgent === '') {
            return 'Perangkat tidak diketahui';
        }

        $browser = match (true) {
            str_contains($userAgent, 'edg/') => 'Microsoft Edge',
            str_contains($userAgent, 'opr/') || str_contains($userAgent, 'opera') => 'Opera',
            str_contains($userAgent, 'firefox/') => 'Firefox',
            str_contains($userAgent, 'chrome/') => 'Chrome',
            str_contains($userAgent, 'safari/') => 'Safari',
            default => 'Browser lain',
        };

        $device = match (true) {
            str_contains($userAgent, 'iphone') => 'iPhone',
            str_contains($userAgent, 'ipad') => 'iPad',
            str_contains($userAgent, 'android') => 'Android',
            str_contains($userAgent, 'windows') => 'Windows',
            str_contains($userAgent, 'macintosh') || str_contains($userAgent, 'mac os') => 'macOS',
            str_contains($userAgent, 'linux') => 'Linux',
            default => null,
        };

        return $device ? "{$browser} · {$device}" : $browser;
    }

    public function getSubjectLabelAttribute(): ?string
    {
        if (! $this->subject_type || ! $this->subject_id) {
            return null;
        }

        if ($this->subject instanceof Order) {
            return $this->subject->invoice_number;
        }

        return class_basename($this->subject_type).' #'.$this->subject_id;
    }
}
