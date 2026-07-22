@extends('frontend.layouts.app')

@section('title', 'ご注文完了 | ThaiSilk')
@section('body-class', 'checkout-complete-page')

@push('styles')
    <style>
        body.checkout-complete-page,
        body.checkout-complete-page main { background: #fff; }
        .complete-main { min-height: 720px; padding: 86px 20px 100px; text-align: center; color: #4b4238; }
        .complete-check { position: relative; width: 70px; height: 70px; margin: 0 auto 34px; border: 1px solid #d4ad6a; border-radius: 50%; background: linear-gradient(145deg,#f9e6bc,#c99b4a); box-shadow: inset 0 0 0 6px #fff7e9; }
        .complete-check::after { position: absolute; top: 19px; left: 18px; width: 30px; height: 16px; content: ''; border-bottom: 5px solid #fff; border-left: 5px solid #fff; transform: rotate(-45deg); }
        .complete-main h1 { margin: 0 0 28px; font-size: 19px; font-weight: 600; letter-spacing: .08em; }
        .complete-message { margin: 0 0 10px; font-size: 14px; line-height: 1.8; }
        .complete-notice { max-width: 535px; margin: 14px auto 12px; padding: 15px 18px; border-left: 5px solid #c9a45f; border-radius: 7px; background: #fcfaf6; font-size: 13px; line-height: 1.9; }
        .complete-order-number { width: min(100%, 355px); margin: 12px auto 46px; padding: 8px 20px; box-sizing: border-box; border: 1px dashed #c99b58; border-radius: 5px; font-size: 13px; line-height: 1.8; }
        .complete-home { display: inline-flex; min-width: 158px; min-height: 30px; align-items: center; justify-content: center; border: 1px solid #a67c35; border-radius: 5px; color: #fff; background: linear-gradient(90deg,#a37a31,#dfba78); font-size: 12px; font-weight: 600; text-decoration: none; transition: filter .2s ease, transform .2s ease; }
        .complete-home:hover { filter: brightness(.92); transform: translateY(-1px); }
    </style>
@endpush

@section('content')
    <main class="complete-main">
        <div class="complete-check" aria-hidden="true"></div>
        <h1>ご注文ありがとうございます</h1>
        <p class="complete-message">ご注文ありがとうございます。</p>
        @if ($emailSent)
            <p class="complete-message">ご注文内容をご登録のメールアドレスにお送り致しました。</p>
        @else
            <p class="complete-message">ご注文は完了しましたが、確認メールを送信できませんでした。</p>
        @endif
        <div class="complete-notice">
            確認メールが届かない場合、迷惑メールに分類されていないかご確認の上、<br>
            toiawase@hotstrap.jp にその旨をご連絡頂くか、TEL: 050-6865-5592までお電話にてお知らせください。
        </div>
        <div class="complete-order-number">
            ご注文番号：<br>
            {{ $orderNo }}
        </div>
        <a href="{{ route('home') }}" class="complete-home">トップページへ</a>
    </main>
@endsection