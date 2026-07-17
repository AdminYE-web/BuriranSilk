<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductArtworkTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductArtworkTemplateController extends Controller
{
    public function index()
    {
        $templates = ProductArtworkTemplate::with('product')
            ->orderBy('product_id')
            ->orderBy('sort_order')
            ->orderBy('template_id', 'desc')
            ->paginate(20);

        return view('admin.product_artwork_templates.index', compact('templates'));
    }

    public function create()
    {
        $products = Product::where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.product_artwork_templates.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',

            'templates' => 'required|array|min:1',
            'templates.*.template_name' => 'required|string|max:255',
            'templates.*.image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'templates.*.sort_order' => 'nullable|integer|min:0',
            'templates.*.is_active' => 'nullable|boolean',
        ]);

        foreach ($request->templates as $index => $templateData) {
            $imagePath = null;

            if ($request->hasFile("templates.$index.image_path")) {
                $imagePath = $request->file("templates.$index.image_path")
                    ->store('artwork-templates', 'public');
            }

            ProductArtworkTemplate::create([
                'product_id' => $request->product_id,
                'template_name' => $templateData['template_name'],
                'image_path' => $imagePath,
                'sort_order' => $templateData['sort_order'] ?? 0,
                'is_active' => isset($templateData['is_active']) ? 1 : 0,
            ]);
        }

        return redirect()
            ->route('admin.product-artwork-templates.index')
            ->with('success', 'Artwork templates created successfully.');
    }

    public function edit(ProductArtworkTemplate $productArtworkTemplate)
    {
        $products = Product::where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.product_artwork_templates.edit', [
            'template' => $productArtworkTemplate,
            'products' => $products,
        ]);
    }

    public function update(Request $request, ProductArtworkTemplate $productArtworkTemplate)
    {
        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'template_name' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = $productArtworkTemplate->image_path;

        if ($request->hasFile('image_path')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image_path')->store('artwork-templates', 'public');
        }

        $productArtworkTemplate->update([
            'product_id' => $request->product_id,
            'template_name' => $request->template_name,
            'image_path' => $imagePath,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.product-artwork-templates.index')
            ->with('success', 'Artwork template updated successfully.');
    }

    public function destroy(ProductArtworkTemplate $productArtworkTemplate)
    {
        if ($productArtworkTemplate->image_path && Storage::disk('public')->exists($productArtworkTemplate->image_path)) {
            Storage::disk('public')->delete($productArtworkTemplate->image_path);
        }

        $productArtworkTemplate->delete();

        return redirect()
            ->route('admin.product-artwork-templates.index')
            ->with('success', 'Artwork template deleted successfully.');
    }
}
