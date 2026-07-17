<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
   public function index(Request $request)
{
    $search = $request->input('search');
    $category = $request->input('category');
    $dateFrom = $request->input('date_from');
    $dateTo = $request->input('date_to');
    $status = $request->input('status');

    $language = session('admin_product_language', 'pt');
    $baseLanguage = 'pt';

    $categories = Article::query()
        ->where('language', $baseLanguage)
        ->whereNotNull('category')
        ->where('category', '<>', '')
        ->select('category')
        ->distinct()
        ->orderBy('category')
        ->pluck('category');

    $applyBaseFilters = function ($query, bool $includeStatus = true) use (
        $search,
        $category,
        $dateFrom,
        $dateTo,
        $status
    ) {
        $query
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', '%' . $search . '%')
                        ->orWhere('category', 'like', '%' . $search . '%');
                });
            })
            ->when($category, function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('article_date', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('article_date', '<=', $dateTo);
            });

        if ($includeStatus && $status !== null && $status !== '') {
            $query->where('is_active', (int) $status);
        }
    };

    if ($language === $baseLanguage) {
        $query = Article::query()
            ->where('language', $baseLanguage);

        $applyBaseFilters($query, true);

        $articles = $query
            ->orderBy('article_date', 'desc')
            ->orderBy('article_id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.articles.index', compact(
            'articles',
            'search',
            'category',
            'dateFrom',
            'dateTo',
            'status',
            'language',
            'categories'
        ));
    }

    $baseQuery = Article::query()
        ->where('language', $baseLanguage);

    $applyBaseFilters($baseQuery, false);

    if ($status !== null && $status !== '') {
        $translationKeysByStatus = Article::query()
            ->where('language', $language)
            ->where('is_active', (int) $status)
            ->whereNotNull('translation_key')
            ->pluck('translation_key');

        $baseQuery->whereIn('translation_key', $translationKeysByStatus);
    }

    $baseArticles = $baseQuery
        ->orderBy('article_date', 'desc')
        ->orderBy('article_id', 'desc')
        ->paginate(15)
        ->withQueryString();

    $translationKeys = $baseArticles
        ->getCollection()
        ->pluck('translation_key')
        ->filter()
        ->values();

    $translatedArticles = Article::query()
        ->where('language', $language)
        ->whereIn('translation_key', $translationKeys)
        ->get()
        ->keyBy('translation_key');

    $baseArticles->getCollection()->transform(function ($baseArticle) use ($translatedArticles) {
        $translatedArticle = $translatedArticles->get($baseArticle->translation_key);

        if ($translatedArticle) {
            $translatedArticle->is_missing_translation = false;
            $translatedArticle->base_article_id = $baseArticle->article_id;

            return $translatedArticle;
        }

        $baseArticle->is_missing_translation = true;
        $baseArticle->base_article_id = $baseArticle->article_id;

        return $baseArticle;
    });

    $articles = $baseArticles;

    return view('admin.articles.index', compact(
        'articles',
        'search',
        'category',
        'dateFrom',
        'dateTo',
        'status',
        'language',
        'categories'
    ));
}
    public function create()
    {
        $language = session('admin_product_language', 'pt');
        $translationKey = 'art_'.strtolower(Str::random(12));

        return view('admin.articles.create', compact('language', 'translationKey'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'article_date' => ['nullable', 'date'],
            'detail' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => ['required', 'integer', 'in:1,3'],
            'description' => ['nullable', 'string'],
            'language' => ['nullable', 'string', 'max:20'],
            'translation_key' => ['nullable', 'string', 'max:255'],

        ]);

        $coverPath = null;

        if ($request->hasFile('cover_image')) {
            $coverPath = $request->file('cover_image')->store('articles/cover', 'public');
        }

        $language = session('admin_product_language', 'pt');

        Article::create([
            'title' => $request->title,
            'category' => $request->category,
            'article_date' => $request->article_date,
            'detail' => $request->detail,
            'cover_image' => $coverPath,
           'is_active' => (int) $request->input('is_active', 1),
            'description' => $request->description,
            'language' => $language,
            'translation_key' => $request->translation_key ?: 'art_'.strtolower(Str::random(12)),
        ]);

        $this->forgetHomeCache();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'article_date' => ['nullable', 'date'],
            'detail' => ['nullable', 'string'],
            'cover_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'is_active' => ['required', 'integer', 'in:1,3'],
            'description' => ['nullable', 'string'],
            'translation_key' => ['nullable', 'string', 'max:255'],
        ]);

        $coverPath = $article->cover_image;

        if ($request->hasFile('cover_image')) {
            if ($coverPath && Storage::disk('public')->exists($coverPath)) {
                Storage::disk('public')->delete($coverPath);
            }

            $coverPath = $request->file('cover_image')->store('articles/cover', 'public');
        }

        $article->update([
            'title' => $request->title,
            'category' => $request->category,
            'article_date' => $request->article_date,
            'detail' => $request->detail,
            'cover_image' => $coverPath,
           'is_active' => (int) $request->input('is_active', 1),
            'description' => $request->description,
            'translation_key' => $request->translation_key ?: $article->translation_key ?: 'art_'.strtolower(Str::random(12)),
        ]);

        $this->forgetHomeCache();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article updated successfully.');
    }

    public function duplicateTranslation(Article $article)
    {
        $targetLanguage = session('admin_product_language', 'pt');

        if ($targetLanguage === 'pt') {
            return redirect()
                ->route('admin.articles.index')
                ->with('success', 'You are already in PT language.');
        }

        if ($article->language !== 'pt') {
            return redirect()
                ->route('admin.articles.index')
                ->with('success', 'Only PT article can be duplicated as translation.');
        }

        $translationKey = $article->translation_key ?: 'art_'.strtolower(Str::random(12));

        $existing = Article::where('translation_key', $translationKey)
            ->where('language', $targetLanguage)
            ->first();

        if ($existing) {
            return redirect()
                ->route('admin.articles.edit', $existing->article_id)
                ->with('success', 'Translation already exists.');
        }

        if (! $article->translation_key) {
            $article->update([
                'translation_key' => $translationKey,
            ]);
        }

        $newArticle = $article->replicate();

        $newArticle->language = $targetLanguage;
        $newArticle->translation_key = $translationKey;
        $newArticle->title = $article->title.' ('.strtoupper($targetLanguage).')';
       $newArticle->is_active = 3;
        $newArticle->created_at = now();
        $newArticle->updated_at = now();
        $newArticle->save();

        $this->forgetHomeCache();

        return redirect()
            ->route('admin.articles.edit', $newArticle->article_id)
            ->with('success', 'Article duplicated for '.strtoupper($targetLanguage).'. Please update the translated content.');
    }

    public function destroy(Article $article)
    {
        if ($article->cover_image && Storage::disk('public')->exists($article->cover_image)) {
            Storage::disk('public')->delete($article->cover_image);
        }

        $article->delete();

        $this->forgetHomeCache();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    public function uploadEditorImage(Request $request)
    {
        $request->validate([
            'upload' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096'],
        ]);

        $path = $request->file('upload')->store('articles/editor', 'public');

        return response()->json([
            'url' => asset('storage/'.$path),
        ]);
    }

    private function forgetHomeCache(): void
    {
        foreach (['pt', 'ja', 'en'] as $language) {
            Cache::forget('home_page_data_'.$language);
        }

        Cache::forget('home_page_data');
    }
}
