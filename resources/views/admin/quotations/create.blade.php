@extends('admin.layouts.app')

@section('title', 'Create Quotation | Indigo Admin')


@section('css')
<style>
    .quotation-page-card {
        max-width: 1180px;
        margin: 0 auto;
        padding: 24px;
    }

    .quotation-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
        margin-top: 20px;
    }

    .quotation-form-group {
        margin-bottom: 16px;
    }

    .quotation-form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 14px;
        font-weight: 700;
        color: var(--fg-dark);
    }

    .quotation-form-group input,
    .quotation-form-group select,
    .quotation-form-group textarea {
        width: 100%;
        min-height: 42px;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px 12px;
        background: #fff;
        color: var(--fg-dark);
        font-size: 14px;
        outline: none;
        font-family: inherit;
    }

    .quotation-form-group textarea {
        resize: vertical;
        line-height: 1.5;
    }

    .quotation-form-full {
        grid-column: 1 / -1;
    }

    .quotation-section-title {
        margin: 26px 0 16px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
        font-size: 20px;
        font-weight: 800;
        color: var(--fg-dark);
    }

    .quotation-item-box {
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 18px;
        background: #fff;
    }

    .quotation-options-area {
        margin-top: 16px;
    }

    .quotation-option-group {
        margin-bottom: 14px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: #f8fafc;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .quotation-option-group-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px;
        cursor: pointer;
        user-select: none;
        background: #f1f5f9;
        transition: background-color 0.2s ease;
    }

    .quotation-option-group-header:hover {
        background: #e2e8f0;
    }

    .quotation-option-group-title-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .quotation-option-group-title {
        font-size: 15px;
        font-weight: 800;
        color: #0b2d68;
        margin: 0;
    }

    .quotation-option-group-selected-badge {
        font-size: 12px;
        font-weight: 500;
        color: #0284c7;
        background: #e0f2fe;
        padding: 2px 8px;
        border-radius: 9999px;
        white-space: nowrap;
    }

    .quotation-option-group-arrow {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #fff;
        border: 1px solid var(--border);
        color: #0b2d68;
        font-size: 10px;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .quotation-option-group-content {
        padding: 0 16px;
        background: #fff;
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: max-height 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease, padding 0.3s ease;
    }

    .quotation-option-group.open .quotation-option-group-content {
        max-height: 500px;
        opacity: 1;
        padding: 16px;
        border-top: 1px solid var(--border);
    }

    .quotation-option-group.open .quotation-option-group-arrow {
        transform: rotate(180deg);
    }

    .quotation-option-label {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 8px 0;
        font-size: 14px;
        color: #111;
    }

    .quotation-option-label input {
        width: 15px;
        height: 15px;
        accent-color: var(--accent);
    }

    .quotation-item-summary {
        margin-top: 14px;
        padding: 12px 14px;
        border-radius: 10px;
        background: #eef4ff;
        color: #0b2d68;
        font-size: 16px;
        font-weight: 800;
    }

    .quotation-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .quotation-save-btn {
        min-width: 160px;
        height: 42px;
        border: 1px solid var(--accent);
        border-radius: 10px;
        background: var(--accent);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        padding: 0 22px;
    }

    .quotation-remove-btn {
        margin-top: 10px;
        min-width: 100px;
        height: 36px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fff;
        color: #dc2626;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    @media (max-width: 768px) {
        .quotation-form-grid {
            grid-template-columns: 1fr;
        }

        .quotation-form-full {
            grid-column: auto;
        }

        .quotation-actions {
            flex-direction: column;
            align-items: stretch;
        }
    }

    .quotation-grand-summary {
        text-align: end;
    }
</style>
@endsection
@section('content')

<form action="{{ route('admin.quotations.store') }}" method="POST" id="quotation-form">
    @csrf

    <div class="table-card quotation-page-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Create Quotation' : '引用作成' }}</div>
                <div class="showing-text">{{ request()->cookie('dev') == '1' ? 'Create quotation for customer.' : '顧客の引用を作成します。' }}</div>
            </div>

            <a href="{{ route('admin.quotations.index') }}" class="btn-outline">
                {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
            </a>
        </div>

        <div class="quotation-form-grid">
            <div class="quotation-form-group quotation-form-full">
                <label>{{ request()->cookie('dev') == '1' ? 'Quotation No.' : '引用番号' }}</label>
                <input type="text" name="quotation_no" value="{{ old('quotation_no', $quotationNo) }}" readonly>
            </div>

            <div class="quotation-form-group quotation-form-full">
                <label>{{ request()->cookie('dev') == '1' ? 'Quotation Date' : '引用日付' }}</label>
                <input type="date" name="quotation_date" value="{{ old('quotation_date', now()->format('Y-m-d')) }}"
                    required>
            </div>

            <div class="quotation-form-group quotation-form-full">
                <label>{{ request()->cookie('dev') == '1' ? 'Customer Name' : '顧客名' }}</label>
                <input type="text" name="customer_name" value="{{ old('customer_name') }}" required>
            </div>

            <div class="quotation-form-group quotation-form-full">
                <label>{{ request()->cookie('dev') == '1' ? 'Customer Email' : '顧客メール' }}</label>
                <input type="email" name="customer_email" value="{{ old('customer_email') }}">
            </div>

            <div class="quotation-form-group quotation-form-full">
                <label>{{ request()->cookie('dev') == '1' ? 'Customer Address' : '顧客住所' }}</label>
                <textarea name="customer_address" rows="3">{{ old('customer_address') }}</textarea>
            </div>

            <div class="quotation-form-group quotation-form-full">
                <label>{{ request()->cookie('dev') == '1' ? 'Note' : '備考' }}</label>
                <textarea name="note" rows="3">{{ old('note') }}</textarea>
            </div>
            <div class="quotation-form-group quotation-form-full">
    <label>{{ request()->cookie('dev') == '1' ? 'Discount Amount' : '割引額' }}</label>
    <input 
        type="number" 
        name="discount_amount" 
        value="{{ old('discount_amount', 0) }}" 
        min="0" 
        step="0.01"
        class="quotation-discount-input"
    >
</div>
        </div>

        <hr>

        <h3 class="quotation-section-title">Products</h3>

        <div id="quotation-items"></div>

        <button type="button" class="btn-outline" id="add-item-btn">
            {{ request()->cookie('dev') == '1' ? '+ Add Product' : '+ 商品を追加' }}
        </button>

        <div class="quotation-actions">
            <button type="submit" class="quotation-save-btn">
                {{ request()->cookie('dev') == '1' ? 'Save Quotation' : '引用を保存' }}
            </button>
        </div>
       <div class="quotation-grand-summary">
    <div>{{ request()->cookie('dev') == '1' ? 'Subtotal:' : '小計:' }} ¥<span id="quotationSubtotal">0.00</span></div>
    <div>{{ request()->cookie('dev') == '1' ? 'Discount:' : '割引:' }} -¥<span id="quotationDiscount">0.00</span></div>
    <div>{{ request()->cookie('dev') == '1' ? 'Shipping:' : '配送料:' }} <span id="quotationShipping">¥800.00</span></div>
    <div>{{ request()->cookie('dev') == '1' ? 'VAT 10%:' : '消費税 10%:' }} ¥<span id="quotationVat">0.00</span></div>
    <div><strong>{{ request()->cookie('dev') == '1' ? 'Grand Total:' : '合計:' }} ¥<span id="quotationGrandTotal">0.00</span></strong></div>
</div>
    </div>

</form>

<template id="quotation-item-template">
    <div class="quotation-item-box" data-item-index="__INDEX__">
        <div class="quotation-form-grid">
            <div class="quotation-form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Product' : '商品' }}</label>
                <select name="items[__INDEX__][product_id]" class="quotation-product-select" required>
                    <option value="">-- Select Product --</option>
                    @foreach ($products as $product)
                    <option value="{{ $product->product_id }}">
                        {{ $product->product_name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="quotation-form-group">
                <label>{{ request()->cookie('dev') == '1' ? 'Quantity' : '数量' }}</label>
                <input type="number" name="items[__INDEX__][quantity]" class="quotation-qty" value="1"
                    min="1" required>
            </div>
        </div>

        <div class="quotation-options-area"></div>

        <div class="quotation-item-summary">
           {{ request()->cookie('dev') == '1' ? ' Item Total:' : ' 商品小計:' }} ¥<span class="quotation-item-total">0.00</span>
        </div>

        <button type="button" class="quotation-remove-btn remove-item-btn">
            {{ request()->cookie('dev') == '1' ? 'Remove' : '削除' }}
        </button>
    </div>
</template>



@endsection

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let itemIndex = 0;

        const wrapper = document.getElementById('quotation-items');
        const template = document.getElementById('quotation-item-template').innerHTML;
        const addBtn = document.getElementById('add-item-btn');
        const discountInput = document.querySelector('input[name="discount_amount"]');

if (discountInput) {
    discountInput.addEventListener('input', function() {
        calculateQuotationSummary();
    });
}

        addBtn.addEventListener('click', function() {
            addItem();
        });

        function addItem() {
            const html = template.replaceAll('__INDEX__', itemIndex);
            wrapper.insertAdjacentHTML('beforeend', html);
            itemIndex++;
        }

        function updateGroupSelectedBadge(group) {
            const checkedInput = group.querySelector('.quotation-option-input:checked');
            const badge = group.querySelector('.quotation-option-group-selected-badge');
            if (checkedInput && badge) {
                const labelText = checkedInput.closest('label').textContent.trim();
                badge.textContent = labelText;
                badge.style.display = 'inline-block';
            } else if (badge) {
                badge.style.display = 'none';
            }
        }

        wrapper.addEventListener('change', function(e) {
            if (e.target.classList.contains('quotation-product-select')) {
                loadProductOptions(e.target);
            }

            if (e.target.classList.contains('quotation-option-input')) {
                updateGroupSelectedBadge(e.target.closest('.quotation-option-group'));
            }

            if (
                e.target.classList.contains('quotation-option-input') ||
                e.target.classList.contains('quotation-qty')
            ) {
                calculateItemTotal(e.target.closest('.quotation-item-box'));
                calculateQuotationSummary();
            }
        });

        wrapper.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-item-btn')) {
                e.target.closest('.quotation-item-box').remove();
                calculateQuotationSummary();
                return;
            }

            const header = e.target.closest('.quotation-option-group-header');
            if (header) {
                const group = header.closest('.quotation-option-group');
                group.classList.toggle('open');
            }
        });

        function loadProductOptions(select) {
            const productId = select.value;
            const itemBox = select.closest('.quotation-item-box');
            const optionsArea = itemBox.querySelector('.quotation-options-area');

            optionsArea.innerHTML = '';

            if (!productId) return;

            fetch(`{{ route('admin.quotations.productOptions', ':productId') }}`.replace(':productId',
                    productId))
                .then(response => response.json())
                .then(data => {
                    let html = '';

                    data.groups.forEach(group => {
                        html += `
    <div class="quotation-option-group">
        <div class="quotation-option-group-header">
            <div class="quotation-option-group-title-wrapper">
                <span class="quotation-option-group-title">${group.group_name} / ${group.group_code}</span>
                <span class="quotation-option-group-selected-badge" style="display: none;"></span>
            </div>
            <span class="quotation-option-group-arrow">▼</span>
        </div>
        <div class="quotation-option-group-content">
`;

                        group.options.forEach(option => {
                            const priceText = Number(option.additional_price) > 0 ?
                                ` (+¥${Number(option.additional_price).toFixed(2)})` :
                                '';

                            html += `
                            <label class="quotation-option-label">
                                <input
                                    type="radio"
                                    class="quotation-option-input"
                                    name="items[${itemBox.dataset.itemIndex}][options][${group.group_id}]"
                                    value="${option.option_id}"
                                    data-price="${option.additional_price}"
                                    data-price-type="${option.price_type || 'per_order'}"
                                    data-free-from-qty="${option.free_from_qty || 0}"
                                    data-price-rates='${JSON.stringify(option.price_rates || [])}'
                                >
                                ${option.option_name}${priceText}
                            </label>
                        `;
                        });

                        html += `
        </div>
    </div>`;
                    });

                    optionsArea.innerHTML = html;

                    optionsArea.querySelectorAll('.quotation-option-group').forEach(group => {
                        updateGroupSelectedBadge(group);
                        const hasChecked = group.querySelector('.quotation-option-input:checked');
                        if (hasChecked) {
                            group.classList.add('open');
                        }
                    });

                    itemBox.dataset.priceRules = JSON.stringify(data.price_rules || []);
                    itemBox.dataset.optionPriceRules = JSON.stringify(data.option_price_rules || []);

                    calculateItemTotal(itemBox);
                    calculateQuotationSummary();
                });
        }

        function calculateItemTotal(itemBox) {
            const qty = parseInt(itemBox.querySelector('.quotation-qty')?.value || 1);
            const checkedOptions = itemBox.querySelectorAll('.quotation-option-input:checked');

            const selectedOptionIds = Array.from(checkedOptions).map(input => parseInt(input.value));
            const priceRules = JSON.parse(itemBox.dataset.priceRules || '[]');
            const optionPriceRules = JSON.parse(itemBox.dataset.optionPriceRules || '[]');

            const matchedRule = findMatchedRule(priceRules, selectedOptionIds);
            const unitPrice = getUnitPrice(matchedRule, qty);

            let optionTotal = 0;

            checkedOptions.forEach(input => {
                const optionId = parseInt(input.value);
                let price = getRatePriceByQty(input, qty);
                const priceType = input.dataset.priceType || 'per_order';
                const freeFromQty = parseInt(input.dataset.freeFromQty || 0);

                if (freeFromQty > 0 && qty >= freeFromQty) {
                    price = 0;
                }

                price = getOptionReplacementPrice(
                    optionPriceRules,
                    optionId,
                    price,
                    qty,
                    selectedOptionIds
                );

                const isRuleOption = matchedRule && matchedRule.option_ids.map(Number).includes(
                    optionId);

                if (!isRuleOption) {
                    optionTotal += priceType === 'per_item' ? price * qty : price;
                }
            });

            const itemTotal = (unitPrice * qty) + optionTotal;

            itemBox.querySelector('.quotation-item-total').textContent = itemTotal.toFixed(2);
        }

        function getRatePriceByQty(input, qty) {
            const basePrice = parseFloat(input.dataset.price || 0);
            let rates = [];

            try {
                rates = JSON.parse(input.dataset.priceRates || '[]');
            } catch (error) {
                rates = [];
            }

            if (!Array.isArray(rates) || !rates.length) {
                return basePrice;
            }

            return rates.reduce((matchedPrice, rate) => {
                return qty >= parseInt(rate.min_qty || 0)
                    ? parseFloat(rate.price || 0)
                    : matchedPrice;
            }, basePrice);
        }

        function getOptionReplacementPrice(optionPriceRules, optionId, currentPrice, qty, selectedOptionIds) {
            const matchedRules = optionPriceRules.filter(rule => {
                const conditionOptionIds = rule.option_ids || [];

                if (parseInt(rule.target_option_id || 0) !== optionId || !conditionOptionIds.length) {
                    return false;
                }

                return conditionOptionIds.every(id => selectedOptionIds.includes(parseInt(id)));
            });

            if (!matchedRules.length) {
                return currentPrice;
            }

            matchedRules.sort((a, b) => (b.option_ids || []).length - (a.option_ids || []).length);

            const tiers = matchedRules[0].tiers || [];
            const matchedTiers = tiers.filter(tier => {
                const min = parseInt(tier.min_qty);
                const max = tier.max_qty === null ? null : parseInt(tier.max_qty);

                return qty >= min && (max === null || qty <= max);
            });

            if (matchedTiers.length) {
                matchedTiers.sort((a, b) => parseInt(b.min_qty) - parseInt(a.min_qty));

                return parseFloat(matchedTiers[0].additional_price || 0);
            }

            const sortedTiers = [...tiers].sort((a, b) => parseInt(b.min_qty) - parseInt(a.min_qty));
            const highestTier = sortedTiers[0];

            return highestTier && qty > parseInt(highestTier.min_qty)
                ? parseFloat(highestTier.additional_price || 0)
                : currentPrice;
        }

        function findMatchedRule(priceRules, selectedOptionIds) {
            const matched = priceRules.filter(rule => {
                const ruleOptionIds = rule.option_ids || [];

                if (!ruleOptionIds.length) return false;

                return ruleOptionIds.every(id => selectedOptionIds.includes(parseInt(id)));
            });

            if (!matched.length) return null;

            matched.sort((a, b) => (b.option_ids || []).length - (a.option_ids || []).length);

            return matched[0];
        }

        function getUnitPrice(rule, qty) {
            if (!rule) return 0;

            const matchedTier = (rule.tiers || []).find(tier => {
                const min = parseInt(tier.min_qty);
                const max = tier.max_qty === null ? null : parseInt(tier.max_qty);

                return qty >= min && (max === null || qty <= max);
            });

            if (matchedTier) {
                return parseFloat(matchedTier.unit_price);
            }

            const sorted = [...(rule.tiers || [])].sort((a, b) => parseInt(b.min_qty) - parseInt(a.min_qty));
            const highestTier = sorted[0];

            return highestTier && qty > parseInt(highestTier.min_qty)
                ? parseFloat(highestTier.unit_price)
                : 0;
        }

        addItem();
        calculateQuotationSummary();
    });

   function calculateQuotationSummary() {
    let subtotal = 0;

    document.querySelectorAll('.quotation-item-total').forEach(function(el) {
        subtotal += parseFloat(el.textContent || 0);
    });

    const discountInput = document.querySelector('input[name="discount_amount"]');
    let discount = discountInput ? parseFloat(discountInput.value || 0) : 0;

    if (discount < 0) {
        discount = 0;
    }

    if (discount > subtotal) {
        discount = subtotal;
    }

    const afterDiscount = Math.max(subtotal - discount, 0);
    const shipping = afterDiscount >= 11000 ? 0 : 800;
    const vat = (afterDiscount + shipping) * 0.10;
    const grandTotal = afterDiscount + shipping + vat;

    document.getElementById('quotationSubtotal').textContent = subtotal.toFixed(2);

    const discountEl = document.getElementById('quotationDiscount');
    if (discountEl) {
        discountEl.textContent = discount.toFixed(2);
    }

    document.getElementById('quotationShipping').textContent = shipping > 0 ? '¥' + shipping.toFixed(2) : 'Free';
    document.getElementById('quotationVat').textContent = vat.toFixed(2);
    document.getElementById('quotationGrandTotal').textContent = grandTotal.toFixed(2);
}
</script>
@endsection
