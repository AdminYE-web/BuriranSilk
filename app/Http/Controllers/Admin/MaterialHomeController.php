<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\MaterialHome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class MaterialHomeController extends Controller
{
    public function index()
    {
        $language = session('admin_product_language', 'pt');
        $baseLanguage = 'pt';

        if ($language === $baseLanguage) {
            $items = MaterialHome::with('material')
                ->where('language', $baseLanguage)
                ->orderBy('sort_order')
                ->orderBy('material_home_id', 'desc')
                ->paginate(10);

            return view('admin.material_homes.index', compact('items', 'language'));
        }

        $baseItems = MaterialHome::with('material')
            ->where('language', $baseLanguage)
            ->orderBy('sort_order')
            ->orderBy('material_home_id', 'desc')
            ->paginate(10);

        $translationKeys = $baseItems
            ->getCollection()
            ->pluck('translation_key')
            ->filter()
            ->values();

        $translatedItems = MaterialHome::with('material')
            ->where('language', $language)
            ->whereIn('translation_key', $translationKeys)
            ->get()
            ->keyBy('translation_key');

        $baseItems->getCollection()->transform(function ($baseItem) use ($translatedItems) {
            $translatedItem = $translatedItems->get($baseItem->translation_key);

            if ($translatedItem) {
                $translatedItem->is_missing_translation = false;
                $translatedItem->base_material_home_id = $baseItem->material_home_id;

                return $translatedItem;
            }

            $baseItem->is_missing_translation = true;
            $baseItem->base_material_home_id = $baseItem->material_home_id;

            return $baseItem;
        });

        $items = $baseItems;

        return view('admin.material_homes.index', compact('items', 'language'));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');

        $materials = Material::where('language', $language)
            ->orderBy('material_name')
            ->get();

        $translationKey = 'mh_'.strtolower(Str::random(12));

        return view('admin.material_homes.create', compact(
            'materials',
            'language',
            'translationKey'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'material_id' => 'nullable|exists:materials,material_id',
            'product_type' => 'required|in:1,2',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'translation_key' => 'nullable|string|max:255',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('material-homes', 'public');
        }

        MaterialHome::create([
            'material_id' => $request->material_id,
              'product_type' => $request->product_type,
            'language' => session('admin_product_language', 'pt'),
            'translation_key' => $request->translation_key ?: 'mh_'.strtolower(Str::random(12)),
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        $this->clearHomeCache();

        return redirect()
            ->route('admin.material-homes.index')
            ->with('success', 'Material Home created successfully.');
    }

    public function edit(MaterialHome $materialHome)
    {
        $language = $materialHome->language ?? session('admin_product_language', 'pt');

        $materials = Material::where('language', $language)
            ->orderBy('material_name')
            ->get();

        return view('admin.material_homes.edit', compact(
            'materialHome',
            'materials',
            'language'
        ));
    }

    public function update(Request $request, MaterialHome $materialHome)
    {
        $request->validate([
            'material_id' => 'nullable|exists:materials,material_id',
            'product_type' => 'required|in:1,2',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'remove_image' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
            'translation_key' => 'nullable|string|max:255',

        ]);

        $imagePath = $materialHome->image_path;

        if ($request->has('remove_image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $imagePath = $request->file('image')->store('material-homes', 'public');
        }

        $materialHome->update([
            'material_id' => $request->material_id,
            'product_type' => $request->product_type,
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $imagePath,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'translation_key' => $request->translation_key ?: $materialHome->translation_key ?: 'mh_'.strtolower(Str::random(12)),
        ]);

        $this->clearHomeCache();

        return redirect()
            ->route('admin.material-homes.index')
            ->with('success', 'Material Home updated successfully.');
    }

    public function duplicateTranslation(MaterialHome $materialHome)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === 'pt') {
            return redirect()
                ->route('admin.material-homes.index')
                ->with('success', 'You are already in PT language.');
        }

        if ($materialHome->language !== 'pt') {
            return redirect()
                ->route('admin.material-homes.index')
                ->with('success', 'Only PT material home can be duplicated as translation.');
        }

        $translationKey = $materialHome->translation_key ?: 'mh_'.strtolower(Str::random(12));

        $existing = MaterialHome::where('translation_key', $translationKey)
            ->where('language', $targetLanguage)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.material-homes.edit', $existing->material_home_id)
                ->with('success', 'Translation already exists.');
        }

        if (! $materialHome->translation_key) {
            $materialHome->update([
                'translation_key' => $translationKey,
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Map material translation
    |--------------------------------------------------------------------------
    | ถ้า Material ของ PT มีตัวแปลภาษาปัจจุบันแล้ว ให้ผูก material_id เป็นตัวแปล
    |--------------------------------------------------------------------------
    */
        $targetMaterialId = $materialHome->material_id;

        if ($materialHome->material) {
            $materialTranslationKey = $materialHome->material->translation_key ?: $materialHome->material->material_code;

            $translatedMaterial = Material::where('translation_key', $materialTranslationKey)
                ->where('language', $targetLanguage)
                   ->where('product_type', $materialHome->product_type)
                ->first();

            if ($translatedMaterial) {
                $targetMaterialId = $translatedMaterial->material_id;
            }
        }

        $newItem = $materialHome->replicate();

        $newItem->material_id = $targetMaterialId;
        $newItem->language = $targetLanguage;
        $newItem->translation_key = $translationKey;
        $newItem->product_type = $materialHome->product_type;
        $newItem->title = $materialHome->title.' ('.strtoupper($targetLanguage).')';
        $newItem->is_active = 0;
        $newItem->created_at = now();
        $newItem->updated_at = now();
        $newItem->save();

        $this->clearHomeCache();

        return redirect()
            ->route('admin.material-homes.edit', $newItem->material_home_id)
            ->with('success', 'Material Home duplicated for '.strtoupper($targetLanguage).'. Please update the translated content.');
    }

    public function destroy(MaterialHome $materialHome)
    {
        if ($materialHome->image_path && Storage::disk('public')->exists($materialHome->image_path)) {
            Storage::disk('public')->delete($materialHome->image_path);
        }

        $materialHome->delete();

        return redirect()
            ->route('admin.material-homes.index')
            ->with('success', 'Material Home deleted successfully.');
    }
    private function clearHomeCache(): void
{
    foreach (['pt', 'en', 'ja'] as $lang) {
        Cache::forget('home_page_data_'.$lang);
    }
}
}
