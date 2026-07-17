<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Material;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $language = session('admin_product_language', 'pt');

        $type = $request->input('type');
        $categoryId = $request->input('category_id');
        $materialId = $request->input('material_id');
        $status = $request->input('status');

        $productsQuery = Product::with(['category', 'material', 'mainImage'])
            ->where(function ($query) use ($language) {
                $query->where('language', $language)
                    ->orWhere(function ($q) use ($language) {
                        $q->where('language', '!=', $language)
                            ->whereNotExists(function ($subQuery) use ($language) {
                                $subQuery->selectRaw(1)
                                    ->from('products as p2')
                                    ->whereColumn('p2.translation_key', 'products.translation_key')
                                    ->where('p2.language', $language);
                            });
                    });
            })
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('product_name', 'like', '%'.$search.'%')
                        ->orWhere('product_code', 'like', '%'.$search.'%');
                });
            })
            ->when($type !== null && $type !== '', function ($query) use ($type) {
                $query->where('product_type', $type);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($materialId, function ($query) use ($materialId) {
                $query->where('material_id', $materialId);
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                $query->where('is_active', $status);
            })
            ->orderBy('product_id', 'desc');

        $products = $productsQuery
            ->paginate(15)
            ->withQueryString();

        $translationKeys = $products->pluck('translation_key')->filter()->unique();

        $allLanguages = Product::whereIn('translation_key', $translationKeys)
            ->select('translation_key', 'language')
            ->get()
            ->groupBy('translation_key')
            ->map(function ($items) {
                return $items->pluck('language')
                    ->map(function ($lang) {
                        return $lang === 'ja' ? 'jp' : $lang;
                    })
                    ->unique()
                    ->sortBy(function ($lang) {
                        $order = ['pt' => 1, 'jp' => 2, 'en' => 3];

                        return $order[$lang] ?? 99;
                    })
                    ->implode(' ');
            });

        $products->getCollection()->transform(function ($product) use ($language, $allLanguages) {
            $product->is_missing_translation = $product->language !== $language;

            $product->all_languages = $product->translation_key && isset($allLanguages[$product->translation_key])
                ? $allLanguages[$product->translation_key]
                : ($product->language === 'ja' ? 'jp' : $product->language);

            return $product;
        });

        $categories = Category::where('is_active', 1)
            ->where('language', $language)
            ->orderBy('category_name')
            ->get();

        $materials = Material::where('is_active', 1)
            ->where('language', $language)
            ->orderBy('material_name')
            ->get();

        return view('admin.products.index', compact(
            'products',
            'search',
            'language',
            'categories',
            'materials',
            'type',
            'categoryId',
            'materialId',
            'status'
        ));
    }

    public function create(Request $request)
    {
        $language = session('admin_product_language', 'pt');

        $productType = (int) $request->input('product_type', 1);

        if (! in_array($productType, [1, 2], true)) {
            $productType = 1;
        }

        $categories = Category::where('is_active', 1)
            ->where('language', $language)
            ->orderBy('category_name')
            ->get();

        $materials = Material::where('is_active', 1)
            ->where('language', $language)
            ->orderBy('material_name')
            ->get();

        $translationKey = 'product_'.strtolower(Str::random(12));

        return view('admin.products.create', compact(
            'categories',
            'materials',
            'language',
            'translationKey',
            'productType'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_code' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,category_id',
            'material_id' => 'nullable|exists:materials,material_id',
            'product_type' => 'required|integer|in:1,2',
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_antivirus_included' => 'nullable|boolean',
            'is_active' => 'required|integer|in:1,3',
            'product_recomend' => 'nullable|boolean',
            'product_recomend_menu' => 'nullable|boolean',
            'product_premium' => 'nullable|boolean',

            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'can_upload_artwork' => 'nullable|boolean',
            'artwork_required' => 'nullable|boolean',
            'allow_no_artwork' => 'nullable|boolean',
            'allow_text_print' => 'nullable|boolean',
            'allow_font_select' => 'nullable|boolean',
            'allow_template_select' => 'nullable|boolean',
            'translation_key' => 'nullable|string|max:255',

        ]);
        $language = session('admin_product_language', 'pt');

        $product = Product::create([
            'product_code' => $request->product_code,
            'category_id' => $request->category_id,
            'material_id' => $request->material_id,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'language' => $language,
            'is_antivirus_included' => $request->has('is_antivirus_included') ? 1 : 0,
            'is_active' => (int) $request->is_active,
            'product_recomend' => $request->has('product_recomend') ? 1 : 0,
            'product_recomend_menu' => $request->has('product_recomend_menu') ? 1 : 0,
            'product_premium' => $request->has('product_premium') ? 1 : 0,
            'product_type' => $request->product_type,
            'can_upload_artwork' => $request->has('can_upload_artwork') ? 1 : 0,
            'artwork_required' => $request->has('artwork_required') ? 1 : 0,
            'allow_no_artwork' => $request->has('allow_no_artwork') ? 1 : 0,
            'allow_text_print' => $request->has('allow_text_print') ? 1 : 0,
            'allow_font_select' => $request->has('allow_font_select') ? 1 : 0,
            'allow_template_select' => $request->has('allow_template_select') ? 1 : 0,
            'translation_key' => $request->translation_key ?: 'product_'.strtolower(Str::random(12)),
        ]);

        // Main images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_path' => $path,
                    'image_alt' => $product->product_name,
                    'image_type' => 'main',
                    'is_main' => $index === 0 ? 1 : 0,
                    'sort_order' => $index + 1,
                ]);
            }
        }

        // Gallery images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('products/gallery', 'public');

                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_path' => $path,
                    'image_alt' => $product->product_name,
                    'image_type' => 'gallery',
                    'is_main' => 0,
                    'sort_order' => $index + 1,
                ]);
            }
        }
        Cache::forget('home_page_data_'.$language);
        Cache::forget('home_page_data');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'galleryImages']);

        $language = $product->language ?? session('admin_product_language', 'pt');

        $categories = Category::where('is_active', 1)
            ->where('language', $language)
            ->orderBy('category_name')
            ->get();

        $materials = Material::where('is_active', 1)
            ->where('language', $language)
            ->orderBy('material_name')
            ->get();

        return view('admin.products.edit', compact(
            'product',
            'categories',
            'materials',
            'language'
        ));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_code' => 'nullable|string|max:100',
            'category_id' => 'nullable|exists:categories,category_id',
            'material_id' => 'nullable|exists:materials,material_id',
            'main_image_id' => 'nullable|exists:product_images,image_id',
            'product_type' => 'required|integer|in:1,2',
            'product_name' => 'required|string|max:255',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
            'is_antivirus_included' => 'nullable|boolean',
            'is_active' => 'required|integer|in:1,3',
            'product_recomend' => 'nullable|boolean',
            'product_recomend_menu' => 'nullable|boolean',
            'product_premium' => 'nullable|boolean',

            'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'can_upload_artwork' => 'nullable|boolean',
            'artwork_required' => 'nullable|boolean',
            'allow_no_artwork' => 'nullable|boolean',
            'allow_text_print' => 'nullable|boolean',
            'allow_font_select' => 'nullable|boolean',
            'allow_template_select' => 'nullable|boolean',
            'delete_gallery_images' => 'nullable|array',
            'delete_gallery_images.*' => 'integer',
            'translation_key' => 'nullable|string|max:255',

        ]);

        $product->update([
            'product_code' => $request->product_code,
            'category_id' => $request->category_id,
            'material_id' => $request->material_id,
            'language' => session('admin_product_language', $product->language ?? 'pt'),
            'product_type' => $request->product_type,
            'product_name' => $request->product_name,
            'description' => $request->description,
            'is_antivirus_included' => $request->has('is_antivirus_included') ? 1 : 0,
            'is_active' => (int) $request->is_active,
            'product_recomend' => $request->has('product_recomend') ? 1 : 0,
            'product_recomend_menu' => $request->has('product_recomend_menu') ? 1 : 0,
            'product_premium' => $request->has('product_premium') ? 1 : 0,
            'can_upload_artwork' => $request->has('can_upload_artwork') ? 1 : 0,
            'artwork_required' => $request->has('artwork_required') ? 1 : 0,
            'allow_no_artwork' => $request->has('allow_no_artwork') ? 1 : 0,
            'allow_text_print' => $request->has('allow_text_print') ? 1 : 0,
            'allow_font_select' => $request->has('allow_font_select') ? 1 : 0,
            'allow_template_select' => $request->has('allow_template_select') ? 1 : 0,
            'translation_key' => $request->translation_key ?: $product->translation_key ?: 'product_'.strtolower(Str::random(12)),
        ]);
        if ($request->filled('delete_gallery_images')) {
            $galleryImages = ProductImage::where('product_id', $product->product_id)
                ->where('image_type', 'gallery')
                ->whereIn('image_id', $request->delete_gallery_images)
                ->get();

            foreach ($galleryImages as $galleryImage) {
                if ($galleryImage->image_path && Storage::disk('public')->exists($galleryImage->image_path)) {
                    Storage::disk('public')->delete($galleryImage->image_path);
                }

                $galleryImage->delete();
            }
        }
        if ($request->filled('main_image_id')) {
            $product->images()->update([
                'is_main' => 0,
            ]);

            $product->images()
                ->where('image_id', $request->main_image_id)
                ->update([
                    'is_main' => 1,
                    'image_type' => 'main',
                ]);
        }

        if ($request->hasFile('images')) {
            $currentMaxSort = $product->images()->max('sort_order') ?? 0;
            $hasMainImage = $product->images()->where('is_main', 1)->exists();

            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_path' => $path,
                    'image_type' => 'main',
                    'image_alt' => $product->product_name,
                    'is_main' => ! $hasMainImage && $index === 0 ? 1 : 0,
                    'sort_order' => $currentMaxSort + $index + 1,
                ]);
            }
        }
        if ($request->hasFile('gallery_images')) {
            $currentMaxSort = $product->galleryImages()->max('sort_order') ?? 0;

            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('products/gallery', 'public');

                ProductImage::create([
                    'product_id' => $product->product_id,
                    'image_path' => $path,
                    'image_alt' => $product->product_name,
                    'image_type' => 'gallery',
                    'is_main' => 0,
                    'sort_order' => $currentMaxSort + $index + 1,
                ]);
            }
        }
        $language = $product->language;
        Cache::forget('home_page_data_'.$language);
        Cache::forget('home_page_data');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->update([
            'is_active' => 0,
        ]);

        $language = $product->language;

        Cache::forget('home_page_data_'.$language);
        Cache::forget('home_page_data');

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product has been moved to inactive.');
    }

    public function duplicate(Product $product)
    {
        $newProduct = DB::transaction(function () use ($product) {
            $newProduct = $product->replicate();
            $newProduct->product_code = $this->uniqueProductCode($product->product_code);
            $newProduct->translation_key = 'product_'.strtolower(Str::random(12));
            $newProduct->product_name = $this->uniqueProductName($product->product_name);
            $newProduct->is_active = 3;
            $newProduct->created_at = now();
            $newProduct->updated_at = now();
            $newProduct->save();

            $this->duplicateRowsForProduct('product_images', 'image_id', $product->product_id, $newProduct->product_id);
            $this->duplicateRowsForProduct('product_details', 'product_detail_id', $product->product_id, $newProduct->product_id);
            $this->duplicateRowsForProduct('product_option_assignments', 'assignment_id', $product->product_id, $newProduct->product_id);
            $this->duplicateRowsForProduct('product_option_group_orders', 'id', $product->product_id, $newProduct->product_id);
            $this->duplicateRowsForProduct('product_price_tiers', 'tier_id', $product->product_id, $newProduct->product_id);
            $this->duplicateRowsForProduct('product_artwork_templates', 'template_id', $product->product_id, $newProduct->product_id);
            $this->duplicateRowsForProduct('product_templates', 'template_id', $product->product_id, $newProduct->product_id);
            $this->duplicateRowsForProduct('faqs', 'faq_id', $product->product_id, $newProduct->product_id);
            $this->duplicateProductPriceRules($product->product_id, $newProduct->product_id);
            $this->duplicateProductOptionPriceRules($product->product_id, $newProduct->product_id);

            return $newProduct;
        });

        Cache::forget('home_page_data_'.$product->language);
        Cache::forget('home_page_data_'.$newProduct->language);
        Cache::forget('home_page_data');

        return redirect()
            ->route('admin.products.edit', $newProduct->product_id)
            ->with('success', 'Product duplicated successfully. Please review and publish it when ready.');
    }

    private function uniqueProductCode(?string $sourceCode): string
    {
        $base = trim((string) $sourceCode) !== ''
            ? trim((string) $sourceCode).'-copy'
            : 'product-copy';

        $candidate = $base;
        $index = 2;

        while (Product::where('product_code', $candidate)->exists()) {
            $candidate = $base.'-'.$index;
            $index++;
        }

        return $candidate;
    }

    private function uniqueProductName(string $sourceName): string
    {
        $base = $sourceName.' - Copy';
        $candidate = $base;
        $index = 2;

        while (Product::where('product_name', $candidate)->exists()) {
            $candidate = $base.' '.$index;
            $index++;
        }

        return $candidate;
    }

    private function duplicateRowsForProduct(string $table, string $primaryKey, int $sourceProductId, int $newProductId): void
    {
        DB::table($table)
            ->where('product_id', $sourceProductId)
            ->orderBy($primaryKey)
            ->get()
            ->each(function ($row) use ($table, $primaryKey, $newProductId) {
                $attributes = (array) $row;
                unset($attributes[$primaryKey]);

                $attributes['product_id'] = $newProductId;

                if (array_key_exists('created_at', $attributes)) {
                    $attributes['created_at'] = now();
                }

                if (array_key_exists('updated_at', $attributes)) {
                    $attributes['updated_at'] = now();
                }

                DB::table($table)->insert($attributes);
            });
    }

    private function duplicateProductPriceRules(int $sourceProductId, int $newProductId): void
    {
        DB::table('product_price_rules')
            ->where('product_id', $sourceProductId)
            ->orderBy('rule_id')
            ->get()
            ->each(function ($rule) use ($newProductId) {
                $attributes = (array) $rule;
                $sourceRuleId = $attributes['rule_id'];
                unset($attributes['rule_id']);

                $attributes['product_id'] = $newProductId;
                $attributes['created_at'] = now();
                $attributes['updated_at'] = now();

                $newRuleId = DB::table('product_price_rules')->insertGetId($attributes);

                DB::table('product_price_rule_options')
                    ->where('rule_id', $sourceRuleId)
                    ->orderBy('rule_option_id')
                    ->get()
                    ->each(function ($ruleOption) use ($newRuleId) {
                        $optionAttributes = (array) $ruleOption;
                        unset($optionAttributes['rule_option_id']);

                        $optionAttributes['rule_id'] = $newRuleId;
                        $optionAttributes['created_at'] = now();
                        $optionAttributes['updated_at'] = now();

                        DB::table('product_price_rule_options')->insert($optionAttributes);
                    });

                DB::table('product_price_rule_tiers')
                    ->where('rule_id', $sourceRuleId)
                    ->orderBy('tier_id')
                    ->get()
                    ->each(function ($tier) use ($newRuleId) {
                        $tierAttributes = (array) $tier;
                        unset($tierAttributes['tier_id']);

                        $tierAttributes['rule_id'] = $newRuleId;
                        $tierAttributes['created_at'] = now();
                        $tierAttributes['updated_at'] = now();

                        DB::table('product_price_rule_tiers')->insert($tierAttributes);
                    });
            });
    }

    private function duplicateProductOptionPriceRules(int $sourceProductId, int $newProductId): void
    {
        DB::table('product_option_price_rules')
            ->where('product_id', $sourceProductId)
            ->orderBy('option_price_rule_id')
            ->get()
            ->each(function ($rule) use ($newProductId) {
                $attributes = (array) $rule;
                $sourceRuleId = $attributes['option_price_rule_id'];
                unset($attributes['option_price_rule_id']);

                $attributes['product_id'] = $newProductId;
                $attributes['created_at'] = now();
                $attributes['updated_at'] = now();

                $newRuleId = DB::table('product_option_price_rules')->insertGetId($attributes);

                DB::table('product_option_price_rule_options')
                    ->where('option_price_rule_id', $sourceRuleId)
                    ->orderBy('id')
                    ->get()
                    ->each(function ($ruleOption) use ($newRuleId) {
                        $optionAttributes = (array) $ruleOption;
                        unset($optionAttributes['id']);

                        $optionAttributes['option_price_rule_id'] = $newRuleId;

                        DB::table('product_option_price_rule_options')->insert($optionAttributes);
                    });

                DB::table('product_option_price_rule_tiers')
                    ->where('option_price_rule_id', $sourceRuleId)
                    ->orderBy('option_price_rule_tier_id')
                    ->get()
                    ->each(function ($tier) use ($newRuleId) {
                        $tierAttributes = (array) $tier;
                        unset($tierAttributes['option_price_rule_tier_id']);

                        $tierAttributes['option_price_rule_id'] = $newRuleId;
                        $tierAttributes['created_at'] = now();
                        $tierAttributes['updated_at'] = now();

                        DB::table('product_option_price_rule_tiers')->insert($tierAttributes);
                    });
            });
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'product_id')
            ->where('is_main', 1);
    }

    public function duplicateTranslation(Product $product)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === $product->language) {
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product is already in the selected language.');
        }

        $translationKey = $product->translation_key ?: $product->product_code;

        $existing = Product::where('translation_key', $translationKey)
            ->where('language', $targetLanguage)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.products.edit', $existing->product_id)
                ->with('success', 'Translation already exists.');
        }

        $newProduct = $product->replicate();

        $newProduct->language = $targetLanguage;
        $newProduct->translation_key = $translationKey;

        $cleanCode = preg_replace('/-(pt|ja|en)$/i', '', $product->product_code);
        $newProduct->product_code = $cleanCode.'-'.$targetLanguage;

        $cleanName = preg_replace('/\s*\((PT|JA|EN)\)$/i', '', $product->product_name);
        $newProduct->product_name = $cleanName.' ('.strtoupper($targetLanguage).')';

        $newProduct->is_active = 3;
        $newProduct->product_recomend = 0;
        $newProduct->product_premium = 0;
        $newProduct->created_at = now();
        $newProduct->updated_at = now();
        $newProduct->save();

        /*
    |--------------------------------------------------------------------------
    | Copy images
    |--------------------------------------------------------------------------
    | ใช้รูปเดิม path เดิม ไม่ duplicate file จริง
    |--------------------------------------------------------------------------
    */
        foreach ($product->images as $image) {
            ProductImage::create([
                'product_id' => $newProduct->product_id,
                'image_path' => $image->image_path,
                'image_alt' => $newProduct->product_name,
                'image_type' => $image->image_type,
                'is_main' => $image->is_main,
                'sort_order' => $image->sort_order,
            ]);
        }

        Cache::forget('home_page_data_'.$targetLanguage);
        Cache::forget('home_page_data');

        return redirect()
            ->route('admin.products.edit', $newProduct->product_id)
            ->with('success', 'Product duplicated for '.strtoupper($targetLanguage).'. Please update the translated content.');
    }

    public function preview(Product $product)
    {
        if (! in_array((int) $product->is_active, [1, 3], true)) {
            abort(404);
        }

        $product->load([
            'mainImage',
            'images',
            'galleryImages',
            'detail',
            'category',
            'material',
        ]);

        $relatedProducts = collect();
        $productFaqs = collect();
        $isPreview = true;

        if ((int) $product->product_type === 2) {
            return view('products.hotmobily_desc', compact(
                'product',
                'relatedProducts',
                'productFaqs',
                'isPreview'
            ));
        }

        return view('products.hotstrap_desc', compact(
            'product',
            'relatedProducts',
            'productFaqs',
            'isPreview'
        ));
    }
}
