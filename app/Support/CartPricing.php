<?php

namespace App\Support;

final class CartPricing
{
    public const SHIPPING_FEE = 800;

    public const FREE_SHIPPING_THRESHOLD = 10000;

    public const VAT_RATE = 0.10;

    public static function summary(array $items): array
    {
        $subtotal = (int) round(collect($items)->sum(
            fn (array $item) => (float) ($item['line_subtotal'] ?? 0)
        ));

        $shipping = count($items) === 0 || $subtotal >= self::FREE_SHIPPING_THRESHOLD
            ? 0
            : self::SHIPPING_FEE;
        $taxableTotal = $subtotal + $shipping;
        $vat = (int) round($taxableTotal * self::VAT_RATE);

        return [
            'item_count' => count($items),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'free_shipping_threshold' => self::FREE_SHIPPING_THRESHOLD,
            'amount_until_free_shipping' => max(0, self::FREE_SHIPPING_THRESHOLD - $subtotal),
            'vat_rate' => self::VAT_RATE,
            'vat' => $vat,
            'total' => $taxableTotal + $vat,
        ];
    }
}
