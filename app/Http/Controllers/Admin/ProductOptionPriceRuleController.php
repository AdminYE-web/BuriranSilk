<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOptionPriceRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductOptionPriceRuleController extends Controller
{
    public function index(Request $request)
    {
        $language = session('admin_product_language', 'pt');

        $search = $request->input('search');
        $type = $request->input('type');
        $productId = $request->input('product_id');

        $products = Product::where('language', $language)
            ->whereIn('is_active', [1, 3])
            ->orderBy('product_name')
            ->get();

        $rules = ProductOptionPriceRule::with(['product', 'targetOption.group', 'options.group', 'tiers'])
            ->whereHas('product', function ($query) use ($language) {
                $query->where('language', $language);
            })
            ->when($type !== null && $type !== '', function ($query) use ($type) {
                $query->whereHas('product', function ($q) use ($type) {
                    $q->where('product_type', $type);
                });
            })
            ->when($productId, function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('rule_name', 'like', '%'.$search.'%')
                        ->orWhereHas('product', function ($productQuery) use ($search) {
                            $productQuery->where('product_name', 'like', '%'.$search.'%')
                                ->orWhere('product_code', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('targetOption', function ($optionQuery) use ($search) {
                            $optionQuery->where('option_name', 'like', '%'.$search.'%')
                                ->orWhere('option_code', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('options', function ($optionQuery) use ($search) {
                            $optionQuery->where('option_name', 'like', '%'.$search.'%')
                                ->orWhere('option_code', 'like', '%'.$search.'%');
                        });
                });
            })
            ->orderBy('option_price_rule_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.option_price_rules.index', compact(
            'rules',
            'products',
            'search',
            'type',
            'productId',
            'language'
        ));
    }

    public function create(Request $request)
    {
        $language = session('admin_product_language', 'pt');
        $selectedProductId = $request->input('product_id');

        $products = Product::where('language', $language)
            ->whereIn('is_active', [1, 3])
            ->orderBy('product_name')
            ->get();

        $selectedProduct = null;
        $options = collect();

        if ($selectedProductId) {
            $selectedProduct = Product::where('language', $language)
                ->where('product_id', $selectedProductId)
                ->first();

            if ($selectedProduct) {
                $selectedProduct->load(['assignedOptions.group']);

                $options = $this->activeAssignedOptionsForProduct($selectedProduct, $language);
            }
        }

        return view('admin.option_price_rules.create', compact(
            'products',
            'selectedProduct',
            'selectedProductId',
            'options',
            'language'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'rule_name' => 'required|string|max:255',
            'target_option_id' => 'required|exists:product_options,option_id',
            'option_ids' => 'required|array|min:1',
            'option_ids.*' => 'exists:product_options,option_id',
            'tiers' => 'required|array|min:1',
            'tiers.*.min_qty' => 'required|integer|min:1',
            'tiers.*.max_qty' => 'nullable|integer|min:1',
            'tiers.*.additional_price' => 'required|numeric|min:0',
            'tiers.*.additional_price_with_tax' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $this->validateRuleOptionsBelongToProduct(
            (int) $validated['product_id'],
            (int) $validated['target_option_id'],
            $validated['option_ids']
        );

        DB::transaction(function () use ($request, $validated) {
            $rule = ProductOptionPriceRule::create([
                'product_id' => $validated['product_id'],
                'target_option_id' => $validated['target_option_id'],
                'rule_name' => $validated['rule_name'],
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            $rule->options()->sync($this->normalizedOptionIds($validated['option_ids']));

            foreach ($validated['tiers'] as $tier) {
                $rule->tiers()->create([
                    'min_qty' => $tier['min_qty'],
                    'max_qty' => $tier['max_qty'] ?? null,
                    'additional_price' => $tier['additional_price'],
                    'additional_price_with_tax' => $tier['additional_price_with_tax'] ?? null,
                    'is_active' => 1,
                ]);
            }
        });

        return redirect()
            ->route('admin.option-price-rules.index')
            ->with('success', 'Option price rule created successfully.');
    }
    public function duplicate(
    Request $request,
    ProductOptionPriceRule $optionPriceRule
) {
    $language = session('admin_product_language', 'pt');

    $optionPriceRule->load([
        'product',
        'targetOption.group',
        'options.group',
        'tiers',
    ]);

    $products = Product::where('language', $language)
        ->whereIn('is_active', [1, 3])
        ->orderBy('product_name')
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Product ที่ต้องการนำกฎไปใช้
    |--------------------------------------------------------------------------
    |
    | ตอนกด Duplicate ครั้งแรก จะเลือก Product เดิมไว้ก่อน
    | เมื่อผู้ใช้เปลี่ยน Product ระบบจะส่ง product_id กลับมาที่หน้านี้
    |
    */
    $selectedProductId = (int) $request->input(
        'product_id',
        $optionPriceRule->product_id
    );

    $selectedProduct = Product::where('language', $language)
        ->whereIn('is_active', [1, 3])
        ->where('product_id', $selectedProductId)
        ->firstOrFail();

    $selectedProduct->load(['assignedOptions.group']);

    $options = $this->activeAssignedOptionsForProduct(
        $selectedProduct,
        $language
    );

    /*
    |--------------------------------------------------------------------------
    | จับคู่ Target Option กับ Product ใหม่
    |--------------------------------------------------------------------------
    |
    | เริ่มจาก option_id เดียวกันก่อน
    | ถ้าเป็นคนละ option_id จะลองจับคู่จาก option_code
    | และ group_name
    |
    */
    $selectedTargetOptionId = $this->findMatchingOptionId(
        $optionPriceRule->targetOption,
        $options
    );

    /*
    |--------------------------------------------------------------------------
    | จับคู่ Required Options กับ Product ใหม่
    |--------------------------------------------------------------------------
    */
    $selectedOptionIds = $optionPriceRule->options
        ->map(function ($sourceOption) use ($options) {
            return $this->findMatchingOptionId(
                $sourceOption,
                $options
            );
        })
        ->filter()
        ->unique()
        ->values()
        ->toArray();

    /*
    |--------------------------------------------------------------------------
    | Copy Additional Price Tiers
    |--------------------------------------------------------------------------
    */
    $tiers = $optionPriceRule->tiers
        ->sortBy('min_qty')
        ->map(function ($tier) {
            return [
                'min_qty' => $tier->min_qty,
                'max_qty' => $tier->max_qty,
                'additional_price' => $tier->additional_price,
                'additional_price_with_tax' => $tier->additional_price_with_tax,
            ];
        })
        ->values()
        ->toArray();

    $duplicateRule = $optionPriceRule;

    $duplicateRuleName = $optionPriceRule->rule_name . ' - Copy';

    return view('admin.option_price_rules.create', compact(
        'products',
        'selectedProduct',
        'selectedProductId',
        'options',
        'selectedTargetOptionId',
        'selectedOptionIds',
        'tiers',
        'duplicateRule',
        'duplicateRuleName',
        'language'
    ));
}

    public function edit(ProductOptionPriceRule $optionPriceRule)
    {
        $language = session('admin_product_language', 'pt');

        $optionPriceRule->load(['product', 'targetOption', 'options', 'tiers']);

        $selectedProduct = $optionPriceRule->product;
        $selectedTargetOptionId = $optionPriceRule->target_option_id ? (int) $optionPriceRule->target_option_id : null;
        $selectedOptionIds = $optionPriceRule->options->pluck('option_id')->map(fn ($id) => (int) $id)->toArray();

        $selectedProduct->load(['assignedOptions.group']);

        $options = $this->activeAssignedOptionsForProduct($selectedProduct, $language);

        $tiers = $optionPriceRule->tiers
            ->sortBy('min_qty')
            ->values();

        return view('admin.option_price_rules.edit', compact(
            'optionPriceRule',
            'selectedProduct',
            'options',
            'selectedTargetOptionId',
            'selectedOptionIds',
            'tiers',
            'language'
        ));
    }

    public function update(Request $request, ProductOptionPriceRule $optionPriceRule)
    {
        $validated = $request->validate([
            'rule_name' => 'required|string|max:255',
            'target_option_id' => 'required|exists:product_options,option_id',
            'option_ids' => 'required|array|min:1',
            'option_ids.*' => 'exists:product_options,option_id',
            'tiers' => 'required|array|min:1',
            'tiers.*.min_qty' => 'required|integer|min:1',
            'tiers.*.max_qty' => 'nullable|integer|min:1',
            'tiers.*.additional_price' => 'required|numeric|min:0',
            'tiers.*.additional_price_with_tax' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $this->validateRuleOptionsBelongToProduct(
            (int) $optionPriceRule->product_id,
            (int) $validated['target_option_id'],
            $validated['option_ids']
        );

        DB::transaction(function () use ($request, $optionPriceRule, $validated) {
            $optionPriceRule->update([
                'target_option_id' => $validated['target_option_id'],
                'rule_name' => $validated['rule_name'],
                'is_active' => $request->has('is_active') ? 1 : 0,
            ]);

            $optionPriceRule->options()->sync($this->normalizedOptionIds($validated['option_ids']));

            $optionPriceRule->tiers()->delete();

            foreach ($validated['tiers'] as $tier) {
                $optionPriceRule->tiers()->create([
                    'min_qty' => $tier['min_qty'],
                    'max_qty' => $tier['max_qty'] ?? null,
                    'additional_price' => $tier['additional_price'],
                    'additional_price_with_tax' => $tier['additional_price_with_tax'] ?? null,
                    'is_active' => 1,
                ]);
            }
        });

        return redirect()
            ->route('admin.option-price-rules.index')
            ->with('success', 'Option price rule updated successfully.');
    }

    public function destroy(ProductOptionPriceRule $optionPriceRule)
    {
        $optionPriceRule->delete();

        return redirect()
            ->route('admin.option-price-rules.index')
            ->with('success', 'Option price rule deleted successfully.');
    }

    private function activeAssignedOptionsForProduct(Product $product, string $language)
    {
        return $product->assignedOptions
            ->filter(function ($option) use ($language) {
                return $option->language === $language
                    && $option->pivot
                    && (int) $option->pivot->is_active === 1;
            })
            ->sortBy(function ($option) {
                return [
                    $option->group->sort_order ?? 999,
                    $option->pivot->sort_order ?? 999,
                    $option->option_name,
                ];
            })
            ->values();
    }

    private function validateRuleOptionsBelongToProduct(int $productId, int $targetOptionId, array $conditionOptionIds): void
    {
        $conditionOptionIds = $this->normalizedOptionIds($conditionOptionIds);

        $optionIds = collect($conditionOptionIds)
            ->push($targetOptionId)
            ->unique()
            ->values();

        $assignedCount = DB::table('product_option_assignments')
            ->where('product_id', $productId)
            ->where('is_active', 1)
            ->whereIn('option_id', $optionIds)
            ->count();

        if ($assignedCount !== $optionIds->count()) {
            throw ValidationException::withMessages([
                'option_ids' => 'Target and required options must belong to the selected product.',
            ]);
        }
    }

    private function normalizedOptionIds(array $optionIds): array
    {
        return collect($optionIds)
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }
    private function findMatchingOptionId(
    $sourceOption,
    $availableOptions
): ?int {
    if (! $sourceOption) {
        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | 1. ถ้า Product ใหม่ใช้ option_id เดียวกัน
    |--------------------------------------------------------------------------
    */
    $matchedOption = $availableOptions->first(function ($option) use ($sourceOption) {
        return (int) $option->option_id === (int) $sourceOption->option_id;
    });

    if ($matchedOption) {
        return (int) $matchedOption->option_id;
    }

    $sourceGroupName = $sourceOption->group->group_name ?? null;

    /*
    |--------------------------------------------------------------------------
    | 2. จับคู่ด้วย option_code และชื่อ Group
    |--------------------------------------------------------------------------
    */
    if (! empty($sourceOption->option_code)) {
        $matchedOption = $availableOptions->first(function ($option) use (
            $sourceOption,
            $sourceGroupName
        ) {
            $sameCode = (string) $option->option_code
                === (string) $sourceOption->option_code;

            $sameGroup = ! $sourceGroupName
                || ($option->group->group_name ?? null) === $sourceGroupName;

            return $sameCode && $sameGroup;
        });

        if ($matchedOption) {
            return (int) $matchedOption->option_id;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 3. สำรองด้วย option_name และชื่อ Group
    |--------------------------------------------------------------------------
    */
    $matchedOption = $availableOptions->first(function ($option) use (
        $sourceOption,
        $sourceGroupName
    ) {
        $sameName = (string) $option->option_name
            === (string) $sourceOption->option_name;

        $sameGroup = ! $sourceGroupName
            || ($option->group->group_name ?? null) === $sourceGroupName;

        return $sameName && $sameGroup;
    });

    return $matchedOption
        ? (int) $matchedOption->option_id
        : null;
}
}
