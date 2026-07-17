<div class="article-form-card">
    <div class="article-form-grid">
        <div class="article-form-group">
            <label>{{ request()->cookie('dev') == '1' ? 'Title' : '記事のタイトル' }} <span>*</span></label>
            <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required>
            @error('title')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        <div class="article-form-group" style="display: none">
            <label>{{ request()->cookie('dev') == '1' ? 'Translation Key' : '記事のタイトル' }}</label>
            <input type="text" name="translation_key"
                value="{{ old('translation_key', $article->translation_key ?? ($translationKey ?? '')) }}"
                placeholder="เช่น art_xxxxxxxx">
            <small>{{ request()->cookie('dev') == '1' ? 'Use the same key for the same article in multiple languages' : '同じ記事を多言語で表示する場合は同じkeyを使用します' }}</small>

            @error('translation_key')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="article-form-group">
            <label>{{ request()->cookie('dev') == '1' ? 'Category' : '記事のカテゴリー' }}</label>
            <input type="text" name="category" value="{{ old('category', $article->category ?? '') }}">
            @error('category')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="article-form-group">
            <label>{{ request()->cookie('dev') == '1' ? 'Article Date' : '記事の日付' }}</label>
            <input type="date" name="article_date"
                value="{{ old('article_date', isset($article) && $article->article_date ? \Carbon\Carbon::parse($article->article_date)->format('Y-m-d') : '') }}">
            @error('article_date')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="article-form-group">
            <label>{{ request()->cookie('dev') == '1' ? 'Cover Image' : 'カバー画像' }}</label>
            <input type="file" name="cover_image" accept="image/*">

            @if (!empty($article->cover_image))
                <div class="article-cover-preview">
                    <img src="{{ asset('storage/' . $article->cover_image) }}" alt="">
                </div>
            @endif

            @error('cover_image')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
        <div class="article-form-group article-form-group-full">
            <label>{{ request()->cookie('dev') == '1' ? 'Description' : '記事の説明' }}</label>
            <textarea name="description" rows="4"
                placeholder="{{ request()->cookie('dev') == '1' ? 'Short description shown under cover image...' : 'カバー画像のすぐ下に表示される短い説明' }}">{{ old('description', $article->description ?? '') }}</textarea>

            @error('description')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="article-form-group">
        <label>{{ request()->cookie('dev') == '1' ? 'Detail' : '記事の詳細' }}</label>
        <div class="article-editor-wrap">
            <textarea name="detail" id="article_detail">{{ old('detail', $article->detail ?? '') }}</textarea>
        </div>
        @error('detail')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>

    <div class="article-form-group" style="max-width: 20%">
        <label>{{ request()->cookie('dev') == '1' ? 'Status' : '記事の状態' }}</label>

        <select name="is_active">
            <option value="1" {{ (string) old('is_active', $article->is_active ?? 3) === '1' ? 'selected' : '' }}>
                {{ request()->cookie('dev') == '1' ? 'Public' : '公開' }}
            </option>

            <option value="3" {{ (string) old('is_active', $article->is_active ?? 3) === '3' ? 'selected' : '' }}>
                {{ request()->cookie('dev') == '1' ? 'Draft' : '下書き' }}
            </option>
        </select>

        @error('is_active')
            <div class="error-text">{{ $message }}</div>
        @enderror
    </div>
</div>
