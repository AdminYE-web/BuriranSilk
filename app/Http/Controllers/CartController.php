<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductOptionAssignment;
use App\Support\CartPricing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart.items', []);

        foreach ($cart as $itemId => $item) {
            $cart[$itemId] = $this->syncQuantityRules($item);
        }

        $request->session()->put('cart.items', $cart);
        $items = array_values($cart);

        return view('frontend.cart.index', [
            'items' => $items,
            'summary' => CartPricing::summary($items),
        ]);
    }

    /** Show the account choice before the checkout information step. */
    public function checkout(Request $request): View|RedirectResponse
    {
        if (empty($request->session()->get('cart.items', []))) {
            return redirect()
                ->route('cart.index')
                ->with('cart_success', 'カートに商品を追加してからご注文手続きへお進みください。');
        }

        return view('frontend.checkout.index');
    }
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product' => ['required', 'string', 'max:255'],
            'cart_item_id' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'integer'],
            'previous_order_number' => ['nullable'],
            'font_entries' => ['nullable', 'array'],
            'font_entries.*' => ['nullable', 'array'],
            'font_entries.*.*.text' => ['nullable', 'string', 'max:1000'],
            'font_entries.*.*.font' => ['nullable', 'string', 'max:255'],
            'font_entries.*.*.size' => ['nullable', 'integer', 'between:1,200'],
            'artwork' => ['nullable', 'array'],
            'artwork.*' => ['nullable', 'file', 'max:51200'],
        ]);

        $product = $this->findProduct($validated['product']);
        $quantity = (int) $validated['quantity'];
        $assignments = $this->selectedAssignments($product, $validated['options'] ?? []);
        $cart = $request->session()->get('cart.items', []);
        $editingItemId = $validated['cart_item_id'] ?? null;
        $existingItem = null;

        if (filled($editingItemId)) {
            $existingItem = $cart[$editingItemId] ?? null;

            if (! $existingItem || (int) $existingItem['product_id'] !== (int) $product->product_id) {
                throw ValidationException::withMessages([
                    'cart_item_id' => '編集するカート商品が見つかりません。カートからもう一度お試しください。',
                ]);
            }
        }

        $this->validateQuantity($quantity, $assignments);
        $this->validateArtworkFiles($request);

        $itemId = $editingItemId ?: (string) Str::uuid();
        $selectedOptions = $assignments->map(function (ProductOptionAssignment $assignment) use ($quantity) {
            $option = $assignment->option;
            $additionalPrice = (float) $option->additional_price;
            $isFree = (int) $option->free_from_qty > 0
                && $quantity >= (int) $option->free_from_qty;
            $priceType = $option->price_type ?: 'per_item';
            $linePrice = $isFree
                ? 0
                : $additionalPrice * ($priceType === 'per_order' ? 1 : $quantity);

            return [
                'group_id' => (int) $option->option_group_id,
                'group_name' => $option->group?->group_name ?: '',
                'display_type' => $option->group?->display_type ?: 'button',
                'option_id' => (int) $option->option_id,
                'option_name' => $option->option_name,
                'option_detail' => $option->option_detail,
                'additional_price' => $additionalPrice,
                'price_type' => $priceType,
                'free_from_qty' => $option->free_from_qty ? (int) $option->free_from_qty : null,
                'line_price' => (int) round($linePrice),
                'quantity_rule' => [
                    'type' => $assignment->qty_rule_type,
                    'min' => $assignment->min_qty ? (int) $assignment->min_qty : null,
                    'max' => $assignment->max_qty ? (int) $assignment->max_qty : null,
                    'exact' => $assignment->exact_qty ? (int) $assignment->exact_qty : null,
                ],
            ];
        })->values()->all();

        $unitPrice = $this->getProductPrice($product);
        $newArtworks = $this->storeArtworkFiles($request, $itemId);
        $item = [
            'id' => $itemId,
            'product_id' => (int) $product->product_id,
            'product_slug' => filled($product->product_code)
                ? $product->product_code
                : (string) $product->product_id,
            'product_name' => $product->product_name,
            'image' => $this->imageAssetPath($product->mainImage?->image_path),
            'quantity' => $quantity,
            'base_unit_price' => $unitPrice,
            'selected_options' => $selectedOptions,
            'previous_order_numbers' => $this->normalizePreviousOrderNumbers(
                $validated['previous_order_number'] ?? []
            ),
            'font_entries' => $this->normalizeFontEntries($validated['font_entries'] ?? []),
            'artworks' => $this->mergeArtworkFiles(
                $existingItem['artworks'] ?? [],
                $newArtworks
            ),
            'custom_fields' => $this->normalizeCustomFields($request->except([
                '_token',
                'product',
                'cart_item_id',
                'quantity',
                'total_price',
                'options',
                'previous_order_number',
                'font_entries',
                'artwork',
            ])),
            'added_at' => $existingItem['added_at'] ?? now()->toIso8601String(),
            'updated_at' => now()->toIso8601String(),
        ];
        $item['quantity_limits'] = $this->quantityLimits($selectedOptions);
        $item = $this->recalculateItem($item, $quantity);

        $cart[$itemId] = $item;
        $request->session()->put('cart.items', $cart);

        return redirect()->route('cart.index')->with(
            'cart_success',
            $existingItem ? 'カートの商品を更新しました。' : '商品をカートに追加しました。'
        );
    }

    public function update(Request $request, string $item): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $cart = $request->session()->get('cart.items', []);

        abort_unless(isset($cart[$item]), 404);

        $quantity = (int) $validated['quantity'];
        $cart[$item] = $this->syncQuantityRules($cart[$item]);
        $this->validateStoredQuantity($quantity, $cart[$item]['selected_options'] ?? []);
        $cart[$item] = $this->recalculateItem($cart[$item], $quantity);
        $request->session()->put('cart.items', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'item' => [
                    'quantity' => $cart[$item]['quantity'],
                    'line_subtotal' => $cart[$item]['line_subtotal'],
                    'quantity_limits' => $cart[$item]['quantity_limits'],
                ],
                'summary' => CartPricing::summary(array_values($cart)),
            ]);
        }

        return redirect()->route('cart.index')->with('cart_success', '数量を更新しました。');
    }

    public function destroy(Request $request, string $item): RedirectResponse
    {
        $cart = $request->session()->get('cart.items', []);
        abort_unless(isset($cart[$item]), 404);

        foreach ($cart[$item]['artworks'] ?? [] as $artwork) {
            if (! empty($artwork['path'])) {
                Storage::disk('public')->delete($artwork['path']);
            }
        }

        unset($cart[$item]);
        $request->session()->put('cart.items', $cart);

        return redirect()->route('cart.index')->with('cart_success', '商品をカートから削除しました。');
    }

    private function findProduct(string $slug): Product
    {
        $language = request()->cookie('dev') === '1' ? 'en' : 'ja';
        $languages = $language === 'pt' ? ['pt'] : [$language, 'pt'];

        return Product::query()
            ->with([
                'mainImage',
                'displayPriceTier',
                'priceTiers',
                'optionAssignments' => fn ($query) => $query
                    ->where('is_active', 1)
                    ->orderBy('sort_order'),
                'optionAssignments.option' => fn ($query) => $query
                    ->where('is_active', 1)
                    ->with('group'),
            ])
            ->where('is_active', 1)
            ->whereIn('language', $languages)
            ->where(function (Builder $query) use ($slug) {
                $query->where('product_code', $slug);

                if (ctype_digit($slug)) {
                    $query->orWhere('product_id', (int) $slug);
                }
            })
            ->orderByRaw('CASE WHEN language = ? THEN 0 ELSE 1 END', [$language])
            ->firstOrFail();
    }

    private function selectedAssignments(Product $product, array $submittedOptions)
    {
        $submittedOptions = collect($submittedOptions)
            ->filter(fn ($optionId) => filled($optionId))
            ->mapWithKeys(fn ($optionId, $groupId) => [(int) $groupId => (int) $optionId]);
        $assignments = $product->optionAssignments
            ->filter(fn ($assignment) => $assignment->option?->group?->is_active)
            ->keyBy(fn ($assignment) => (int) $assignment->option_id);

        return $submittedOptions->map(function ($optionId, $groupId) use ($assignments) {
            $assignment = $assignments->get($optionId);

            if (! $assignment || (int) $assignment->option->option_group_id !== $groupId) {
                throw ValidationException::withMessages([
                    'options' => '選択されたオプションが無効です。もう一度選択してください。',
                ]);
            }

            return $assignment;
        })->values();
    }

    private function validateQuantity(int $quantity, $assignments): void
    {
        $this->validateStoredQuantity($quantity, $assignments->map(fn ($assignment) => [
            'quantity_rule' => [
                'type' => $assignment->qty_rule_type,
                'min' => $assignment->min_qty,
                'max' => $assignment->max_qty,
                'exact' => $assignment->exact_qty,
            ],
        ])->all());
    }

    private function validateStoredQuantity(int $quantity, array $selectedOptions): void
    {
        $limits = $this->quantityLimits($selectedOptions);

        if ($limits['conflict']
            || ($limits['min'] !== null && $quantity < $limits['min'])
            || ($limits['max'] !== null && $quantity > $limits['max'])) {
            throw ValidationException::withMessages([
                'quantity' => '選択したオプションで指定された数量条件を確認してください。',
            ]);
        }
    }

    private function syncQuantityRules(array $item): array
    {
        $optionIds = collect($item['selected_options'] ?? [])
            ->pluck('option_id')
            ->filter()
            ->map(fn ($optionId) => (int) $optionId)
            ->values();

        if ($optionIds->isEmpty()) {
            $item['quantity_limits'] = $this->quantityLimits($item['selected_options'] ?? []);

            return $item;
        }

        $assignments = ProductOptionAssignment::query()
            ->where('product_id', (int) $item['product_id'])
            ->where('is_active', 1)
            ->whereIn('option_id', $optionIds)
            ->get()
            ->keyBy(fn ($assignment) => (int) $assignment->option_id);

        foreach ($item['selected_options'] as &$option) {
            $assignment = $assignments->get((int) ($option['option_id'] ?? 0));
            $option['quantity_rule'] = [
                'type' => $assignment?->qty_rule_type,
                'min' => $assignment?->min_qty ? (int) $assignment->min_qty : null,
                'max' => $assignment?->max_qty ? (int) $assignment->max_qty : null,
                'exact' => $assignment?->exact_qty ? (int) $assignment->exact_qty : null,
            ];
        }
        unset($option);

        $item['quantity_limits'] = $this->quantityLimits($item['selected_options']);

        return $item;
    }

    private function quantityLimits(array $selectedOptions): array
    {
        $minimums = [];
        $maximums = [];
        $hasRule = false;

        foreach ($selectedOptions as $option) {
            $rule = $option['quantity_rule'] ?? [];
            $type = $rule['type'] ?? null;

            if ($type === 'exact' && (int) ($rule['exact'] ?? 0) > 0) {
                $minimums[] = (int) $rule['exact'];
                $maximums[] = (int) $rule['exact'];
                $hasRule = true;
            }

            if (in_array($type, ['min', 'range'], true) && (int) ($rule['min'] ?? 0) > 0) {
                $minimums[] = (int) $rule['min'];
                $hasRule = true;
            }

            if (in_array($type, ['max', 'range'], true) && (int) ($rule['max'] ?? 0) > 0) {
                $maximums[] = (int) $rule['max'];
                $hasRule = true;
            }
        }

        $minimum = $minimums ? max($minimums) : null;
        $maximum = $maximums ? min($maximums) : null;
        $conflict = $minimum !== null && $maximum !== null && $minimum > $maximum;

        if ($conflict) {
            $note = '選択したオプションの数量条件が一致しません。';
        } elseif ($minimum !== null && $maximum !== null && $minimum === $maximum) {
            $note = '数量は'.$minimum.'個に指定されています。';
        } elseif ($minimum !== null && $maximum !== null) {
            $note = 'ご注文数量は'.$minimum.'〜'.$maximum.'個です。';
        } elseif ($minimum !== null) {
            $note = 'ご注文数量は'.$minimum.'個以上です。';
        } elseif ($maximum !== null) {
            $note = 'ご注文数量は'.$maximum.'個以下です。';
        } else {
            $note = null;
        }

        return [
            'has_rule' => $hasRule,
            'min' => $minimum,
            'max' => $maximum,
            'conflict' => $conflict,
            'note' => $note,
        ];
    }

    private function validateArtworkFiles(Request $request): void
    {
        $allowedExtensions = ['ai', 'pdf', 'eps', 'psd', 'png', 'jpg', 'jpeg'];

        foreach ((array) $request->file('artwork', []) as $file) {
            if ($file && ! in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions, true)) {
                throw ValidationException::withMessages([
                    'artwork' => '入稿データのファイル形式が正しくありません。',
                ]);
            }
        }
    }

    private function storeArtworkFiles(Request $request, string $itemId): array
    {
        $artworks = [];

        foreach ((array) $request->file('artwork', []) as $groupId => $file) {
            if (! $file) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $filename = Str::uuid().($extension ? '.'.$extension : '');
            $path = $file->storeAs('cart-artwork/'.$itemId, $filename, 'public');
            $artworks[] = [
                'group_id' => (int) $groupId,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ];
        }

        return $artworks;
    }

    private function mergeArtworkFiles(array $existingArtworks, array $newArtworks): array
    {
        if ($newArtworks === []) {
            return $existingArtworks;
        }

        $replacedGroupIds = collect($newArtworks)
            ->pluck('group_id')
            ->map(fn ($groupId) => (int) $groupId);
        $remainingArtworks = collect($existingArtworks)
            ->reject(function ($artwork) use ($replacedGroupIds) {
                $isReplaced = $replacedGroupIds->contains((int) ($artwork['group_id'] ?? 0));

                if ($isReplaced && ! empty($artwork['path'])) {
                    Storage::disk('public')->delete($artwork['path']);
                }

                return $isReplaced;
            })
            ->values()
            ->all();

        return [...$remainingArtworks, ...$newArtworks];
    }

    private function normalizePreviousOrderNumbers(mixed $values): array
    {
        if (! is_array($values)) {
            $values = ['legacy' => $values];
        }

        return collect($values)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->mapWithKeys(fn ($value, $groupId) => [(string) $groupId => $value])
            ->all();
    }

    private function normalizeFontEntries(array $groups): array
    {
        return collect($groups)->map(function ($entries) {
            return collect((array) $entries)->map(fn ($entry) => [
                'text' => trim((string) ($entry['text'] ?? '')),
                'font' => trim((string) ($entry['font'] ?? '')),
                'size' => max(1, min(200, (int) ($entry['size'] ?? 12))),
            ])->values()->all();
        })->all();
    }

    private function normalizeCustomFields(array $fields): array
    {
        return collect($fields)->map(function ($value) {
            if (is_array($value)) {
                return $this->normalizeCustomFields($value);
            }

            return is_scalar($value) ? trim((string) $value) : null;
        })->filter(fn ($value) => $value !== null && $value !== '')->all();
    }

    private function recalculateItem(array $item, int $quantity): array
    {
        $item['quantity'] = $quantity;
        $item['base_subtotal'] = (int) round((float) $item['base_unit_price'] * $quantity);
        $optionSubtotal = 0;

        foreach ($item['selected_options'] as &$option) {
            $isFree = (int) ($option['free_from_qty'] ?? 0) > 0
                && $quantity >= (int) $option['free_from_qty'];
            $option['line_price'] = $isFree
                ? 0
                : (int) round((float) $option['additional_price'] * (
                    ($option['price_type'] ?? 'per_item') === 'per_order' ? 1 : $quantity
                ));
            $optionSubtotal += $option['line_price'];
        }
        unset($option);

        $item['option_subtotal'] = $optionSubtotal;
        $item['line_subtotal'] = $item['base_subtotal'] + $optionSubtotal;

        return $item;
    }

    private function getProductPrice(Product $product): float
    {
        if ($product->displayPriceTier) {
            return (float) ($product->displayPriceTier->unit_price ?? 0);
        }

        return (float) ($product->priceTiers->first()?->unit_price ?? 0);
    }

    private function imageAssetPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');

        if (Str::startsWith($normalizedPath, ['http://', 'https://', 'storage/', 'assets/'])) {
            return $normalizedPath;
        }

        return 'storage/'.ltrim(Str::after($normalizedPath, 'public/'), '/');
    }
}
