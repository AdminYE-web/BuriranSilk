<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class MaterialController extends Controller
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
        $materials = Material::query()
            ->where('language', $baseLanguage)
            ->where('product_type', $productType)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('material_name', 'like', '%'.$search.'%')
                        ->orWhere('material_code', 'like', '%'.$search.'%');
                });
            })
            ->orderBy('material_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.materials.index', compact(
            'materials',
            'search',
            'language',
            'productType',
            'typeTabs'
        ));
    }

    $baseMaterials = Material::query()
        ->where('language', $baseLanguage)
        ->where('product_type', $productType)
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('material_name', 'like', '%'.$search.'%')
                    ->orWhere('material_code', 'like', '%'.$search.'%');
            });
        })
        ->orderBy('material_id', 'desc')
        ->paginate(15)
        ->withQueryString();

    $translationKeys = $baseMaterials
        ->getCollection()
        ->pluck('translation_key')
        ->filter()
        ->values();

    $translatedMaterials = Material::query()
        ->where('language', $language)
        ->where('product_type', $productType)
        ->whereIn('translation_key', $translationKeys)
        ->get()
        ->keyBy('translation_key');

    $baseMaterials->getCollection()->transform(function ($baseMaterial) use ($translatedMaterials) {
        $translatedMaterial = $translatedMaterials->get($baseMaterial->translation_key);

        if ($translatedMaterial) {
            $translatedMaterial->is_missing_translation = false;
            $translatedMaterial->base_material_id = $baseMaterial->material_id;

            return $translatedMaterial;
        }

        $baseMaterial->is_missing_translation = true;
        $baseMaterial->base_material_id = $baseMaterial->material_id;

        return $baseMaterial;
    });

    $materials = $baseMaterials;

    return view('admin.materials.index', compact(
        'materials',
        'search',
        'language',
        'productType',
        'typeTabs'
    ));
}

  public function create(Request $request)
{
    $language = session('admin_product_language', 'pt');
    $translationKey = 'mat_'.strtolower(Str::random(12));

    $productType = (int) $request->input('product_type', 1);

    if (!in_array($productType, [1, 2], true)) {
        $productType = 1;
    }

    return view('admin.materials.create', compact(
        'language',
        'translationKey',
        'productType'
    ));
}

    public function store(Request $request)
    {
        $request->validate([
            'material_code' => 'nullable|string|max:100',
            'material_name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'translation_key' => 'nullable|string|max:255',
            'product_type' => 'required|in:1,2',
        ]);

        Material::create([
            'material_code' => $request->material_code,
            'translation_key' => $request->translation_key ?: 'mat_'.strtolower(Str::random(12)),
            'material_name' => $request->material_name,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'language' => session('admin_product_language', 'pt'),
            'product_type' => $request->product_type,
        ]);

        Cache::forget('home_page_data');
        Cache::forget('product_list_shared_components_pt');
        Cache::forget('product_list_shared_components_ja');
        Cache::forget('product_list_shared_components_en');
        Cache::forget('product_list_shared_components');

      return redirect()
    ->route('admin.materials.index', ['product_type' => $request->product_type])
    ->with('success', 'เพิ่ม Material เรียบร้อยแล้ว');
    }

    public function edit(Material $material)
    {
        return view('admin.materials.edit', compact('material'));
    }

    public function update(Request $request, Material $material)
    {
        $request->validate([
            'material_code' => 'nullable|string|max:100',
            'material_name' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
            'translation_key' => 'nullable|string|max:255',
            'product_type' => 'required|in:1,2',
        ]);

        $material->update([
            'material_code' => $request->material_code,
            'translation_key' => $request->translation_key ?: $material->translation_key ?: 'mat_'.strtolower(Str::random(12)),
            'material_name' => $request->material_name,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'product_type' => $request->product_type,
        ]);

        Cache::forget('home_page_data');
        Cache::forget('product_list_shared_components_pt');
        Cache::forget('product_list_shared_components_ja');
        Cache::forget('product_list_shared_components_en');
        Cache::forget('product_list_shared_components');

        return redirect()
    ->route('admin.materials.index', ['product_type' => $request->product_type])
    ->with('success', 'แก้ไข Material เรียบร้อยแล้ว');
    }

    public function destroy(Material $material)
    {
        $material->delete();

        Cache::forget('home_page_data');
        Cache::forget('product_list_shared_components_pt');
        Cache::forget('product_list_shared_components_ja');
        Cache::forget('product_list_shared_components_en');
        Cache::forget('product_list_shared_components');

        return redirect()
            ->route('admin.materials.index')
            ->with('success', 'ลบ Material เรียบร้อยแล้ว');
    }

    public function duplicateTranslation(Material $material)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === 'pt') {
            return redirect()
                ->route('admin.materials.index')
                ->with('success', 'You are already in PT language.');
        }

        if ($material->language !== 'pt') {
            return redirect()
                ->route('admin.materials.index')
                ->with('success', 'Only PT material can be duplicated as translation.');
        }

        $translationKey = $material->translation_key ?: $material->material_code ?: 'mat_'.strtolower(Str::random(12));

        $existing = Material::where('translation_key', $translationKey)
            ->where('language', $targetLanguage)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.materials.edit', $existing->material_id)
                ->with('success', 'Translation already exists.');
        }

        if (! $material->translation_key) {
            $material->update([
                'translation_key' => $translationKey,
            ]);
        }

        $newMaterial = $material->replicate();

        $newMaterial->language = $targetLanguage;
        $newMaterial->product_type = $material->product_type;
        $newMaterial->translation_key = $translationKey;
        $newMaterial->material_code = $material->material_code
            ? $material->material_code.'-'.$targetLanguage
            : null;
        $newMaterial->material_name = $material->material_name.' ('.strtoupper($targetLanguage).')';
        $newMaterial->is_active = 0;
        $newMaterial->created_at = now();
        $newMaterial->updated_at = now();
        $newMaterial->save();

        Cache::forget('home_page_data');
        Cache::forget('product_list_shared_components_pt');
        Cache::forget('product_list_shared_components_ja');
        Cache::forget('product_list_shared_components_en');
        Cache::forget('product_list_shared_components');

        return redirect()
            ->route('admin.materials.edit', $newMaterial->material_id)
            ->with('success', 'Material duplicated for '.strtoupper($targetLanguage).'. Please update the translated content.');
    }
}
