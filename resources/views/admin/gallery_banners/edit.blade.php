@extends('admin.layouts.app')

@section('title', 'Edit Gallery Banner | Indigo Admin')

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

        .form-group input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
        }

        .image-panel {
            margin-bottom: 18px;
        }

        .image-grid {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
        }

        .image-box {
            width: 180px;
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
        }

        .checkbox-box {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
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
                <h1>{{ request()->cookie('dev') == '1' ? 'Edit Gallery Banner' : 'ギャラリーバナーを編集' }}</h1>
                <p>{{ request()->cookie('dev') == '1' ? 'Update banner for gallery page with PC and mobile images.' : 'ギャラリーページ用のバナーを更新します（PC用とモバイル用の画像が必要です）。' }}
                </p>
            </div>

            <a href="{{ route('admin.gallery-banners.index') }}" class="btn-outline">
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

        <form action="{{ route('admin.gallery-banners.update', $galleryBanner->gallery_banner_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group full">
                    <label>{{ request()->cookie('dev') == '1' ? 'Title' : 'タイトル' }}</label>
                    <input type="text" name="title" value="{{ old('title', $galleryBanner->title) }}">
                </div>

                <div class="form-group full">
                    <label>{{ request()->cookie('dev') == '1' ? 'Link URL' : 'リンクURL' }}</label>
                    <input type="text" name="link_url" value="{{ old('link_url', $galleryBanner->link_url) }}">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Sort Order' : '表示順' }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $galleryBanner->sort_order) }}">
                </div>


            </div>

            <div class="image-panel">
                <label>{{ request()->cookie('dev') == '1' ? 'Current PC Image' : '現在のPC画像' }}</label>

                @if ($galleryBanner->image_pc)
                    <div class="image-grid">
                        <div class="image-box">
                            <img src="{{ asset('storage/' . $galleryBanner->image_pc) }}" alt="PC banner">
                        </div>
                    </div>
                @else
                    <p class="muted-text">{{ request()->cookie('dev') == '1' ? 'No PC image' : 'PC画像がありません' }}</p>
                @endif
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Change PC Image' : 'PC画像を更新' }}</label>
                <input type="file" name="image_pc" accept="image/*">
            </div>

            <div class="image-panel">
                <label>{{ request()->cookie('dev') == '1' ? 'Current Mobile Image' : '現在のモバイル画像' }}</label>

                @if ($galleryBanner->image_mobile)
                    <div class="image-grid">
                        <div class="image-box">
                            <img src="{{ asset('storage/' . $galleryBanner->image_mobile) }}" alt="Mobile banner">
                        </div>
                    </div>
                @else
                    <p class="muted-text">{{ request()->cookie('dev') == '1' ? 'No Mobile image' : 'モバイル画像がありません' }}</p>
                @endif
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Change Mobile Image' : 'モバイル画像を更新' }}</label>
                <input type="file" name="image_mobile" accept="image/*">
            </div>

            <label class="checkbox-box">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $galleryBanner->is_active) ? 'checked' : '' }}>
                {{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}
            </label>


            <div class="form-actions">
                <a href="{{ route('admin.gallery-banners.index') }}"
                    class="btn-outline">{{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}</a>
                <button type="submit"
                    class="btn-primary">{{ request()->cookie('dev') == '1' ? 'Update Banner' : '更新' }}</button>
            </div>
        </form>
    </div>

@endsection
