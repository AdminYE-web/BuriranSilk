@extends('frontend.layouts.app')

@section('title', 'パスワードを再設定する | ThaiSilk')
@section('body-class', 'password-page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/password-reset.css') }}">
@endsection

@section('content')
    <section class="password-reset-page">
        <div class="password-reset-card">
            <a href="{{ route('home') }}" class="password-reset-logo">ThaiSilk</a>
            <h1>パスワードを再設定する</h1>
            <p>下記の項目をご入力の上、パスワードをリセットするボタンを押してください。</p>
            <hr>
            <form action="{{ route('password.update') }}" method="POST" class="password-reset-form">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ old('email', $email) }}">
                <label>新しいパスワード <em>※</em>
                    <input type="password" name="password" placeholder="8文字以上の半角英数字" autocomplete="new-password" required>
                    @error('password')<small>{{ $message }}</small>@enderror
                </label>
                <label>パスワード（確認） <em>※</em>
                    <input type="password" name="password_confirmation" placeholder="新しいパスワード（再入力）" autocomplete="new-password" required>
                </label>
                @error('email')<small class="password-reset-error">{{ $message }}</small>@enderror
                <button type="submit">パスワードをリセットする</button>
            </form>
            <a href="{{ route('home', ['login' => 1]) }}" class="password-reset-login-link">ログインはこちらへ</a>
        </div>
    </section>
@endsection