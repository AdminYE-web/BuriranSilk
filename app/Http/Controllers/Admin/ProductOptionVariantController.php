<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOption;
use App\Models\ProductOptionVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductOptionVariantController extends Controller
{
    public function index(ProductOption $option)
    {
        $option->load(['group', 'variants']);

        return view('admin.product_option_variants.index', compact('option'));
    }

    public function create(ProductOption $option)
    {
        return view('admin.product_option_variants.create', compact('option'));
    }

    public function store(Request $request, ProductOption $option)
    {
        $request->validate([
            'variant_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:20',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'additional_price' => 'nullable|numeric|min:0',
            'additional_price_with_tax' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = null;

        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')
                ->store('product-option-variants', 'public');
        }

        if ($request->has('is_default')) {
            $option->variants()->update(['is_default' => 0]);
        }

        ProductOptionVariant::create([
            'option_id' => $option->option_id,
            'variant_name' => $request->variant_name,
            'color_code' => $request->color_code,
            'image_path' => $imagePath,
            'additional_price' => $request->additional_price ?? 0,
            'additional_price_with_tax' => $request->additional_price_with_tax,
            'sort_order' => $request->sort_order ?? 0,
            'is_default' => $request->has('is_default') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.product-options.variants.index', $option->option_id)
            ->with('success', 'เพิ่ม Variant เรียบร้อยแล้ว');
    }

    public function edit(ProductOptionVariant $variant)
    {
        return view('admin.product_option_variants.edit', compact('variant'));
    }

    public function update(Request $request, ProductOptionVariant $variant)
    {
        $request->validate([
            'variant_name' => 'required|string|max:255',
            'color_code' => 'nullable|string|max:20',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'additional_price' => 'nullable|numeric|min:0',
            'additional_price_with_tax' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0',
            'is_default' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $variant->image_path;

        if ($request->hasFile('image_path')) {
            if ($variant->image_path) {
                Storage::disk('public')->delete($variant->image_path);
            }

            $imagePath = $request->file('image_path')
                ->store('product-option-variants', 'public');
        }

        if ($request->has('is_default')) {
            ProductOptionVariant::where('option_id', $variant->option_id)
                ->where('variant_id', '!=', $variant->variant_id)
                ->update(['is_default' => 0]);
        }

        $variant->update([
            'variant_name' => $request->variant_name,
            'color_code' => $request->color_code,
            'image_path' => $imagePath,
            'additional_price' => $request->additional_price ?? 0,
            'additional_price_with_tax' => $request->additional_price_with_tax,
            'sort_order' => $request->sort_order ?? 0,
            'is_default' => $request->has('is_default') ? 1 : 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.product-options.variants.index', $variant->option_id)
            ->with('success', 'แก้ไข Variant เรียบร้อยแล้ว');
    }

    public function destroy(ProductOptionVariant $variant)
    {
        $optionId = $variant->option_id;

        if ($variant->image_path) {
            Storage::disk('public')->delete($variant->image_path);
        }

        $variant->delete();

        return redirect()
            ->route('admin.product-options.variants.index', $optionId)
            ->with('success', 'ลบ Variant เรียบร้อยแล้ว');
    }
}
