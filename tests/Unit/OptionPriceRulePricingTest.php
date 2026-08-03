<?php

namespace Tests\Unit;

use App\Support\OptionPriceRulePricing;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OptionPriceRulePricingTest extends TestCase
{
    private array $rules = [
        [
            'id' => 1,
            'target_option_id' => 10,
            'option_ids' => [10, 20],
            'tiers' => [
                ['min_qty' => 1, 'max_qty' => 99, 'additional_price' => 25],
                ['min_qty' => 100, 'max_qty' => null, 'additional_price' => 15],
            ],
        ],
    ];

    #[Test]
    public function it_uses_the_matching_quantity_tier(): void
    {
        $price = OptionPriceRulePricing::replacementPrice(
            $this->rules,
            10,
            50,
            100,
            [10, 20]
        );

        $this->assertSame(15.0, $price);
    }

    #[Test]
    public function it_keeps_the_original_price_when_rule_conditions_do_not_match(): void
    {
        $price = OptionPriceRulePricing::replacementPrice(
            $this->rules,
            10,
            50,
            100,
            [10]
        );

        $this->assertSame(50.0, $price);
    }

    #[Test]
    public function it_only_replaces_the_target_option_price(): void
    {
        $price = OptionPriceRulePricing::replacementPrice(
            $this->rules,
            20,
            30,
            100,
            [10, 20]
        );

        $this->assertSame(30.0, $price);
    }
}
