<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductOptionPriceRate;
use App\Http\Controllers\Controller;
use App\Models\OptionGroup;
use App\Models\OptionImage;
use App\Models\ProductOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductOptionController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $language = session('admin_product_language', 'pt');
        $baseLanguage = 'pt';

        if ($language === $baseLanguage) {
            $options = ProductOption::with([
                'group',
                'mainImage',
            ])
                ->where('language', $baseLanguage)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('option_name', 'like', '%'.$search.'%')
                            ->orWhere('option_code', 'like', '%'.$search.'%')
                            ->orWhereHas('group', function ($groupQuery) use ($search) {
                                $groupQuery->where('group_name', 'like', '%'.$search.'%')
                                    ->orWhere('group_code', 'like', '%'.$search.'%');
                            });
                    });
                })
                ->orderBy('option_id', 'desc')
                ->paginate(15)
                ->withQueryString();

            return view('admin.product_options.index', compact('options', 'search', 'language'));
        }

        $baseOptions = ProductOption::with([
            'group',
            'mainImage',
        ])
            ->where('language', $baseLanguage)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('option_name', 'like', '%'.$search.'%')
                        ->orWhere('option_code', 'like', '%'.$search.'%')
                        ->orWhereHas('group', function ($groupQuery) use ($search) {
                            $groupQuery->where('group_name', 'like', '%'.$search.'%')
                                ->orWhere('group_code', 'like', '%'.$search.'%');
                        });
                });
            })
            ->orderBy('option_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $translationKeys = $baseOptions
            ->getCollection()
            ->pluck('translation_key')
            ->filter()
            ->values();

        $translatedOptions = ProductOption::with([
            'group',
            'mainImage',
        ])
            ->where('language', $language)
            ->whereIn('translation_key', $translationKeys)
            ->get()
            ->keyBy('translation_key');

        $baseOptions->getCollection()->transform(function ($baseOption) use ($translatedOptions) {
            $translatedOption = $translatedOptions->get($baseOption->translation_key);

            if ($translatedOption) {
                $translatedOption->is_missing_translation = false;
                $translatedOption->base_option_id = $baseOption->option_id;

                return $translatedOption;
            }

            $baseOption->is_missing_translation = true;
            $baseOption->base_option_id = $baseOption->option_id;

            return $baseOption;
        });

        $options = $baseOptions;

        return view('admin.product_options.index', compact('options', 'search', 'language'));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');

        $groups = OptionGroup::where('is_active', 1)
            ->where('language', $language)
            ->orderBy('group_name')
            ->get();

        $translationKey = 'opt_'.strtolower(Str::random(12));

        return view('admin.product_options.create', compact(
            'groups',
            'language',
            'translationKey'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'option_group_id' => 'required|exists:option_groups,option_group_id',
            'option_code' => 'nullable|string|max:100',
            'option_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:20',
            'additional_price' => 'required|numeric|min:0',
            'price_type' => 'required|in:per_item,per_order',
            'option_detail' => 'nullable|string',
            'free_from_qty' => 'nullable|integer|min:1',
            'translation_key' => 'nullable|string|max:255',
            'additional_price_with_tax' => 'nullable|numeric|min:0',
            'price_rates' => 'nullable|array',
'price_rates.*.min_qty' => 'nullable|integer|min:1',
'price_rates.*.additional_price' => 'nullable|numeric|min:0',
'price_rates.*.additional_price_with_tax' => 'nullable|numeric|min:0',
'price_mode' => 'nullable|in:normal,rate',

        ]);

        $option = ProductOption::create([
            'option_group_id' => $request->option_group_id,
            'option_code' => $request->option_code,
            'option_name' => $request->option_name,
            'color_code' => $request->color_code,
            'additional_price' => $request->additional_price,
            'price_type' => $request->price_type,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'option_detail' => $request->option_detail,
            'language' => session('admin_product_language', 'pt'),
            'free_from_qty' => $request->free_from_qty,
            'translation_key' => $request->translation_key ?: 'opt_'.strtolower(Str::random(12)),
            'additional_price_with_tax' => $request->additional_price_with_tax,

        ]);
        if ($request->input('price_mode') === 'rate') {
    $this->syncPriceRates($option, $request);
}
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('options', 'public');

                OptionImage::create([
                    'option_id' => $option->option_id,
                    'image_path' => $path,
                    'image_alt' => $option->option_name,
                    'is_main' => $index === 0 ? 1 : 0,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('admin.product-options.index')
            ->with('success', 'เพิ่มตัวเลือกสินค้าเรียบร้อยแล้ว');
    }

    public function edit(ProductOption $productOption)
    {
       $productOption->load(['images', 'priceRates']);

        $language = $productOption->language ?? session('admin_product_language', 'pt');

        $groups = OptionGroup::where('is_active', 1)
            ->where('language', $language)
            ->orderBy('group_name')
            ->get();

        return view('admin.product_options.edit', [
            'option' => $productOption,
            'groups' => $groups,
            'language' => $language,
        ]);
    }

    public function update(Request $request, ProductOption $productOption)
    {
        $option = $productOption;
        $request->validate([
            'option_group_id' => 'required|exists:option_groups,option_group_id',
            'option_code' => 'nullable|string|max:100',
            'option_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:20',
            'additional_price' => 'required|numeric|min:0',
            'price_type' => 'required|in:per_item,per_order',
            'option_detail' => 'nullable|string',
            'free_from_qty' => 'nullable|integer|min:1',
            'translation_key' => 'nullable|string|max:255',
            'additional_price_with_tax' => 'nullable|numeric|min:0',
            'price_rates' => 'nullable|array',
'price_rates.*.min_qty' => 'nullable|integer|min:1',
'price_rates.*.additional_price' => 'nullable|numeric|min:0',
'price_rates.*.additional_price_with_tax' => 'nullable|numeric|min:0',
'price_mode' => 'nullable|in:normal,rate',

        ]);
        if ($request->has('delete_images')) {
            $deleteImageIds = $request->input('delete_images', []);

            $images = OptionImage::whereIn('image_id', $deleteImageIds)
                ->where('option_id', $option->option_id)
                ->get();

            foreach ($images as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }

                $image->delete();
            }
        }

        $productOption->update([
            'option_group_id' => $request->option_group_id,
            'option_code' => $request->option_code,
            'option_name' => $request->option_name,
            'color_code' => $request->color_code,
            'additional_price' => $request->additional_price,
            'price_type' => $request->price_type,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'option_detail' => $request->option_detail,
            'free_from_qty' => $request->free_from_qty,
            'translation_key' => $request->translation_key ?: $productOption->translation_key ?: 'opt_'.strtolower(Str::random(12)),
            'additional_price_with_tax' => $request->additional_price_with_tax,
        ]);
        if ($request->input('price_mode') === 'rate') {
    $this->syncPriceRates($productOption, $request);
} else {
    $productOption->priceRates()->delete();
}
        if ($request->hasFile('images')) {
            $currentMaxSort = $productOption->images()->max('sort_order') ?? 0;
            $hasMainImage = $productOption->images()->where('is_main', 1)->exists();

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('options', 'public');

                OptionImage::create([
                    'option_id' => $productOption->option_id,
                    'image_path' => $path,
                    'image_alt' => $productOption->option_name,
                    'is_main' => ! $hasMainImage && $index === 0 ? 1 : 0,
                    'sort_order' => $currentMaxSort + $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('admin.product-options.index')
            ->with('success', 'แก้ไขตัวเลือกสินค้าเรียบร้อยแล้ว');
    }

    public function duplicateTranslation(ProductOption $productOption)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === 'pt') {
            return redirect()
                ->route('admin.product-options.index')
                ->with('success', 'You are already in PT language.');
        }

        if ($productOption->language !== 'pt') {
            return redirect()
                ->route('admin.product-options.index')
                ->with('success', 'Only PT product option can be duplicated as translation.');
        }

        $translationKey = $productOption->translation_key ?: $productOption->option_code ?: 'opt_'.strtolower(Str::random(12));

        $existing = ProductOption::where('translation_key', $translationKey)
            ->where('language', $targetLanguage)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.product-options.edit', $existing->option_id)
                ->with('success', 'Translation already exists.');
        }

        if (! $productOption->translation_key) {
            $productOption->update([
                'translation_key' => $translationKey,
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Map option group translation
    |--------------------------------------------------------------------------
    | ถ้า group ของ PT มีตัวแปลในภาษาปัจจุบันแล้ว ให้ผูกกับ group ภาษานั้น
    | ถ้ายังไม่มี ให้ใช้ group เดิมไว้ก่อนหรือจะให้ null ก็ได้
    |--------------------------------------------------------------------------
    */
        $targetGroupId = $productOption->option_group_id;

        if ($productOption->group) {
            $groupTranslationKey = $productOption->group->translation_key ?: $productOption->group->group_code;

            $translatedGroup = OptionGroup::where('translation_key', $groupTranslationKey)
                ->where('language', $targetLanguage)
                ->first();

            if ($translatedGroup) {
                $targetGroupId = $translatedGroup->option_group_id;
            }
        }

        $newOption = $productOption->replicate();

        $newOption->option_group_id = $targetGroupId;
        $newOption->language = $targetLanguage;
        $newOption->translation_key = $translationKey;
        $newOption->option_code = $productOption->option_code
            ? $productOption->option_code.'-'.$targetLanguage
            : null;
        $newOption->option_name = $productOption->option_name.' ('.strtoupper($targetLanguage).')';
        $newOption->is_active = 0;
        $newOption->created_at = now();
        $newOption->updated_at = now();
        $newOption->save();

        $productOption->load('images');

        foreach ($productOption->images as $image) {
            OptionImage::create([
                'option_id' => $newOption->option_id,
                'image_path' => $image->image_path,
                'image_alt' => $newOption->option_name,
                'is_main' => $image->is_main,
                'sort_order' => $image->sort_order,
            ]);
        }

        return redirect()
            ->route('admin.product-options.edit', $newOption->option_id)
            ->with('success', 'Product option duplicated for '.strtoupper($targetLanguage).'. Please update the translated content.');
    }

    public function destroy(ProductOption $productOption)
    {
        $productOption->delete();

        return redirect()
            ->route('admin.product-options.index')
            ->with('success', 'ลบตัวเลือกสินค้าเรียบร้อยแล้ว');
    }
    private function syncPriceRates(ProductOption $option, Request $request): void
{
    $option->priceRates()->delete();

    $rates = $request->input('price_rates', []);

    foreach ($rates as $rate) {
        if (empty($rate['min_qty'])) {
            continue;
        }

        $price = $rate['additional_price'] ?? 0;
        $priceWithTax = $rate['additional_price_with_tax'] ?? null;

        ProductOptionPriceRate::create([
            'option_id' => $option->option_id,
            'min_qty' => $rate['min_qty'],
            'additional_price' => $price,
            'additional_price_with_tax' => $priceWithTax !== null
                ? $priceWithTax
                : round($price * 1.1, 2),
        ]);
    }
}
}
