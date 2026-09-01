<?php

namespace App\Services;

use App\Models\Order;

class InvoiceNumberService
{
    public function generate(): string
    {
        do {
            $number = 'INV-'.now()->format('Ymd').'-'.str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
        } while (Order::query()->where('invoice_number', $number)->exists());

        return $number;
    }

    public function generateApproved(int $adminId): string
    {
        $date = now()->format('Ymd');
        $adminPadded = str_pad((string) $adminId, 2, '0', STR_PAD_LEFT);
        $prefix = $date.'-'.$adminPadded.'-';

        $lastSequence = Order::query()
            ->where('invoice_number', 'like', $prefix.'%')
            ->orderByDesc('invoice_number')
            ->value('invoice_number');

        $nextSequence = 1;
        if ($lastSequence) {
            $parts = explode('-', $lastSequence);
            $nextSequence = ((int) end($parts)) + 1;
        }

        return $prefix.str_pad((string) $nextSequence, 3, '0', STR_PAD_LEFT);
    }
}
