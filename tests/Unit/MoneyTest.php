<?php

namespace Tests\Unit;

use App\Support\Money;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_it_formats_rupiah(): void
    {
        $this->assertSame('Rp 13.500.000', Money::rupiah(13_500_000));
    }

    public function test_it_parses_formatted_rupiah(): void
    {
        $this->assertSame(13_500_000, Money::parseRupiah('Rp 13.500.000'));
    }

    public function test_empty_value_becomes_zero(): void
    {
        $this->assertSame(0, Money::parseRupiah(null));
        $this->assertSame('Rp 0', Money::rupiah(null));
    }
}
