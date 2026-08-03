<?php

namespace App\Support;

use App\Models\Product;

class OptionPriceRulePricing
{
    public static function forProduct(Product $product): array
    {
        return $product->optionPriceRules
            ->map(fn ($rule) => [
                'id' => (int) $rule->option_price_rule_id,
                'target_option_id' => (int) $rule->target_option_id,
                'option_ids' => $rule->options->pluck('option_id')
                    ->map(fn ($optionId) => (int) $optionId)->values()->all(),
                'tiers' => $rule->tiers->map(fn ($tier) => [
                    'min_qty' => (int) $tier->min_qty,
                    'max_qty' => $tier->max_qty === null ? null : (int) $tier->max_qty,
                    'additional_price' => (float) $tier->additional_price,
                ])->values()->all(),
            ])
            ->sort(function (array $left, array $right) {
                $specificity = count($right['option_ids']) <=> count($left['option_ids']);

                return $specificity !== 0 ? $specificity : $left['id'] <=> $right['id'];
            })
            ->values()->all();
    }

    public static function replacementPrice(
        array $rules,
        int $targetOptionId,
        float $currentPrice,
        int $quantity,
        array $selectedOptionIds
    ): float {
        $selectedOptionIds = array_map('intval', $selectedOptionIds);

        foreach ($rules as $rule) {
            if ((int) $rule['target_option_id'] !== $targetOptionId) {
                continue;
            }

            $conditionOptionIds = array_map('intval', $rule['option_ids'] ?? []);

            if ($conditionOptionIds === [] || ! collect($conditionOptionIds)->every(
                fn ($optionId) => in_array($optionId, $selectedOptionIds, true)
            )) {
                continue;
            }

            $tiers = collect($rule['tiers'] ?? [])->sortBy('min_qty')->values();
            $matchedTier = $tiers->filter(function (array $tier) use ($quantity) {
                $minimum = (int) $tier['min_qty'];
                $maximum = $tier['max_qty'] === null ? null : (int) $tier['max_qty'];

                return $quantity >= $minimum && ($maximum === null || $quantity <= $maximum);
            })->sortByDesc('min_qty')->first();

            if ($matchedTier) {
                return (float) $matchedTier['additional_price'];
            }

            $highestTier = $tiers->sortByDesc('min_qty')->first();

            return $highestTier && $quantity > (int) $highestTier['min_qty']
                ? (float) $highestTier['additional_price']
                : $currentPrice;
        }

        return $currentPrice;
    }
}
