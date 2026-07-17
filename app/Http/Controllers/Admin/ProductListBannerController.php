<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductListBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductListBannerController extends Controller
{
    public function index()
    {
        $banners = ProductListBanner::orderBy('sort_order')
            ->orderBy('banner_id', 'desc')
            ->paginate(10);

        return view('admin.product_list_banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.product_list_banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_path' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = null;
        $imageMobilePath = null;

        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')
                ->store('product-list-banners', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            $imageMobilePath = $request->file('image_mobile')
                ->store('product-list-banners/mobile', 'public');
        }

        ProductListBanner::create([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $imagePath,
            'image_mobile' => $imageMobilePath,
            'link_url' => $request->link_url,
            'button_text' => $request->button_text,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
        Cache::forget('product_list_shared_components_pt');
        Cache::forget('product_list_shared_components_ja');
        Cache::forget('product_list_shared_components_en');
        Cache::forget('product_list_shared_components');

        return redirect()
            ->route('admin.product-list-banners.index')
            ->with('success', 'เพิ่ม Product List Banner เรียบร้อยแล้ว');
    }

    public function edit(ProductListBanner $productListBanner)
    {
        return view('admin.product_list_banners.edit', [
            'banner' => $productListBanner,
        ]);
    }

    public function update(Request $request, ProductListBanner $productListBanner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'link_url' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $productListBanner->image_path;
        $imageMobilePath = $productListBanner->image_mobile;

        if ($request->hasFile('image_path')) {
            if ($productListBanner->image_path) {
                Storage::disk('public')->delete($productListBanner->image_path);
            }

            $imagePath = $request->file('image_path')
                ->store('product-list-banners', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            if ($productListBanner->image_mobile) {
                Storage::disk('public')->delete($productListBanner->image_mobile);
            }

            $imageMobilePath = $request->file('image_mobile')
                ->store('product-list-banners/mobile', 'public');
        }

        $productListBanner->update([
            'title' => $request->title,
            'subtitle' => $request->subtitle,
            'image_path' => $imagePath,
            'image_mobile' => $imageMobilePath,
            'link_url' => $request->link_url,
            'button_text' => $request->button_text,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);
        Cache::forget('product_list_shared_components_pt');
        Cache::forget('product_list_shared_components_ja');
        Cache::forget('product_list_shared_components_en');
        Cache::forget('product_list_shared_components');

        return redirect()
            ->route('admin.product-list-banners.index')
            ->with('success', 'แก้ไข Product List Banner เรียบร้อยแล้ว');
    }

    public function destroy(ProductListBanner $productListBanner)
    {
        if ($productListBanner->image_path) {
            Storage::disk('public')->delete($productListBanner->image_path);
        }

        if ($productListBanner->image_mobile) {
            Storage::disk('public')->delete($productListBanner->image_mobile);
        }

        $productListBanner->delete();
        Cache::forget('product_list_shared_components_pt');
        Cache::forget('product_list_shared_components_ja');
        Cache::forget('product_list_shared_components_en');
        Cache::forget('product_list_shared_components');

        return redirect()
            ->route('admin.product-list-banners.index')
            ->with('success', 'ลบ Product List Banner เรียบร้อยแล้ว');
    }
}
