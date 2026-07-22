@extends('frontend.layouts.app')

@section('title', 'ショッピングカート | ThaiSilk')
@section('body-class', 'cart-page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">
@endsection


@section('content')
@php
    $isEnglish = request()->cookie('dev') === '1';
@endphp
    <header class="cart-page-header">
        <div class="cart-container cart-page-header-inner">
            <h1>ショッピングカート</h1>
            <ol class="cart-progress" aria-label="ご注文手順">
                <li class="is-current">カート</li>
                <li>情報入力</li>
                <li>内容確認</li>
                <li>ご注文完了</li>
            </ol>
        </div>
    </header>

    <div class="cart-container cart-layout">
        <section class="cart-items" aria-labelledby="cartItemsTitle">
            <div class="cart-section-heading">
                <h2 id="cartItemsTitle">ショッピングカート内の商品</h2>
                <span>{{ $summary['item_count'] }}点</span>
            </div>

            @if (session('cart_success'))
                <p class="cart-alert cart-alert-success">{{ session('cart_success') }}</p>
            @endif

            @if ($errors->any())
                <div class="cart-alert cart-alert-error" role="alert">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            @forelse ($items as $item)
                <article class="cart-item">
                    <form action="{{ route('cart.items.destroy', $item['id']) }}" method="POST"
                        class="cart-item-remove-form">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cart-item-remove" aria-label="商品を削除">×</button>
                    </form>

                    <div class="cart-item-main">
                        <div class="cart-item-image">
                            @if (!empty($item['image']))
                                <img src="{{ asset($item['image']) }}" alt="{{ $item['product_name'] }}">
                            @else
                                <span>NO IMAGE</span>
                            @endif
                        </div>

                        <div class="cart-item-description">
                            <h3>{{ $item['product_name'] }}</h3>

                            @foreach ($item['selected_options'] as $option)
                                <p>
                                    <span>{{ $option['group_name'] }}</span>
                                    <strong>{{ $option['option_name'] }}</strong>
                                    @if (!empty($option['option_detail']))
                                        <small>{{ $option['option_detail'] }}</small>
                                    @endif
                                </p>
                            @endforeach

                            @foreach ($item['previous_order_numbers'] ?? [] as $number)
                                <p><span>前回ご注文管理番号</span><strong>{{ $number }}</strong></p>
                            @endforeach

                            @foreach ($item['font_entries'] ?? [] as $entries)
                                @foreach ($entries as $entryIndex => $entry)
                                    <p class="cart-item-custom-detail">
                                        <span>テキスト {{ $entryIndex + 1 }}</span>
                                        <strong>{{ $entry['text'] ?: '未入力' }}</strong>
                                        <small>{{ $entry['font'] ?: '書体指定なし' }} / {{ $entry['size'] }}pt</small>
                                    </p>
                                @endforeach
                            @endforeach

                            @foreach ($item['artworks'] ?? [] as $artwork)
                                <p class="cart-item-custom-detail">
                                    <span>入稿データ</span>
                                    <strong>{{ $artwork['original_name'] }}</strong>
                                </p>
                            @endforeach

                            @foreach ($item['custom_fields'] ?? [] as $field => $value)
                                <p class="cart-item-custom-detail">
                                    <span>{{ \Illuminate\Support\Str::headline($field) }}</span>
                                    <strong>{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</strong>
                                </p>
                            @endforeach

                        </div>

                        <div class="cart-item-pricing">
                            <span class="cart-item-unit-price">
                                単価 {{ number_format($item['base_unit_price']) }}円（税別）
                            </span>

                            <form action="{{ route('cart.items.update', $item['id']) }}" method="POST"
                                class="cart-quantity-form" data-cart-quantity-form>
                                @csrf
                                @method('PATCH')
                                @php($quantityLimits = $item['quantity_limits'] ?? ['min' => null, 'max' => null, 'note' => null])
                                <label>
                                    <span>数量</span>
                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}"
                                        @if ($quantityLimits['min'] !== null) min="{{ $quantityLimits['min'] }}" @endif
                                        @if ($quantityLimits['max'] !== null) max="{{ $quantityLimits['max'] }}" @endif
                                        step="1" required>
                                </label>
                                <button type="submit" class="visually-hidden">数量を更新</button>
                            </form>

                            <strong class="cart-item-total" data-cart-line-total aria-live="polite">
                                {{ number_format($item['line_subtotal']) }}円
                                <small>（税別）</small>
                            </strong>
                            <small class="cart-quantity-rule-note" data-cart-quantity-rule-note
                                @if (empty($quantityLimits['note'])) hidden @endif>{{ $quantityLimits['note'] }}</small>
                            <a href="{{ route('products.show', ['slug' => $item['product_slug'], 'edit_cart' => $item['id']]) }}"
                                class="cart-item-edit">
                                修正
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="cart-empty">
                    <p>カートに商品は入っていません。</p>
                    <a href="{{ route('products.index') }}">商品を見る</a>
                </div>
            @endforelse
        </section>

        <aside class="cart-summary" aria-labelledby="cartSummaryTitle">
            <div class="cart-summary-card">
                <div class="cart-summary-title">
                    <h2 id="cartSummaryTitle">ご依頼内容</h2>
                    <strong>{{ $summary['item_count'] }}点</strong>
                </div>

                <dl class="cart-summary-lines">
                    <div>
                        <dt>商品小計（税別）</dt>
                        <dd data-cart-summary-subtotal>{{ number_format($summary['subtotal']) }}円</dd>
                    </div>
                    <div>
                        <dt>送料（税別）</dt>
                        <dd data-cart-summary-shipping>
                            {{ $summary['shipping'] ? number_format($summary['shipping']) . '円' : '無料' }}</dd>
                    </div>
                    <div>
                        <dt>消費税（10%）</dt>
                        <dd data-cart-summary-vat>{{ number_format($summary['vat']) }}円</dd>
                    </div>
                </dl>

                <div class="cart-summary-total">
                    <span>合計（税込）</span>
                    <strong data-cart-summary-total aria-live="polite">{{ number_format($summary['total']) }}円</strong>
                </div>

                <button type="button" class="cart-action cart-action-secondary" data-quotation-open
                    @disabled(empty($items))>
                    <img src="{{ asset('assets/images/product/Group 1654.png') }}" alt="" class="cart-action-icon"
                        aria-hidden="true">

                    {{ $isEnglish ? 'Create quotation' : '見積書作成' }}
                </button>
                <a href="{{ empty($items) ? '#' : route(auth()->check() ? 'checkout.information' : 'checkout.index') }}"
                    class="cart-action cart-action-primary {{ empty($items) ? 'is-disabled' : '' }}"
                    @if (empty($items)) aria-disabled="true" tabindex="-1" @endif>
                    ご注文手続きへ進む
                    <img src="{{ asset('assets/images/product/Vector (10).png') }}" alt=""
                        class="cart-action-arrow" aria-hidden="true">
                </a>
                <a href="{{ route('products.index') }}" class="cart-action cart-action-outline">
                    <img src="{{ asset('assets/images/product/Vector (11).png') }}" alt=""
                        class="cart-action-arrow" aria-hidden="true">
                    買い物を続ける
                </a>
            </div>

            @if (!empty($items))
                <p class="cart-shipping-note" data-cart-shipping-note>
                    @if ($summary['shipping'] === 0)
                        ご注文金額が10,000円以上のため、送料は無料です。
                    @else
                        ご注文金額が10,000円未満の場合は、通常送料800円（税別）が必要となります。
                        あと{{ number_format($summary['amount_until_free_shipping']) }}円で送料無料です。
                    @endif
                </p>
            @endif
        </aside>
    </div>
    @include('frontend.cart.partials.quotation-modal', [
        'isEnglish' => $isEnglish,
    ])
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatter = new Intl.NumberFormat('ja-JP');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const subtotalElement = document.querySelector('[data-cart-summary-subtotal]');
            const shippingElement = document.querySelector('[data-cart-summary-shipping]');
            const vatElement = document.querySelector('[data-cart-summary-vat]');
            const totalElement = document.querySelector('[data-cart-summary-total]');
            const shippingNote = document.querySelector('[data-cart-shipping-note]');

            const updateSummary = function(summary) {
                subtotalElement.textContent = formatter.format(summary.subtotal) + '円';
                shippingElement.textContent = summary.shipping ?
                    formatter.format(summary.shipping) + '円' :
                    '無料';
                vatElement.textContent = formatter.format(summary.vat) + '円';
                totalElement.textContent = formatter.format(summary.total) + '円';

                if (!shippingNote) {
                    return;
                }

                shippingNote.textContent = summary.shipping === 0 ?
                    'ご注文金額が10,000円以上のため、送料は無料です。' :
                    'ご注文金額が10,000円未満の場合は、通常送料800円（税別）が必要となります。' +
                    ' あと' + formatter.format(summary.amount_until_free_shipping) +
                    '円で送料無料です。';
            };

            document.querySelectorAll('[data-cart-quantity-form]').forEach(function(form) {
                const input = form.querySelector('input[name="quantity"]');
                const cartItem = form.closest('.cart-item');
                const lineTotal = cartItem.querySelector('[data-cart-line-total]');
                const quantityRuleNote = cartItem.querySelector('[data-cart-quantity-rule-note]');
                let debounceTimer = null;
                let requestNumber = 0;

                const saveQuantity = async function(currentRequest) {
                    input.setCustomValidity('');

                    if (!input.value || !input.checkValidity()) {
                        input.reportValidity();
                        return;
                    }

                    form.classList.add('is-updating');

                    try {
                        const response = await fetch(form.action, {
                            method: 'PATCH',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                quantity: Number(input.value),
                            }),
                        });
                        const data = await response.json();

                        if (currentRequest !== requestNumber) {
                            return;
                        }

                        if (!response.ok) {
                            const message = data.errors?.quantity?.[0] ||
                                '数量を更新できませんでした。';
                            input.setCustomValidity(message);
                            input.reportValidity();
                            return;
                        }

                        input.value = data.item.quantity;
                        const limits = data.item.quantity_limits;

                        if (limits.min === null) {
                            input.removeAttribute('min');
                        } else {
                            input.min = limits.min;
                        }

                        if (limits.max === null) {
                            input.removeAttribute('max');
                        } else {
                            input.max = limits.max;
                        }

                        quantityRuleNote.textContent = limits.note || '';
                        quantityRuleNote.hidden = !limits.note;
                        lineTotal.innerHTML = formatter.format(data.item.line_subtotal) +
                            '円 <small>（税別）</small>';
                        updateSummary(data.summary);
                    } catch (error) {
                        if (currentRequest === requestNumber) {
                            input.setCustomValidity('通信エラーが発生しました。もう一度お試しください。');
                            input.reportValidity();
                        }
                    } finally {
                        if (currentRequest === requestNumber) {
                            form.classList.remove('is-updating');
                        }
                    }
                };

                const scheduleSave = function() {
                    input.setCustomValidity('');
                    window.clearTimeout(debounceTimer);
                    const currentRequest = ++requestNumber;
                    debounceTimer = window.setTimeout(function() {
                        saveQuantity(currentRequest);
                    }, 350);
                };

                input.addEventListener('input', scheduleSave);
                input.addEventListener('change', function() {
                    window.clearTimeout(debounceTimer);
                    saveQuantity(++requestNumber);
                });
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    window.clearTimeout(debounceTimer);
                    saveQuantity(++requestNumber);
                });
            });
            const quotationModal = document.querySelector('[data-quotation-modal]');
            const quotationDialog = quotationModal?.querySelector(
                '.quotation-modal-dialog'
            );
            const quotationOpenButton = document.querySelector(
                '[data-quotation-open]'
            );
            const quotationCloseButton = quotationModal?.querySelector(
                '[data-quotation-close]'
            );
            const postalButton = quotationModal?.querySelector(
                '[data-quotation-address-search]'
            );
            const postalInput = quotationModal?.querySelector(
                '[data-quotation-postal-code]'
            );
            const prefectureInput = quotationModal?.querySelector(
                '[data-quotation-prefecture]'
            );
            const addressInput = quotationModal?.querySelector(
                '[data-quotation-address]'
            );
            const postalMessage = quotationModal?.querySelector(
                '[data-quotation-postal-message]'
            );

            const openQuotationModal = function() {
                if (!quotationModal) {
                    return;
                }

                quotationModal.classList.add('is-open');
                quotationModal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('quotation-modal-open');

                window.requestAnimationFrame(function() {
                    quotationDialog?.focus();
                });
            };

            const closeQuotationModal = function() {
                if (!quotationModal) {
                    return;
                }

                quotationModal.classList.remove('is-open');
                quotationModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('quotation-modal-open');
            };

            quotationOpenButton?.addEventListener(
                'click',
                openQuotationModal
            );

            quotationCloseButton?.addEventListener(
                'click',
                closeQuotationModal
            );

            quotationModal?.addEventListener('click', function(event) {
                if (event.target === quotationModal) {
                    closeQuotationModal();
                }
            });

            document.addEventListener('keydown', function(event) {
                if (
                    event.key === 'Escape' &&
                    quotationModal?.classList.contains('is-open')
                ) {
                    closeQuotationModal();
                }
            });

            if (quotationModal?.dataset.openOnLoad === '1') {
                openQuotationModal();
            }

            postalButton?.addEventListener('click', async function() {
                const postalCode = postalInput?.value.trim();

                if (!postalCode) {
                    postalMessage.textContent = @json($isEnglish ? 'Please enter a postal code.' : '郵便番号を入力してください。');
                    postalMessage.hidden = false;
                    return;
                }

                postalButton.disabled = true;

                postalMessage.textContent = @json($isEnglish ? 'Searching...' : '住所を検索しています。');
                postalMessage.hidden = false;

                try {
                    const response = await fetch(
                        @json(route('cart.quotation.postal-code')), {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                postal_code: postalCode,
                            }),
                        }
                    );

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.message ||
                            @json($isEnglish ? 'Address not found.' : '住所が見つかりませんでした。')
                        );
                    }

                    prefectureInput.value = data.prefecture || '';
                    addressInput.value = data.address || '';

                    postalMessage.textContent = @json($isEnglish ? 'The address has been entered.' : '住所を入力しました。');
                } catch (error) {
                    postalMessage.textContent = error.message;
                } finally {
                    postalButton.disabled = false;
                }
            });
        });
    </script>
@endsection
