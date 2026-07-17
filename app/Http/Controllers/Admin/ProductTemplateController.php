<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductTemplateController extends Controller
{
    public function index(Request $request)
    {
        $language = session('admin_product_language', 'pt');

        $templates = ProductTemplate::with('product')
            ->where('language', $language)
            ->when($request->search, function ($query) use ($request) {
                $query->whereHas('product', function ($productQuery) use ($request) {
                    $productQuery->where('product_name', 'like', '%'.$request->search.'%');
                })
                    ->orWhere('template_size', 'like', '%'.$request->search.'%')
                    ->orWhere('original_name', 'like', '%'.$request->search.'%');
            })
            ->orderBy('template_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.product_templates.index', compact('templates', 'language'));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');

        $products = Product::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.product_templates.create', compact('products', 'language'));
    }

    public function store(Request $request)
    {

        $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'template_size' => ['nullable', 'string', 'max:100'],
            'template_file' => [
                'required',
                'file',
                'max:51200',
                function ($attribute, $value, $fail) {
                    $allowed = ['pdf', 'ai'];
                    $extension = strtolower($value->getClientOriginalExtension());

                    if (! in_array($extension, $allowed, true)) {
                        $fail('The template file must be a PDF or AI file.');
                    }
                },
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $language = session('admin_product_language', 'pt');

        $file = $request->file('template_file');
        $path = $file->store('product_templates', 'public');

        ProductTemplate::create([
            'product_id' => $request->product_id,
            'language' => $language,
            'template_size' => $request->template_size,
            'file_path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'file_type' => strtolower($file->getClientOriginalExtension()),
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.product-templates.index')
            ->with('success', 'Product template uploaded successfully.');
    }

    public function edit(ProductTemplate $productTemplate)
    {
        $language = session('admin_product_language', 'pt');

        $products = Product::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.product_templates.edit', [
            'template' => $productTemplate,
            'products' => $products,
            'language' => $language,
        ]);
    }

    public function update(Request $request, ProductTemplate $productTemplate)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,product_id'],
            'template_size' => ['nullable', 'string', 'max:100'],
            'template_file' => [
                'nullable',
                'file',
                'max:51200',
                function ($attribute, $value, $fail) {
                    if (! $value) {
                        return;
                    }

                    $allowed = ['pdf', 'ai'];
                    $extension = strtolower($value->getClientOriginalExtension());

                    if (! in_array($extension, $allowed, true)) {
                        $fail('The template file must be a PDF or AI file.');
                    }
                },
            ],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data = [
            'product_id' => $request->product_id,
            'template_size' => $request->template_size,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ];

        if ($request->hasFile('template_file')) {
            if ($productTemplate->file_path && Storage::disk('public')->exists($productTemplate->file_path)) {
                Storage::disk('public')->delete($productTemplate->file_path);
            }

            $file = $request->file('template_file');
            $path = $file->store('product_templates', 'public');

            $data['file_path'] = $path;
            $data['original_name'] = $file->getClientOriginalName();
            $data['file_type'] = strtolower($file->getClientOriginalExtension());
        }

        $productTemplate->update($data);

        return redirect()
            ->route('admin.product-templates.index')
            ->with('success', 'Product template updated successfully.');
    }

    public function destroy(ProductTemplate $productTemplate)
    {
        if ($productTemplate->file_path && Storage::disk('public')->exists($productTemplate->file_path)) {
            Storage::disk('public')->delete($productTemplate->file_path);
        }

        $productTemplate->delete();

        return redirect()
            ->route('admin.product-templates.index')
            ->with('success', 'Product template deleted successfully.');
    }
}
