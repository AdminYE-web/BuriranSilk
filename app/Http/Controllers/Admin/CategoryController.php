<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request)
{
    $search = $request->input('search');
    $language = session('admin_product_language', 'pt');
    $baseLanguage = 'pt';

    $typeTabs = [
        1 => 'Hotstrap',
        2 => 'Hotmobily',
    ];

    $productType = (int) $request->input('product_type', 1);

    if (!array_key_exists($productType, $typeTabs)) {
        $productType = 1;
    }

    if ($language === $baseLanguage) {
        $categories = Category::query()
            ->where('language', $baseLanguage)
            ->where('product_type', $productType)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('category_name', 'like', '%'.$search.'%')
                        ->orWhere('category_code', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('sort_order')
            ->orderBy('category_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.categories.index', compact(
            'categories',
            'search',
            'language',
            'productType',
            'typeTabs'
        ));
    }

    $baseCategories = Category::query()
        ->where('language', $baseLanguage)
        ->where('product_type', $productType)
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('category_name', 'like', '%'.$search.'%')
                    ->orWhere('category_code', 'like', '%'.$search.'%');
            });
        })
        ->orderBy('sort_order')
        ->orderBy('category_id', 'desc')
        ->paginate(15)
        ->withQueryString();

    $translationKeys = $baseCategories
        ->getCollection()
        ->pluck('translation_key')
        ->filter()
        ->values();

    $translatedCategories = Category::query()
        ->where('language', $language)
        ->where('product_type', $productType)
        ->whereIn('translation_key', $translationKeys)
        ->get()
        ->keyBy('translation_key');

    $baseCategories->getCollection()->transform(function ($baseCategory) use ($translatedCategories) {
        $translatedCategory = $translatedCategories->get($baseCategory->translation_key);

        if ($translatedCategory) {
            $translatedCategory->is_missing_translation = false;
            $translatedCategory->base_category_id = $baseCategory->category_id;

            return $translatedCategory;
        }

        $baseCategory->is_missing_translation = true;
        $baseCategory->base_category_id = $baseCategory->category_id;

        return $baseCategory;
    });

    $categories = $baseCategories;

    return view('admin.categories.index', compact(
        'categories',
        'search',
        'language',
        'productType',
        'typeTabs'
    ));
}

   public function create(Request $request)
{
    $language = session('admin_product_language', 'pt');
    $translationKey = 'cat_'.strtolower(Str::random(12));

    $productType = (int) $request->input('product_type', 1);

    if (!in_array($productType, [1, 2], true)) {
        $productType = 1;
    }

    return view('admin.categories.create', compact(
        'language',
        'translationKey',
        'productType'
    ));
}

    public function store(Request $request)
    {
        $request->validate([
            'category_code' => 'nullable|string|max:100',
            'category_name' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'translation_key' => 'nullable|string|max:255',
            'product_type' => 'required|in:1,2',
        ]);

        $imagePath = null;

        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')
                ->store('categories', 'public');
        }

        Category::create([
            'category_code' => $request->category_code,
            'category_name' => $request->category_name,
            'image_path' => $imagePath,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'language' => session('admin_product_language', 'pt'),
            'translation_key' => $request->translation_key ?: 'cat_'.strtolower(Str::random(12)),
            'product_type' => $request->product_type,
        ]);

        $this->clearProductListSharedCache();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'เพิ่ม Category เรียบร้อยแล้ว');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_code' => 'nullable|string|max:100',
            'category_name' => 'required|string|max:255',
            'image_path' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'translation_key' => 'nullable|string|max:255',
            'product_type' => 'required|in:1,2',
        ]);

        $imagePath = $category->image_path;

        if ($request->hasFile('image_path')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }

            $imagePath = $request->file('image_path')
                ->store('categories', 'public');
        }

        $category->update([
            'category_code' => $request->category_code,
            'category_name' => $request->category_name,
            'product_type' => $request->product_type,
            'image_path' => $imagePath,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'translation_key' => $request->translation_key ?: $category->translation_key ?: 'cat_'.strtolower(Str::random(12)),
        ]);

      $this->clearProductListSharedCache();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'แก้ไข Category เรียบร้อยแล้ว');
    }

    public function destroy(Category $category)
    {
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'ลบ Category เรียบร้อยแล้ว');
    }

    public function duplicateTranslation(Category $category)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === 'pt') {
            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'You are already in PT language.');
        }

        if ($category->language !== 'pt') {
            return redirect()
                ->route('admin.categories.index')
                ->with('success', 'Only PT category can be duplicated as translation.');
        }

        $translationKey = $category->translation_key ?: $category->category_code ?: 'cat_'.strtolower(Str::random(12));

        $existing = Category::where('translation_key', $translationKey)
            ->where('language', $targetLanguage)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.categories.edit', $existing->category_id)
                ->with('success', 'Translation already exists.');
        }

        if (! $category->translation_key) {
            $category->update([
                'translation_key' => $translationKey,
            ]);
        }

        $newCategory = $category->replicate();

        $newCategory->language = $targetLanguage;
        $newCategory->translation_key = $translationKey;
        $newCategory->product_type = $category->product_type;
        $newCategory->category_code = $category->category_code
            ? $category->category_code.'-'.$targetLanguage
            : null;
        $newCategory->category_name = $category->category_name.' ('.strtoupper($targetLanguage).')';
        $newCategory->is_active = 0;
        $newCategory->created_at = now();
        $newCategory->updated_at = now();
        $newCategory->save();

       $this->clearProductListSharedCache();

        return redirect()
            ->route('admin.categories.edit', $newCategory->category_id)
            ->with('success', 'Category duplicated for '.strtoupper($targetLanguage).'. Please update the translated content.');
    }
    public function updateSort(Request $request)
{
    $request->validate([
        'orders' => 'required|array',
        'orders.*.id' => 'required|integer|exists:categories,category_id',
        'orders.*.sort_order' => 'required|integer|min:0',
    ]);

    foreach ($request->orders as $item) {
        Category::where('category_id', $item['id'])
            ->update([
                'sort_order' => $item['sort_order'],
            ]);
    }

  $this->clearProductListSharedCache();
    return response()->json([
        'success' => true,
    ]);
}
private function clearProductListSharedCache(): void
{
    foreach (['pt', 'ja', 'en'] as $lang) {
        foreach ([1, 2] as $type) {
            Cache::forget("product_list_shared_components_{$lang}_type_{$type}");
        }
    }
}
}
