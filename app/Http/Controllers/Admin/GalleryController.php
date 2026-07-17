<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\GalleryImage;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $language = session('admin_product_language', 'pt');
        $baseLanguage = 'pt';

        if ($language === $baseLanguage) {
            $galleries = Gallery::with(['category', 'material', 'images'])
                ->where('language', $baseLanguage)
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', '%'.$search.'%')
                            ->orWhere('purpose', 'like', '%'.$search.'%')
                            ->orWhereHas('category', function ($categoryQuery) use ($search) {
                                $categoryQuery->where('category_name', 'like', '%'.$search.'%');
                            })
                            ->orWhereHas('material', function ($materialQuery) use ($search) {
                                $materialQuery->where('material_name', 'like', '%'.$search.'%');
                            });
                    });
                })
                ->orderBy('sort_order')
                ->orderBy('gallery_id', 'desc')
                ->paginate(15)
                ->withQueryString();

            return view('admin.galleries.index', compact('galleries', 'search', 'language'));
        }

        $baseGalleries = Gallery::with(['category', 'material', 'images'])
            ->where('language', $baseLanguage)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%'.$search.'%')
                        ->orWhere('purpose', 'like', '%'.$search.'%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('category_name', 'like', '%'.$search.'%');
                        })
                        ->orWhereHas('material', function ($materialQuery) use ($search) {
                            $materialQuery->where('material_name', 'like', '%'.$search.'%');
                        });
                });
            })
            ->orderBy('sort_order')
            ->orderBy('gallery_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $translationKeys = $baseGalleries
            ->getCollection()
            ->pluck('translation_key')
            ->filter()
            ->values();

        $translatedGalleries = Gallery::with(['category', 'material', 'images'])
            ->where('language', $language)
            ->whereIn('translation_key', $translationKeys)
            ->get()
            ->keyBy('translation_key');

        $baseGalleries->getCollection()->transform(function ($baseGallery) use ($translatedGalleries) {
            $translatedGallery = $translatedGalleries->get($baseGallery->translation_key);

            if ($translatedGallery) {
                $translatedGallery->is_missing_translation = false;
                $translatedGallery->base_gallery_id = $baseGallery->gallery_id;

                return $translatedGallery;
            }

            $baseGallery->is_missing_translation = true;
            $baseGallery->base_gallery_id = $baseGallery->gallery_id;

            return $baseGallery;
        });

        $galleries = $baseGalleries;

        return view('admin.galleries.index', compact('galleries', 'search', 'language'));
    }

    public function create()
    {
        $language = session('admin_product_language', 'pt');

        $categories = Category::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('category_name')
            ->get();

        $materials = Material::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('material_name')
            ->get();

        $translationKey = 'gal_'.strtolower(Str::random(12));

        return view('admin.galleries.create', compact(
            'categories',
            'materials',
            'language',
            'translationKey'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,category_id'],
            'material_id' => ['nullable', 'exists:materials,material_id'],
            'purpose' => ['nullable', 'string'],
            'gallery_date' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'product_link' => ['nullable', 'string', 'max:255'],
            'translation_key' => ['nullable', 'string', 'max:255'],
        ]);

        $coverImagePath = null;

        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('galleries/covers', 'public');
        }

        $gallery = Gallery::create([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'material_id' => $request->material_id,
            'language' => session('admin_product_language', 'pt'),
            'translation_key' => $request->translation_key ?: 'gal_'.strtolower(Str::random(12)),
            'purpose' => $request->purpose,
            'gallery_date' => $request->gallery_date,
            'cover_image' => $coverImagePath,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'product_link' => $request->product_link,
        ]);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('galleries/images', 'public');

                GalleryImage::create([
                    'gallery_id' => $gallery->gallery_id,
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'sort_order' => $index,
                ]);
            }
        }

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Gallery created successfully.');
    }

    public function edit(Gallery $gallery)
    {
        $gallery->load(['images', 'category', 'material']);

        $language = $gallery->language ?? session('admin_product_language', 'pt');

        $categories = Category::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('category_name')
            ->get();

        $materials = Material::where('language', $language)
            ->where('is_active', 1)
            ->orderBy('material_name')
            ->get();

        return view('admin.galleries.edit', compact(
            'gallery',
            'categories',
            'materials',
            'language'
        ));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:categories,category_id'],
            'material_id' => ['nullable', 'exists:materials,material_id'],
            'purpose' => ['nullable', 'string'],
            'gallery_date' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'gallery_images' => ['nullable', 'array'],
            'gallery_images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
            'delete_gallery_images' => ['nullable', 'array'],
            'delete_gallery_images.*' => ['integer'],
            'remove_cover_image' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer'],
            'product_link' => ['nullable', 'string', 'max:255'],
            'translation_key' => ['nullable', 'string', 'max:255'],
        ]);

        $coverImagePath = $gallery->cover_image;

        if ($request->has('remove_cover_image')) {
            if ($coverImagePath && Storage::disk('public')->exists($coverImagePath)) {
                Storage::disk('public')->delete($coverImagePath);
            }

            $coverImagePath = null;
        }

        if ($request->hasFile('cover_image')) {
            if ($coverImagePath && Storage::disk('public')->exists($coverImagePath)) {
                Storage::disk('public')->delete($coverImagePath);
            }

            $coverImagePath = $request->file('cover_image')->store('galleries/covers', 'public');
        }

        $gallery->update([
            'title' => $request->title,
            'category_id' => $request->category_id,
            'material_id' => $request->material_id,
            'translation_key' => $request->translation_key ?: $gallery->translation_key ?: 'gal_'.strtolower(Str::random(12)),
            'purpose' => $request->purpose,
            'gallery_date' => $request->gallery_date,
            'cover_image' => $coverImagePath,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
            'product_link' => $request->product_link,
        ]);

        if ($request->filled('delete_gallery_images')) {
            $imagesToDelete = GalleryImage::where('gallery_id', $gallery->gallery_id)
                ->whereIn('gallery_image_id', $request->delete_gallery_images)
                ->get();

            foreach ($imagesToDelete as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }

                $image->delete();
            }
        }

        if ($request->hasFile('gallery_images')) {
            $startSort = GalleryImage::where('gallery_id', $gallery->gallery_id)->max('sort_order') ?? 0;

            foreach ($request->file('gallery_images') as $index => $image) {
                $path = $image->store('galleries/images', 'public');

                GalleryImage::create([
                    'gallery_id' => $gallery->gallery_id,
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'sort_order' => $startSort + $index + 1,
                ]);
            }
        }

        return redirect()
            ->route('admin.galleries.edit', $gallery->gallery_id)
            ->with('success', 'Gallery updated successfully.');
    }

    public function duplicateTranslation(Gallery $gallery)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === 'pt') {
            return redirect()
                ->route('admin.galleries.index')
                ->with('success', 'You are already in PT language.');
        }

        if ($gallery->language !== 'pt') {
            return redirect()
                ->route('admin.galleries.index')
                ->with('success', 'Only PT gallery can be duplicated as translation.');
        }

        $translationKey = $gallery->translation_key ?: 'gal_'.strtolower(Str::random(12));

        $existing = Gallery::where('translation_key', $translationKey)
            ->where('language', $targetLanguage)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.galleries.edit', $existing->gallery_id)
                ->with('success', 'Translation already exists.');
        }

        if (! $gallery->translation_key) {
            $gallery->update([
                'translation_key' => $translationKey,
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Map category / material translation
    |--------------------------------------------------------------------------
    */
        $targetCategoryId = $gallery->category_id;

        if ($gallery->category) {
            $categoryTranslationKey = $gallery->category->translation_key ?: $gallery->category->category_code;

            $translatedCategory = Category::where('translation_key', $categoryTranslationKey)
                ->where('language', $targetLanguage)
                ->first();

            if ($translatedCategory) {
                $targetCategoryId = $translatedCategory->category_id;
            }
        }

        $targetMaterialId = $gallery->material_id;

        if ($gallery->material) {
            $materialTranslationKey = $gallery->material->translation_key ?: $gallery->material->material_code;

            $translatedMaterial = Material::where('translation_key', $materialTranslationKey)
                ->where('language', $targetLanguage)
                ->first();

            if ($translatedMaterial) {
                $targetMaterialId = $translatedMaterial->material_id;
            }
        }

        $gallery->load('images');

        $newGallery = $gallery->replicate();

        $newGallery->category_id = $targetCategoryId;
        $newGallery->material_id = $targetMaterialId;
        $newGallery->language = $targetLanguage;
        $newGallery->translation_key = $translationKey;
        $newGallery->title = $gallery->title.' ('.strtoupper($targetLanguage).')';
        $newGallery->is_active = 0;
        $newGallery->created_at = now();
        $newGallery->updated_at = now();
        $newGallery->save();

        foreach ($gallery->images as $image) {
            GalleryImage::create([
                'gallery_id' => $newGallery->gallery_id,
                'image_path' => $image->image_path,
                'original_name' => $image->original_name,
                'sort_order' => $image->sort_order,
            ]);
        }

        return redirect()
            ->route('admin.galleries.edit', $newGallery->gallery_id)
            ->with('success', 'Gallery duplicated for '.strtoupper($targetLanguage).'. Please update the translated content.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->cover_image && Storage::disk('public')->exists($gallery->cover_image)) {
            Storage::disk('public')->delete($gallery->cover_image);
        }

        foreach ($gallery->images as $image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }

        $gallery->delete();

        return redirect()
            ->route('admin.galleries.index')
            ->with('success', 'Gallery deleted successfully.');
    }
}
