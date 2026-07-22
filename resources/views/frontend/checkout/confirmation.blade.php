@extends('frontend.layouts.app')

@section('title', 'ご注文内容確認 | ThaiSilk')
@section('body-class', 'checkout-confirmation-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
    <style>
        body.checkout-confirmation-page,
        body.checkout-confirmation-page main { background: #f4f2ed; color: #333; }
        .confirmation-header { background: #fff; border-bottom: 1px solid #e8e4dd; }
        .confirmation-container { width: min(100% - 64px, 1120px); margin: 0 auto;background: #FFFFFF;
    padding: 20px; }
        .confirmation-header-inner { min-height: 76px; display: flex; justify-content: space-between; align-items: center; gap: 24px; }
        .confirmation-header h1 { margin: 0; font-size: 18px; font-weight: 600; }
        .confirmation-progress { display: flex; gap: 10px; padding: 0; margin: 0; list-style: none; color: #999; font-size: 12px; }
        .confirmation-progress li:not(:last-child)::after { content: '>'; margin-left: 10px; color: #bbb; }
        .confirmation-progress .is-current { color: #a88b5d; font-weight: 700; }
        .confirmation-main { position: relative; min-height: calc(100vh - 76px); padding: 42px 0 86px; overflow: hidden; }
        .confirmation-main::before { content: ''; position: absolute; top: -20px; right: 12%; width: 155px; height: 155px; background: url('{{ asset('assets/images/home/ph_flower-lotus-thin.png') }}') center / contain no-repeat; opacity: .14; pointer-events: none; }
        .confirmation-title { margin: 0 0 28px; font-size: 18px; font-weight: 600; }
        .confirmation-grid { display: grid; grid-template-columns: minmax(0, 1fr) 310px; gap: 26px; align-items: start; }
        .confirmation-card { margin: 0 0 22px; padding: 26px 30px 18px; border: 1px solid #a98248; border-radius: 10px; background: #fff; }
        .confirmation-card legend { position: absolute; top: -11px; left: 24px; width: auto; margin: 0; padding: 0 8px; color: #8d682f; background: #fff; font-size: 20px; font-weight: 600; line-height: 1.1; letter-spacing: .04em; }
        fieldset.confirmation-card { position: relative; min-inline-size: 0; }
        .confirmation-item { padding: 22px 0 28px; border-bottom: 1px solid #ececec; }
        .confirmation-item:first-of-type { padding-top: 10px; }
        .confirmation-item:last-child { border-bottom: 0; padding-bottom: 0; }
        .confirmation-item-number { margin: 0 0 10px; color: #222; font-size: 15px; font-weight: 500; }
        .confirmation-item-body { display: grid; grid-template-columns: 110px minmax(0, 1fr) auto; gap: 20px; align-items: center; }
        .confirmation-item-image { width: 110px; height: 110px; display: grid; place-items: center; overflow: hidden; background: #f5f5f7; }
        .confirmation-item-image img { width: 100%; height: 100%; object-fit: cover; }
        .confirmation-item-image span { color: #999; font-size: 10px; }
        .confirmation-item p { margin: 4px 0; color: #666; font-size: 14px; line-height: 1.5; }
        .confirmation-item-price { align-self: center; color: #222; font-size: 15px; font-weight: 500; white-space: nowrap; }
        .confirmation-summary { position: relative; padding: 28px 22px 22px; border: 1px solid #a98248; border-radius: 10px; background: #fff; }
        .confirmation-summary h2 { position: absolute; top: -10px; left: 16px; width: auto; margin: 0; padding: 0 8px; color: #8d682f; background: #fff; font-size: 15px; font-weight: 600; line-height: 1.2; }
        .confirmation-summary dl { margin: 0; }
        .confirmation-summary dl div { display: flex; justify-content: space-between; gap: 12px; padding: 9px 0; border-bottom: 1px solid #eee8de; font-size: 13px; }
        .confirmation-summary dt { color: #666; } .confirmation-summary dd { margin: 0; color: #444; }
        .confirmation-total { display: flex; justify-content: space-between; margin-top: 14px; color: #997842; font-size: 15px; font-weight: 700; }
        .confirmation-details { position: relative; margin-bottom: 22px; padding: 28px 22px 18px; border: 1px solid #a98248; border-radius: 10px; background: #fff; }
        .confirmation-details h2 { position: absolute; top: -10px; left: 16px; width: auto; margin: 0; padding: 0 8px; color: #8d682f; background: #fff; font-size: 15px; font-weight: 600; line-height: 1.2; }
        .confirmation-details p { margin: 3px 0; color: #555; font-size: 13px; line-height: 1.65; }
        .confirmation-actions { display: flex; justify-content: space-between; gap: 28px; margin-top: 28px; padding: 24px 30px; border-radius: 4px; background: #fff; }
        .confirmation-actions-left { display: flex; gap: 26px; }
        .confirmation-actions form { margin: 0; }
        .confirmation-actions a, .confirmation-actions button { display: inline-flex; width: 228px; min-height: 54px; box-sizing: border-box; align-items: center; justify-content: center; padding: 0 24px; border-radius: 999px; font: inherit; font-size: 15px; font-weight: 600; text-decoration: none; cursor: pointer; transition: color .2s ease, background-color .2s ease, border-color .2s ease, box-shadow .2s ease, transform .2s ease; }
        .confirmation-actions a { border: 1px solid #9b6b2f; color: #8d682f; background: #fff; }
        .confirmation-actions button { border: 1px solid #bd572a; color: #fff; background: #bd572a; }
        .confirmation-actions a:hover { color: #fff; border-color: #9b6b2f; background: #9b6b2f; box-shadow: 0 6px 14px rgba(141, 104, 47, .2); transform: translateY(-2px); }
        .confirmation-actions button:hover { border-color: #9f431e; background: #9f431e; box-shadow: 0 6px 14px rgba(189, 87, 42, .25); transform: translateY(-2px); }
        .confirmation-actions a:focus-visible, .confirmation-actions button:focus-visible { outline: 3px solid rgba(189, 87, 42, .28); outline-offset: 3px; }
        @media (max-width: 760px) { .confirmation-container { width: min(100% - 32px, 1120px); } .confirmation-header-inner { align-items: flex-start; flex-direction: column; padding: 18px 0; } .confirmation-progress { flex-wrap: wrap; } .confirmation-grid { grid-template-columns: 1fr; } .confirmation-card { padding: 20px 18px 14px; } .confirmation-card legend { font-size: 17px; } .confirmation-item-body { grid-template-columns: 76px minmax(0, 1fr); gap: 14px; } .confirmation-item-image { width: 76px; height: 76px; } .confirmation-item-price { grid-column: 2; } .confirmation-actions { flex-direction: column; padding: 20px 16px; } .confirmation-actions-left { flex-direction: column; gap: 14px; } .confirmation-actions a, .confirmation-actions button { width: 100%; } }
    </style>
@endpush

@section('content')
    <header class="confirmation-header">
        <div class="confirmation-container confirmation-header-inner">
            <h1>ご注文・見積もり手続きの進め方</h1>
            <ol class="confirmation-progress" aria-label="ご注文手順">
                <li>カート</li><li>情報入力</li><li class="is-current">内容確認</li><li>ご注文完了</li>
            </ol>
        </div>
    </header>

    <main class="confirmation-main">
        <div class="confirmation-container">
            <h1 class="confirmation-title">ご注文内容確認</h1>
            <div class="confirmation-grid">
                <div>
                    <fieldset class="confirmation-card">
                        <legend>ご注文製品情報</legend>
                        @foreach ($items as $index => $item)
                            <article class="confirmation-item">
                                <h3 class="confirmation-item-number">【商品 {{ $index + 1 }}】</h3>
                                <div class="confirmation-item-body">
                                    <div class="confirmation-item-image">
                                        @if (!empty($item['image']))<img src="{{ asset($item['image']) }}" alt="{{ $item['product_name'] }}">@else<span>NO IMAGE</span>@endif
                                    </div>
                                    <div>
                                        <p>商品名：{{ $item['product_name'] }}</p>
                                        @foreach ($item['selected_options'] ?? [] as $option)
                                            <p>{{ $option['group_name'] ?: 'オプション' }}：{{ $option['option_name'] }}</p>
                                        @endforeach
                                        <p>数量：{{ number_format($item['quantity']) }}</p>
                                    </div>
                                    <div class="confirmation-item-price">{{ number_format($item['line_subtotal']) }} 円</div>
                                </div>
                            </article>
                        @endforeach
                    </fieldset>

                    <section class="confirmation-details">
                        <h2>お客様情報</h2>
                        <p>お名前：{{ $customer['name'] ?? '-' }}</p>
                        <p>お名前（フリガナ）：{{ $customer['name_kana'] ?? '-' }}</p>
                        @if (!empty($customer['company_name']))
                            <p>会社名：{{ $customer['company_name'] }}</p>
                        @endif
                        <p>TEL：{{ $customer['phone'] ?? '-' }}</p>
                        <p>郵便番号：{{ !empty($customer['postal_code_front']) && !empty($customer['postal_code_back']) ? $customer['postal_code_front'].'-'.$customer['postal_code_back'] : '-' }}</p>
                        <p>住所：{{ $customer['prefecture'] ?? '' }}{{ $customer['city'] ?? '' }}{{ $customer['address'] ?? '' }}</p>
                    </section>

                    <section class="confirmation-details">
                        <h2>お届け先情報</h2>
                        @if (($customer['info_method'] ?? '') === 'メールの署名等をコピーする' && !empty($customer['signature_text']))
                            <p>メールの署名等をコピーする</p>
                            <p>{!! nl2br(e($customer['signature_text'])) !!}</p>
                        @elseif (($customer['same_as_customer'] ?? null) === '1')
                            <p>お客様情報と同じ住所を使用する</p>
                        @else
                            <p>お名前：{{ $customer['shipping_name'] ?? '-' }}</p>
                            <p>お名前（フリガナ）：{{ $customer['shipping_name_kana'] ?? '-' }}</p>
                            <p>郵便番号：{{ !empty($customer['shipping_postal_code_front']) && !empty($customer['shipping_postal_code_back']) ? $customer['shipping_postal_code_front'].'-'.$customer['shipping_postal_code_back'] : '-' }}</p>
                            <p>住所：{{ $customer['shipping_prefecture'] ?? '' }}{{ $customer['shipping_city'] ?? '' }}{{ $customer['shipping_address'] ?? '' }}</p>
                        @endif
                    </section>

                    <section class="confirmation-details">
                        <h2>請求先情報</h2>
                        @if (($customer['billing_address_type'] ?? 'same_as_customer') === 'different')
                            <p>別の請求先住所を使用する</p>
                            <p>お名前：{{ $customer['billing_name'] ?? '-' }} {{ !empty($customer['billing_name_kana']) ? '（'.$customer['billing_name_kana'].'）' : '' }}</p>
                            <p>住所：〒{{ ($customer['billing_postal_code_front'] ?? '').($customer['billing_postal_code_back'] ?? '') }} {{ $customer['billing_prefecture'] ?? '' }}{{ $customer['billing_city'] ?? '' }}{{ $customer['billing_address'] ?? '' }}</p>
                        @elseif (($customer['billing_address_type'] ?? 'same_as_customer') === 'same_as_shipping')
                            <p>お届け先情報と同じ住所を使用する</p>
                        @else
                            <p>お客様情報と同じ住所を使用する</p>
                        @endif
                    </section>

                    <section class="confirmation-details">
                        <h2>その他情報</h2>
                        <p>お支払い方法：{{ ($customer['payment_method'] ?? 'bank_transfer') === 'bank_transfer' ? '銀行振込' : 'PayPalクレジットカード決済' }}</p>
                        @if (!empty($customer['notes']))<p>ご連絡事項：{{ $customer['notes'] }}</p>@endif
                    </section>

                </div>

                <aside class="confirmation-summary">
                    <h2>ご注文内容の合計</h2>
                    <dl>
                        <div><dt>商品合計（税込）</dt><dd>{{ number_format($summary['subtotal'] + $summary['vat']) }} 円</dd></div>
                        <div><dt>送料</dt><dd>{{ $summary['shipping'] ? number_format($summary['shipping']).' 円' : '無料' }}</dd></div>
                    </dl>
                    <div class="confirmation-total"><span>合計（税込）</span><strong>{{ number_format($summary['total']) }} 円</strong></div>
                </aside>
            </div>

            <div class="confirmation-actions">
                <div class="confirmation-actions-left">
                    <a href="{{ route('checkout.information') }}">お客様情報修正</a>
                    <a href="{{ route('cart.index') }}">製品情報修正</a>
                </div>
                <form action="{{ route('checkout.orders.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="checkout_token" value="{{ $checkoutToken }}">
                    <button type="submit">ご注文確定</button>
                </form>
            </div>
        </div>
    </main>
@endsection