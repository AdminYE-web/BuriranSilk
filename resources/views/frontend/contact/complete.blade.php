@extends('frontend.layouts.app')

@section('title', 'お問い合わせ完了 | ThaiSilk')
@section('body-class', 'contact-complete-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
@endpush

@section('content')
    <section class="contact-complete-main">
        <div class="contact-complete-check" aria-hidden="true"></div>

        <h1>お問い合わせありがとうございました</h1>
        <p>送信が完了いたしました。</p>
        <p>通常1〜2営業日以内にご返信させていただきますので、<br>しばらくお待ちください。</p>

        @if ($emailSent)
            <p class="contact-complete-email">ご入力いただいたメールアドレス宛に確認メールを送信いたしました。</p>
        @else
            <p class="contact-complete-email">お問い合わせは受け付けましたが、確認メールを送信できませんでした。</p>
        @endif

        <p>受信箱をご確認いただき、届いていない場合は迷惑メールフォルダもご確認ください。</p>

        <div class="contact-complete-actions">
            <a href="{{ route('products.index') }}" class="contact-complete-button contact-complete-products">商品一覧へ</a>
            <a href="{{ route('home') }}" class="contact-complete-button contact-complete-home">トップページへ</a>
        </div>
    </section>
@endsection
