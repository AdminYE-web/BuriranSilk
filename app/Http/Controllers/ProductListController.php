<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductListController extends Controller
{
    public function index(Request $request): View
    {
        $categories = collect([
            ['slug' => 'silk-employee-id-case', 'name' => 'シルク製社員証ケース'],
            ['slug' => 'key-ring', 'name' => 'key ring'],
            ['slug' => 'business-card-holder', 'name' => 'Business Card Holder'],
            ['slug' => 'gold-thread-products', 'name' => '製品金撚'],
        ]);

        /*
         * ใช้ข้อมูลตัวอย่างก่อน เมื่อมีตารางสินค้าแล้วสามารถเปลี่ยนส่วนนี้
         * เป็น Eloquent query ได้โดยไม่ต้องแก้ Blade และ CSS
         */
        $products = collect([
            [
                'slug' => 'silk-employee-id-case',
                'category' => 'silk-employee-id-case',
                'name' => 'シルク製社員証ケース',
                'price' => 132,
                'delivery' => '10営業日〜20営業日',
                'image' => 'assets/images/home/Rectangle 158.png',
                'hover_image' => 'assets/images/home/Rectangle 158 (1).png',
                'sort_order' => 1,
                'is_available' => true,
            ],
            [
                'slug' => 'key-ring',
                'category' => 'key-ring',
                'name' => 'key ring',
                'price' => 132,
                'delivery' => '10営業日〜20営業日',
                'image' => 'assets/images/home/Rectangle 160.png',
                'hover_image' => 'assets/images/home/Rectangle 160 (1).png',
                'sort_order' => 2,
                'is_available' => true,
            ],
            [
                'slug' => 'business-card-holder',
                'category' => 'business-card-holder',
                'name' => 'Business Card Holder',
                'price' => 132,
                'delivery' => '10営業日〜20営業日',
                'image' => 'assets/images/home/Rectangle 162.png',
                'hover_image' => 'assets/images/home/Rectangle 162 (1).png',
                'sort_order' => 3,
                'is_available' => true,
            ],
            [
                'slug' => 'gold-thread-products',
                'category' => 'gold-thread-products',
                'name' => '製品金撚',
                'price' => 520,
                'delivery' => '10営業日〜20営業日',
                'image' => null,
                'hover_image' => null,
                'sort_order' => 4,
                'is_available' => false,
            ],
        ]);

        $selectedCategories = collect((array) $request->input('categories', []))
            ->filter(fn ($category) => $categories->contains('slug', $category))
            ->values()
            ->all();

        $priceLimit = 20000;
        $minPrice = max(0, min((int) $request->input('min_price', 0), $priceLimit));
        $maxPrice = max($minPrice, min((int) $request->input('max_price', $priceLimit), $priceLimit));
        $sort = in_array($request->input('sort'), ['newest', 'price_asc', 'price_desc'], true)
            ? $request->input('sort')
            : 'newest';

        $products = $products
            ->when(
                count($selectedCategories) > 0,
                fn ($items) => $items->whereIn('category', $selectedCategories)
            )
            ->filter(fn ($product) => $product['is_available'])
            ->filter(fn ($product) => $product['price'] >= $minPrice && $product['price'] <= $maxPrice);

        $products = match ($sort) {
            'price_asc' => $products->sortBy('price'),
            'price_desc' => $products->sortByDesc('price'),
            default => $products->sortBy('sort_order'),
        };

        return view('frontend.products.index', [
            'categories' => $categories,
            'products' => $products->values(),
            'selectedCategories' => $selectedCategories,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'priceLimit' => $priceLimit,
            'sort' => $sort,
        ]);
    }
    public function show(string $slug): View
    {
        // รองรับ URL เดิมที่หน้า Home ใช้อยู่
        if ($slug === 'id-case') {
            $slug = 'silk-employee-id-case';
        }

        $products = collect([
            'silk-employee-id-case' => [
                'slug' => 'silk-employee-id-case',
                'name' => 'シルク（タイシルク）製のオリジナルIDカードホルダーの製作',
                'short_name' => 'シルク製社員証ケース',
                'description' => 'タイシルク製のカードケースに、お客様のオリジナルデザインをフルカラーで印刷。シルクの質感や高級感を活かしたオリジナルIDカードホルダーを製作いたします。',
                'unit_price' => 132,
                'gallery' => [
                    'assets/images/product/Rectangle 204.png',
                    'assets/images/product/Rectangle 215.png',
                    'assets/images/product/Frame 18 (1).png',
                    'assets/images/product/Frame 18.png',
                  
                ],
            ]
        ]);

        abort_unless($products->has($slug), 404);

        return view('frontend.products.show', [
            'product' => $products->get($slug),
        ]);
    }
}