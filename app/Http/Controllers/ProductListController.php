<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\OptionDependency;
use App\Models\Product;
use App\Models\ProductOptionGroupOrder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductListController extends Controller
{
    public function index(Request $request): View
    {
        /*
         * dev=1 แสดงข้อมูลภาษาอังกฤษ
         * ค่าอื่นแสดงข้อมูลภาษาญี่ปุ่น
         * หากไม่มีภาษาที่เลือก จะใช้ภาษา pt
         */
        $language = $request->cookie('dev') === '1'
            ? 'en'
            : 'ja';

        $languages = $language === 'pt'
            ? ['pt']
            : [$language, 'pt'];

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categoryModels = Category::query()
            ->where('is_active', 1)
            ->where('product_type', 1)
            ->whereIn('language', $languages)
            ->orderByRaw(
                'CASE WHEN language = ? THEN 0 ELSE 1 END',
                [$language]
            )
            ->orderBy('sort_order')
            ->orderBy('category_id')
            ->get()
            /*
             * ถ้ามี Category หลายภาษา ให้เลือกภาษาปัจจุบันก่อน
             */
            ->unique(function (Category $category) {
                return $category->translation_key
                    ?: $category->category_code
                    ?: 'category-'.$category->category_id;
            })
            ->values();

        /*
         * ใช้ category_id เป็นค่าของ Filter
         * ยังคงใช้ key slug เพื่อให้ Blade เดิมใช้ต่อได้
         */
        $categories = $categoryModels
            ->map(function (Category $category) {
                return [
                    'slug' => (string) $category->category_id,
                    'name' => $category->category_name,
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Selected Categories
        |--------------------------------------------------------------------------
        */

        $validCategoryIds = $categoryModels
            ->pluck('category_id')
            ->map(fn ($id) => (string) $id);

        $selectedCategories = collect(
            (array) $request->input('categories', [])
        )
            ->map(fn ($id) => (string) $id)
            ->filter(
                fn ($id) => $validCategoryIds->contains($id)
            )
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Price Filter
        |--------------------------------------------------------------------------
        */

        $priceLimit = 20000;

        $minPrice = max(
            0,
            min(
                (int) $request->input('min_price', 0),
                $priceLimit
            )
        );

        $maxPrice = max(
            $minPrice,
            min(
                (int) $request->input(
                    'max_price',
                    $priceLimit
                ),
                $priceLimit
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Sort
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'newest',
            'price_asc',
            'price_desc',
        ];

        $sort = in_array(
            $request->input('sort'),
            $allowedSorts,
            true
        )
            ? $request->input('sort')
            : 'newest';

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        $productModels = Product::query()
            ->with([
                'category',
                'mainImage',

                // รูปที่สองสำหรับ Hover
                'secondImage',

                'galleryImages',

                'detail' => function ($query) {
                    $query->where('is_active', 1);
                },

                'displayPriceTier',
                'priceTiers',
            ])
            ->where('is_active', 1)
            ->where('product_type', 1)
            ->whereIn('language', $languages)
            ->when(
                count($selectedCategories) > 0,
                function (Builder $query) use (
                    $selectedCategories
                ) {
                    $query->whereIn(
                        'category_id',
                        $selectedCategories
                    );
                }
            )
            ->orderByRaw(
                'CASE WHEN language = ? THEN 0 ELSE 1 END',
                [$language]
            )
            ->orderByDesc('product_id')
            ->get()
            /*
             * เลือกสินค้าแค่ภาษาเดียวต่อ translation_key
             */
            ->unique(function (Product $product) {
                return $product->translation_key
                    ?: $product->product_code
                    ?: 'product-'.$product->product_id;
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Prepare Product Data For Blade
        |--------------------------------------------------------------------------
        */

        $products = $productModels
            ->map(function (Product $product) use ($language) {
                /*
                 * Main Image
                 */
                $mainImagePath =
                    $product->mainImage?->image_path
                    ?? $product->galleryImages
                        ->first()?->image_path
                    ?? $product->category?->image_path;

                /*
                 * Second Image สำหรับ Hover
                 */
                $hoverImagePath =
                    $product->secondImage?->image_path;

                return [
                    /*
                     * ใช้ product_code เป็น URL
                     * ถ้าไม่มีให้ใช้ product_id
                     */
                    'slug' => filled($product->product_code)
                        ? $product->product_code
                        : (string) $product->product_id,

                    'category' =>
                        (string) $product->category_id,

                    'name' => $product->product_name,

                    'description' =>
                        $product->detail?->short_description
                        ?: $product->description
                        ?: '',

                    'long_description' =>
                        $product->detail?->long_description,

                    'price' =>
                        $this->getProductPrice($product),

                    /*
                     * ตอนนี้ยังไม่มี Delivery ในฐานข้อมูล
                     */
                    'delivery' => $language === 'en'
                        ? 'Ships in 10–20 business days'
                        : '10営業日〜20営業日',

                    /*
                     * Main Image
                     */
                    'image' => $this->imageAssetPath(
                        $mainImagePath
                    ),

                    /*
                     * Second Image สำหรับ Hover
                     */
                    'hover_image' => $this->imageAssetPath(
                        $hoverImagePath
                    ),

                    'sort_order' =>
                        $product->product_id,

                    'is_available' => true,
                ];
            })
            ->filter(function (array $product) use (
                $minPrice,
                $maxPrice
            ) {
                return $product['price'] >= $minPrice
                    && $product['price'] <= $maxPrice;
            });

        /*
        |--------------------------------------------------------------------------
        | Product Sorting
        |--------------------------------------------------------------------------
        */

        $products = match ($sort) {
            'price_asc' =>
                $products->sortBy('price'),

            'price_desc' =>
                $products->sortByDesc('price'),

            default =>
                $products->sortByDesc('sort_order'),
        };

        return view('frontend.products.index', [
            'categories' => $categories,
            'products' => $products->values(),
            'selectedCategories' =>
                $selectedCategories,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'priceLimit' => $priceLimit,
            'sort' => $sort,
        ]);
    }

    public function show(string $slug): View
    {
        /*
         * รองรับ URL เก่าจากหน้า Home
         */
        if ($slug === 'id-case') {
            $slug = 'silk-employee-id-case';
        }

        $language = request()->cookie('dev') === '1'
            ? 'en'
            : 'ja';

        $languages = $language === 'pt'
            ? ['pt']
            : [$language, 'pt'];

        $productModel = Product::query()
            ->with([
                'mainImage',
                'secondImage',
                'galleryImages',

                'detail' => function ($query) {
                    $query->where('is_active', 1);
                },

                'displayPriceTier',
                'priceTiers',
                'optionAssignments' => function ($query) {
                    $query->where('is_active', 1)
                        ->orderBy('sort_order')
                        ->orderBy('assignment_id');
                },
                'optionAssignments.option' => function ($query) {
                    $query->where('is_active', 1)
                        ->with(['group', 'mainImage']);
                },
            ])
            ->where('is_active', 1)
            ->whereIn('language', $languages)
            ->where(function (Builder $query) use ($slug) {
                $query->where(
                    'product_code',
                    $slug
                );

                if (ctype_digit($slug)) {
                    $query->orWhere(
                        'product_id',
                        (int) $slug
                    );
                }
            })
            ->orderByRaw(
                'CASE WHEN language = ? THEN 0 ELSE 1 END',
                [$language]
            )
            ->firstOrFail();

        /*
        |--------------------------------------------------------------------------
        | Gallery
        |--------------------------------------------------------------------------
        */

        $gallery = collect([
            $productModel->mainImage?->image_path,

            ...$productModel->galleryImages
                ->pluck('image_path')
                ->all(),
        ])
            ->filter()
            ->unique()
            ->map(
                fn ($path) =>
                    $this->imageAssetPath($path)
            )
            ->values()
            ->all();

        /*
         * ป้องกันหน้า Detail error กรณีไม่มีรูป
         */
        if (empty($gallery)) {
            $gallery[] =
                'assets/images/home/Rectangle 158.png';
        }

        $groupSortOrders = ProductOptionGroupOrder::query()
            ->where('product_id', $productModel->product_id)
            ->pluck('sort_order', 'option_group_id');

        $optionGroups = $productModel->optionAssignments
            ->filter(function ($assignment) {
                return $assignment->option?->group?->is_active;
            })
            ->groupBy(function ($assignment) {
                return $assignment->option->option_group_id;
            })
            ->map(function ($assignments) use ($groupSortOrders) {
                $group = $assignments->first()->option->group;

                return [
                    'id' => $group->option_group_id,
                    'code' => $group->group_code,
                    'name' => $group->group_name,
                    'help_text' => $group->help_text,
                    'display_type' => $group->display_type ?: 'button',
                    'is_required' => (bool) $group->is_required,
                    'sort_order' => $groupSortOrders[$group->option_group_id]
                        ?? $group->sort_order
                        ?? 999,
                    'options' => $assignments
                        ->map(function ($assignment) {
                            $option = $assignment->option;

                            return [
                                'id' => $option->option_id,
                                'code' => $option->option_code,
                                'name' => $option->option_name,
                                'detail' => $option->option_detail,
                                'image' => $this->imageAssetPath(
                                    $option->mainImage?->image_path
                                ),
                                'image_alt' => $option->mainImage?->image_alt,
                                'additional_price' => (float) $option->additional_price,
                                'price_type' => $option->price_type ?: 'per_item',
                                'free_from_qty' => $option->free_from_qty,
                                'is_default' => (bool) $assignment->is_default,
                                'qty_rule_type' => $assignment->qty_rule_type,
                                'min_qty' => $assignment->min_qty
                                    ? (int) $assignment->min_qty
                                    : null,
                                'max_qty' => $assignment->max_qty
                                    ? (int) $assignment->max_qty
                                    : null,
                                'exact_qty' => $assignment->exact_qty
                                    ? (int) $assignment->exact_qty
                                    : null,
                            ];
                        })
                        ->values()
                        ->all(),
                ];
            })
            ->sortBy('sort_order')
            ->values()
            ->all();

        $optionQuantityRules = collect($optionGroups)
            ->flatMap(fn ($group) => $group['options'])
            ->filter(fn ($option) => filled($option['qty_rule_type']))
            ->mapWithKeys(function ($option) {
                return [
                    $option['id'] => [
                        'type' => $option['qty_rule_type'],
                        'min' => $option['min_qty'],
                        'max' => $option['max_qty'],
                        'exact' => $option['exact_qty'],
                    ],
                ];
            })
            ->all();

        $assignedGroupIds = collect($optionGroups)
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->values();

        $assignedOptionIds = collect($optionGroups)
            ->flatMap(fn ($group) => collect($group['options'])->pluck('id'))
            ->map(fn ($id) => (int) $id)
            ->values();

        $optionDependencies = $assignedOptionIds->isEmpty()
            ? []
            : OptionDependency::query()
                ->where('is_active', 1)
                ->whereIn('parent_option_id', $assignedOptionIds)
                ->where(function ($query) use ($assignedGroupIds, $assignedOptionIds) {
                    $query->where(function ($groupQuery) use ($assignedGroupIds) {
                        $groupQuery->where('target_type', 'group')
                            ->whereIn('target_group_id', $assignedGroupIds);
                    })->orWhere(function ($optionQuery) use ($assignedOptionIds) {
                        $optionQuery->where('target_type', 'option')
                            ->whereIn('target_option_id', $assignedOptionIds);
                    });
                })
                ->orderBy('sort_order')
                ->orderBy('dependency_id')
                ->get()
                ->map(function (OptionDependency $dependency) {
                    return [
                        'parent_option_id' => (int) $dependency->parent_option_id,
                        'target_type' => $dependency->target_type,
                        'action_type' => $dependency->action_type,
                        'target_group_id' => $dependency->target_group_id
                            ? (int) $dependency->target_group_id
                            : null,
                        'target_option_id' => $dependency->target_option_id
                            ? (int) $dependency->target_option_id
                            : null,
                    ];
                })
                ->values()
                ->all();

        /*
        |--------------------------------------------------------------------------
        | Prepare Product Detail Data
        |--------------------------------------------------------------------------
        */

        $product = [
            'id' => $productModel->product_id,

            'slug' => filled($productModel->product_code)
                ? $productModel->product_code
                : (string) $productModel->product_id,

            'name' => $productModel->product_name,

            'short_name' =>
                $productModel->product_name,

            'description' =>
                $productModel->detail?->short_description
                ?: $productModel->description
                ?: '',

            'short_description' =>
                $productModel->detail?->short_description,

            'long_description' =>
                $productModel->detail?->long_description,
            'specification_image' =>
                $this->imageAssetPath(
                    $productModel->detail?->specification_image
                ),


            'unit_price' =>
                $this->getProductPrice($productModel),

            /*
             * Main + Gallery
             * ไม่ใส่ Second Image ใน Gallery
             */
            'gallery' => $gallery,

            /*
             * Second Image ยังคงส่งไปด้วย
             * เผื่อต้องใช้ในหน้า Detail
             */
            'hover_image' =>
                $this->imageAssetPath(
                    $productModel
                        ->secondImage?->image_path
                ),

            'detail_content' =>
                $productModel->detail?->detail_content
                ?? [],

            'specification_content' =>
                $productModel
                    ->detail?->specification_content
                ?? [],

            'accordion_content' =>
                $productModel
                    ->detail?->accordion_content
                ?? [],
        ];

        $editingCartItem = null;
        $editingCartItemId = request()->query('edit_cart');

        if (filled($editingCartItemId)) {
            $editingCartItem = request()->session()->get('cart.items.'.$editingCartItemId);

            abort_unless(
                $editingCartItem
                    && (int) ($editingCartItem['product_id'] ?? 0) === (int) $productModel->product_id,
                404
            );
        }

        return view(
            'frontend.products.show',
            compact(
                'product',
                'optionGroups',
                'optionDependencies',
                'optionQuantityRules',
                'editingCartItem',
                'editingCartItemId'
            )
        );
    }

    private function getProductPrice(
        Product $product
    ): float {
        /*
         * ราคาที่เลือกให้แสดงจาก Price Rule
         */
        $displayTier = $product->displayPriceTier;

        if ($displayTier) {
            return (float) ($displayTier->unit_price ?? 0);
        }

        /*
         * ถ้าไม่มี Price Rule ให้ใช้ Product Price Tier
         */
        return (float) (
            $product->priceTiers
                ->first()?->unit_price
            ?? 0
        );
    }

    private function imageAssetPath(
        ?string $path
    ): ?string {
        if (blank($path)) {
            return null;
        }

        $path = ltrim($path, '/');

        /*
         * กรณีเป็น URL เต็ม
         */
        if (
            Str::startsWith(
                $path,
                ['http://', 'https://']
            )
        ) {
            return $path;
        }

        /*
         * กรณีมี storage/ หรือ assets/ แล้ว
         */
        if (
            Str::startsWith(
                $path,
                ['storage/', 'assets/']
            )
        ) {
            return $path;
        }

        /*
         * รูปที่อัปโหลดจาก Admin
         */
        return 'storage/'.$path;
    }
}
