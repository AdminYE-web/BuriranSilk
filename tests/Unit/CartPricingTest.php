<?php

namespace Tests\Unit;

use App\Support\CartPricing;
use PHPUnit\Framework\TestCase;

class CartPricingTest extends TestCase
{
    public function test_empty_cart_has_no_shipping_or_tax(): void
    {
        $summary = CartPricing::summary([]);

        $this->assertSame(0, $summary['shipping']);
        $this->assertSame(0, $summary['vat']);
        $this->assertSame(0, $summary['total']);
    }

    public function test_shipping_and_vat_are_added_below_free_shipping_threshold(): void
    {
        $summary = CartPricing::summary([
            ['line_subtotal' => 9000],
        ]);

        $this->assertSame(800, $summary['shipping']);
        $this->assertSame(980, $summary['vat']);
        $this->assertSame(10780, $summary['total']);
    }

    public function test_shipping_is_free_from_ten_thousand_yen(): void
    {
        $summary = CartPricing::summary([
            ['line_subtotal' => 10000],
        ]);

        $this->assertSame(0, $summary['shipping']);
        $this->assertSame(1000, $summary['vat']);
        $this->assertSame(11000, $summary['total']);
    }
}
