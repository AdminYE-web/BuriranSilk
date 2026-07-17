<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\ProductPriceRule;
use App\Models\ProductPriceRuleOption;
use App\Models\ProductPriceRuleTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductPriceRuleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $language = session('admin_product_language', 'pt');
        $productId = $request->input('product_id');

        /*
    |--------------------------------------------------------------------------
    | Product list
    |--------------------------------------------------------------------------
    */
        $products = Product::with(['category', 'material'])
            ->where('language', $language)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('product_name', 'like', '%'.$search.'%')
                        ->orWhere('product_code', 'like', '%'.$search.'%');
                });
            })
            ->withCount('priceRules')
            ->orderBy('product_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | Selected product rules
    |--------------------------------------------------------------------------
    */
        $selectedProduct = null;
        $rules = null;

        if ($productId) {
            $selectedProduct = Product::where('language', $language)
                ->where('product_id', $productId)
                ->firstOrFail();

            $rules = ProductPriceRule::with(['product', 'options.group', 'tiers'])
                ->where('product_id', $selectedProduct->product_id)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('rule_name', 'like', '%'.$search.'%')
                            ->orWhereHas('options', function ($optionQuery) use ($search) {
                                $optionQuery->where('option_name', 'like', '%'.$search.'%')
                                    ->orWhereHas('group', function ($groupQuery) use ($search) {
                                        $groupQuery->where('group_name', 'like', '%'.$search.'%');
                                    });
                            });
                    });
                })
                ->orderBy('rule_id', 'desc')
                ->paginate(15)
                ->withQueryString();
        }

        return view('admin.product_price_rules.index', compact(
            'products',
            'rules',
            'search',
            'language',
            'selectedProduct',
            'productId'
        ));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');
        $selectedProductId = request('product_id');

        $products = Product::where('language', $language)
            ->orderBy('product_name')
            ->get();

        $options = ProductOption::with('group')
            ->where('language', $language)
            ->where('is_active', 1)
            ->whereHas('group', function ($query) use ($language) {
                $query->where('language', $language)
                    ->where('option_group_main', 1);
            })
            ->orderBy('option_group_id')
            ->orderBy('option_name')
            ->get();

        return view('admin.product_price_rules.create', compact(
            'products',
            'options',
            'language',
            'selectedProductId'
        ));
    }

    public function duplicate(Request $request, ProductPriceRule $productPriceRule)
    {
        $language = session('admin_product_language', 'pt');

        $productPriceRule->load(['product', 'options.group', 'tiers']);

        $products = Product::where('language', $language)
            ->orderBy('product_name')
            ->get();

        $selectedProductId = (int) $request->input(
            'product_id',
            $productPriceRule->product_id
        );

        $selectedProduct = Product::where('language', $language)
            ->where('product_id', $selectedProductId)
            ->firstOrFail();

        $availableOptions = $selectedProduct->options()
            ->with('group')
            ->wherePivot('is_active', 1)
            ->where('product_options.is_active', 1)
            ->where('product_options.language', $language)
            ->whereHas('group', function ($query) use ($language) {
                $query->where('language', $language)
                    ->where('option_group_main', 1);
            })
            ->get();

        $selectedOptionIds = $productPriceRule->options
            ->map(fn ($sourceOption) => $this->findMatchingOptionId($sourceOption, $availableOptions))
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $tiers = $productPriceRule->tiers
            ->sortBy('min_qty')
            ->values()
            ->map(fn ($tier) => [
                'min_qty' => $tier->min_qty,
                'max_qty' => $tier->max_qty,
                'unit_price' => $tier->unit_price,
                'unit_price_with_tax' => $tier->unit_price_with_tax,
            ])
            ->toArray();

        $displayTierIndex = $productPriceRule->tiers
            ->sortBy('min_qty')
            ->values()
            ->search(fn ($tier) => (int) $tier->is_display === 1);

        if ($displayTierIndex === false) {
            $displayTierIndex = 0;
        }

        return view('admin.product_price_rules.create', [
            'products' => $products,
            'options' => $availableOptions,
            'language' => $language,
            'selectedProductId' => $selectedProductId,
            'selectedOptionIds' => $selectedOptionIds,
            'tiers' => $tiers,
            'displayTierIndex' => $displayTierIndex,
            'duplicateRule' => $productPriceRule,
            'duplicateRuleName' => trim(($productPriceRule->rule_name ?? '').' - Copy'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'rule_name' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',

            'option_ids' => 'required|array|min:1',
            'option_ids.*' => 'exists:product_options,option_id',

            'tiers' => 'required|array|min:1',
            'tiers.*.min_qty' => 'required|integer|min:1',
            'tiers.*.max_qty' => 'nullable|integer|min:1',
            'tiers.*.unit_price' => 'required|numeric|min:0',
            'tiers.*.unit_price_with_tax' => 'nullable|numeric|min:0',

            'is_active' => 'nullable|boolean',
            'display_tier_index' => 'nullable|integer|min:0',
        ]);

        $this->validateRuleOptionsBelongToProduct(
            (int) $validated['product_id'],
            $validated['option_ids']
        );

        $rule = ProductPriceRule::create([
            'product_id' => $request->product_id,
            'rule_name' => $request->rule_name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        foreach ($request->option_ids as $optionId) {
            ProductPriceRuleOption::create([
                'rule_id' => $rule->rule_id,
                'option_id' => $optionId,
            ]);
        }

        $displayTierIndex = (int) $request->input('display_tier_index', 0);

        $this->resetDisplayTiersByProduct((int) $request->product_id);

        $tiers = collect($request->tiers)
            ->map(function ($tier, $index) {
                $tier['form_index'] = (int) $index;

                return $tier;
            })
            ->filter(function ($tier) {
                return ! empty($tier['min_qty']) && isset($tier['unit_price']);
            })
            ->sortBy(function ($tier) {
                return (int) $tier['min_qty'];
            })
            ->values();

        foreach ($tiers as $index => $tier) {
            $nextTier = $tiers->get($index + 1);

            $maxQty = null;

            if ($nextTier) {
                $maxQty = ((int) $nextTier['min_qty']) - 1;
            }

            ProductPriceRuleTier::create([
                'rule_id' => $rule->rule_id,
                'min_qty' => (int) $tier['min_qty'],
                'max_qty' => $maxQty,
                'unit_price' => $tier['unit_price'],
                'is_display' => (int) $tier['form_index'] === $displayTierIndex ? 1 : 0,
                'is_active' => 1,
                'unit_price_with_tax' => $tier['unit_price_with_tax'] ?? null,
            ]);
        }

        return redirect()
            ->route('admin.product-price-rules.index', [
                'product_id' => $request->product_id,
            ])
            ->with('success', 'Product price rule created successfully.');
    }

    public function edit(ProductPriceRule $productPriceRule)
    {
        $productPriceRule->load(['product', 'options', 'tiers']);

        $language = $productPriceRule->product->language
            ?? session('admin_product_language', 'pt');

        $products = Product::where('language', $language)
            ->orderBy('product_name')
            ->get();

        $options = ProductOption::with('group')
            ->where('language', $language)
            ->where('is_active', 1)
            ->whereHas('group', function ($query) use ($language) {
                $query->where('language', $language)
                    ->where('option_group_main', 1);
            })
            ->orderBy('option_group_id')
            ->orderBy('option_name')
            ->get();

        return view('admin.product_price_rules.edit', [
            'rule' => $productPriceRule,
            'products' => $products,
            'options' => $options,
            'language' => $language,
        ]);
    }

    public function update(Request $request, ProductPriceRule $productPriceRule)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'rule_name' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',

            'option_ids' => 'required|array|min:1',
            'option_ids.*' => 'exists:product_options,option_id',

            'tiers' => 'required|array|min:1',
            'tiers.*.min_qty' => 'required|integer|min:1',
            'tiers.*.max_qty' => 'nullable|integer|min:1',
            'tiers.*.unit_price' => 'required|numeric|min:0',
            'tiers.*.unit_price_with_tax' => 'nullable|numeric|min:0',

            'is_active' => 'nullable|boolean',
            'display_tier_index' => 'nullable|integer|min:0',
        ]);

        $this->validateRuleOptionsBelongToProduct(
            (int) $validated['product_id'],
            $validated['option_ids']
        );

        $productPriceRule->update([
            'product_id' => $request->product_id,
            'rule_name' => $request->rule_name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        ProductPriceRuleOption::where('rule_id', $productPriceRule->rule_id)->delete();
        ProductPriceRuleTier::where('rule_id', $productPriceRule->rule_id)->delete();

        $this->resetDisplayTiersByProduct((int) $request->product_id);

        foreach ($request->option_ids as $optionId) {
            ProductPriceRuleOption::create([
                'rule_id' => $productPriceRule->rule_id,
                'option_id' => $optionId,
            ]);
        }

        $displayTierIndex = (int) $request->input('display_tier_index', 0);

        $tiers = collect($request->tiers)
            ->map(function ($tier, $index) {
                $tier['form_index'] = (int) $index;

                return $tier;
            })
            ->filter(function ($tier) {
                return ! empty($tier['min_qty']) && isset($tier['unit_price']);
            })
            ->sortBy(function ($tier) {
                return (int) $tier['min_qty'];
            })
            ->values();

        foreach ($tiers as $index => $tier) {
            $nextTier = $tiers->get($index + 1);

            $maxQty = null;

            if ($nextTier) {
                $maxQty = ((int) $nextTier['min_qty']) - 1;
            }

            ProductPriceRuleTier::create([
                'rule_id' => $productPriceRule->rule_id,
                'min_qty' => (int) $tier['min_qty'],
                'max_qty' => $maxQty,
                'unit_price' => $tier['unit_price'],
                'is_display' => (int) $tier['form_index'] === $displayTierIndex ? 1 : 0,
                'is_active' => 1,
                'unit_price_with_tax' => $tier['unit_price_with_tax'] ?? null,
            ]);
        }

        return redirect()
            ->route('admin.product-price-rules.index', [
                'product_id' => $request->product_id,
            ])
            ->with('success', 'Product price rule updated successfully.');
    }

    public function destroy(ProductPriceRule $productPriceRule)
    {
        $productId = $productPriceRule->product_id;

        $productPriceRule->delete();

        return redirect()
            ->route('admin.product-price-rules.index', [
                'product_id' => $productId,
            ])
            ->with('success', 'Product price rule deleted successfully.');
    }

    public function show($id)
    {
        $rule = ProductPriceRule::with([
            'product',
            'options.group',
            'tiers',
        ])
            ->where('rule_id', $id)
            ->firstOrFail();

        return view('admin.product_price_rules.show', compact('rule'));
    }

    public function getProductOptions(Product $product)
    {
        $language = $product->language ?? session('admin_product_language', 'pt');

        $options = $product->options()
            ->with('group')
            ->wherePivot('is_active', 1)
            ->where('product_options.is_active', 1)
            ->where('product_options.language', $language)
            ->whereHas('group', function ($query) use ($language) {
                $query->where('language', $language)
                    ->where('option_group_main', 1);
            })
            ->orderBy('product_option_assignments.sort_order')
            ->orderBy('product_options.option_id')
            ->get();

        $groupedOptions = $options
            ->groupBy('option_group_id')
            ->map(function ($groupOptions) {
                $group = $groupOptions->first()->group;

                return [
                    'group_id' => $group->option_group_id ?? null,
                    'group_name' => $group->group_name ?? '-',
                    'group_code' => $group->group_code ?? null,
                    'options' => $groupOptions->map(function ($option) {
                        return [
                            'option_id' => $option->option_id,
                            'option_name' => $option->option_name,
                            'option_code' => $option->option_code,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'groups' => $groupedOptions,
        ]);
    }

    private function resetDisplayTiersByProduct(int $productId): void
    {
        $ruleIds = ProductPriceRule::where('product_id', $productId)
            ->pluck('rule_id');

        if ($ruleIds->isEmpty()) {
            return;
        }

        ProductPriceRuleTier::whereIn('rule_id', $ruleIds)
            ->update([
                'is_display' => 0,
            ]);
    }

    private function validateRuleOptionsBelongToProduct(int $productId, array $optionIds): void
    {
        $optionIds = collect($optionIds)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $assignedCount = DB::table('product_option_assignments')
            ->where('product_id', $productId)
            ->where('is_active', 1)
            ->whereIn('option_id', $optionIds)
            ->count();

        if ($assignedCount !== $optionIds->count()) {
            throw ValidationException::withMessages([
                'option_ids' => __('admin.product_price_rules.validation.options_belong_to_product'),
            ]);
        }
    }

    private function findMatchingOptionId($sourceOption, $availableOptions): ?int
    {
        $matchedOption = $availableOptions->first(
            fn ($option) => (int) $option->option_id === (int) $sourceOption->option_id
        );

        if ($matchedOption) {
            return (int) $matchedOption->option_id;
        }

        if (! empty($sourceOption->translation_key)) {
            $matchedOption = $availableOptions->first(
                fn ($option) => (string) $option->translation_key === (string) $sourceOption->translation_key
            );

            if ($matchedOption) {
                return (int) $matchedOption->option_id;
            }
        }

        $sourceGroupCode = $sourceOption->group->group_code ?? null;
        $sourceGroupName = $sourceOption->group->group_name ?? null;

        $matchedOption = $availableOptions->first(function ($option) use (
            $sourceOption,
            $sourceGroupCode,
            $sourceGroupName
        ) {
            $sameOption = ! empty($sourceOption->option_code)
                ? (string) $option->option_code === (string) $sourceOption->option_code
                : (string) $option->option_name === (string) $sourceOption->option_name;

            $sameGroup = $sourceGroupCode
                ? (string) ($option->group->group_code ?? '') === (string) $sourceGroupCode
                : (string) ($option->group->group_name ?? '') === (string) $sourceGroupName;

            return $sameOption && $sameGroup;
        });

        return $matchedOption ? (int) $matchedOption->option_id : null;
    }
}
