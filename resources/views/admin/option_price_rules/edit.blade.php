@extends('admin.layouts.app')

@section('title', 'Edit Option Price Rule | Indigo Admin')

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

        .form-group input[disabled] {
            background: #f8fafc;
            color: var(--muted);
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

        .option-rule-columns {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .required-option-simple-box {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            padding: 14px 18px;
            max-height: 420px;
            overflow-y: auto;
        }

        .required-option-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
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

        .price-tier-card {
            border: 1px solid #d9e0ea;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            max-width: 970px;
        }

        .price-tier-header,
        .price-tier-row {
            display: grid;
            grid-template-columns: 250px 250px 250px 90px;
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
            padding-right: 0 !important;
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

        @media (max-width: 700px) {

            .form-grid,
            .price-tier-header,
            .price-tier-row {
                grid-template-columns: 1fr;
            }

            .tier-action {
                justify-content: flex-start;
            }
        }
    </style>
@endsection

@section('content')

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ request()->cookie('dev') == '1' ? 'Edit Option Price Rule' : 'オプョン価格ルールの編集' }}</h1>
                <p>{{ request()->cookie('dev') == '1'
                    ? 'Edit additional option prices by selected options and quantity
                                tiers.'
                    : '選択したオプションと数量の階層に基づいて追加のオプション価格を編集します。' }}
                </p>
            </div>

            <a href="{{ route('admin.option-price-rules.index') }}" class="btn-outline">
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

        <form action="{{ route('admin.option-price-rules.update', $optionPriceRule->option_price_rule_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Rule Information' : '基本情報' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product' : '商品' }}</label>

                    <input type="text"
                        value="{{ $selectedProduct->product_name }} | {{ $selectedProduct->product_code }}" disabled>

                    <input type="hidden" id="product_id" value="{{ $selectedProduct->product_id }}">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Rule Name' : 'ルール名' }}</label>
                    <input type="text" name="rule_name" value="{{ old('rule_name', $optionPriceRule->rule_name) }}"
                        placeholder="{{ request()->cookie('dev') == '1' ? 'For example, Black rope + 200m' : '例：黒いロープ + 200m' }}"
                        required>
                </div>
            </div>

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Option Rule Conditions' : 'オプョン価格ルールの適用条件' }}
            </div>

            <div class="option-rule-columns">
                <div>
                    <p class="muted-text" style="margin-bottom: 12px;">
                        {{ request()->cookie('dev') == '1'
                            ? 'Select the option whose price will be replaced by this
                                            rule.'
                            : 'このルールによって置き換えられるオプションを選択してください。' }}
                    </p>

                    <div class="required-option-simple-box" id="target-options-box">
                        <p class="muted-text">
                            Loading options...
                        </p>
                    </div>
                </div>

                <div>
                    <p class="muted-text" style="margin-bottom: 12px;">
                        {{ request()->cookie('dev') == '1'
                            ? 'Select all options the customer must choose before this
                                            price applies.'
                            : 'この価格を適用するには、お客様が選択する必要のあるオプションをすべて選択してください。' }}
                    </p>

                    <div class="required-option-simple-box" id="required-options-box">
                        <p class="muted-text">
                            Loading options...
                        </p>
                    </div>
                </div>
            </div>
            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Additional Price Tiers' : '数量別追加価格' }}
            </div>

            <div class="price-tier-card">
                <div class="price-tier-header">
                    <div class="price-tier-title">{{ request()->cookie('dev') == '1' ? 'Quantity' : '数量' }}</div>
                    <div class="price-tier-title">{{ request()->cookie('dev') == '1' ? 'Additional Price' : '追加価格' }}</div>
                    <div class="price-tier-title">
                        {{ request()->cookie('dev') == '1' ? 'Additional Price With Tax' : '追加価格（税込）' }}
                    </div>
                    <div class="price-tier-title"></div>
                </div>

                <div id="tier-wrapper">
                    @php
                        $oldTiers = old('tiers');

                        if (!$oldTiers) {
                            $oldTiers = $tiers
                                ->map(function ($tier) {
                                    return [
                                        'min_qty' => $tier->min_qty,
                                        'max_qty' => $tier->max_qty,
                                        'additional_price' => $tier->additional_price,
                                        'additional_price_with_tax' => $tier->additional_price_with_tax,
                                    ];
                                })
                                ->toArray();
                        }

                        if (empty($oldTiers)) {
                            $oldTiers = [
                                [
                                    'min_qty' => '',
                                    'max_qty' => '',
                                    'additional_price' => '',
                                    'additional_price_with_tax' => '',
                                ],
                            ];
                        }
                    @endphp

                    @foreach ($oldTiers as $index => $tier)
                        <div class="price-tier-row">
                            <div class="tier-input-group">
                                <input type="number" name="tiers[{{ $index }}][min_qty]"
                                    value="{{ $tier['min_qty'] ?? '' }}" class="tier-input" min="1"
                                    data-tier-field="min_qty" required>

                                <span class="tier-suffix"></span>

                                <input type="hidden" name="tiers[{{ $index }}][max_qty]"
                                    value="{{ $tier['max_qty'] ?? '' }}">
                            </div>

                            <div class="tier-input-group">
                                <span class="tier-prefix">¥</span>
                                <input type="number" step="0.01" name="tiers[{{ $index }}][additional_price]"
                                    value="{{ $tier['additional_price'] ?? '' }}" class="tier-input" min="0"
                                    data-tier-field="additional_price" required>
                            </div>

                            <div class="tier-input-group">
                                <span class="tier-prefix">¥</span>
                                <input type="number" step="0.01"
                                    name="tiers[{{ $index }}][additional_price_with_tax]"
                                    value="{{ $tier['additional_price_with_tax'] ?? '' }}" class="tier-input"
                                    min="0" data-tier-field="additional_price_with_tax">
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
                {{ request()->cookie('dev') == '1' ? '+ Add Tier' : '+ 数量別価格の追加' }}
            </button>

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $optionPriceRule->is_active) ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.option-price-rules.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? 'Update Option Price Rule' : 'オプョン価格ルールの更新' }}
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
                    'input[name*="[additional_price]"]:not([name*="[additional_price_with_tax]"])');
                const priceWithTaxInput = row.querySelector('input[name*="[additional_price_with_tax]"]');

                if (minInput) {
                    minInput.name = `tiers[${index}][min_qty]`;
                }

                if (maxInput) {
                    maxInput.name = `tiers[${index}][max_qty]`;
                }

                if (priceInput) {
                    priceInput.name = `tiers[${index}][additional_price]`;
                }

                if (priceWithTaxInput) {
                    priceWithTaxInput.name = `tiers[${index}][additional_price_with_tax]`;
                }
            });

            tierIndex = document.querySelectorAll('.price-tier-row').length;
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
                        required
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
                        name="tiers[${currentIndex}][additional_price]"
                        class="tier-input"
                        min="0"
                        data-tier-field="additional_price"
                        required
                    >
                </div>

                <div class="tier-input-group">
                    <span class="tier-prefix">¥</span>
                    <input
                        type="number"
                        step="0.01"
                        name="tiers[${currentIndex}][additional_price_with_tax]"
                        class="tier-input"
                        min="0"
                        data-tier-field="additional_price_with_tax"
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

            if (nextRow && nextRow.classList.contains('price-tier-row')) {
                const nextInput = nextRow.querySelector(`[data-tier-field="${field}"]`);

                if (nextInput) {
                    nextInput.focus();
                }

                return;
            }

            addTier(field);
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-tier')) {
                const items = document.querySelectorAll('.price-tier-row');

                if (items.length <= 1) {
                    alert('ต้องมีอย่างน้อย 1 tier');
                    return;
                }

                e.target.closest('.price-tier-row').remove();
                reindexTiers();
            }
        });

        document.querySelector('form').addEventListener('submit', function() {
            reindexTiers();
        });

        /*
        |--------------------------------------------------------------------------
        | Load required options by selected product
        |--------------------------------------------------------------------------
        */

        const productIdInput = document.getElementById('product_id');
        const targetOptionsBox = document.getElementById('target-options-box');
        const requiredOptionsBox = document.getElementById('required-options-box');

        const oldTargetOptionId = @json(old('target_option_id', $selectedTargetOptionId ?? null));
        const oldOptionIds = @json(old('option_ids', $selectedOptionIds ?? []));

        function escapeHtml(value) {
            return String(value ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function renderOptionSelectors(groups) {
            if (!groups || groups.length === 0) {
                const emptyHtml = `
                <p class="muted-text">
                    No options assigned to this product.
                </p>
            `;
                targetOptionsBox.innerHTML = emptyHtml;
                requiredOptionsBox.innerHTML = emptyHtml;
                return;
            }

            let targetHtml = `<div class="required-option-list">`;
            let requiredHtml = `<div class="required-option-list">`;

            groups.forEach(function(group) {
                const groupName = escapeHtml(group.group_name || '-');
                const groupCode = group.group_code ?
                    ` (${escapeHtml(group.group_code)})` :
                    '';

                targetHtml += `
                <div class="required-option-group">
                    <div class="required-option-group-title">
                        ${groupName}${groupCode}
                    </div>
            `;

                requiredHtml += `
                <div class="required-option-group">
                    <div class="required-option-group-title">
                        ${groupName}${groupCode}
                    </div>
            `;

                group.options.forEach(function(option) {
                    const optionName = escapeHtml(option.option_name || '-');
                    const optionCode = option.option_code ?
                        ` (${escapeHtml(option.option_code)})` :
                        '';
                    const targetChecked = String(oldTargetOptionId || '') === String(option.option_id) ?
                        'checked' : '';
                    const requiredChecked = oldOptionIds.map(String).includes(String(option.option_id)) ?
                        'checked' : '';

                    targetHtml += `
                    <label class="required-option-item">
                        <input
                            type="radio"
                            name="target_option_id"
                            value="${option.option_id}"
                            ${targetChecked}
                            required
                        >
                        <span>${optionName}${optionCode}</span>
                    </label>
                `;

                    requiredHtml += `
                    <label class="required-option-item">
                        <input
                            type="checkbox"
                            name="option_ids[]"
                            value="${option.option_id}"
                            data-required-option-checkbox
                            ${requiredChecked}
                        >
                        <span>${optionName}${optionCode}</span>
                    </label>
                `;
                });

                targetHtml += `</div>`;
                requiredHtml += `</div>`;
            });

            targetHtml += `</div>`;
            requiredHtml += `</div>`;

            targetOptionsBox.innerHTML = targetHtml;
            requiredOptionsBox.innerHTML = requiredHtml;
        }

        function loadProductOptions(productId) {
            if (!productId) {
                const errorHtml = `
                <p class="muted-text">
                    Cannot load product options.
                </p>
            `;
                targetOptionsBox.innerHTML = errorHtml;
                requiredOptionsBox.innerHTML = errorHtml;
                return;
            }

            const loadingHtml = `
            <p class="muted-text">
                Loading options...
            </p>
        `;
            targetOptionsBox.innerHTML = loadingHtml;
            requiredOptionsBox.innerHTML = loadingHtml;

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
                    renderOptionSelectors(data.groups);
                })
                .catch(function(error) {
                    console.error(error);

                    const errorHtml = `
                <p class="muted-text" style="color:#b91c1c;">
                    Cannot load product options.
                </p>
            `;
                    targetOptionsBox.innerHTML = errorHtml;
                    requiredOptionsBox.innerHTML = errorHtml;
                });
        }


        if (productIdInput && productIdInput.value) {
            loadProductOptions(productIdInput.value);
        }
        /*
        |--------------------------------------------------------------------------
        | Auto-calculate additional price with 10% tax
        |--------------------------------------------------------------------------
        */
        const tierWrapper = document.getElementById('tier-wrapper');

        if (tierWrapper) {
            tierWrapper.addEventListener('input', function(e) {
                if (
                    e.target &&
                    e.target.name &&
                    e.target.name.includes('[additional_price]') &&
                    !e.target.name.includes('[additional_price_with_tax]')
                ) {
                    const row = e.target.closest('.price-tier-row');

                    if (row) {
                        const priceWithTaxInput = row.querySelector('input[name*="[additional_price_with_tax]"]');

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
    </script>
@endsection
