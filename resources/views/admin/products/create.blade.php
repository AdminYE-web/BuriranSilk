@extends('admin.layouts.app')

@section('title', 'Add Product | Indigo Admin')
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

        .form-group input[type="text"],
        .form-group input[type="file"],
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

        .image-panel {
            margin-bottom: 18px;
        }

        .image-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .image-box {
            width: 120px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px;
            background: var(--bg);
        }

        .image-box img {
            width: 100%;
            height: 90px;
            object-fit: contain;
            display: block;
            border-radius: 8px;
            background: #fff;
            margin-bottom: 8px;
        }

        .radio-label {
            font-size: 12px;
            color: var(--fg);
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
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

        .btn-primary:hover {
            background: var(--accent-hover);
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
                <h1>{{ request()->cookie('dev') == '1' ? 'Add Product' : '商品を追加' }}</h1>
                <p>{{ request()->cookie('dev') == '1' ? 'Create new product, upload images, and set product display options.' : '新しい商品を作成し、画像や表示設定を行います。' }}
                </p>
            </div>

            <a href="{{ route('admin.products.index') }}" class="btn-outline">
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

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Product Images' : '商品画像' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product Main Images' : '商品画像' }}</label>
                    <input type="file" name="images[]" multiple accept="image/*">
                    <small>{{ request()->cookie('dev') == '1' ? 'Recommended size: 300x300.' : '推奨サイズ: 300x300。' }}</small>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product Gallery Images' : '商品ギャラリー画像' }}</label>
                    <input type="file" name="gallery_images[]" multiple accept="image/*">
                    <small>{{ request()->cookie('dev') == '1' ? 'Recommended size: 521x274.' : '推奨サイズ: 521x274。' }}</small>
                </div>
            </div>

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Product Information' : '商品情報' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product Code' : '商品コード' }}</label>
                    <input type="text" name="product_code" value="{{ old('product_code') }}">
                    <small>{{ request()->cookie('dev') == '1' ? 'This section will be displayed as the product URL.' : 'このセクションは商品URLとして表示されます。' }}</small>
                </div>
                <div class="form-group" style="display: none">
                    <label>Translation Key</label>
                    <input type="text" name="translation_key"
                        value="{{ old('translation_key', $translationKey ?? '') }}" placeholder="เช่น product_xxxxxxxx">

                    <small>ใช้สำหรับผูกสินค้าตัวเดียวกันข้ามภาษา</small>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product Type' : '商品タイプ' }}</label>
                    <select name="product_type" id="product_type">
                        <option value="1" {{ old('product_type', $productType ?? 1) == 1 ? 'selected' : '' }}>
                            hotstrap
                        </option>
                        <option value="2" {{ old('product_type', $productType ?? 1) == 2 ? 'selected' : '' }}>
                            hotmobily
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Category' : 'カテゴリー' }}</label>
                    <select name="category_id" id="category_id">
                        <option value="">
                            {{ request()->cookie('dev') == '1' ? '-- Select Category --' : '-- カテゴリーを選択 --' }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->category_id }}" data-product-type="{{ $category->product_type }}"
                                {{ old('category_id') == $category->category_id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Material' : '素材' }}</label>
                    <select name="material_id" id="material_id">
                        <option value="">
                            {{ request()->cookie('dev') == '1' ? '-- Select Material --' : '-- 素材を選択 --' }}</option>
                        @foreach ($materials as $material)
                            <option value="{{ $material->material_id }}" data-product-type="{{ $material->product_type }}"
                                {{ old('material_id') == $material->material_id ? 'selected' : '' }}>
                                {{ $material->material_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product Name' : '商品名' }}</label>
                    <input type="text" name="product_name" value="{{ old('product_name') }}">
                </div>

                <div class="form-group full">
                    <label>{{ request()->cookie('dev') == '1' ? 'Description' : '説明' }}</label>
                    <textarea name="description" rows="5">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Artwork / Template Setting' : 'アートワーク / テンプレート設定' }}</div>

            <div class="checkbox-grid">
                {{-- <label>
                    <input type="checkbox" name="product_recomend" value="1"
                        {{ old('product_recomend') ? 'checked' : '' }}>
                    Product Recommend
                </label>
                <label>
                    <input type="checkbox" name="product_recomend_menu" value="1"
                        {{ old('product_recomend_menu') ? 'checked' : '' }}>
                    Product Recommend Menu
                </label> --}}
                {{-- <label>
                    <input type="checkbox" name="is_antivirus_included" value="1"
                        {{ old('is_antivirus_included') ? 'checked' : '' }}>
                    Antivirus Included
                </label> --}}

                <label>
                    <input type="checkbox" name="can_upload_artwork" value="1"
                        {{ old('can_upload_artwork') ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Allow Upload Artwork' : 'アップロード許可' }}
                </label>

                <label>
                    <input type="checkbox" name="artwork_required" value="1"
                        {{ old('artwork_required') ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Artwork Required' : '必須' }}
                </label>

                <label>
                    <input type="checkbox" name="allow_text_print" value="1"
                        {{ old('allow_text_print') ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Allow Text Printing' : 'テキスト印刷' }}
                </label>

                <label>
                    <input type="checkbox" name="allow_font_select" value="1"
                        {{ old('allow_font_select') ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Allow Text Printing' : 'テキスト印刷を許可する' }}
                </label>

                <label>
                    <input type="checkbox" name="allow_template_select" value="1"
                        {{ old('allow_template_select') ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Allow Template Selection' : 'テンプレート選択を許可する' }}
                </label>

                {{-- <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    Active
                </label> --}}



                {{-- <label>
                    <input type="checkbox" name="product_premium" value="1"
                        {{ old('product_premium') ? 'checked' : '' }}>
                    Product Premium
                </label> --}}
            </div>
            <br>
            <div class="form-group" style="max-width: 20%">
                <label>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</label>

                <select name="is_active">
                    <option value="1" {{ old('is_active', 3) == 1 ? 'selected' : '' }}>
                        {{ request()->cookie('dev') == '1' ? 'Public' : '公開' }}
                    </option>

                    <option value="3" {{ old('is_active', 3) == 3 ? 'selected' : '' }}>
                        {{ request()->cookie('dev') == '1' ? 'Draft' : '下書き' }}
                    </option>
                </select>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Cancel' : '' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? 'Save Product' : '' }}
                </button>
            </div>
        </form>
    </div>

@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const productTypeSelect = document.getElementById('product_type');
            const categorySelect = document.getElementById('category_id');
            const materialSelect = document.getElementById('material_id');

            if (!productTypeSelect || !categorySelect || !materialSelect) {
                return;
            }

            function filterSelectByProductType(selectElement) {
                const selectedType = String(productTypeSelect.value);

                Array.from(selectElement.options).forEach(function(option) {
                    if (!option.value) {
                        option.hidden = false;
                        option.disabled = false;
                        return;
                    }

                    const optionType = String(option.dataset.productType);
                    const isSameType = optionType === selectedType;

                    option.hidden = !isSameType;
                    option.disabled = !isSameType;
                });

                const selectedOption = selectElement.options[selectElement.selectedIndex];

                if (
                    selectedOption &&
                    selectedOption.value &&
                    String(selectedOption.dataset.productType) !== selectedType
                ) {
                    selectElement.value = '';
                }
            }

            function filterCategoryAndMaterial() {
                filterSelectByProductType(categorySelect);
                filterSelectByProductType(materialSelect);
            }

            productTypeSelect.addEventListener('change', filterCategoryAndMaterial);

            filterCategoryAndMaterial();
        });
    </script>
@endsection
