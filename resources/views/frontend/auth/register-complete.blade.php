@extends('frontend.layouts.app')

@section('title', '登録完了 | ThaiSilk')
@section('body-class', 'register-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
@endpush

@section('content')
    <div class="container register-shell">
        <div class="row justify-content-center w-100">
            <section class="register-card register-complete-card col-12 col-sm-10 col-md-9 col-lg-8 col-xl-8">
            <a href="{{ route('home') }}" class="register-logo" aria-label="ThaiSilk Home">
                <svg viewBox="0 0 42 42" aria-hidden="true">
                    <path d="M21 34V13M21 25c-7-2-11-7-11-14 7 1 11 6 11 14Zm0 0c7-2 11-7 11-14-7 1-11 6-11 14ZM21 34c-6-1-10-4-13-9 6-1 11 2 13 9Zm0 0c6-1 10-4 13-9-6-1-11 2-13 9Z" />
                </svg>
                <img src="{{ asset('assets/images/auth/image-Photoroom (12) 1.png') }}" alt="ThaiSilk">
            </a>

            <ol class="register-steps" aria-label="会員登録の進行状況">
                @foreach ([1 => 'アカウント情報', 2 => 'お客様情報', 3 => '入力内容確認', 4 => '登録完了'] as $step => $label)
                    <li class="register-step {{ $step === 4 ? 'is-active' : 'is-complete' }}">
                        <span class="register-step-number">{{ $step }}</span>
                        <span class="register-step-label">{{ $label }}</span>
                    </li>
                @endforeach
            </ol>

            <div class="register-complete-content">
                <span class="register-complete-icon" aria-hidden="true">✓</span>
                <h1>会員登録が完了しました</h1>
                <p>会員登録ありがとうございます。<br>ご登録いただいたメールアドレス宛に、登録完了メールをお送りいたしました。</p>
                <p class="register-complete-email">ご登録メールアドレス: <strong>{{ $email }}</strong></p>
                <div class="register-complete-actions">
                    <a href="{{ route('home') }}" class="register-secondary-button">トップページへ</a>
                    <a href="{{ route('home', ['login' => 1]) }}" class="register-primary-button">ログインする</a>
                </div>
            </div>
            </section>
        </div>
    </div>
@endsection
