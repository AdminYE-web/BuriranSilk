@extends('admin.layouts.app')

@section('title', 'Add Product List Banner | Indigo Admin')

@section('css')
<style>
    .form-card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; }
    .form-header { display: flex; justify-content: space-between; gap: 16px; margin-bottom: 24px; }
    .form-header h1 { margin: 0 0 6px; font-size: 24px; color: var(--fg-dark); }
    .form-header p { margin: 0; color: var(--muted); font-size: 14px; }

    .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 18px; }
    .form-group { margin-bottom: 18px; }
    .form-group label { display: block; margin-bottom: 8px; font-size: 14px; font-weight: 600; color: var(--fg-dark); }

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

    .form-group small {
        display: block;
        margin-top: 6px;
        color: var(--muted);
        font-size: 12px;
    }

    .section-title {
        margin: 28px 0 16px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        font-size: 18px;
        font-weight: 700;
        color: var(--fg-dark);
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

    .btn-outline { background: #fff; border-color: var(--border); color: var(--fg); }
    .btn-primary { background: var(--accent); border-color: var(--accent); color: #fff; }

    .alert-error {
        margin-bottom: 18px;
        padding: 12px 16px;
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        border-radius: 8px;
        font-size: 14px;
    }

    .alert-error ul { margin: 0; padding-left: 20px; }

    @media (max-width: 900px) {
        .form-grid,
        .checkbox-grid { grid-template-columns: 1fr; }
        .form-header { flex-direction: column; }
    }
</style>
@endsection

@section('content')

<div class="form-card">
    <div class="form-header">
        <div>
            <h1>{{ request()->cookie('dev') == '1' ? 'Add Product List Banner' : '商品一覧バナーを追加' }}</h1>
            <p>{{ request()->cookie('dev') == '1' ? 'Create product listing banner, image, CTA link and display status.' : '商品一覧のバナー、画像、CTAリンク、表示ステータスを作成します。' }}</p>
        </div>

        <a href="{{ route('admin.product-list-banners.index') }}" class="btn-outline">
            {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
        </a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.product-list-banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="section-title">Banner Information</div>

        <div class="form-grid">
            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Title' : 'タイトル' }}</label>
                <input type="text" name="title" value="{{ old('title') }}">
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Subtitle' : 'サブタイトル' }}</label>
                <input type="text" name="subtitle" value="{{ old('subtitle') }}">
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Button Text' : 'ボタンテキスト' }}</label>
                <input type="text" name="button_text" value="{{ old('button_text') }}" placeholder="Ver Detalhes">
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Link URL' : 'リンクURL' }}</label>
                <input type="text" name="link_url" value="{{ old('link_url') }}" placeholder="/products">
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Sort Order' : '並び替え' }}</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Desktop Banner Image' : 'デスクトップバナー画像' }}</label>
                <input type="file" name="image_path" accept="image/*">
                <small>{{ request()->cookie('dev') == '1' ? 'Recommended desktop size:  1920x480.' : '推奨デスクトップサイズ:  1920x480。' }}</small>
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Mobile Banner Image' : 'モバイルバナー画像' }}</label>
                <input type="file" name="image_mobile" accept="image/*">
                <small>{{ request()->cookie('dev') == '1' ? 'Recommended mobile size:  750x400.' : '推奨モバイルサイズ:  750x400。' }}</small>
                <small>{{ request()->cookie('dev') == '1' ? 'Recommended for mobile screens. If empty, desktop image will be used.' : 'モバイル画面用に推奨されます。空の場合、デスクトップ画像が使用されます。' }}</small>
            </div>
        </div>

        <div class="section-title">Status</div>

        <div class="checkbox-grid">
            <label>
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                {{ request()->cookie('dev') == '1' ? 'Active' : '有効' }}
            </label>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.product-list-banners.index') }}" class="btn-outline">
                {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
            </a>

            <button type="submit" class="btn-primary">
                {{ request()->cookie('dev') == '1' ? 'Save Banner' : 'バナーを保存' }}
            </button>
        </div>
    </form>
</div>

@endsection
