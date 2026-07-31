@extends('frontend.layouts.app')

@section('title', 'ご注文のキャンセル | ThaiSilk')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/cancel-order.css') }}">
@endsection

@section('content')
    <section class="cancel-order-page">
        <div class="cancel-order-container">
            <header class="cancel-order-header">
                <h1>ご注文のキャンセル</h1>
                <p>キャンセルをご希望の場合は、下記の内容をご確認ください。</p>
                <p>製作状況を確認のうえ、通常1〜2営業日以内にご案内いたします。</p>
                <small>お急ぎの場合：{{ config('quotation.company.email') }}</small>
            </header>

            <article class="cancel-order-card">
                <h2>キャンセルについて</h2>

                <div class="cancel-order-content">
                    <section>
                        <h3>キャンセル可能な期間</h3>
                        <p>ご注文後、製作を開始する前までキャンセルのご相談を承ります。注文番号を添えて、できるだけ早くお問い合わせください。</p>
                    </section>

                    <section>
                        <h3>キャンセルをお受けできない場合</h3>
                        <p>デザイン確定後または製作開始後は、オーダーメイド商品の性質上、原則としてキャンセルをお受けできません。</p>
                        <p>材料の手配や加工が始まっている場合は、製作開始前でも発生済みの費用をご負担いただく場合があります。</p>
                    </section>

                    <section>
                        <h3>お支払い済みのご注文</h3>
                        <p>キャンセルを承った場合は、お支払い状況を確認後に返金方法と返金時期をご案内します。振込手数料などは返金額から差し引く場合があります。</p>
                    </section>

                    <section>
                        <h3>キャンセルのお申し込み方法</h3>
                        <p>お問い合わせフォームに「注文番号」「ご注文者名」「メールアドレス」「キャンセル理由」をご入力ください。</p>
                        <p>送信した時点ではキャンセルは確定していません。当店からの受付完了メールをもって確定となります。</p>
                    </section>

                    <div class="cancel-order-action">
                        <a href="{{ route('contact.index', ['inquiry_type' => 'order']) }}">キャンセルについて問い合わせる <span>→</span></a>
                        <p>お問い合わせ前に<a href="{{ route('privacy-policy') }}">プライバシーポリシー</a>をご確認ください。</p>
                    </div>
                </div>
            </article>
        </div>
    </section>
@endsection
