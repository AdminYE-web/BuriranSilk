<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuProductController extends Controller
{
    public function index()
    {
        $language = session('admin_product_language', 'pt');

        

        $menuHotstrap = Product::where('language', $language)
            ->where('product_type', 1)
            ->where('product_recomend_menu', 1)
            ->orderBy('product_id', 'desc')
            ->get();

        $menuHotmobily = Product::where('language', $language)
            ->where('product_type', 2)
            ->where('product_recomend_menu', 1)
            ->orderBy('product_id', 'desc')
            ->get();

        $recommendedHotstrap = Product::where('language', $language)
            ->where('product_type', 1)
            ->where('product_recomend', 1)
            ->orderBy('product_id', 'desc')
            ->get();

        $recommendedHotmobily = Product::where('language', $language)
            ->where('product_type', 2)
            ->where('product_recomend', 1)
            ->orderBy('product_id', 'desc')
            ->get();

        $products = Product::where('language', $language)
            ->whereIn('is_active', [1])
            ->orderBy('product_name')
            ->get();

        return view('admin.menu_products.index', compact(
            'language',
            'products',
            'menuHotstrap',
            'menuHotmobily',
            'recommendedHotstrap',
            'recommendedHotmobily'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'target' => 'required|in:menu,recommended',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($request->target === 'menu') {
            $field = 'product_recomend_menu';
            $limit = 12;
            $successMessage = 'Product added to menu bar.';
        } else {
            $field = 'product_recomend';
            $limit = 5;
            $successMessage = 'Product added to recommended products.';
        }

        if ((int) $product->{$field} === 1) {
            return back()->with('success', 'This product is already selected.');
        }

        $currentCount = Product::where('language', $product->language)
            ->where('product_type', $product->product_type)
            ->where($field, 1)
            ->count();

        if ($currentCount >= $limit) {
            return back()->withErrors([
                'limit' => 'This type already has maximum '.$limit.' products. Please remove one before adding a new product.',
            ]);
        }

        $product->update([
            $field => 1,
        ]);
        $this->clearHomeCache($product->language);

        return back()->with('success', $successMessage);
    }

    public function remove(Request $request, Product $product)
    {
        $request->validate([
            'target' => 'required|in:menu,recommended',
        ]);

        if ($request->target === 'menu') {
            $product->update([
                'product_recomend_menu' => 0,
            ]);

            return back()->with('success', 'Product removed from menu bar.');
        }

        $product->update([
            'product_recomend' => 0,
        ]);
        $this->clearHomeCache($product->language);

        return back()->with('success', 'Product removed from recommended products.');
    }
    private function clearHomeCache(string $language): void
{
    Cache::forget('home_page_data_'.$language);
    Cache::forget('home_page_data');
}
}