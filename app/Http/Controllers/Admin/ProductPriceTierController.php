<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductPriceTier;
use Illuminate\Http\Request;

class ProductPriceTierController extends Controller
{
    public function index()
    {
        $tiers = ProductPriceTier::with('product')
            ->orderBy('tier_id', 'desc')
            ->paginate(10);

        return view('admin.product_price_tiers.index', compact('tiers'));
    }

    public function create()
    {
        $products = Product::where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.product_price_tiers.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',

            'tiers' => 'required|array|min:1',
            'tiers.*.min_qty' => 'required|integer|min:1',
            'tiers.*.max_qty' => 'nullable|integer|min:1',
            'tiers.*.unit_price' => 'required|numeric|min:0',
            'tiers.*.is_active' => 'nullable|boolean',
        ]);

        foreach ($request->tiers as $tier) {
            ProductPriceTier::create([
                'product_id' => $request->product_id,
                'min_qty' => $tier['min_qty'],
                'max_qty' => $tier['max_qty'] ?? null,
                'unit_price' => $tier['unit_price'],
                'is_active' => ! empty($tier['is_active']) ? 1 : 0,
            ]);
        }

        return redirect()
            ->route('admin.product-price-tiers.index')
            ->with('success', 'Product price tiers created successfully.');
    }

    public function edit(ProductPriceTier $productPriceTier)
    {
        $products = Product::where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.product_price_tiers.edit', [
            'tier' => $productPriceTier,
            'products' => $products,
        ]);
    }

    public function update(Request $request, ProductPriceTier $productPriceTier)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'min_qty' => 'required|integer|min:1',
            'max_qty' => 'nullable|integer|gte:min_qty',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $productPriceTier->update([
            'product_id' => $request->product_id,
            'min_qty' => $request->min_qty,
            'max_qty' => $request->max_qty,
            'unit_price' => $request->unit_price,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.product-price-tiers.index')
            ->with('success', 'แก้ไขราคาขั้นบันไดเรียบร้อยแล้ว');
    }

    public function destroy(ProductPriceTier $productPriceTier)
    {
        $productPriceTier->delete();

        return redirect()
            ->route('admin.product-price-tiers.index')
            ->with('success', 'ลบราคาขั้นบันไดเรียบร้อยแล้ว');
    }
}
