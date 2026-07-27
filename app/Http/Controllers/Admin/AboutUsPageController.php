<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUsPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AboutUsPageController extends Controller
{
    private const IMAGE_FIELDS = [
        'banner_desktop' => 'about-us/banner/desktop',
        'banner_mobile' => 'about-us/banner/mobile',
        'intro_image' => 'about-us/intro',
    ];

    public function edit(): View
    {
        $aboutUsPage = AboutUsPage::query()->firstOrNew();

        return view('admin.about_us.edit', compact('aboutUsPage'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'banner_desktop' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'banner_mobile' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'intro_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'intro_content' => ['nullable', 'string'],
            'detail_content' => ['nullable', 'string'],
            'remove_banner_desktop' => ['nullable', 'boolean'],
            'remove_banner_mobile' => ['nullable', 'boolean'],
            'remove_intro_image' => ['nullable', 'boolean'],
        ]);

        $aboutUsPage = AboutUsPage::query()->firstOrNew();
        $imagePaths = [];

        foreach (self::IMAGE_FIELDS as $field => $directory) {
            $currentPath = $aboutUsPage->{$field};

            if ($request->boolean('remove_'.$field)) {
                $this->deletePublicFile($currentPath);
                $currentPath = null;
            }

            if ($request->hasFile($field)) {
                $newPath = $request->file($field)->store($directory, 'public');
                $this->deletePublicFile($currentPath);
                $currentPath = $newPath;
            }

            $imagePaths[$field] = $currentPath;
        }

        $aboutUsPage->fill(array_merge($imagePaths, [
            'intro_content' => $validated['intro_content'] ?? null,
            'detail_content' => $validated['detail_content'] ?? null,
        ]));
        $aboutUsPage->save();

        return redirect()
            ->route('admin.about-us.edit')
            ->with('success', 'About Us page updated successfully.');
    }

    public function uploadEditorImage(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'upload' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:8192'],
        ]);

        $path = $validated['upload']->store('about-us/editor', 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
        ]);
    }

    private function deletePublicFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
