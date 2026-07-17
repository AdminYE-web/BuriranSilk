<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Product;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $language = session('admin_product_language', 'pt');

        $faqs = Faq::with('product')
            ->where('language', $language)
            ->when($request->search, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('question', 'like', '%'.$request->search.'%')
                        ->orWhere('answer', 'like', '%'.$request->search.'%')
                        ->orWhereHas('product', function ($productQuery) use ($request) {
                            $productQuery->where('product_name', 'like', '%'.$request->search.'%');
                        });
                });
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->orderBy('sort_order')
            ->orderBy('faq_id', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.faqs.index', compact('faqs', 'language'));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');

        $products = Product::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.faqs.create', compact('products', 'language'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['nullable', 'exists:products,product_id'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['nullable', 'string'],
            'show_main' => ['nullable', 'boolean'],
            'show_product' => ['nullable', 'boolean'],
            'status' => ['required', 'in:show,hide'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $language = session('admin_product_language', 'pt');

        Faq::create([
            'product_id' => $request->product_id,
            'language' => $language,
            'question' => $request->question,
            'answer' => $request->answer,
            'show_main' => $request->has('show_main') ? 1 : 0,
            'show_product' => $request->has('show_product') ? 1 : 0,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    public function edit(Faq $faq)
    {
        $language = session('admin_product_language', 'pt');

        $products = Product::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('product_name')
            ->get();

        return view('admin.faqs.edit', compact('faq', 'products', 'language'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'product_id' => ['nullable', 'exists:products,product_id'],
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['nullable', 'string'],
            'show_main' => ['nullable', 'boolean'],
            'show_product' => ['nullable', 'boolean'],
            'status' => ['required', 'in:show,hide'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $faq->update([
            'product_id' => $request->product_id,
            'question' => $request->question,
            'answer' => $request->answer,
            'show_main' => $request->has('show_main') ? 1 : 0,
            'show_product' => $request->has('show_product') ? 1 : 0,
            'status' => $request->status,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()
            ->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    public function updateSort(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array'],
            'items.*.faq_id' => ['required', 'exists:faqs,faq_id'],
            'items.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->items as $item) {
            Faq::where('faq_id', $item['faq_id'])->update([
                'sort_order' => $item['sort_order'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'FAQ order updated successfully.',
        ]);
    }
}
