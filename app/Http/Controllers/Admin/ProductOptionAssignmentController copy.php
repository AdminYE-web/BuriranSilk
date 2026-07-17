<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OptionGroup;
use App\Models\Product;
use App\Models\ProductOptionGroupOrder;
use Illuminate\Http\Request;

class ProductOptionAssignmentController extends Controller
{
    public function edit(Product $product)
    {
        $product->load('assignedOptions');

        $language = $product->language ?? session('admin_product_language', 'pt');
        $productType = (int) $product->product_type;

        $groups = OptionGroup::with(['options' => function ($query) use ($language) {
            $query->where('language', $language)
                ->where('is_active', 1)
                ->orderBy('option_name');
        }])
            ->where('language', $language)
            ->where(function ($query) use ($productType) {
    $query->where('product_type', $productType)
        ->orWhereNull('product_type')
        ->orWhere('product_type', 0);
})
            ->where('is_active', 1)
            ->get();

        /*
    |--------------------------------------------------------------------------
    | ดึงลำดับ group เฉพาะ product นี้
    |--------------------------------------------------------------------------
    */
        $groupOrders = ProductOptionGroupOrder::where('product_id', $product->product_id)
            ->pluck('sort_order', 'option_group_id');

        /*
    |--------------------------------------------------------------------------
    | เรียง group ตามลำดับเฉพาะ product นี้
    | ถ้า product นี้ยังไม่เคยจัดลำดับ ให้ fallback ไปใช้ sort_order กลาง / group_name
    |--------------------------------------------------------------------------
    */
        $groups = $groups->sortBy(function ($group) use ($groupOrders) {
            return [
                $groupOrders[$group->option_group_id] ?? 999,
                $group->sort_order ?? 999,
                $group->group_name,
            ];
        })->values();

        $assignedOptionIds = $product->assignedOptions
            ->pluck('option_id')
            ->map(fn ($id) => (int) $id)
            ->toArray();

        $assignedPivot = $product->assignedOptions
            ->keyBy('option_id');

        $groups = $groups->map(function ($group) use ($assignedPivot) {
            $group->setRelation(
                'options',
                $group->options
                    ->sortBy(function ($option) use ($assignedPivot) {
                        $pivot = $assignedPivot[$option->option_id]->pivot ?? null;

                        return [
                            $pivot->sort_order ?? 999,
                            $option->option_name,
                        ];
                    })
                    ->values()
            );

            return $group;
        });

        return view('admin.product_option_assignments.edit', compact(
            'product',
            'groups',
            'language',
            'assignedOptionIds',
            'assignedPivot'
        ));
    }

    public function update(Request $request, Product $product)
    {
        
        
        $request->validate([
            'options' => 'nullable|array',
            'options.*.option_id' => 'required|exists:product_options,option_id',
            'options.*.sort_order' => 'nullable|integer|min:0',
            'options.*.is_default' => 'nullable|boolean',
            'options.*.is_active' => 'nullable|boolean',
            'options.*.qty_rule_type' => 'nullable|in:min,max,exact,range',
            'options.*.min_qty' => 'nullable|integer|min:1',
            'options.*.max_qty' => 'nullable|integer|min:1',
            'options.*.exact_qty' => 'nullable|integer|min:1',
        ]);

    

        $syncData = [];

        foreach ($request->input('options', []) as $item) {
            $optionId = (int) $item['option_id'];

            $qtyRuleType = $item['qty_rule_type'] ?? null;

            $minQty = null;
            $maxQty = null;
            $exactQty = null;

            if ($qtyRuleType === 'min') {
                $minQty = ! empty($item['min_qty']) ? (int) $item['min_qty'] : null;
            }

            if ($qtyRuleType === 'max') {
                $maxQty = ! empty($item['max_qty']) ? (int) $item['max_qty'] : null;
            }

            if ($qtyRuleType === 'exact') {
                $exactQty = ! empty($item['exact_qty']) ? (int) $item['exact_qty'] : null;
            }

            if ($qtyRuleType === 'range') {
                $minQty = ! empty($item['min_qty']) ? (int) $item['min_qty'] : null;
                $maxQty = ! empty($item['max_qty']) ? (int) $item['max_qty'] : null;
            }

            $syncData[$optionId] = [
                'sort_order' => $item['sort_order'] ?? 0,
                'is_default' => ! empty($item['is_default']) ? 1 : 0,
                'is_active' => ! empty($item['is_active']) ? 1 : 0,

                'qty_rule_type' => $qtyRuleType,
                'min_qty' => $minQty,
                'max_qty' => $maxQty,
                'exact_qty' => $exactQty,
            ];
        }

        $product->assignedOptions()->sync($syncData);
        

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product options updated successfully.');
    }

    public function updateGroupSort(Request $request, Product $product)
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.option_group_id' => ['required', 'exists:option_groups,option_group_id'],
            'items.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->items as $item) {
            ProductOptionGroupOrder::updateOrCreate(
                [
                    'product_id' => $product->product_id,
                    'option_group_id' => $item['option_group_id'],
                ],
                [
                    'sort_order' => $item['sort_order'],
                ]
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Product option group order updated successfully.',
        ]);
    }
}
