@extends('admin.layouts.app')

@section('title', 'Edit Variant | Indigo Admin')

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

        .color-picker-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .color-picker-group input[type="color"] {
            width: 52px;
            height: 42px;
            border: none;
            background: transparent;
            padding: 0;
            cursor: pointer;
        }

        .current-image-box {
            width: 140px;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px;
            background: #fff;
        }

        .current-image-box img {
            width: 100%;
            height: 110px;
            object-fit: contain;
            display: block;
            border-radius: 8px;
            background: var(--bg);
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
                <h1>{{ request()->cookie('dev') === '1' ? 'Edit Variant' : 'バリエーションを編集' }}</h1>
                <p>{{ request()->cookie('dev') === '1' ? 'Option:' : 'オプション：' }}
                    {{ $variant->option->option_name ?? '-' }}</p>
            </div>

            <a href="{{ route('admin.product-options.variants.index', $variant->option_id) }}" class="btn-outline">
                {{ request()->cookie('dev') === '1' ? 'Back' : '戻る' }}
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

        <form action="{{ route('admin.product-option-variants.update', $variant->variant_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Variant Information' : 'バリエーション情報' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Variant Name' : 'バリエーション名' }}</label>
                    <input type="text" name="variant_name" value="{{ old('variant_name', $variant->variant_name) }}">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Color Code' : 'カラーコード' }}</label>

                    <div class="color-picker-group">
                        <input type="text" name="color_code" id="color_code_input"
                            value="{{ old('color_code', $variant->color_code) }}" placeholder="เช่น #000000">

                        <input type="color" value="{{ old('color_code', $variant->color_code ?: '#000000') }}"
                            onchange="document.getElementById('color_code_input').value = this.value">
                    </div>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Additional Price' : '追加料金' }}</label>
                    <input type="number" step="0.01" name="additional_price"
                        value="{{ old('additional_price', $variant->additional_price) }}">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Additional Price With Tax' : '税込追加料金' }}</label>
                    <input type="number" step="0.01" name="additional_price_with_tax"
                        value="{{ old('additional_price_with_tax', $variant->additional_price_with_tax) }}" min="0"
                        placeholder="เช่น 220">
                </div>

                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $variant->sort_order) }}">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Current Image' : '現在の画像' }}</label>

                    @if ($variant->image_path)
                        <div class="current-image-box">
                            <img src="{{ asset('storage/' . $variant->image_path) }}" alt="{{ $variant->variant_name }}">
                        </div>
                    @else
                        <p class="muted-text">{{ request()->cookie('dev') === '1' ? 'No image' : '画像がありません' }}</p>
                    @endif
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Change Variant Image' : '変更後の画像' }}</label>
                    <input type="file" name="image_path" accept="image/*">
                </div>
            </div>

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Variant Status' : 'バリエーションステータス' }}</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_default" value="1"
                        {{ old('is_default', $variant->is_default) ? 'checked' : '' }}>
                    {{ request()->cookie('dev') === '1' ? 'Default' : 'デフォルト' }}
                </label>

                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $variant->is_active) ? 'checked' : '' }}>
                    {{ request()->cookie('dev') === '1' ? 'Active' : 'アクティブ' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.product-options.variants.index', $variant->option_id) }}" class="btn-outline">
                    {{ request()->cookie('dev') === '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') === '1' ? 'Update Variant' : '更新' }}
                </button>
            </div>
        </form>
    </div>

@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.querySelector('input[name="additional_price"]');
            const priceWithTaxInput = document.querySelector('input[name="additional_price_with_tax"]');

            if (priceInput && priceWithTaxInput) {
                priceInput.addEventListener('input', function() {
                    const priceVal = parseFloat(this.value);
                    if (!isNaN(priceVal)) {
                        priceWithTaxInput.value = (priceVal * 1.1).toFixed(2);
                    } else {
                        priceWithTaxInput.value = '';
                    }
                });
            }
        });
    </script>
@endsection
