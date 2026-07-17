<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductDetailController extends Controller
{
    public function index()
    {
        $details = ProductDetail::with('product')
            ->orderBy('product_detail_id', 'desc')
            ->paginate(10);

        return view('admin.product_details.index', compact('details'));
    }

    public function create(Request $request)
    {
        $selectedProductId = $request->query('product_id');

        if ($selectedProductId) {
            $existingDetail = ProductDetail::where('product_id', $selectedProductId)->first();

            if ($existingDetail) {
                return redirect()->route(
                    'admin.product-details.edit',
                    $existingDetail->product_detail_id
                );
            }
        }

        $products = Product::where('is_active', 1)
            ->whereDoesntHave('detail')
            ->orderBy('product_name')
            ->get();

        return view('admin.product_details.create', compact('products', 'selectedProductId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id|unique:product_details,product_id',
            'sample_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_content' => 'nullable|array',
            'detail_content.*.headline' => 'nullable|string|max:255',
            'detail_content.*.desc' => 'nullable|string',
            'detail_content.*.emoticon' => 'nullable|string|max:50',
            'detail_icon_images' => 'nullable|array',
            'detail_icon_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'specification_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'accordion_content' => 'nullable|array',
            'accordion_content.*.title' => 'nullable|string|max:255',
            'accordion_content.*.content' => 'nullable|string',

            'specification_content' => 'nullable|array',
            'specification_content.*.title' => 'nullable|string|max:255',
            'specification_content.*.desc' => 'nullable|string',
            'specification_content.*.icon_image' => 'nullable|string',
            'specification_content.*.link_text' => 'nullable|string|max:255',
            'specification_content.*.link_url' => 'nullable|string|max:500',

            'spec_icon_images' => 'nullable|array',
            'spec_icon_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $sampleImagePath = null;

        if ($request->hasFile('sample_image')) {
            $sampleImagePath = $request->file('sample_image')
                ->store('products/details', 'public');
        }
        $detailContent = $request->detail_content ?? [];

        if ($request->hasFile('detail_icon_images')) {
            foreach ($request->file('detail_icon_images') as $index => $iconImage) {
                if ($iconImage) {
                    $iconPath = $iconImage->store('products/detail-icons', 'public');

                    if (isset($detailContent[$index])) {
                        $detailContent[$index]['icon_image'] = $iconPath;
                    }
                }
            }
        }
        $specificationImagePath = null;

        if ($request->hasFile('specification_image')) {
            $specificationImagePath = $request->file('specification_image')
                ->store('products/specifications', 'public');
        }

        $specificationContent = $request->specification_content ?? [];

        if ($request->hasFile('spec_icon_images')) {
            foreach ($request->file('spec_icon_images') as $index => $iconImage) {
                if ($iconImage) {
                    $iconPath = $iconImage->store('products/spec-icons', 'public');

                    if (isset($specificationContent[$index])) {
                        $specificationContent[$index]['icon_image'] = $iconPath;
                    }
                }
            }
        }
        $accordionContent = $request->accordion_content ?? [];

        ProductDetail::create([
            'product_id' => $request->product_id,
            'sample_image' => $sampleImagePath,
            'specification_image' => $specificationImagePath,
            'detail_content' => $detailContent,
            'specification_content' => $specificationContent,
            'accordion_content' => $accordionContent,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'เพิ่มรายละเอียดสินค้าเรียบร้อยแล้ว');
    }

    public function edit(ProductDetail $productDetail)
    {
        $products = Product::where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.product_details.edit', [
            'detail' => $productDetail,
            'products' => $products,
        ]);
    }

    public function update(Request $request, ProductDetail $productDetail)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id|unique:product_details,product_id,'.$productDetail->product_detail_id.',product_detail_id',
            'sample_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'detail_content' => 'nullable|array',
            'detail_content.*.headline' => 'nullable|string|max:255',
            'detail_content.*.desc' => 'nullable|string',
            'detail_content.*.emoticon' => 'nullable|string|max:50',
            'detail_icon_images' => 'nullable|array',
            'detail_icon_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'specification_content' => 'nullable|array',
            'specification_content.*.title' => 'nullable|string|max:255',
            'specification_content.*.desc' => 'nullable|string',
            'specification_content.*.icon_image' => 'nullable|string',
            'specification_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'specification_content.*.link_text' => 'nullable|string|max:255',
            'specification_content.*.link_url' => 'nullable|string|max:500',
            'accordion_content' => 'nullable|array',
            'accordion_content.*.title' => 'nullable|string|max:255',
            'accordion_content.*.content' => 'nullable|string',

            'spec_icon_images' => 'nullable|array',
            'spec_icon_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'is_active' => 'nullable|boolean',
        ]);

        $sampleImagePath = $productDetail->sample_image;

        if ($request->hasFile('sample_image')) {
            if ($productDetail->sample_image) {
                Storage::disk('public')->delete($productDetail->sample_image);
            }

            $sampleImagePath = $request->file('sample_image')
                ->store('products/details', 'public');
        }
        $specificationImagePath = $productDetail->specification_image;

        if ($request->hasFile('specification_image')) {
            if ($productDetail->specification_image) {
                Storage::disk('public')->delete($productDetail->specification_image);
            }

            $specificationImagePath = $request->file('specification_image')
                ->store('products/specifications', 'public');
        }
        $detailContent = $request->detail_content ?? [];

        if ($request->hasFile('detail_icon_images')) {
            foreach ($request->file('detail_icon_images') as $index => $iconImage) {
                if ($iconImage) {
                    $iconPath = $iconImage->store('products/detail-icons', 'public');

                    if (isset($detailContent[$index])) {
                        $detailContent[$index]['icon_image'] = $iconPath;
                    }
                }
            }
        }
        $specificationContent = $request->specification_content ?? [];

        if ($request->hasFile('spec_icon_images')) {
            foreach ($request->file('spec_icon_images') as $index => $iconImage) {
                if ($iconImage) {
                    $iconPath = $iconImage->store('products/spec-icons', 'public');

                    if (isset($specificationContent[$index])) {
                        $specificationContent[$index]['icon_image'] = $iconPath;
                    }
                }
            }
        }
        $accordionContent = $request->accordion_content ?? [];

        $productDetail->update([
            'product_id' => $request->product_id,
            'sample_image' => $sampleImagePath,
            'specification_image' => $specificationImagePath,
            'detail_content' => $detailContent,
            'specification_content' => $specificationContent,
            'accordion_content' => $accordionContent,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'แก้ไขรายละเอียดสินค้าเรียบร้อยแล้ว');
    }

    public function destroy(ProductDetail $productDetail)
    {
        if ($productDetail->sample_image) {
            Storage::disk('public')->delete($productDetail->sample_image);
        }

        $productDetail->delete();

        return redirect()
            ->route('admin.product-details.index')
            ->with('success', 'ลบรายละเอียดสินค้าเรียบร้อยแล้ว');
    }
}
