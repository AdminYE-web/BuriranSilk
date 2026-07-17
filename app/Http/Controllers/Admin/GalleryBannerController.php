<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryBannerController extends Controller
{
    public function index()
    {
        $banners = GalleryBanner::orderBy('sort_order')
            ->orderBy('gallery_banner_id', 'desc')
            ->paginate(15);

        return view('admin.gallery_banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.gallery_banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'image_pc' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_mobile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePcPath = null;
        $imageMobilePath = null;

        if ($request->hasFile('image_pc')) {
            $imagePcPath = $request->file('image_pc')->store('gallery-banners/pc', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            $imageMobilePath = $request->file('image_mobile')->store('gallery-banners/mobile', 'public');
        }

        GalleryBanner::create([
            'title' => $request->title,
            'link_url' => $request->link_url,
            'image_pc' => $imagePcPath,
            'image_mobile' => $imageMobilePath,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.gallery-banners.index')
            ->with('success', 'Gallery banner created successfully.');
    }

    public function edit(GalleryBanner $galleryBanner)
    {
        return view('admin.gallery_banners.edit', compact('galleryBanner'));
    }

    public function update(Request $request, GalleryBanner $galleryBanner)
    {
        $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'link_url' => ['nullable', 'string', 'max:255'],
            'image_pc' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'image_mobile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $imagePcPath = $galleryBanner->image_pc;
        $imageMobilePath = $galleryBanner->image_mobile;

        if ($request->hasFile('image_pc')) {
            if ($imagePcPath && Storage::disk('public')->exists($imagePcPath)) {
                Storage::disk('public')->delete($imagePcPath);
            }

            $imagePcPath = $request->file('image_pc')->store('gallery-banners/pc', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            if ($imageMobilePath && Storage::disk('public')->exists($imageMobilePath)) {
                Storage::disk('public')->delete($imageMobilePath);
            }

            $imageMobilePath = $request->file('image_mobile')->store('gallery-banners/mobile', 'public');
        }

        $galleryBanner->update([
            'title' => $request->title,
            'link_url' => $request->link_url,
            'image_pc' => $imagePcPath,
            'image_mobile' => $imageMobilePath,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.gallery-banners.edit', $galleryBanner->gallery_banner_id)
            ->with('success', 'Gallery banner updated successfully.');
    }

    public function destroy(GalleryBanner $galleryBanner)
    {
        if ($galleryBanner->image_pc && Storage::disk('public')->exists($galleryBanner->image_pc)) {
            Storage::disk('public')->delete($galleryBanner->image_pc);
        }

        if ($galleryBanner->image_mobile && Storage::disk('public')->exists($galleryBanner->image_mobile)) {
            Storage::disk('public')->delete($galleryBanner->image_mobile);
        }

        $galleryBanner->delete();

        return redirect()
            ->route('admin.gallery-banners.index')
            ->with('success', 'Gallery banner deleted successfully.');
    }
}
