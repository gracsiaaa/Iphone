<?php

namespace App\Support;

final class Money
{
    /**
     * Mengubah nilai numerik menjadi format Rupiah.
     *
     * Contoh:
     * 13500000 menjadi Rp 13.500.000
     */
    public static function rupiah(int|float|string|null $amount): string
    {
        $numericAmount = is_numeric($amount) ? (float) $amount : 0;

        return 'Rp ' . number_format(
            num: $numericAmount,
            decimals: 0,
            decimal_separator: ',',
            thousands_separator: '.',
        );
    }

    /**
     * Membersihkan input Rupiah menjadi angka bulat yang aman disimpan.
     *
     * Contoh:
     * Rp 13.500.000 menjadi 13500000
     */
    public static function parseRupiah(int|float|string|null $amount): int
    {
        if (is_int($amount)) {
            return max(0, $amount);
        }

        if (is_float($amount)) {
            return max(0, (int) round($amount));
        }

        $digits = preg_replace('/[^0-9]/', '', (string) $amount);

        return $digits === '' ? 0 : (int) $digits;
    }
}
