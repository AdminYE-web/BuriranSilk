@extends('admin.layouts.app')

@section('title', 'Add Category | Indigo Admin')

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
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
            outline: none;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="number"]:focus,
        .form-group input[type="file"]:focus,
        .form-group select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
            align-items: center;
            gap: 12px;
            margin-top: 28px;
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
            line-height: 1;
        }

        .btn-outline {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--fg);
        }

        .btn-primary {
            background: var(--accent);
            border: 1px solid var(--accent);
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

        .form-group input[type="file"] {
            width: 100%;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 6px 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
            outline: none;
            line-height: 28px;
        }
    </style>
@endsection

@section('content')

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ request()->cookie('dev') == '1' ? 'Add Category' : 'カテゴリを追加' }}</h1>
                <p>{{ request()->cookie('dev') == '1' ? 'Create product category, upload image, set sort order and status.' : '商品カテゴリ、画像、並べ替え、ステータスを管理します。' }}
                </p>
            </div>

            <a href="{{ route('admin.categories.index', ['product_type' => $productType ?? 1]) }}" class="btn-outline">
                {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
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

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Category Information' : 'カテゴリ情報' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Category Code' : 'カテゴリコード' }}</label>
                    <input type="text" name="category_code" value="{{ old('category_code') }}">
                </div>
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product Type' : 'プロダクトタイプ' }}</label>
                    <select name="product_type" required>
                        <option value="1" {{ old('product_type', $productType ?? 1) == 1 ? 'selected' : '' }}>
                            Hotstrap
                        </option>
                        <option value="2" {{ old('product_type', $productType ?? 1) == 2 ? 'selected' : '' }}>
                            Hotmobily
                        </option>
                    </select>
                </div>
                <div class="form-group " style="display: none">
                    <label>{{ request()->cookie('dev') == '1' ? 'Translation Key' : 'トランスクリプションキー' }}</label>
                    <input type="text" name="translation_key"
                        value="{{ old('translation_key', $translationKey ?? '') }}" placeholder="เช่น cat_xxxxxxxx">
                    <small>{{ request()->cookie('dev') == '1' ? 'Used to link categories across languages' : 'ใช้สำหรับผูก category เดียวกันข้ามภาษา' }}</small>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Category Name' : 'カテゴリ名' }}</label>
                    <input type="text" name="category_name" value="{{ old('category_name') }}">
                </div>

                {{-- <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                </div> --}}

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Category Image' : 'カテゴリ画像' }}</label>
                    <input type="file" name="image_path" accept="image/*">
                    <small>{{ request()->cookie('dev') == '1' ? 'Recommended mobile size:  88x88.' : '推奨サイズ: 88x88' }}</small>
                </div>
            </div>

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Status' : '状態' }}</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.categories.index', ['product_type' => $productType ?? 1]) }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? 'Save Category' : '保存カテゴリ' }}
                </button>
            </div>
        </form>
    </div>

@endsection
