@extends('frontend.layouts.app')

@section('title', 'ログイン・会員確認 | ThaiSilk')
@section('body-class', 'checkout-choice-page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
@endsection

@section('content')
    <header class="checkout-choice-header">
        <div class="checkout-choice-container checkout-choice-header-inner">
            <h1>ご注文・見積もり手続きの進め方</h1>
            <ol class="checkout-choice-progress" aria-label="ご注文手順">
                <li>カート</li><li class="is-current">情報入力</li><li>内容確認</li><li>ご注文完了</li>
            </ol>
        </div>
    </header>

    <section class="checkout-choice-main">
        <div class="checkout-choice-container">
            <h2>ログイン／会員確認</h2>
            <div class="checkout-choice-grid">
                <section class="checkout-choice-column" aria-labelledby="checkoutLoginTitle">
                    <h3 id="checkoutLoginTitle">会員の方（ログイン）</h3>
                    @auth
                        <p class="checkout-choice-copy">ログイン済みです。お客様情報の入力へお進みください。</p>
                        <a href="#" class="checkout-choice-button">お客様情報の入力へ</a>
                    @else
                        <form action="{{ route('login') }}" method="POST" class="checkout-login-form">
                            @csrf
                            <label class="visually-hidden" for="checkoutEmail">メールアドレス</label>
                            <input id="checkoutEmail" type="email" name="email" value="{{ old('email') }}" placeholder="メールアドレス" autocomplete="email" required>
                            <label class="visually-hidden" for="checkoutPassword">パスワード</label>
                            <input id="checkoutPassword" type="password" name="password" placeholder="パスワード" autocomplete="current-password" required>
                            @error('email', 'login')<p class="checkout-choice-error">{{ $message }}</p>@enderror
                            <button type="submit" class="checkout-choice-button">ログインして進む</button>
                        </form>
                        <button type="button" class="checkout-forgot-password" data-password-reset-open>パスワードを忘れた方はこちら</button>
                    @endauth
                </section>
                <section class="checkout-choice-column" aria-labelledby="checkoutRegisterTitle">
                    <h3 id="checkoutRegisterTitle">新規会員登録して進む</h3>
                    <p class="checkout-choice-copy">会員登録すると、注文履歴や見積書履歴の確認、次回からの入力省略が可能です。</p>
                    <a href="{{ route('register') }}" class="checkout-choice-button">会員登録手続きへ</a>
                </section>
                <section class="checkout-choice-column" aria-labelledby="checkoutGuestTitle">
                    <h3 id="checkoutGuestTitle">会員登録せずに進む</h3>
                    <p class="checkout-choice-copy">会員登録をせずに、データ入稿とお客様情報の入力へ進めます。</p>
                    <a href="#" class="checkout-choice-button checkout-choice-button-outline">ゲストとして次へ</a>
                </section>
            </div>
        </div>
    </section>
@endsection