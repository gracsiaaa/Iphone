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
}
