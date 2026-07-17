@extends('admin.layouts.app')

@section('title',
    isset($duplicateRule)
    ? 'Duplicate Option Price Rule | Indigo Admin'
    : 'Add Option Price Rule | Indigo
    Admin')

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

        .duplicate-notice {
            margin-bottom: 20px;
            padding: 12px 16px;
            border: 1px solid #ddd6fe;
            border-radius: 8px;
            background: #f5f3ff;
            color: #6d28d9;
            font-size: 14px;
            line-height: 1.5;
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

        .required-option-item input[type="checkbox"],
        .required-option-item input[type="radio"] {
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
            min-width: 0;
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

        @media (max-width: 900px) {
            .option-rule-columns {
                grid-template-columns: 1fr;
            }

            .price-tier-card {
                max-width: 100%;
                overflow-x: auto;
            }

            .price-tier-header,
            .price-tier-row {
                min-width: 840px;
            }
        }

        @media (max-width: 700px) {
            .form-grid {
                grid-template-columns: 1fr;
            }

            .form-header {
                flex-direction: column;
            }

            .checkbox-grid {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-wrap: wrap;
            }
        }
    </style>
@endsection

@section('content')

    @php
        /*
    |--------------------------------------------------------------------------
    | Determine page mode
    |--------------------------------------------------------------------------
    */
        $isDuplicate = isset($duplicateRule);

        /*
    |--------------------------------------------------------------------------
    | Rule name
    |--------------------------------------------------------------------------
    |
    | old() has first priority when validation fails.
    | Duplicate name is used when opening duplicate page for the first time.
    |
    */
        $formRuleName = old('rule_name', $duplicateRuleName ?? '');

        /*
    |--------------------------------------------------------------------------
    | Selected product
    |--------------------------------------------------------------------------
    */
        $formProductId = old('product_id', $selectedProductId ?? '');

        /*
    |--------------------------------------------------------------------------
    | Target option
    |--------------------------------------------------------------------------
    */
        $formTargetOptionId = old('target_option_id', $selectedTargetOptionId ?? null);

        /*
    |--------------------------------------------------------------------------
    | Required condition options
    |--------------------------------------------------------------------------
    */
        $formSelectedOptionIds = collect(old('option_ids', $selectedOptionIds ?? []))
            ->map(fn($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        /*
    |--------------------------------------------------------------------------
    | Price tiers
    |--------------------------------------------------------------------------
    */
        $formTiers = old(
            'tiers',
            $tiers ?? [
                [
                    'min_qty' => '',
                    'max_qty' => '',
                    'additional_price' => '',
                    'additional_price_with_tax' => '',
                ],
            ],
        );

        /*
    |--------------------------------------------------------------------------
    | Active status
    |--------------------------------------------------------------------------
    */
        $formIsActive = old('is_active', $isDuplicate ? (int) ($duplicateRule->is_active ?? 0) : 1);
    @endphp

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>
                    {{ request()->cookie('dev') === '1'
                        ? ($isDuplicate
                            ? 'Duplicate Option Price Rule'
                            : 'Add Option Price Rule')
                        : ($isDuplicate
                            ? 'オプション価格ルールを複製'
                            : 'オプション価格ルールを追加') }}
                </h1>

                <p>
                    @if (request()->cookie('dev') === '1')
                        @if ($isDuplicate)
                            Copy the existing rule, select another product and save it as a new rule.
                        @else
                            Create additional option prices by selected options and quantity tiers.
                        @endif
                    @else
                        @if ($isDuplicate)
                            既存のルールをコピーし、別の商品を選択して新しいルールとして保存します。
                        @else
                            選択したオプションと数量区分に基づいて追加料金を設定します。
                        @endif
                    @endif
                </p>
            </div>

            <a href="{{ route('admin.option-price-rules.index') }}" class="btn-outline">
                {{ request()->cookie('dev') === '1' ? 'Back' : '戻る' }}
            </a>
        </div>

        @if ($isDuplicate)
            <div class="duplicate-notice">
                @if (request()->cookie('dev') === '1')
                    This is a copy of
                    <strong>{{ $duplicateRule->rule_name }}</strong>.

                    You may change the product, rule name, options and prices before saving.
                    The original rule will not be changed.
                @else
                    これは
                    <strong>{{ $duplicateRule->rule_name }}</strong>
                    のコピーです。

                    保存する前に、商品、ルール名、オプション、価格を変更できます。
                    元のルールは変更されません。
                @endif
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

        <form id="option-price-rule-form" action="{{ route('admin.option-price-rules.store') }}" method="POST">
            @csrf

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Rule Information' : 'ルール情報' }}
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label for="product_id">
                        {{ request()->cookie('dev') == '1' ? 'Product' : '商品' }}
                    </label>

                    <select name="product_id" id="product_id" required>
                        <option value="">
                            {{ request()->cookie('dev') == '1' ? '-- Select Product --' : '-- 商品を選択してください --' }}
                        </option>

                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}"
                                {{ (string) $formProductId === (string) $product->product_id ? 'selected' : '' }}>
                                {{ $product->product_name }}
                                |
                                {{ $product->product_code }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="rule_name">
                        {{ request()->cookie('dev') == '1' ? 'Rule Name' : 'ルール名' }}
                    </label>

                    <input type="text" id="rule_name" name="rule_name" value="{{ $formRuleName }}"
                        placeholder="For example, Black rope + 200m" required>
                </div>
            </div>

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Option Rule Conditions' : 'ルール条件' }}
            </div>

            <div class="option-rule-columns">
                <div>
                    <p class="muted-text" style="margin-bottom: 12px;">
                        {{ request()->cookie('dev') == '1' ? 'Select the option whose price will be replaced by this rule.' : 'このルールによって置き換えられるオプションを選択してください。' }}
                    </p>

                    <div class="required-option-simple-box" id="target-options-box">
                        <p class="muted-text">
                            {{ request()->cookie('dev') == '1' ? 'Select a product first.' : '商品を選択してください。' }}
                        </p>
                    </div>
                </div>

                <div>
                    <p class="muted-text" style="margin-bottom: 12px;">
                        {{ request()->cookie('dev') == '1' ? 'Select all options the customer must choose before this price applies.' : 'この価格を適用するには、お客様が選択する必要のあるオプションをすべて選択してください。' }}
                    </p>

                    <div class="required-option-simple-box" id="required-options-box">
                        <p class="muted-text">
                            {{ request()->cookie('dev') == '1' ? 'Select a product first.' : '商品を選択してください。' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Additional Price Tiers' : '追加料金区分' }}
            </div>

            <div class="price-tier-card">
                <div class="price-tier-header">
                    <div class="price-tier-title">
                        {{ request()->cookie('dev') == '1' ? 'Quantity' : '数量' }}
                    </div>

                    <div class="price-tier-title">
                        {{ request()->cookie('dev') == '1' ? 'Additional Price' : '追加料金' }}
                    </div>

                    <div class="price-tier-title">
                        {{ request()->cookie('dev') == '1' ? 'Additional Price With Tax' : '税込追加料金' }}
                    </div>

                    <div class="price-tier-title"></div>
                </div>

                <div id="tier-wrapper">
                    @foreach ($formTiers as $index => $tier)
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
                                <span class="tier-prefix">
                                    ¥
                                </span>

                                <input type="number" step="0.01" name="tiers[{{ $index }}][additional_price]"
                                    value="{{ $tier['additional_price'] ?? '' }}" class="tier-input" min="0"
                                    data-tier-field="additional_price" required>
                            </div>

                            <div class="tier-input-group">
                                <span class="tier-prefix">
                                    ¥
                                </span>

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
                {{ request()->cookie('dev') == '1' ? '+ Add Tier' : '+ 追加料金区分' }}
            </button>

            <div class="section-title">
                {{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}
            </div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ $formIsActive ? 'checked' : '' }}>

                    {{ request()->cookie('dev') == '1' ? 'Active' : '有効' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.option-price-rules.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ $isDuplicate
                        ? (request()->cookie('dev') == '1'
                            ? 'Save Duplicated Rule'
                            : '重複したルールを保存')
                        : (request()->cookie('dev') == '1'
                            ? 'Save Option Price Rule'
                            : 'オプション価格ルールを保存') }}
                </button>
            </div>
        </form>
    </div>

@endsection

@section('js')
    <script>
        /*
            |--------------------------------------------------------------------------
            | PHP values
            |--------------------------------------------------------------------------
            */
        const isDuplicate = @json($isDuplicate);
        const selectedTargetOptionId = @json($formTargetOptionId);
        const selectedRequiredOptionIds = @json($formSelectedOptionIds);

        const duplicateUrl = @json($isDuplicate ? route('admin.option-price-rules.duplicate', $duplicateRule->option_price_rule_id) : null);

        /*
        |--------------------------------------------------------------------------
        | Tier rows
        |--------------------------------------------------------------------------
        */
        const tierWrapper = document.getElementById('tier-wrapper');
        const addTierButton = document.getElementById('add-tier');
        const optionPriceRuleForm = document.getElementById('option-price-rule-form');

        let tierIndex = document.querySelectorAll('.price-tier-row').length;

        function reindexTiers() {
            document
                .querySelectorAll('.price-tier-row')
                .forEach(function(row, index) {
                    const minInput = row.querySelector(
                        'input[name*="[min_qty]"]'
                    );

                    const maxInput = row.querySelector(
                        'input[name*="[max_qty]"]'
                    );

                    const priceInput = row.querySelector(
                        'input[name*="[additional_price]"]:not([name*="[additional_price_with_tax]"])'
                    );

                    const priceWithTaxInput = row.querySelector(
                        'input[name*="[additional_price_with_tax]"]'
                    );

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
                        priceWithTaxInput.name =
                            `tiers[${index}][additional_price_with_tax]`;
                    }
                });

            tierIndex = document.querySelectorAll('.price-tier-row').length;
        }

        function addTier(focusField = 'min_qty') {
            if (!tierWrapper) {
                return;
            }

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
                    <span class="tier-prefix">
                        ¥
                    </span>

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
                    <span class="tier-prefix">
                        ¥
                    </span>

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
                    <button
                        type="button"
                        class="remove-tier"
                    >
                        Remove
                    </button>
                </div>
            </div>
        `;

            tierWrapper.insertAdjacentHTML('beforeend', html);

            tierIndex++;

            const newRow = tierWrapper.querySelector(
                '.price-tier-row:last-child'
            );

            if (!newRow) {
                return;
            }

            const focusInput = newRow.querySelector(
                `[data-tier-field="${focusField}"]`
            );

            if (focusInput) {
                setTimeout(function() {
                    focusInput.focus();
                }, 50);
            }
        }

        if (addTierButton) {
            addTierButton.addEventListener('click', function() {
                addTier('min_qty');
            });
        }

        if (tierWrapper) {
            tierWrapper.addEventListener('keydown', function(e) {
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

                if (
                    nextRow &&
                    nextRow.classList.contains('price-tier-row')
                ) {
                    const nextInput = nextRow.querySelector(
                        `[data-tier-field="${field}"]`
                    );

                    if (nextInput) {
                        nextInput.focus();
                    }

                    return;
                }

                addTier(field);
            });

            tierWrapper.addEventListener('click', function(e) {
                const removeButton = e.target.closest('.remove-tier');

                if (!removeButton) {
                    return;
                }

                const rows = document.querySelectorAll('.price-tier-row');

                if (rows.length <= 1) {
                    alert('ต้องมีอย่างน้อย 1 tier');
                    return;
                }

                const row = removeButton.closest('.price-tier-row');

                if (row) {
                    row.remove();
                    reindexTiers();
                }
            });

            /*
            |--------------------------------------------------------------------------
            | Auto-calculate additional price with 10% tax
            |--------------------------------------------------------------------------
            */
            tierWrapper.addEventListener('input', function(e) {
                if (
                    !e.target ||
                    !e.target.name ||
                    !e.target.name.includes('[additional_price]') ||
                    e.target.name.includes('[additional_price_with_tax]')
                ) {
                    return;
                }

                const row = e.target.closest('.price-tier-row');

                if (!row) {
                    return;
                }

                const priceWithTaxInput = row.querySelector(
                    'input[name*="[additional_price_with_tax]"]'
                );

                if (!priceWithTaxInput) {
                    return;
                }

                const priceValue = parseFloat(e.target.value);

                if (isNaN(priceValue)) {
                    priceWithTaxInput.value = '';
                    return;
                }

                priceWithTaxInput.value = (priceValue * 1.1).toFixed(2);
            });
        }

        if (optionPriceRuleForm) {
            optionPriceRuleForm.addEventListener('submit', function() {
                reindexTiers();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Product options
        |--------------------------------------------------------------------------
        */
        const productSelect = document.getElementById('product_id');
        const targetOptionsBox = document.getElementById('target-options-box');
        const requiredOptionsBox = document.getElementById('required-options-box');

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

            const normalizedRequiredIds = (
                Array.isArray(selectedRequiredOptionIds) ?
                selectedRequiredOptionIds : []
            ).map(String);

            let targetHtml = `
            <div class="required-option-list">
        `;

            let requiredHtml = `
            <div class="required-option-list">
        `;

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

                const groupOptions = Array.isArray(group.options) ?
                    group.options : [];

                groupOptions.forEach(function(option) {
                    const optionId = String(option.option_id);
                    const optionName = escapeHtml(option.option_name || '-');
                    const optionCode = option.option_code ?
                        ` (${escapeHtml(option.option_code)})` :
                        '';

                    const targetChecked =
                        String(selectedTargetOptionId || '') === optionId ?
                        'checked' :
                        '';

                    const requiredChecked =
                        normalizedRequiredIds.includes(optionId) ?
                        'checked' :
                        '';

                    targetHtml += `
                    <label class="required-option-item">
                        <input
                            type="radio"
                            name="target_option_id"
                            value="${optionId}"
                            ${targetChecked}
                            required
                        >

                        <span>
                            ${optionName}${optionCode}
                        </span>
                    </label>
                `;

                    requiredHtml += `
                    <label class="required-option-item">
                        <input
                            type="checkbox"
                            name="option_ids[]"
                            value="${optionId}"
                            data-required-option-checkbox
                            ${requiredChecked}
                        >

                        <span>
                            ${optionName}${optionCode}
                        </span>
                    </label>
                `;
                });

                targetHtml += `
                </div>
            `;

                requiredHtml += `
                </div>
            `;
            });

            targetHtml += `
            </div>
        `;

            requiredHtml += `
            </div>
        `;

            targetOptionsBox.innerHTML = targetHtml;
            requiredOptionsBox.innerHTML = requiredHtml;
        }

        function loadProductOptions(productId) {
            if (!productId) {
                const selectProductHtml = `
                <p class="muted-text">
                    Please select a product first.
                </p>
            `;

                targetOptionsBox.innerHTML = selectProductHtml;
                requiredOptionsBox.innerHTML = selectProductHtml;

                return;
            }

            const loadingHtml = `
            <p class="muted-text">
                Loading options...
            </p>
        `;

            targetOptionsBox.innerHTML = loadingHtml;
            requiredOptionsBox.innerHTML = loadingHtml;

            const productOptionsBaseUrl =
                @json(url('admin-panel/product-price-rules/product-options'));

            const url = `${productOptionsBaseUrl}/${productId}`;

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
                    renderOptionSelectors(data.groups || []);
                })
                .catch(function(error) {
                    console.error(error);

                    const errorHtml = `
                    <p
                        class="muted-text"
                        style="color:#b91c1c;"
                    >
                        Cannot load product options.
                    </p>
                `;

                    targetOptionsBox.innerHTML = errorHtml;
                    requiredOptionsBox.innerHTML = errorHtml;
                });
        }

        if (productSelect) {
            productSelect.addEventListener('change', function() {
                const productId = this.value;

                if (!productId) {
                    loadProductOptions('');
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | Duplicate mode
                |--------------------------------------------------------------------------
                |
                | Reload through duplicate route so the controller can match the
                | source options with the options assigned to the new product.
                |
                */
                if (isDuplicate && duplicateUrl) {
                    const url = new URL(
                        duplicateUrl,
                        window.location.origin
                    );

                    url.searchParams.set('product_id', productId);

                    window.location.href = url.toString();

                    return;
                }

                loadProductOptions(productId);
            });

            if (productSelect.value) {
                loadProductOptions(productSelect.value);
            }
        }
    </script>
@endsection
