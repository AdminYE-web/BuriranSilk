@extends('admin.layouts.app')

@section('title', 'Edit Product Artwork Template | Indigo Admin')

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

        .form-group.full {
            grid-column: 1 / -1;
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
        .form-group input[type="file"],
        .form-group select {
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

        .current-image-box {
            width: 180px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px;
            background: #fff;
        }

        .current-image-box img {
            width: 100%;
            height: 120px;
            object-fit: contain;
            display: block;
            border-radius: 8px;
            background: var(--bg);
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
    </style>
@endsection

@section('content')

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ request()->cookie('dev') === '1' ? 'Edit Product Artwork Template' : '製品アートワークテンプレートの編集' }}</h1>
                <p>{{ request()->cookie('dev') === '1' ? 'Update artwork template product, name, image, sorting and status.' : '製品アートワークテンプレートの製品、名前、画像、並べ替え、ステータスを更新します。' }}
                </p>
            </div>

            <a href="{{ route('admin.product-artwork-templates.index') }}" class="btn-outline">
                Back
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

        <form action="{{ route('admin.product-artwork-templates.update', $template->template_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Template Information' : 'テンプレート情報' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Product' : '製品' }}</label>
                    <select name="product_id" required>
                        <option value="">-- Select Product --</option>

                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}"
                                {{ old('product_id', $template->product_id) == $product->product_id ? 'selected' : '' }}>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Template Name' : 'テンプレート名' }}</label>
                    <input type="text" name="template_name" value="{{ old('template_name', $template->template_name) }}"
                        required>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Sort Order' : '並べ替え順序' }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $template->sort_order) }}"
                        min="0">
                </div>
            </div>

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Template Image' : 'テンプレート画像' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Current Image' : '現在の画像' }}</label>

                    @if ($template->image_path)
                        <div class="current-image-box">
                            <img src="{{ asset('storage/' . $template->image_path) }}"
                                alt="{{ $template->template_name }}">
                        </div>
                    @else
                        <p class="muted-text">No image</p>
                    @endif
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Change Image' : '画像を変更' }}</label>
                    <input type="file" name="image_path" accept="image/*">
                    <small>{{ request()->cookie('dev') === '1' ? 'recommend the size 200x200.' : '推奨サイズ 200x200.' }}</small>
                </div>
            </div>

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Status' : 'ステータス' }}</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $template->is_active) ? 'checked' : '' }}>
                    {{ request()->cookie('dev') === '1' ? 'Active' : '有効' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.product-artwork-templates.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') === '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') === '1' ? 'Update Artwork Template' : 'アートワークテンプレートを更新' }}
                </button>
            </div>
        </form>
    </div>

@endsection
