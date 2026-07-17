@extends('admin.layouts.app')

@section('title', 'Edit Home Banner | Indigo Admin')

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
            gap: 16px;
            margin-bottom: 24px;
        }

        .form-header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            color: var(--fg-dark);
        }

        .form-header p {
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

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
        }

        .section-title {
            margin: 28px 0 16px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .current-image-card {
            min-height: 227px;

            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            background: #fff;
            max-width: 280px;
        }

        .current-image-card.pc img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            background: var(--bg);
        }

        .current-image-card.mobile {
            max-width: 150px;
        }

        .current-image-card.mobile img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            background: var(--bg);
        }

        .remove-check {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            color: #dc2626;
            font-size: 13px;
        }

        .remove-check input {
            margin: 0;
            width: 14px;
            height: 14px;
        }

        .muted-text {
            color: var(--muted);
            font-size: 14px;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .checkbox-grid label {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            color: var(--fg);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn-outline,
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            border: 1px solid transparent;
        }

        .btn-outline {
            background: #fff;
            border-color: var(--border);
            color: var(--fg);
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .alert-error {
            margin-bottom: 18px;
            padding: 12px 16px;
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        @media (max-width: 900px) {

            .form-grid,
            .checkbox-grid {
                grid-template-columns: 1fr;
            }

            .form-header {
                flex-direction: column;
            }
        }

        .image-edit-grid {
            display: grid;
            grid-template-columns: repeat(2, max-content);
            gap: 28px;
            align-items: start;
        }

        .image-edit-card>label,
        .upload-under-image label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .upload-under-image {
            margin-top: 14px;
        }

        .upload-under-image input[type="file"] {
            width: 280px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            background: #fff;
        }

        @media (max-width: 900px) {
            .image-edit-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $isDev = request()->cookie('dev') == '1';
    @endphp
    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ $isDev ? 'Edit Home Banner' : 'ホームバナーを編集' }}</h1>

                <p>
                    {{ $isDev
                        ? 'Update homepage banner images, link, sort order and status.'
                        : 'ホームページバナーの画像、リンク、表示順、ステータスを更新します。' }}
                </p>
            </div>

            <a href="{{ route('admin.home-banners.index') }}" class="btn-outline">
                {{ $isDev ? 'Back' : '戻る' }}
            </a>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.home-banners.update', $homeBanner->home_banner_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="section-title">
                {{ $isDev ? 'Banner Information' : 'バナー情報' }}
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ $isDev ? 'Title' : 'タイトル' }}</label>
                    <input type="text" name="title" value="{{ old('title', $homeBanner->title) }}">
                </div>

                <div class="form-group">
                    <label>{{ $isDev ? 'Link URL' : 'リンク先URL' }}</label>
                    <input type="text" name="link_url" value="{{ old('link_url', $homeBanner->link_url) }}"
                        placeholder="https://example.com または /products">
                </div>

                <div class="form-group">
                    <label>{{ $isDev ? 'Sort Order' : '表示順' }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $homeBanner->sort_order) }}"
                        min="0">
                </div>
            </div>

            <div class="section-title">
                {{ $isDev ? 'Banner Images' : 'バナー画像' }}
            </div>

            <div class="image-edit-grid">
                <div class="image-edit-card">
                    <label>{{ $isDev ? 'PC Image' : 'PC画像' }}</label>

                    @if ($homeBanner->image_pc)
                        <div class="current-image-card pc">
                            <img src="{{ asset('storage/' . $homeBanner->image_pc) }}" alt="{{ $homeBanner->title }}">

                            <label class="remove-check">
                                <input type="checkbox" name="remove_image_pc" value="1">
                                {{ $isDev ? 'Remove PC image' : 'PC画像を削除' }}
                            </label>
                        </div>
                    @else
                        <p class="muted-text">No image</p>
                    @endif

                    <div class="upload-under-image">
                        <label>{{ $isDev ? 'Upload New PC Image' : '新しいPC画像のアップロード' }}</label>
                        <input type="file" name="image_pc" accept="image/*">
                    </div>
                </div>

                <div class="image-edit-card">
                    <label>{{ $isDev ? 'Mobile Image' : 'モバイル画像' }}</label>

                    @if ($homeBanner->image_mobile)
                        <div class="current-image-card mobile">
                            <img src="{{ asset('storage/' . $homeBanner->image_mobile) }}" alt="{{ $homeBanner->title }}">

                            <label class="remove-check">
                                <input type="checkbox" name="remove_image_mobile" value="1">
                                {{ $isDev ? 'Remove Mobile image' : 'モ바일画像を削除' }}
                            </label>
                        </div>
                    @else
                        <p class="muted-text">{{ $isDev ? 'No image' : '画像がありません' }}</p>
                    @endif

                    <div class="upload-under-image">
                        <label>{{ $isDev ? 'Upload New Mobile Image' : '新しいモバイル画像のアップロード' }}</label>
                        <input type="file" name="image_mobile" accept="image/*">
                    </div>
                </div>
            </div>


            <div class="section-title">
                {{ $isDev ? 'Status' : 'ステータス' }}
            </div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $homeBanner->is_active) ? 'checked' : '' }}>
                    {{ $isDev ? 'Active' : 'アクティブ' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.home-banners.index') }}" class="btn-outline">
                    {{ $isDev ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ $isDev ? 'Update Home Banner' : 'ホームバナーを更新' }}
                </button>
            </div>
        </form>
    </div>

@endsection
