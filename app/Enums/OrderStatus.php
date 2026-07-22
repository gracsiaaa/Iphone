<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING_PAYMENT = 'pending_payment';
    case WAITING_VERIFICATION = 'waiting_verification';
    case PAID = 'paid';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'Menunggu Pembayaran',
            self::WAITING_VERIFICATION => 'Menunggu Verifikasi',
            self::PAID => 'Sudah Dibayar',
            self::COMPLETED => 'Selesai',
            self::CANCELLED => 'Dibatalkan',
            self::REJECTED => 'Ditolak',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::PENDING_PAYMENT => 'bg-amber-50 text-amber-700 ring-amber-600/20',
            self::WAITING_VERIFICATION => 'bg-blue-50 text-blue-700 ring-blue-600/20',
            self::PAID => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
            self::COMPLETED => 'bg-zinc-100 text-zinc-700 ring-zinc-600/20',
            self::CANCELLED, self::REJECTED => 'bg-red-50 text-red-700 ring-red-600/20',
        };
    }
}
