<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOption;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\QuotationItemOption;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        $quotations = Quotation::query()
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('quotation_no', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('quotation_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('quotation_date', '<=', $dateTo);
            })
            ->orderBy('quotation_id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.quotations.index', compact(
            'quotations',
            'search',
            'status',
            'dateFrom',
            'dateTo'
        ));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');

        $quotationNo = $this->generateQuotationNo();

        $products = Product::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.quotations.create', compact(
            'quotationNo',
            'products',
            'language'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'quotation_no' => ['required', 'string', 'max:100', 'unique:quotations,quotation_no'],
            'quotation_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.options' => ['nullable', 'array'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $language = session('admin_product_language', 'pt');

        DB::transaction(function () use ($request, $language) {
            $quotation = Quotation::create([
                'quotation_no' => $request->quotation_no,
                'quotation_date' => $request->quotation_date,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'note' => $request->note,
                'language' => $language,
                'status' => 'issued',
                'subtotal' => 0,
                'grand_total' => 0,
            ]);

            $subtotal = 0;

            foreach ($request->items as $itemData) {
                $product = Product::with([
                    'priceRules.options',
                    'priceRules.tiers',
                    'optionPriceRules.options',
                    'optionPriceRules.tiers',
                ])->findOrFail($itemData['product_id']);

                $quantity = (int) $itemData['quantity'];
                $selectedOptionIds = array_values($itemData['options'] ?? []);

                $priceData = $this->calculateProductPrice(
                    $product,
                    $quantity,
                    $selectedOptionIds
                );

                $quotationItem = QuotationItem::create([
                    'quotation_id' => $quotation->quotation_id,
                    'product_id' => $product->product_id,
                    'product_name_snapshot' => $product->product_name,
                    'product_code_snapshot' => $product->product_code,
                    'quantity' => $quantity,
                    'unit_price' => $priceData['unit_price'],
                    'option_total' => $priceData['option_total'],
                    'item_total' => $priceData['item_total'],
                    'price_rule_snapshot' => $priceData['matched_rule'],
                ]);

                foreach ($priceData['selected_options'] as $optionData) {
                    QuotationItemOption::create([
                        'quotation_item_id' => $quotationItem->quotation_item_id,
                        'option_group_id' => $optionData['option_group_id'],
                        'option_id' => $optionData['option_id'],
                        'group_name' => $optionData['group_name'],
                        'option_name' => $optionData['option_name'],
                        'additional_price' => $optionData['additional_price'],
                        'price_type' => $optionData['price_type'],
                    ]);
                }

                $subtotal += $priceData['item_total'];
            }

            $discountAmount = (float) ($request->discount_amount ?? 0);

            $afterDiscount = max($subtotal - $discountAmount, 0);

            $shippingFee = $afterDiscount >= 11000 ? 0 : 800;

            $vatAmount = ($afterDiscount + $shippingFee) * 0.10;

            $grandTotal = $afterDiscount + $shippingFee + $vatAmount;

            $quotation->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_fee' => $shippingFee,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
            ]);
        });

        return redirect()
            ->route('admin.quotations.index')
            ->with('success', 'Quotation created successfully.');
    }

    public function show(Quotation $quotation)
    {
        $quotation->load(['items.options']);

        return view('admin.quotations.show', compact('quotation'));
    }

    public function downloadPdf(Quotation $quotation)
    {
        $quotation->load(['items.options']);

        $pdf = Pdf::loadView('admin.quotations.pdf', compact('quotation'))
            ->setPaper('a4', 'portrait');

        return $pdf->download($quotation->quotation_no.'.pdf');
    }

    public function productOptions(Product $product)
    {
        $product->load([
            'assignedOptions.group.parent',
            'assignedOptions.mainImage',
            'assignedOptions.variants',
            'assignedOptions.priceRates',
            'priceRules.options',
            'priceRules.tiers',
            'optionPriceRules.options',
            'optionPriceRules.tiers',
        ]);

        $groups = $product->assignedOptions
            ->where('pivot.is_active', 1)
            ->sortBy(function ($option) {
                $group = $option->group;
                $parent = $group?->parent;

                return [
                    $parent->sort_order ?? $group->sort_order ?? 999,
                    $group->sort_order ?? 999,
                    $option->pivot->sort_order ?? 0,
                ];
            })
            ->groupBy(function ($option) {
                $group = $option->group;
                $displayGroup = $group?->parent ?: $group;

                return $displayGroup?->option_group_id ?? 0;
            })
            ->map(function ($options) {
                $first = $options->first();
                $group = $first?->group?->parent ?: $first?->group;

                return [
                    'group_id' => $group?->option_group_id,
                    'group_name' => $group?->group_name,
                    'group_code' => $group?->group_code,
                    'is_required' => (int) ($group?->is_required ?? 0),
                    'options' => $options->map(function ($option) {
                        return [
                            'option_id' => $option->option_id,
                            'option_group_id' => $option->option_group_id,
                            'option_name' => $option->option_name,
                            'additional_price' => (float) ($option->additional_price ?? 0),
                            'price_type' => $option->price_type,
                            'free_from_qty' => $option->free_from_qty ? (int) $option->free_from_qty : null,
                            'price_rates' => $option->priceRates->map(function ($rate) {
                                return [
                                    'min_qty' => (int) $rate->min_qty,
                                    'price' => (float) ($rate->additional_price ?? 0),
                                ];
                            })->values(),
                            'is_default' => (int) ($option->pivot->is_default ?? 0),
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json([
            'product' => [
                'product_id' => $product->product_id,
                'product_name' => $product->product_name,
                'product_code' => $product->product_code,
            ],
            'groups' => $groups,
            'price_rules' => $product->priceRules->map(function ($rule) {
                return [
                    'rule_id' => $rule->rule_id,
                    'rule_name' => $rule->rule_name,
                    'option_ids' => $rule->options->pluck('option_id')->map(fn ($id) => (int) $id)->values(),
                    'tiers' => $rule->tiers->map(function ($tier) {
                        return [
                            'min_qty' => (int) $tier->min_qty,
                            'max_qty' => $tier->max_qty ? (int) $tier->max_qty : null,
                            'unit_price' => (float) $tier->unit_price,
                        ];
                    })->values(),
                ];
            })->values(),
            'option_price_rules' => $product->optionPriceRules->map(function ($rule) {
                return [
                    'option_price_rule_id' => (int) $rule->option_price_rule_id,
                    'rule_name' => $rule->rule_name,
                    'target_option_id' => $rule->target_option_id ? (int) $rule->target_option_id : null,
                    'option_ids' => $rule->options->pluck('option_id')->map(fn ($id) => (int) $id)->values(),
                    'tiers' => $rule->tiers->map(function ($tier) {
                        return [
                            'min_qty' => (int) $tier->min_qty,
                            'max_qty' => $tier->max_qty ? (int) $tier->max_qty : null,
                            'additional_price' => (float) ($tier->additional_price ?? 0),
                        ];
                    })->values(),
                ];
            })->values(),
        ]);
    }

    private function generateQuotationNo(): string
    {
        $prefix = 'QT'.now()->format('Ymd');

        $latest = Quotation::where('quotation_no', 'like', $prefix.'%')
            ->orderBy('quotation_no', 'desc')
            ->first();

        if (! $latest) {
            return $prefix.'001';
        }

        $lastNumber = (int) substr($latest->quotation_no, -3);

        return $prefix.str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    private function calculateProductPrice(Product $product, int $quantity, array $selectedOptionIds): array
    {
        $selectedOptionIds = array_map('intval', $selectedOptionIds);

        $matchedRule = $product->priceRules
            ->filter(function ($rule) use ($selectedOptionIds) {
                $ruleOptionIds = $rule->options
                    ->pluck('option_id')
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->toArray();

                if (empty($ruleOptionIds)) {
                    return false;
                }

                return collect($ruleOptionIds)->every(fn ($id) => in_array($id, $selectedOptionIds, true));
            })
            ->sortByDesc(fn ($rule) => $rule->options->count())
            ->first();

        $unitPrice = 0;

        if ($matchedRule) {
            $tier = $matchedRule->tiers
                ->first(function ($tier) use ($quantity) {
                    $min = (int) $tier->min_qty;
                    $max = $tier->max_qty ? (int) $tier->max_qty : null;

                    return $quantity >= $min && ($max === null || $quantity <= $max);
                });

            if (! $tier) {
                $highestTier = $matchedRule->tiers
                    ->sortByDesc('min_qty')
                    ->first();

                if ($highestTier && $quantity > (int) $highestTier->min_qty) {
                    $tier = $highestTier;
                }
            }

            $unitPrice = $tier ? (float) $tier->unit_price : 0;
        }

        $selectedOptions = ProductOption::with(['group', 'priceRates'])
            ->whereIn('option_id', $selectedOptionIds)
            ->get();

        $ruleOptionIds = $matchedRule
            ? $matchedRule->options->pluck('option_id')->map(fn ($id) => (int) $id)->toArray()
            : [];

        $optionTotal = 0;
        $optionRows = [];

        foreach ($selectedOptions as $option) {
            $additionalPrice = $this->getOptionPriceForQuantity($option, $quantity);
            $priceType = $option->price_type ?? 'per_order';

            if ($option->free_from_qty && $quantity >= (int) $option->free_from_qty) {
                $additionalPrice = 0;
            }

            $additionalPrice = $this->getOptionReplacementPrice(
                $product,
                (int) $option->option_id,
                $additionalPrice,
                $quantity,
                $selectedOptionIds
            );

            $isIncludedInRule = in_array((int) $option->option_id, $ruleOptionIds, true);

            if (! $isIncludedInRule) {
                if ($priceType === 'per_item') {
                    $optionTotal += $additionalPrice * $quantity;
                } else {
                    $optionTotal += $additionalPrice;
                }
            }

            $optionRows[] = [
                'option_group_id' => $option->option_group_id,
                'option_id' => $option->option_id,
                'group_name' => $option->group->group_name ?? '',
                'option_name' => $option->option_name,
                'additional_price' => $additionalPrice,
                'price_type' => $priceType,
            ];
        }

        $productTotal = $unitPrice * $quantity;
        $itemTotal = $productTotal + $optionTotal;

        return [
            'unit_price' => $unitPrice,
            'option_total' => $optionTotal,
            'item_total' => $itemTotal,
            'matched_rule' => $matchedRule ? [
                'rule_id' => $matchedRule->rule_id,
                'rule_name' => $matchedRule->rule_name,
            ] : null,
            'selected_options' => $optionRows,
        ];
    }

    private function getOptionPriceForQuantity(ProductOption $option, int $quantity): float
    {
        $basePrice = (float) ($option->additional_price ?? 0);

        $matchedRate = $option->priceRates
            ->where('min_qty', '<=', $quantity)
            ->sortByDesc('min_qty')
            ->first();

        return $matchedRate
            ? (float) ($matchedRate->additional_price ?? 0)
            : $basePrice;
    }

    private function getOptionReplacementPrice(
        Product $product,
        int $targetOptionId,
        float $currentPrice,
        int $quantity,
        array $selectedOptionIds
    ): float {
        $matchedRule = $product->optionPriceRules
            ->filter(function ($rule) use ($targetOptionId, $selectedOptionIds) {
                if ((int) $rule->target_option_id !== $targetOptionId) {
                    return false;
                }

                $conditionOptionIds = $rule->options
                    ->pluck('option_id')
                    ->map(fn ($id) => (int) $id)
                    ->values()
                    ->toArray();

                if (empty($conditionOptionIds)) {
                    return false;
                }

                return collect($conditionOptionIds)
                    ->every(fn ($id) => in_array($id, $selectedOptionIds, true));
            })
            ->sortByDesc(fn ($rule) => $rule->options->count())
            ->first();

        if (! $matchedRule) {
            return $currentPrice;
        }

        $matchedTier = $matchedRule->tiers
            ->filter(function ($tier) use ($quantity) {
                $min = (int) $tier->min_qty;
                $max = $tier->max_qty ? (int) $tier->max_qty : null;

                return $quantity >= $min && ($max === null || $quantity <= $max);
            })
            ->sortByDesc('min_qty')
            ->first();

        if ($matchedTier) {
            return (float) ($matchedTier->additional_price ?? 0);
        }

        $highestTier = $matchedRule->tiers
            ->sortByDesc('min_qty')
            ->first();

        if ($highestTier && $quantity > (int) $highestTier->min_qty) {
            return (float) ($highestTier->additional_price ?? 0);
        }

        return $currentPrice;
    }

    public function edit(Quotation $quotation)
    {
        $language = session('admin_product_language', 'pt');

        $quotation->load([
            'items.options',
        ]);

        $products = Product::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.quotations.edit', compact(
            'quotation',
            'products',
            'language'
        ));
    }

    public function update(Request $request, Quotation $quotation)
    {
        $request->validate([
            'quotation_no' => [
                'required',
                'string',
                'max:100',
                'unique:quotations,quotation_no,'.$quotation->quotation_id.',quotation_id',
            ],
            'quotation_date' => ['required', 'date'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string'],
            'note' => ['nullable', 'string'],
            'discount_amount' => ['nullable', 'numeric', 'min:0'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,product_id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.options' => ['nullable', 'array'],
        ]);

        DB::transaction(function () use ($request, $quotation) {
            $quotation->update([
                'quotation_no' => $request->quotation_no,
                'quotation_date' => $request->quotation_date,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_address' => $request->customer_address,
                'note' => $request->note,
            ]);

            /*
            |--------------------------------------------------------------------------
            | ลบ item/options เดิม แล้วสร้างใหม่
            |--------------------------------------------------------------------------
            */
            $quotation->items()->delete();

            $subtotal = 0;

            foreach ($request->items as $itemData) {
                $product = Product::with([
                    'priceRules.options',
                    'priceRules.tiers',
                    'optionPriceRules.options',
                    'optionPriceRules.tiers',
                ])->findOrFail($itemData['product_id']);

                $quantity = (int) $itemData['quantity'];

                $selectedOptionIds = array_values($itemData['options'] ?? []);
                $selectedOptionIds = array_filter($selectedOptionIds);

                $priceData = $this->calculateProductPrice(
                    $product,
                    $quantity,
                    $selectedOptionIds
                );

                $quotationItem = QuotationItem::create([
                    'quotation_id' => $quotation->quotation_id,
                    'product_id' => $product->product_id,
                    'product_name_snapshot' => $product->product_name,
                    'product_code_snapshot' => $product->product_code,
                    'quantity' => $quantity,
                    'unit_price' => $priceData['unit_price'],
                    'option_total' => $priceData['option_total'],
                    'item_total' => $priceData['item_total'],
                    'price_rule_snapshot' => $priceData['matched_rule'],
                ]);

                foreach ($priceData['selected_options'] as $optionData) {
                    QuotationItemOption::create([
                        'quotation_item_id' => $quotationItem->quotation_item_id,
                        'option_group_id' => $optionData['option_group_id'],
                        'option_id' => $optionData['option_id'],
                        'group_name' => $optionData['group_name'],
                        'option_name' => $optionData['option_name'],
                        'additional_price' => $optionData['additional_price'],
                        'price_type' => $optionData['price_type'],
                    ]);
                }

                $subtotal += $priceData['item_total'];
            }

            $discountAmount = (float) ($request->discount_amount ?? 0);

            $afterDiscount = max($subtotal - $discountAmount, 0);

            $shippingFee = $afterDiscount >= 11000 ? 0 : 800;

            $vatAmount = ($afterDiscount + $shippingFee) * 0.10;

            $grandTotal = $afterDiscount + $shippingFee + $vatAmount;

            $quotation->update([
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'shipping_fee' => $shippingFee,
                'vat_amount' => $vatAmount,
                'grand_total' => $grandTotal,
            ]);
        });

        return redirect()
            ->route('admin.quotations.show', $quotation->quotation_id)
            ->with('success', 'Quotation updated successfully.');
    }

    public function updateStatus(Request $request, Quotation $quotation)
    {
        $request->validate([
            'status' => ['required', 'in:active,not_active'],
        ]);

        $quotation->update([
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Quotation status updated successfully.',
            'status' => $quotation->status,
        ]);
    }
}
