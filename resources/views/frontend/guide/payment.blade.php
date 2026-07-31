@extends('frontend.layouts.app')

@section('title', 'お支払い方法 | ThaiSilk')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/how-to-pay.css') }}">
@endsection

@section('content')
    <section class="how-pay-page">
        <div class="how-pay-container">
            <header class="how-pay-header">
                <h1>お支払い方法</h1>
                <p>ご利用可能なお支払い方法と手数料</p>
            </header>

            <div class="how-pay-sections">
                <section class="how-pay-section">
                    <h2>クレジットカード決済</h2>
                    <div class="how-pay-body">
                        <p>VISA、Mastercard、JCB、American Express等の各種クレジットカードをご利用いただけます。</p>
                        <p>お支払いは一括払いとなります。決済手数料は無料です。</p>
                        <span class="how-pay-status is-maintenance">現在メンテナンス中</span>

                        <div class="how-pay-brands" aria-label="対応予定のクレジットカード">
                            <span class="is-visa">VISA</span>
                            <span class="is-mastercard">Mastercard</span>
                            <span class="is-jcb">JCB</span>
                            <span class="is-amex">AMEX</span>
                            <span class="is-paypal">PayPal</span>
                        </div>
                    </div>
                </section>

                <section class="how-pay-section">
                    <h2>銀行振込</h2>
                    <div class="how-pay-body">
                        <p>ご注文完了後、注文確認メールに記載されている口座へお振り込みください。</p>
                        <p>ご入金の確認後に製作を開始いたします。振込手数料はお客様のご負担となります。</p>
                        <span class="how-pay-status">ご利用いただけます</span>

                        <dl class="how-pay-bank-details">
                            <div><dt>振込先口座</dt><dd>ご注文確認メールにてご案内いたします</dd></div>
                            <div><dt>お支払い期限</dt><dd>メールに記載された期日までにお振り込みください</dd></div>
                            <div><dt>振込名義</dt><dd>ご注文者様のお名前をご入力ください</dd></div>
                        </dl>
                    </div>
                </section>

                <section class="how-pay-section">
                    <h2>その他の決済</h2>
                    <div class="how-pay-body">
                        <p>以下の決済方法は現在準備中です。</p>
                        <ul class="how-pay-other-list">
                            <li><span></span>PayPay</li>
                            <li><span></span>コンビニ決済</li>
                            <li><span></span>その他の電子決済</li>
                        </ul>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection
