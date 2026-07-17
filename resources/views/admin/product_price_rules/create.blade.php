@extends('admin.layouts.app')

@section('title',
    isset($duplicateRule)
    ? __('admin.product_price_rules.duplicate.title') . ' | Indigo Admin'
    : 'Add
    Product Price Rule | Indigo Admin')

@section('css')
    <style>
        .option-select-box {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--bg);
            padding: 16px;
            max-height: 360px;
            overflow: auto;
        }

        .option-group-block {
            margin-bottom: 18px;
        }

        .option-group-block:last-child {
            margin-bottom: 0;
        }

        .option-group-title {
            margin-bottom: 8px;
            font-weight: 700;
            color: var(--fg-dark);
            font-size: 14px;
        }

        .option-check-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 8px;
        }

        .option-check-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            font-size: 14px;
            color: var(--fg);
        }

        .tier-item {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
            background: #fff;
            margin-bottom: 16px;
        }

        .tier-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .tier-header h3 {
            margin: 0;
            font-size: 16px;
            color: var(--fg-dark);
        }

        @media (max-width: 800px) {
            .option-check-list {
                grid-template-columns: 1fr;
            }
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

        .muted-text {
            color: var(--muted);
            font-size: 14px;
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

        .duplicate-notice {
            margin-bottom: 18px;
            padding: 12px 16px;
            border: 1px solid #c4b5fd;
            border-radius: 8px;
            background: #f5f3ff;
            color: #5b21b6;
            font-size: 14px;
        }

        .btn-danger-light {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-danger-light:hover {
            background: #fee2e2;
        }

        .required-option-simple-box {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            padding: 14px 18px;
            max-height: 420px;
            overflow-y: auto;
        }

        .required-option-category-link {
            display: inline-block;
            margin-bottom: 8px;
            color: #1d4ed8;
            font-size: 14px;
            text-decoration: underline;
            font-weight: 500;
        }

        .required-option-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .required-option-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1f3b66;
            font-size: 14px;
            line-height: 1.35;
            cursor: pointer;
        }

        .required-option-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
            flex-shrink: 0;
        }

        .required-option-group {
            margin-bottom: 18px;
        }

        .required-option-group:last-child {
            margin-bottom: 0;
        }

        .required-option-group-title {
            margin: 12px 0 8px;
            padding-bottom: 7px;
            border-bottom: 1px solid #d1d5db;
            color: #111827;
            font-size: 14px;
            font-weight: 700;
        }

        .required-option-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #1f3b66;
            font-size: 14px;
            line-height: 1.35;
            cursor: pointer;
            margin-bottom: 8px;
        }

        .required-option-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
            flex-shrink: 0;
        }





        .price-tier-title {
            padding: 14px 16px;
            font-size: 15px;
            font-weight: 700;
            color: #23324a;
        }

        .price-tier-card {
            border: 1px solid #d9e0ea;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            max-width: 680px;
        }

        .price-tier-header,
        .price-tier-row {
            display: grid;
            grid-template-columns: 280px 280px 90px 70px;
            align-items: center;
        }

        .price-tier-header {
            background: #f8fafc;
            border-bottom: 1px solid #d9e0ea;
        }

        .price-tier-title {
            padding: 14px 16px;
            font-size: 15px;
            font-weight: 700;
            color: #23324a;
        }

        .price-tier-row {
            padding: 14px 16px;
            border-bottom: 1px solid #eef2f7;
        }

        .price-tier-row>div {
            padding-right: 16px;
        }

        .price-tier-row>div:last-child {
            padding-right: 0;
        }

        .price-tier-row:last-child {
            border-bottom: 0;
        }

        .tier-input-group {
            display: flex;
            align-items: stretch;
            width: 100%;
        }

        .tier-input {
            flex: 1;
            height: 38px;
            border: 1px solid #cfd7e3;
            padding: 0 14px;
            font-size: 15px;
            color: #22314a;
            outline: none;
            background: #fff;
        }

        .tier-input:first-child {
            border-radius: 6px 0 0 6px;
        }

        .tier-input:last-child {
            border-radius: 0 6px 6px 0;
        }

        .tier-display {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 0 !important;
        }

        .tier-display input[type="radio"] {
            width: 18px;
            height: 18px;
            accent-color: #2563eb;
        }

        .tier-prefix,
        .tier-suffix {
            min-width: 58px;
            height: 38px;
            padding: 0 12px;
            border: 1px solid #cfd7e3;
            background: #f8fafc;
            color: #23324a;
            font-size: 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .tier-prefix {
            border-right: 0;
            border-radius: 6px 0 0 6px;
        }

        .tier-suffix {
            border-left: 0;
            border-radius: 0 6px 6px 0;
        }

        .tier-action {
            display: flex;
            justify-content: center;
        }

        .remove-tier {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
            border-radius: 6px;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        @media (max-width: 700px) {

            .price-tier-header,
            .price-tier-row {
                grid-template-columns: 1fr;
            }

            .price-tier-header .price-tier-title:last-child {
                display: none;
            }

            .tier-action {
                justify-content: flex-start;
            }
        }

        .price-tier-card {
            border: 1px solid #d9e0ea;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            max-width: 1060px;
        }

        .price-tier-header,
        .price-tier-row {
            display: grid;
            grid-template-columns: 250px 250px 250px 90px 90px;
            align-items: center;
        }

        .price-tier-header {
            background: #f8fafc;
            border-bottom: 1px solid #d9e0ea;
        }

        .price-tier-title {
            padding: 14px 16px;
            font-size: 15px;
            font-weight: 700;
            color: #23324a;
        }

        .price-tier-row {
            padding: 14px 16px;
            border-bottom: 1px solid #eef2f7;
        }

        .price-tier-row>div {
            padding-right: 16px;
        }

        .price-tier-row>div:last-child {
            padding-right: 0;
        }

        .tier-display,
        .tier-action {
            display: flex;
            align-items: center;
            justify-content: center;
            padding-right: 0 !important;
        }
    </style>
@endsection

@section('content')

    @php
        $isDuplicate = isset($duplicateRule);
        $formProductId = old('product_id', $selectedProductId ?? '');
        $formRuleName = old('rule_name', $duplicateRuleName ?? '');
        $formSortOrder = old('sort_order', $isDuplicate ? $duplicateRule->sort_order : 0);
        $formSelectedOptionIds = collect(old('option_ids', $selectedOptionIds ?? []))
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->toArray();
        $formIsActive = old('is_active', $isDuplicate ? (int) $duplicateRule->is_active : 1);
    @endphp

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>
                    @if (request()->cookie('dev') === '1')
                        {{ $isDuplicate ? 'Duplicate Product Price Rule' : 'Add Product Price Rule' }}
                    @else
                        {{ $isDuplicate ? '商品価格ルールを複製' : '商品価格ルールを追加' }}
                    @endif
                </h1>
                <p>
                    {{ request()->cookie('dev') === '1'
                        ? ($isDuplicate
                            ? 'Create a new pricing rule based on the duplicated rule.'
                            : 'Create pricing rules by selected options and quantity tiers.')
                        : ($isDuplicate
                            ? '複製したルールをもとに新しい価格ルールを作成します。'
                            : '選択したオプションと数量区分に基づいて価格ルールを作成します。') }}
                </p>
            </div>

            <a href="{{ route('admin.product-price-rules.index') }}" class="btn-outline">
                {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
            </a>
        </div>

        @if ($isDuplicate)
            <div class="duplicate-notice">
                {{ __('admin.product_price_rules.duplicate.notice', ['name' => $duplicateRule->rule_name ?? '-']) }}
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

        <form action="{{ route('admin.product-price-rules.store') }}" method="POST">
            @csrf

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Rule Information' : 'ルール情報' }}
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>
                        {{ request()->cookie('dev') == '1' ? 'Product' : '商品' }}
                    </label>
                    <select name="product_id" id="product_id" required>
                        <option value="">-- Select Product --</option>

                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}"
                                {{ $formProductId == $product->product_id ? 'selected' : '' }}>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>
                        {{ request()->cookie('dev') == '1' ? 'Rule Name' : 'ルール名' }}
                    </label>
                    <input type="text" name="rule_name" value="{{ $formRuleName }}"
                        placeholder="For example, 20mm + One Side">
                </div>

                <div class="form-group" style="display: none">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ $formSortOrder }}">
                </div>
            </div>

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Required Options' : '必須オプション' }}
            </div>

            <p class="muted-text" style="margin-bottom: 12px;">
                {{ request()->cookie('dev') == '1' ? 'Select all the options that the customer must choose in order to use this price rate.' : '選択したオプションと数量区分に基づいて価格ルールを作成します。' }}
            </p>

            <div class="required-option-simple-box" id="required-options-box">
                <p class="muted-text">
                    {{ request()->cookie('dev') == '1' ? 'Please select a product first.' : 'まず商品を選択してください。' }}
                </p>
            </div>

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Price Tiers' : '数量区分' }}
            </div>

            <div class="price-tier-card">
                <div class="price-tier-header">
                    <div class="price-tier-title">
                        {{ request()->cookie('dev') == '1' ? 'Quantity' : '数量' }}
                    </div>
                    <div class="price-tier-title">
                        {{ request()->cookie('dev') == '1' ? 'Unit Price' : '単価' }}
                    </div>
                    <div class="price-tier-title">
                        {{ request()->cookie('dev') == '1' ? 'Unit Price With Tax' : '税込単価' }}
                    </div>
                    <div class="price-tier-title">
                        {{ request()->cookie('dev') == '1' ? 'Display' : '表示' }}
                    </div>
                    <div class="price-tier-title"></div>
                </div>

                <div id="tier-wrapper">
                    @php
                        $oldTiers = old(
                            'tiers',
                            $tiers ?? [
                                [
                                    'min_qty' => '',
                                    'max_qty' => '',
                                    'unit_price' => '',
                                    'unit_price_with_tax' => '',
                                ],
                            ],
                        );
                    @endphp

                    @foreach ($oldTiers as $index => $tier)
                        <div class="price-tier-row">
                            <div class="tier-input-group">
                                <input type="number" name="tiers[{{ $index }}][min_qty]"
                                    value="{{ $tier['min_qty'] ?? '' }}" class="tier-input" min="1"
                                    data-tier-field="min_qty">
                                <span class="tier-suffix"></span>

                                <input type="hidden" name="tiers[{{ $index }}][max_qty]"
                                    value="{{ $tier['max_qty'] ?? '' }}">
                            </div>

                            <div class="tier-input-group">
                                <span class="tier-prefix">¥</span>
                                <input type="number" step="0.01" name="tiers[{{ $index }}][unit_price]"
                                    value="{{ $tier['unit_price'] ?? '' }}" class="tier-input" min="0"
                                    data-tier-field="unit_price">
                            </div>
                            <div class="tier-input-group">
                                <span class="tier-prefix">¥</span>
                                <input type="number" step="0.01" name="tiers[{{ $index }}][unit_price_with_tax]"
                                    value="{{ $tier['unit_price_with_tax'] ?? '' }}" class="tier-input" min="0"
                                    data-tier-field="unit_price_with_tax">
                            </div>
                            <div class="tier-display">
                                <input type="radio" name="display_tier_index" value="{{ $index }}"
                                    {{ old('display_tier_index', $displayTierIndex ?? 0) == $index ? 'checked' : '' }}>
                            </div>

                            <div class="tier-action">
                                <button type="button" class="remove-tier">
                                    {{ request()->cookie('dev') == '1' ? 'Remove' : '削除' }}
                                </button>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <br>

            <button type="button" id="add-tier" class="btn-outline">
                {{ request()->cookie('dev') == '1' ? '+ Add Tier' : '+ 数量区分を追加' }}
            </button>



            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}
            </div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ $formIsActive ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.product-price-rules.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? 'Save Price Rule' : '保存' }}
                </button>
            </div>
        </form>
    </div>

@endsection

@section('js')
    <script>
        let tierIndex = document.querySelectorAll('.price-tier-row').length;

        function reindexTiers() {
            document.querySelectorAll('.price-tier-row').forEach(function(row, index) {
                const minInput = row.querySelector('input[name*="[min_qty]"]');
                const maxInput = row.querySelector('input[name*="[max_qty]"]');
                const priceInput = row.querySelector(
                    'input[name*="[unit_price]"]:not([name*="[unit_price_with_tax]"])');
                const priceWithTaxInput = row.querySelector('input[name*="[unit_price_with_tax]"]');
                const displayRadio = row.querySelector('input[name="display_tier_index"]');

                if (minInput) {
                    minInput.name = `tiers[${index}][min_qty]`;
                }

                if (maxInput) {
                    maxInput.name = `tiers[${index}][max_qty]`;
                }

                if (priceInput) {
                    priceInput.name = `tiers[${index}][unit_price]`;
                }
                if (priceWithTaxInput) {
                    priceWithTaxInput.name = `tiers[${index}][unit_price_with_tax]`;
                }

                if (displayRadio) {
                    displayRadio.value = index;
                }
            });

            tierIndex = document.querySelectorAll('.price-tier-row').length;
        }

        function ensureOneDisplayTier() {
            const radios = document.querySelectorAll('input[name="display_tier_index"]');

            if (!radios.length) {
                return;
            }

            const hasChecked = Array.from(radios).some(function(radio) {
                return radio.checked;
            });

            if (!hasChecked) {
                radios[0].checked = true;
            }
        }

        function addTier(focusField = 'min_qty') {
            const wrapper = document.getElementById('tier-wrapper');

            const currentIndex = tierIndex;

            const html = `
        <div class="price-tier-row">
            <div class="tier-input-group">
                <input
                    type="number"
                    name="tiers[${currentIndex}][min_qty]"
                    class="tier-input"
                    min="1"
                    data-tier-field="min_qty"
                >
                <span class="tier-suffix"></span>

                <input
                    type="hidden"
                    name="tiers[${currentIndex}][max_qty]"
                    value=""
                >
            </div>

            <div class="tier-input-group">
                <span class="tier-prefix">¥</span>
                <input
                    type="number"
                    step="0.01"
                    name="tiers[${currentIndex}][unit_price]"
                    class="tier-input"
                    min="0"
                    data-tier-field="unit_price"
                >
            </div>

            <div class="tier-input-group">
                <span class="tier-prefix">¥</span>
                <input
                    type="number"
                    step="0.01"
                    name="tiers[${currentIndex}][unit_price_with_tax]"
                    class="tier-input"
                    min="0"
                    data-tier-field="unit_price_with_tax"
                >
            </div>

            <div class="tier-display">
                <input
                    type="radio"
                    name="display_tier_index"
                    value="${currentIndex}"
                >
            </div>

            <div class="tier-action">
                <button type="button" class="remove-tier">
                    Remove
                </button>
            </div>
        </div>
    `;

            wrapper.insertAdjacentHTML('beforeend', html);
            tierIndex++;

            ensureOneDisplayTier();

            const newRow = wrapper.querySelector('.price-tier-row:last-child');

            if (newRow) {
                const focusInput = newRow.querySelector(`[data-tier-field="${focusField}"]`);

                if (focusInput) {
                    setTimeout(function() {
                        focusInput.focus();
                    }, 50);
                }
            }
        }

        document.getElementById('add-tier').addEventListener('click', function() {
            addTier('min_qty');
        });
        document.getElementById('tier-wrapper').addEventListener('keydown', function(e) {
            if (e.key !== 'Enter') {
                return;
            }

            const input = e.target;

            if (!input.classList.contains('tier-input')) {
                return;
            }

            e.preventDefault();

            const currentRow = input.closest('.price-tier-row');
            const field = input.dataset.tierField || 'min_qty';

            if (!currentRow) {
                return;
            }

            const nextRow = currentRow.nextElementSibling;

            /*
            |--------------------------------------------------------------------------
            | ถ้ามี tier ถัดไปอยู่แล้ว
            | ให้ focus ช่องประเภทเดียวกันของ row ถัดไป
            |--------------------------------------------------------------------------
            */
            if (nextRow && nextRow.classList.contains('price-tier-row')) {
                const nextInput = nextRow.querySelector(`[data-tier-field="${field}"]`);

                if (nextInput) {
                    nextInput.focus();
                }

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | ถ้ายังไม่มี tier ถัดไป
            | ให้ add tier ใหม่ แล้ว focus ช่องประเภทเดียวกัน
            |--------------------------------------------------------------------------
            */
            addTier(field);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-tier')) {
                const items = document.querySelectorAll('.price-tier-row');

                if (items.length <= 1) {
                    alert('ต้องมีอย่างน้อย 1 tier');
                    return;
                }

                const row = e.target.closest('.price-tier-row');
                const wasDisplay = row.querySelector('input[name="display_tier_index"]')?.checked;

                row.remove();

                reindexTiers();

                if (wasDisplay) {
                    ensureOneDisplayTier();
                }
            }
        });

        document.querySelector('form').addEventListener('submit', function() {
            reindexTiers();
            ensureOneDisplayTier();
        });

        /*
        |--------------------------------------------------------------------------
        | Load required options by selected product
        |--------------------------------------------------------------------------
        */

        const productSelect = document.getElementById('product_id');
        const requiredOptionsBox = document.getElementById('required-options-box');
        const oldOptionIds = @json($formSelectedOptionIds);
        const isDuplicate = @json($isDuplicate);
        const duplicateUrl = @json($isDuplicate ? route('admin.product-price-rules.duplicate', $duplicateRule->rule_id) : null);

        function renderRequiredOptions(groups) {
            if (!groups || groups.length === 0) {
                requiredOptionsBox.innerHTML = `
                    <p class="muted-text">
                        No options assigned to this product.
                    </p>
                `;
                return;
            }

            let html = `
                <div class="required-option-list">
            `;

            groups.forEach(function(group) {
                html += `
                    <div class="required-option-group">
                        <div class="required-option-group-title">
                            ${group.group_name || '-'}
                        </div>
                `;

                group.options.forEach(function(option) {
                    const checked = oldOptionIds.map(String).includes(String(option.option_id)) ?
                        'checked' : '';

                    html += `
                        <label class="required-option-item">
                            <input
                                type="checkbox"
                                name="option_ids[]"
                                value="${option.option_id}"
                                ${checked}
                            >
                            <span>${option.option_name}</span>
                        </label>
                    `;
                });

                html += `
                    </div>
                `;
            });

            html += `</div>`;

            requiredOptionsBox.innerHTML = html;
        }

        function loadProductOptions(productId) {
            if (!productId) {
                requiredOptionsBox.innerHTML = `
                    <p class="muted-text">
                        Please select a product first.
                    </p>
                `;
                return;
            }

            requiredOptionsBox.innerHTML = `
                <p class="muted-text">
                    Loading options...
                </p>
            `;

            const url = `{{ url('admin-panel/product-price-rules/product-options') }}/${productId}`;

            fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('HTTP ' + response.status);
                    }

                    return response.json();
                })
                .then(function(data) {
                    renderRequiredOptions(data.groups);
                })
                .catch(function(error) {
                    console.error(error);

                    requiredOptionsBox.innerHTML = `
                        <p class="muted-text" style="color:#b91c1c;">
                            Cannot load product options.
                        </p>
                    `;
                });
        }

        if (productSelect) {
            productSelect.addEventListener('change', function() {
                if (isDuplicate && duplicateUrl && this.value) {
                    const url = new URL(duplicateUrl, window.location.origin);
                    url.searchParams.set('product_id', this.value);
                    window.location.href = url.toString();

                    return;
                }

                loadProductOptions(this.value);
            });

            if (productSelect.value) {
                loadProductOptions(productSelect.value);
            }
        }

        // Auto-calculate unit price with 10% tax
        const tierWrapper = document.getElementById('tier-wrapper');
        if (tierWrapper) {
            tierWrapper.addEventListener('input', function(e) {
                if (e.target && e.target.name && e.target.name.includes('[unit_price]') && !e.target.name.includes(
                        '[unit_price_with_tax]')) {
                    const row = e.target.closest('.price-tier-row');
                    if (row) {
                        const priceWithTaxInput = row.querySelector('input[name*="[unit_price_with_tax]"]');
                        if (priceWithTaxInput) {
                            const priceVal = parseFloat(e.target.value);
                            if (!isNaN(priceVal)) {
                                priceWithTaxInput.value = (priceVal * 1.1).toFixed(2);
                            } else {
                                priceWithTaxInput.value = '';
                            }
                        }
                    }
                }
            });
        }

        ensureOneDisplayTier();
    </script>
@endsection
