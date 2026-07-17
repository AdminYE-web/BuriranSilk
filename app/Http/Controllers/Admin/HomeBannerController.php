<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HomeBannerController extends Controller
{
    public function index()
    {
        $banners = HomeBanner::orderBy('sort_order')
            ->orderBy('home_banner_id', 'desc')
            ->paginate(10);

        return view('admin.home_banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.home_banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:500',
            'image_pc' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $imagePcPath = null;
        $imageMobilePath = null;

        if ($request->hasFile('image_pc')) {
            $imagePcPath = $request->file('image_pc')->store('home-banners/pc', 'public');
        }

        if ($request->hasFile('image_mobile')) {
            $imageMobilePath = $request->file('image_mobile')->store('home-banners/mobile', 'public');
        }

        HomeBanner::create([
            'title' => $request->title,
            'link_url' => $request->link_url,
            'image_pc' => $imagePcPath,
            'image_mobile' => $imageMobilePath,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ja', 'pt'] as $lang) {
    Cache::forget('home_page_data_'.$lang);
}

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Home banner created successfully.');
    }

    public function edit(HomeBanner $homeBanner)
    {
        return view('admin.home_banners.edit', compact('homeBanner'));
    }

    public function update(Request $request, HomeBanner $homeBanner)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'link_url' => 'nullable|string|max:500',
            'image_pc' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:4096',
            'remove_image_pc' => 'nullable|boolean',
            'remove_image_mobile' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $imagePcPath = $homeBanner->image_pc;
        $imageMobilePath = $homeBanner->image_mobile;

        // remove pc image
        if ($request->has('remove_image_pc')) {
            if ($imagePcPath && Storage::disk('public')->exists($imagePcPath)) {
                Storage::disk('public')->delete($imagePcPath);
            }

            $imagePcPath = null;
        }

        // remove mobile image
        if ($request->has('remove_image_mobile')) {
            if ($imageMobilePath && Storage::disk('public')->exists($imageMobilePath)) {
                Storage::disk('public')->delete($imageMobilePath);
            }

            $imageMobilePath = null;
        }

        // upload new pc image
        if ($request->hasFile('image_pc')) {
            if ($imagePcPath && Storage::disk('public')->exists($imagePcPath)) {
                Storage::disk('public')->delete($imagePcPath);
            }

            $imagePcPath = $request->file('image_pc')->store('home-banners/pc', 'public');
        }

        // upload new mobile image
        if ($request->hasFile('image_mobile')) {
            if ($imageMobilePath && Storage::disk('public')->exists($imageMobilePath)) {
                Storage::disk('public')->delete($imageMobilePath);
            }

            $imageMobilePath = $request->file('image_mobile')->store('home-banners/mobile', 'public');
        }

        $homeBanner->update([
            'title' => $request->title,
            'link_url' => $request->link_url,
            'image_pc' => $imagePcPath,
            'image_mobile' => $imageMobilePath,
            'is_active' => $request->has('is_active') ? 1 : 0,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        foreach (['en', 'ja', 'pt'] as $lang) {
    Cache::forget('home_page_data_'.$lang);
}

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Home banner updated successfully.');
    }

    public function destroy(HomeBanner $homeBanner)
    {
        foreach (['en', 'ja', 'pt'] as $lang) {
    Cache::forget('home_page_data_'.$lang);
}

        if ($homeBanner->image_pc && Storage::disk('public')->exists($homeBanner->image_pc)) {
            Storage::disk('public')->delete($homeBanner->image_pc);
        }

        if ($homeBanner->image_mobile && Storage::disk('public')->exists($homeBanner->image_mobile)) {
            Storage::disk('public')->delete($homeBanner->image_mobile);
        }

        $homeBanner->delete();

        return redirect()
            ->route('admin.home-banners.index')
            ->with('success', 'Home banner deleted successfully.');
    }
}
