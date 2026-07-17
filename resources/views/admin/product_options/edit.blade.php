@extends('admin.layouts.app')

@section('title', 'Edit Product Option | Indigo Admin')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--fg);
            line-height: 42px;
            padding-left: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

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

        .btn-outline:hover {
            background: var(--bg);
        }

        .btn-primary {
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
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

        .image-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 16px;
        }

        .image-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
        }

        .image-card img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block;
        }

        .image-card-body {
            padding: 12px;
        }

        .image-main-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
            padding: 4px 8px;
            border-radius: 999px;
            background: #ecfdf5;
            color: #047857;
            font-size: 11px;
            font-weight: 700;
        }

        .remove-check {
            color: #dc2626;
            font-size: 13px;
        }

        .remove-check input {
            margin-right: 4px;
        }

        @media (max-width: 900px) {

            .form-grid,
            .checkbox-grid {
                grid-template-columns: 1fr;
            }

            .form-header {
                flex-direction: column;
            }

            .image-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .remove-check,
        .main-image-check {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 8px;
            font-size: 13px;
            line-height: 1.2;
        }

        .remove-check {
            color: #dc2626;
        }

        .main-image-check {
            color: #047857;
            font-weight: 600;
        }

        .remove-check input,
        .main-image-check input {
            width: 14px;
            height: 14px;
            margin: 0;
        }

        .price-rate-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 12px;
            align-items: end;
            padding: 12px;
            margin-bottom: 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--bg);
        }

        .price-rate-row label {
            font-size: 13px;
            margin-bottom: 6px;
        }

        @media (max-width: 900px) {
            .price-rate-row {
                grid-template-columns: 1fr;
            }
        }

        .price-mode-box {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .price-mode-item {
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            cursor: pointer;
            font-weight: 600;
        }

        .price-mode-item input {
            width: auto !important;
            margin: 0;
        }
    </style>
@endsection

@section('content')

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ request()->cookie('dev') === '1' ? 'Edit Product Option' : '商品オプションを編集' }}</h1>
                <p>{{ request()->cookie('dev') === '1'
                    ? 'Update option details, price, color, images and variants.'
                    : 'オプションの詳細、価格、カラー、画像、バリエーションを更新します。' }}
                </p>
            </div>

            <a href="{{ route('admin.product-options.index') }}" class="btn-outline">
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

        <form action="{{ route('admin.product-options.update', $option->option_id) }}" method="POST"
            enctype="multipart/form-data">

            @csrf
            @method('PUT')

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Option Information' : 'オプション情報' }}
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Option Group' : 'オプショングループ' }}</label>

                    <select name="option_group_id" id="option_group_id" class="searchable-select">
                        <option value="">
                            {{ request()->cookie('dev') === '1' ? '-- Select Group --' : '-- グループを選択 --' }}</option>

                        <optgroup label="Hotstrap (Type 1)">
                            @foreach ($groups->where('product_type', 1) as $group)
                                <option value="{{ $group->option_group_id }}"
                                    {{ old('option_group_id', $option->option_group_id) == $group->option_group_id ? 'selected' : '' }}>
                                    {{ $group->group_name }} ({{ $group->group_code }})
                                </option>
                            @endforeach
                        </optgroup>

                        <optgroup label="Hotmobily (Type 2)">
                            @foreach ($groups->where('product_type', 2) as $group)
                                <option value="{{ $group->option_group_id }}"
                                    {{ old('option_group_id', $option->option_group_id) == $group->option_group_id ? 'selected' : '' }}>
                                    {{ $group->group_name }} ({{ $group->group_code }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Option Code' : 'オプションコード' }}</label>

                    <input type="text" name="option_code" value="{{ old('option_code', $option->option_code) }}">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Option Name' : 'オプション名' }}</label>

                    <textarea name="option_name" rows="4">{{ old('option_name', $option->option_name) }}</textarea>
                </div>
                <div class="form-group" style="display: none">
                    <label>Translation Key</label>
                    <input type="text" name="translation_key"
                        value="{{ old('translation_key', $option->translation_key ?? '') }}" placeholder=" opt_xxxxxxxx">
                    <small>ใช้สำหรับผูก option เดียวกันข้ามภาษา</small>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Additional Price' : '追加料金' }}</label>

                    <input type="number" step="0.01" name="additional_price"
                        value="{{ old('additional_price', $option->additional_price) }}">
                </div>
                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Additional Price With Tax' : '税込追加料金' }}</label>

                    <input type="number" step="0.01" name="additional_price_with_tax"
                        value="{{ old('additional_price_with_tax', $option->additional_price_with_tax ?? '') }}"
                        min="0" placeholder=" 220">
                </div>

                @php
                    $currentPriceMode = old('price_mode');

                    if (!$currentPriceMode) {
                        $currentPriceMode = $option->priceRates && $option->priceRates->count() ? 'rate' : 'normal';
                    }
                @endphp

                <div class="form-group full">
                    <label>{{ request()->cookie('dev') === '1' ? 'Additional Price Mode' : '追加料金モード' }}</label>

                    <div class="price-mode-box">
                        <label class="price-mode-item">
                            <input type="radio" name="price_mode" value="normal"
                                {{ $currentPriceMode == 'normal' ? 'checked' : '' }}>
                            {{ request()->cookie('dev') === '1' ? 'Normal Price' : '通常価格' }}
                        </label>

                        <label class="price-mode-item">
                            <input type="radio" name="price_mode" value="rate"
                                {{ $currentPriceMode == 'rate' ? 'checked' : '' }}>
                            {{ request()->cookie('dev') === '1' ? 'Rate by Quantity' : '数量別価格' }}
                        </label>
                    </div>
                </div>

                @php
                    $priceRates = old('price_rates');

                    if (is_null($priceRates)) {
                        if ($option->priceRates && $option->priceRates->count()) {
                            $priceRates = $option->priceRates
                                ->map(function ($rate) {
                                    return [
                                        'min_qty' => $rate->min_qty,
                                        'additional_price' => $rate->additional_price,
                                        'additional_price_with_tax' => $rate->additional_price_with_tax,
                                    ];
                                })
                                ->toArray();
                        } else {
                            $priceRates = [
                                [
                                    'min_qty' => 1,
                                    'additional_price' => $option->additional_price ?? 0,
                                    'additional_price_with_tax' => $option->additional_price_with_tax ?? 0,
                                ],
                            ];
                        }
                    }
                @endphp

                <div class="form-group full" id="price-rate-section">
                    <label>{{ request()->cookie('dev') === '1' ? 'Additional Price Rates' : '追加料金レート' }}</label>

                    <div id="price-rate-list">
                        @foreach ($priceRates as $index => $rate)
                            <div class="price-rate-row">
                                <div>
                                    <label>{{ request()->cookie('dev') === '1' ? 'From Qty' : '開始数量' }}</label>
                                    <input type="number" name="price_rates[{{ $index }}][min_qty]" min="1"
                                        value="{{ $rate['min_qty'] ?? 1 }}">
                                </div>

                                <div>
                                    <label>{{ request()->cookie('dev') === '1' ? 'Additional Price' : '追加料金' }}</label>
                                    <input type="number" step="0.01"
                                        name="price_rates[{{ $index }}][additional_price]"
                                        value="{{ $rate['additional_price'] ?? 0 }}" class="rate-price">
                                </div>

                                <div>
                                    <label>{{ request()->cookie('dev') === '1' ? 'With Tax' : '税込' }}</label>
                                    <input type="number" step="0.01"
                                        name="price_rates[{{ $index }}][additional_price_with_tax]"
                                        value="{{ $rate['additional_price_with_tax'] ?? 0 }}" class="rate-price-tax">
                                </div>

                                <button type="button" class="btn-outline remove-rate"
                                    style="{{ $index == 0 ? 'display:none;' : '' }}">
                                    {{ request()->cookie('dev') === '1' ? 'Remove' : '削除' }}
                                </button>
                            </div>
                        @endforeach
                    </div>

                    <button type="button" id="add-price-rate" class="btn-outline" style="margin-top:12px;">
                        {{ request()->cookie('dev') === '1' ? '+ Add Rate' : '+ レート追加' }}
                    </button>

                    <small style="display:block; margin-top:6px; color:#6b7280;">
                        {{ request()->cookie('dev') === '1'
                            ? 'Example: Qty 1 = 39, Qty 50 = 35 means orders from 50 pcs will use 35 per item.'
                            : '例：数量 1 = 39, 数量 50 = 35 の場合、50個から注文は1個あたり35を使用します。' }}
                    </small>
                </div>
                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Free From Quantity' : '無料数量' }}</label>

                    <input type="number" name="free_from_qty" value="{{ old('free_from_qty', $option->free_from_qty) }}"
                        min="1" placeholder=" 100">

                    <small style="display:block; margin-top:6px; color:#6b7280;">
                        {{ request()->cookie('dev') === '1'
                            ? 'Entering 100 means that for orders of 100 units or more, the Additional Price for this option will be free.'
                            : '100を入力すると、100個以上の注文に対して、このオプションの追加料金が無料になります。' }}
                    </small>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Price Type' : '価格タイプ' }}</label>

                    <select name="price_type">
                        <option value="per_item"
                            {{ old('price_type', $option->price_type) == 'per_item' ? 'selected' : '' }}>
                            {{ request()->cookie('dev') === '1' ? 'per_item - Per Item' : 'per_item - 1個あたり' }}
                        </option>

                        <option value="per_order"
                            {{ old('price_type', $option->price_type) == 'per_order' ? 'selected' : '' }}>
                            {{ request()->cookie('dev') === '1' ? 'per_order - Per Order' : 'per_order - 1注文あたり' }}
                        </option>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Color Code' : 'カラーコード' }}</label>

                    <div class="color-picker-group">
                        <input type="text" name="color_code" id="color_code_input"
                            value="{{ old('color_code', $option->color_code) }}" placeholder=" #ff0000">

                        <input type="color" value="{{ old('color_code', $option->color_code ?: '#000000') }}"
                            onchange="document.getElementById('color_code_input').value = this.value">
                    </div>
                </div>

                <div class="form-group full">
                    <label>{{ request()->cookie('dev') === '1' ? 'Option Detail' : 'オプション詳細' }}</label>

                    <textarea name="option_detail" rows="8"
                        placeholder="&#10;Model: ID-6_N&#10;Type: Soft Card Holder&#10;Card Size: 91 mm (H) x 55 mm (W)">{{ old('option_detail', $option->option_detail) }}</textarea>
                </div>
            </div>

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Option Status' : 'オプションステータス' }}
            </div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $option->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Current Images' : '現在の画像' }}</div>

            @if ($option->images && $option->images->count())
                <div class="image-grid">
                    @foreach ($option->images as $image)
                        <div class="image-card">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Option Image">

                            <div class="image-card-body">
                                @if ($image->is_main)
                                    <label class="main-image-check">
                                        <span>{{ request()->cookie('dev') === '1' ? 'Main Image' : 'メイン画像' }}</span>
                                        <input type="checkbox" checked disabled>
                                    </label>
                                @endif

                                <label class="remove-check">
                                    <input type="checkbox" name="delete_images[]" value="{{ $image->image_id }}">
                                    <span>{{ request()->cookie('dev') === '1' ? 'Remove image' : '削除' }}</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p>No images</p>
            @endif

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Add New Images' : '新しい画像' }}
            </div>

            <div class="form-group">
                <label>{{ request()->cookie('dev') === '1' ? 'Add New Option Images' : '新しい画像' }}
                </label>
                <input type="file" name="images[]" multiple accept="image/*">
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.product-options.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') === '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') === '1' ? 'Update Product Option' : '更新' }}
                </button>
            </div>
        </form>
    </div>

@endsection

@section('js')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const priceInput = document.querySelector('input[name="additional_price"]');
            const priceWithTaxInput = document.querySelector('input[name="additional_price_with_tax"]');

            function calculateTax(price) {
                const priceVal = parseFloat(price);
                return !isNaN(priceVal) ? (priceVal * 1.1).toFixed(2) : '';
            }

            if (priceInput && priceWithTaxInput) {
                priceInput.addEventListener('input', function() {
                    priceWithTaxInput.value = calculateTax(this.value);
                });
            }

            const rateList = document.getElementById('price-rate-list');
            const addRateBtn = document.getElementById('add-price-rate');
            const priceRateSection = document.getElementById('price-rate-section');
            const priceModeInputs = document.querySelectorAll('input[name="price_mode"]');

            let rateIndex = rateList ? rateList.querySelectorAll('.price-rate-row').length : 0;

            function togglePriceRateSection() {
                const selectedMode = document.querySelector('input[name="price_mode"]:checked')?.value || 'normal';
                const isRateMode = selectedMode === 'rate';

                if (!priceRateSection) {
                    return;
                }

                priceRateSection.style.display = isRateMode ? 'block' : 'none';

                priceRateSection.querySelectorAll('input, button').forEach(function(el) {
                    el.disabled = !isRateMode;
                });

                if (addRateBtn) {
                    addRateBtn.disabled = !isRateMode;
                }
            }

            priceModeInputs.forEach(function(input) {
                input.addEventListener('change', togglePriceRateSection);
            });

            if (addRateBtn && rateList) {
                addRateBtn.addEventListener('click', function() {
                    const row = document.createElement('div');
                    row.className = 'price-rate-row';

                    row.innerHTML = `
                        <div>
                            <label>From Qty</label>
                            <input type="number" name="price_rates[${rateIndex}][min_qty]" min="1" placeholder="50">
                        </div>

                        <div>
                            <label>Additional Price</label>
                            <input type="number" step="0.01" name="price_rates[${rateIndex}][additional_price]" value="0" class="rate-price">
                        </div>

                        <div>
                            <label>With Tax</label>
                            <input type="number" step="0.01" name="price_rates[${rateIndex}][additional_price_with_tax]" value="0" class="rate-price-tax">
                        </div>

                        <button type="button" class="btn-outline remove-rate">
                            Remove
                        </button>
                    `;

                    rateList.appendChild(row);
                    rateIndex++;

                    togglePriceRateSection();
                });

                rateList.addEventListener('input', function(e) {
                    if (e.target.classList.contains('rate-price')) {
                        const row = e.target.closest('.price-rate-row');
                        const taxInput = row.querySelector('.rate-price-tax');

                        taxInput.value = calculateTax(e.target.value);
                    }
                });

                rateList.addEventListener('click', function(e) {
                    if (e.target.classList.contains('remove-rate')) {
                        e.target.closest('.price-rate-row').remove();
                    }
                });
            }

            togglePriceRateSection();

            $('#option_group_id').select2({
                placeholder: '-- Select Group --',
                allowClear: true,
                width: '100%'
            });
        });
    </script>
@endsection
