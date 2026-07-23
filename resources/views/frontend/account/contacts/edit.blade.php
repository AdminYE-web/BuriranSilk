@extends('frontend.layouts.app')

@section('title', '連絡先情報の編集')

@section('css')
<style>
    .account-page {
        background: #f3f3f3;
        padding: 32px 0;
        min-height: 620px;
    }

    .account-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 36px;
        align-items: start;
    }

    .contact-card {
        background: #fff;
        border-radius: 8px;
        padding: 42px 54px;
        min-height: 420px;
    }

    .contact-card h1 {
        font-size: 34px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    .contact-divider {
        border-top: 1px solid #e5e5e5;
        margin-bottom: 24px;
        max-width: 560px;
    }

    .contact-form {
        max-width: 780px;
    }

    .contact-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .contact-group {
        margin-bottom: 20px;
    }

    .contact-group.full {
        grid-column: 1 / -1;
    }

    .contact-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #111;
    }

    .contact-group label span {
        color: #ff0000;
    }

    .contact-group input[type="text"],
    .contact-group input[type="email"] {
        width: 100%;
        height: 38px;
        border: 1px solid #cfd4dc;
        border-radius: 4px;
        padding: 0 14px;
        font-size: 14px;
    }

    .contact-checkbox {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        margin: 14px 0 20px;
        font-size: 14px;
    }

    .contact-checkbox input {
        margin-top: 3px;
    }

    .contact-actions-row {
        display: flex;
        align-items: center;
        gap: 34px;
    }

    .save-btn {
        min-width: 106px;
        height: 29px;
        border: 0;
        border-radius: 4px;
        background: #2f70c9;
        color: #fff;
        font-weight: 700;
    }

    .cancel-link {
        color: #111;
        text-decoration: none;
        font-weight: 700;
    }

    .error-text {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }

    @media (max-width: 900px) {
        .account-layout {
            grid-template-columns: 1fr;
        }

        .contact-card {
            padding: 28px 22px;
        }

        .contact-form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="account-page">
    <div class="container">
        <div class="account-layout">

            @include('frontend.account.partials.sidebar', ['user' => $user])

            <main class="contact-card">
                <h1>連絡先情報の編集</h1>
                <div class="contact-divider"></div>

                <form action="{{ route('account.contacts.update', $contact->user_contact_id) }}" method="POST" class="contact-form">
                    @csrf
                    @method('PUT')

                    <div class="contact-form-grid">
                        <div class="contact-group">
                            <label>姓<span>*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name', $contact->last_name) }}" placeholder="姓">
                            @error('last_name') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="contact-group">
                            <label>名<span>*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name', $contact->first_name) }}" placeholder="名">
                            @error('first_name') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="contact-group full">
                            <label>電話番号<span>*</span></label>
                            <input type="text" name="phone" value="{{ old('phone', $contact->phone) }}" placeholder="電話番号">
                            @error('phone') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="contact-group full">
                            <label>メールアドレス<span>*</span></label>
                            <input type="email" name="email" value="{{ old('email', $contact->email) }}" placeholder="メールアドレス">
                            @error('email') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <label class="contact-checkbox">
                        <input type="checkbox" name="receive_email" value="1" {{ old('receive_email', $contact->receive_email) ? 'checked' : '' }}>
                        <span>
                            ニュース、割引、セール情報などのメールや通知を受け取る。
                        </span>
                    </label>

                    <label class="contact-checkbox">
                        <input type="checkbox" name="is_main" value="1" {{ old('is_main', $contact->is_main) ? 'checked' : '' }}>
                        <span>
                            デフォルトの連絡先に設定する
                        </span>
                    </label>

                    <div class="contact-actions-row">
                        <button type="submit" class="save-btn">
                            保存する
                        </button>

                        <a href="{{ route('account.contacts.index') }}" class="cancel-link">
                            キャンセル
                        </a>
                    </div>
                </form>
            </main>

        </div>
    </div>
</div>
@endsection