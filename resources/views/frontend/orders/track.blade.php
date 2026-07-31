@extends('frontend.layouts.app')

@section('title', 'ご注文の追跡 | ThaiSilk')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/track-order.css') }}">
@endsection

@section('content')
    <section class="tracking-page">
        <div class="tracking-shell">
            @if (!$order)
                <div class="tracking-search-card">
                    <div class="tracking-brand">
                        <img src="{{ asset('assets/images/logo/logo_thaisilk.png') }}" alt="ThaiSilk">
                    </div>
                    <h1 class="tracking-title">ご注文の追跡</h1>
                    <p class="tracking-lead">ご注文番号とメールアドレスを入力して、配送状況をご確認いただけます。</p>
                    <div class="tracking-divider"></div>

                    @error('lookup')
                        <p class="tracking-lookup-error" role="alert">{{ $message }}</p>
                    @enderror

                    <form method="POST" action="{{ route('orders.track.search') }}">
                        @csrf
                        <div class="tracking-field">
                            <label for="tracking-order-no">ご注文番号 <span class="tracking-required">※</span></label>
                            <input id="tracking-order-no" class="tracking-input @error('order_no') is-invalid @enderror"
                                type="text" name="order_no" value="{{ old('order_no') }}"
                                placeholder="例: ORD-TS-20260618171503385" autocomplete="off" required>
                            @error('order_no')
                                <span class="tracking-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="tracking-field">
                            <label for="tracking-email">メールアドレス <span class="tracking-required">※</span></label>
                            <input id="tracking-email" class="tracking-input @error('email') is-invalid @enderror"
                                type="email" name="email" value="{{ old('email') }}"
                                placeholder="example@example.com" autocomplete="email" required>
                            @error('email')
                                <span class="tracking-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <button class="tracking-submit" type="submit">配送状況を追跡する</button>
                    </form>
                    <p class="tracking-member-link">
                        会員の方はこちらから<a href="{{ auth()->check() ? route('account.orders.index') : route('home', ['login' => 1]) }}">ログインして注文履歴をご確認いただけます &gt;</a>
                    </p>
                </div>
            @else
                @php
                    $status = strtolower((string) ($order->order_status ?: $order->status ?: 'order_pending'));
                    $statusAliases = ['pending' => 'order_pending', 'confirmed' => 'design_in_progress', 'processing' => 'production', 'shipped' => 'delivered'];
                    $normalizedStatus = $statusAliases[$status] ?? $status;
                    $statusIndexes = ['order_pending' => 0, 'design_in_progress' => 1, 'production' => 2, 'delivery' => 3, 'delivered' => 4, 'completed' => 4];
                    $statusLabels = ['order_pending' => '受付済み', 'design_in_progress' => 'デザイン確認中', 'production' => '生産中', 'delivery' => '発送準備中', 'delivered' => '発送済み', 'completed' => '完了', 'cancelled' => 'キャンセル'];
                    $isCancelled = $normalizedStatus === 'cancelled';
                    $currentStep = $statusIndexes[$normalizedStatus] ?? 0;
                    $progress = $isCancelled ? 0 : $currentStep * 25;
                    $customer = $order->customer;
                    $customerName = $customer?->personal_name ?: trim(($customer?->personal_last_name ?? '') . ' ' . ($customer?->personal_first_name ?? ''));
                    $shippingName = $customer?->shipping_name ?: $customerName;
                    $customerAddress = collect([$customer?->personal_postcode ? '〒' . $customer->personal_postcode : null, $customer?->personal_province, $customer?->personal_city, $customer?->personal_area])->filter()->implode(' ');
                    $shippingAddress = collect([$customer?->shipping_postcode ? '〒' . $customer->shipping_postcode : null, $customer?->shipping_province, $customer?->shipping_city, $customer?->shipping_area, $customer?->shipping_address])->filter()->implode(' ');
                    if ($customer?->same_as_customer || blank($shippingAddress)) $shippingAddress = $customerAddress;
                    $paymentStatus = strtolower((string) ($order->payment?->payment_status ?? $order->payment_status ?? 'pending'));
                    $paymentLabels = ['pending' => '未払い', 'paid' => '支払い済み', 'failed' => '決済失敗', 'cancelled' => 'キャンセル', 'refunded' => '返金済み'];
                    $steps = ['注文完了', 'デザイン確定', '生産', '発送準備中', '発送済み'];
                    $stepIcons = [
                        'assets/images/icon/solar_box-bold-duotone (1).png',
                        'assets/images/icon/clarity_design-solid (1).png',
                        'assets/images/icon/flat-color-icons_factory (1).png',
                        'assets/images/icon/delivery (1).png',
                        'assets/images/icon/box-return (1) (1).png',
                    ];
                @endphp

                <a class="tracking-back" href="{{ route('orders.track') }}">
                    <img src="{{ asset('assets/images/icon/tabler_arrow-up.png') }}" alt="" aria-hidden="true">
                    <span>ご注文の追跡</span>
                </a>
                <article class="tracking-result-card">
                    <div class="tracking-order-overview">
                        <div class="tracking-overview-item"><span class="tracking-meta-label">注文番号</span><span class="tracking-meta-value">{{ $order->order_no }}</span></div>
                        <div class="tracking-overview-item"><span class="tracking-meta-label">注文日</span><span class="tracking-meta-value">{{ $order->created_at?->format('Y-m-d') ?? '-' }}</span></div>
                        <div class="tracking-overview-item"><span class="tracking-meta-label">注文点数</span><span class="tracking-meta-value">{{ number_format($order->total_quantity ?: $order->items->sum(fn ($item) => $item->qty ?: $item->quantity)) }} 点</span></div>
                        <div class="tracking-overview-item"><span class="tracking-meta-label">ステータス</span><span class="tracking-status-badge {{ $isCancelled ? 'is-cancelled' : '' }}">{{ $statusLabels[$normalizedStatus] ?? $normalizedStatus }}</span></div>
                    </div>
                    <h2 class="tracking-section-title">注文状況</h2>
                    <div class="tracking-progress" style="--progress-width: {{ $progress * 0.8 }}%;" aria-label="注文の進捗">
                        <div class="tracking-progress-fill"></div>
                        @foreach ($steps as $stepIndex => $stepLabel)
                            <div class="tracking-step {{ !$isCancelled && $stepIndex <= $currentStep ? 'is-reached' : '' }} {{ !$isCancelled && $stepIndex < $currentStep ? 'is-done' : '' }}">
                                <span class="tracking-step-icon" aria-hidden="true">
                                    <img src="{{ asset($stepIcons[$stepIndex]) }}" alt="">
                                </span>
                                <span class="tracking-step-dot"></span>
                                <span class="tracking-step-name">{{ $stepLabel }}</span>
                                <span class="tracking-step-date">
                                    @if ($stepIndex === 0)
                                        {{ $order->created_at?->format('Y-m-d H:i') }}
                                    @elseif ($stepIndex === 4 && $order->shipping_date)
                                        {{ $order->shipping_date->format('Y-m-d H:i') }}
                                    @endif
                                </span>
                            </div>
                        @endforeach
                    </div>
                    @if ($isCancelled)
                        <p class="tracking-cancelled-note">このご注文はキャンセルされました。</p>
                    @endif
                    <div class="tracking-info-grid">
                        <section>
                            <h2 class="tracking-info-title">お客様情報</h2>
                            <div class="tracking-address-block">
                                <span class="tracking-address-label">ご連絡先</span>
                                {{ $customerName ?: '-' }}<br>
                                {{ $customer?->personal_email ?: '-' }}<br>
                                {{ $customer?->personal_phone ?: '-' }}
                            </div>
                            <div class="tracking-address-block">
                                <span class="tracking-address-label">お届け先住所</span>
                                {{ $shippingName ?: '-' }}<br>
                                {{ $shippingAddress ?: '-' }}
                            </div>
                        </section>
                        <section>
                            <h2 class="tracking-info-title">注文サマリー</h2>
                            <div class="tracking-summary-row"><span>小計</span><span>¥{{ number_format((float) $order->subtotal) }}</span></div>
                            <div class="tracking-summary-row"><span>配送料</span><span>¥{{ number_format((float) $order->shipping_fee) }}</span></div>
                            <div class="tracking-summary-row"><span>消費税</span><span>¥{{ number_format((float) ($order->tax_amount ?: $order->vat_amount)) }}</span></div>
                            <div class="tracking-summary-row is-total"><span>合計（税込）</span><span>¥{{ number_format((float) $order->grand_total) }}</span></div>
                            <p class="tracking-payment-note">
                                ご注文者名: {{ $customerName ?: '-' }}<br>
                                お支払い方法: {{ data_get($order->checkout_data, 'payment_method', '-') }}<br>
                                決済ステータス: {{ $paymentLabels[$paymentStatus] ?? $paymentStatus }}
                            </p>
                        </section>
                    </div>

                    <h2 class="tracking-products-title">注文商品一覧</h2>
                    <div class="tracking-products-head"><span>商品名</span><span>数量</span><span>合計金額</span></div>
                    @foreach ($order->items as $item)
                        @php
                            $imagePath = ltrim(str_replace('\\', '/', (string) $item->product_image), '/');
                            $imageUrl = null;
                            if (filled($imagePath)) {
                                $imageUrl = \Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])
                                    ? $imagePath
                                    : asset(\Illuminate\Support\Str::startsWith($imagePath, ['storage/', 'assets/']) ? $imagePath : 'storage/' . $imagePath);
                            }
                            $quantity = $item->qty ?: $item->quantity;
                        @endphp
                        <article class="tracking-product-card">
                            <div class="tracking-product-main">
                                <div class="tracking-product-info">
                                    <div class="tracking-product-image">
                                        @if ($imageUrl)
                                            <img src="{{ $imageUrl }}" alt="{{ $item->product_name_snapshot ?: $item->product_name }}">
                                        @else
                                            <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="tracking-product-name">{{ $item->product_name_snapshot ?: $item->product_name }}</p>
                                        <p class="tracking-product-sub">商品番号: {{ $item->order_item_id }}</p>
                                    </div>
                                </div>
                                <div class="tracking-product-qty">{{ number_format($quantity) }}</div>
                                <div class="tracking-product-price">¥{{ number_format((float) $item->item_total) }}</div>
                            </div>
                            @if ($item->optionDetails->isNotEmpty())
                                <details class="tracking-options">
                                    <summary>詳細を見る</summary>
                                    <div class="tracking-options-list">
                                        @foreach ($item->optionDetails as $option)
                                            <div><strong>{{ $option->group_name_snapshot ?: 'オプション' }}:</strong> {{ $option->custom_value ?: $option->option_name_snapshot }}</div>
                                        @endforeach
                                    </div>
                                </details>
                            @endif
                        </article>
                    @endforeach
                </article>
            @endif
        </div>
    </section>
@endsection
