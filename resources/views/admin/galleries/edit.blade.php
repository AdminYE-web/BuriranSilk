@extends('admin.layouts.app')

@section('title', 'Edit Gallery | Indigo Admin')

@section('css')
    <style>
        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 24px;
        }

        .form-header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            color: var(--fg-dark);
        }

        .form-header p,
        .muted-text {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label,
        .image-panel>label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
        }

        .form-group textarea {
            resize: vertical;
        }

        .section-title {
            margin: 28px 0 16px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .image-panel {
            margin-bottom: 18px;
        }

        .image-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .image-box {
            width: 130px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px;
            background: var(--bg);
        }

        .image-box img {
            width: 100%;
            height: 90px;
            object-fit: cover;
            display: block;
            border-radius: 8px;
            background: #fff;
            margin-bottom: 8px;
        }

        .delete-image-label {
            margin-top: 6px;
            font-size: 12px;
            color: #dc2626;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }

        .checkbox-grid label {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            color: var(--fg);
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }

        .alert-error,
        .alert-success {
            margin-bottom: 18px;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-error {
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
        }

        .btn-primary {
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #fff;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ request()->cookie('dev') == '1' ? 'Edit Gallery' : 'ギャラリーを編集' }}</h1>
                <p>{{ request()->cookie('dev') == '1' ? 'Update gallery information, cover image and gallery images.' : 'ギャラリー情報、カバー画像、ギャラリー画像を更新します。' }}
                </p>
            </div>

            <a href="{{ route('admin.galleries.index') }}" class="btn-outline">
                {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.galleries.update', $gallery->gallery_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group full">
                    <label>Gallery Title</label>
                    <input type="text" name="title" value="{{ old('title', $gallery->title) }}">
                </div>
                <div class="form-group" style="display: none">
                    <label>Translation Key</label>
                    <input type="text" name="translation_key"
                        value="{{ old('translation_key', $gallery->translation_key ?? '') }}"
                        placeholder="เช่น gal_xxxxxxxx">
                    <small>ใช้สำหรับผูก Gallery เดียวกันข้ามภาษา</small>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Category' : 'カテゴリ' }}</label>
                    <select name="category_id">
                        <option value="">
                            {{ request()->cookie('dev') == '1' ? '-- Select Category --' : '-- カテゴリを選択 --' }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}"
                                {{ old('category_id', $gallery->category_id) == $category->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Material' : '素材' }}</label>
                    <select name="material_id">
                        <option value="">
                            {{ request()->cookie('dev') == '1' ? '-- Select Material --' : '-- 素材を選択 --' }}</option>
                        @foreach ($materials as $material)
                            <option value="{{ $material->material_id }}"
                                {{ old('material_id', $gallery->material_id) == $material->material_id ? 'selected' : '' }}>
                                {{ $material->material_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label>{{ request()->cookie('dev') == '1' ? 'Purpose' : '用途' }}</label>
                    <textarea name="purpose" rows="4">{{ old('purpose', $gallery->purpose) }}</textarea>
                </div>
                <div class="form-group full">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product Link' : '製品リンク' }}</label>
                    <input type="text" name="product_link" value="{{ old('product_link', $gallery->product_link) }}"
                        placeholder="https://example.com/products/...">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Date' : '日付' }}</label>
                    <input type="date" name="gallery_date"
                        value="{{ old('gallery_date', $gallery->gallery_date ? $gallery->gallery_date->format('Y-m-d') : '') }}">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Sort Order' : '表示順' }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $gallery->sort_order) }}">
                </div>
            </div>

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Cover Image' : 'カバー画像' }}</div>

            <div class="image-panel">
                <label>{{ request()->cookie('dev') == '1' ? 'Current Cover Image' : '現在のカバー画像' }}</label>

                @if ($gallery->cover_image)
                    <div class="image-grid">
                        <div class="image-box">
                            <img src="{{ asset('storage/' . $gallery->cover_image) }}" alt="{{ $gallery->title }}">

                            <label class="delete-image-label">
                                <input type="checkbox" name="remove_cover_image" value="1">
                                {{ request()->cookie('dev') == '1' ? 'Remove Cover' : 'カバーを削除' }}
                            </label>
                        </div>
                    </div>
                @else
                    <p class="muted-text">{{ request()->cookie('dev') == '1' ? 'No cover image' : 'カバー画像はありません' }}</p>
                @endif
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Change Cover Image' : 'カバー画像を変更' }}</label>
                <input type="file" name="cover_image" accept="image/*">
            </div>

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Gallery Images' : 'ギャラリー画像' }}</div>

            <div class="image-panel">
                <label>{{ request()->cookie('dev') == '1' ? 'Current Gallery Images' : '現在のギャラリー画像' }}</label>

                <div class="image-grid">
                    @forelse($gallery->images as $image)
                        <div class="image-box">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Gallery image">

                            <label class="delete-image-label">
                                <input type="checkbox" name="delete_gallery_images[]"
                                    value="{{ $image->gallery_image_id }}">
                                {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                            </label>
                        </div>
                    @empty
                        <p class="muted-text">{{ request()->cookie('dev') == '1' ? 'No gallery images' : 'ギャラリー画像はありません' }}
                        </p>
                    @endforelse
                </div>
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Add Gallery Images' : 'ギャラリー画像を追加' }}</label>
                <input type="file" name="gallery_images[]" multiple accept="image/*">
            </div>

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $gallery->is_active) ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.galleries.index') }}"
                    class="btn-outline">{{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}</a>
                <button type="submit"
                    class="btn-primary">{{ request()->cookie('dev') == '1' ? 'Update Gallery' : 'ギャラリーを更新' }}</button>
            </div>
        </form>
    </div>

@endsection
