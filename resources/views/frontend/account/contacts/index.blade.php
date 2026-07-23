@extends('frontend.layouts.app')

@section('title', '連絡先情報')

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
            margin-bottom: 20px;
            max-width: 560px;
        }

        .add-contact-box {
            width: 100%;
            min-height: 86px;
            border: 2px dashed #bdbdbd;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            color: #111;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
        }

        .add-contact-box:hover {
            color: #111;
            background: #fafafa;
        }

        .add-plus {
            font-size: 36px;
            color: #777;
            font-weight: 300;
        }

        .contact-list {
            margin-top: 24px;
            display: grid;
            gap: 14px;
        }

        .contact-item {
            border: 1px solid #e5e5e5;
            border-radius: 8px;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            gap: 20px;
            align-items: flex-start;
        }

        .contact-name {
            font-weight: 700;
            margin-bottom: 6px;
        }

        .contact-text {
            color: #555;
            font-size: 14px;
            line-height: 1.6;
        }

        .main-badge {
            display: inline-block;
            margin-left: 8px;
            padding: 3px 8px;
            border-radius: 999px;
            background: #eaf3ff;
            color: #1683ff;
            font-size: 12px;
            font-weight: 700;
        }

        .contact-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .small-btn {
            border: 1px solid #d1d5db;
            background: #fff;
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 13px;
            cursor: pointer;
        }

        .small-btn.primary {
            background: #2f70c9;
            color: #fff;
            border-color: #2f70c9;
        }

        .small-btn.danger {
            color: #dc2626;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        @media (max-width: 991px) {
            .account-page {
                padding: 16px 0;
                min-height: auto;
            }

            .account-layout {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .contact-card {
                padding: 24px 20px;
                min-height: auto;
            }

            .contact-item {
                flex-direction: column;
            }
        }
        .contact-actions-image-style {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 12px;
        }

        .contact-top-actions {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .contact-text-action {
            border: 0;
            background: transparent;
            padding: 0;
            color: #2563eb;
            font-size: 14px;
            line-height: 1;
            font-weight: 500;
            text-decoration: none;
            cursor: pointer;
        }

        .contact-text-action:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .contact-delete-action {
            color: #dc2626;
        }

        .contact-delete-action:hover {
            color: #b91c1c;
        }

        .set-default-btn {
            min-width: 100px;
            height: 34px;
            padding: 0 14px;
            border: 1px solid #2563eb;
            border-radius: 6px;
            background: #fff;
            color: #2563eb;
            font-size: 13px;
            font-weight: 500;
            line-height: 1;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .set-default-btn:hover {
            background: #eff6ff;
        }

        .set-default-btn.is-current {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: default;
            color: #2563eb;
            border-color: #2563eb;
            background: #eff6ff;
            font-size: 13px;
            font-weight: 600;
        }

        @media (max-width: 991px) {
            .contact-actions-image-style {
                align-items: flex-start;
                gap: 10px;
                margin-top: 10px;
            }

            .contact-top-actions {
                gap: 16px;
            }

            .contact-text-action {
                font-size: 14px;
            }

            .set-default-btn {
                min-width: auto;
                height: 34px;
                font-size: 13px;
                padding: 0 14px;
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
                    <h1>連絡先情報</h1>
                    <div class="contact-divider"></div>

                    @if (session('success'))
                        <div class="alert-success">{{ session('success') }}</div>
                    @endif

                    @if (session('error'))
                        <div class="alert-error">{{ session('error') }}</div>
                    @endif

                    @if ($contacts->count() < 5)
                        <a href="{{ route('account.contacts.create') }}" class="add-contact-box">
                            <span class="add-plus">+</span>
                            <span>新しい連絡先を追加</span>
                        </a>
                    @else
                        <div class="alert-error">
                            登録できる連絡先は最大5件までです。
                        </div>
                    @endif

                    <div class="contact-list">
                        @forelse($contacts as $contact)
                            <div class="contact-item">
                                <div>
                                    <div class="contact-name">
                                        {{ $contact->last_name }} {{ $contact->first_name }}

                                        @if ($contact->is_main)
                                            <span class="main-badge">デフォルト</span>
                                        @endif
                                    </div>

                                    <div class="contact-text">
                                        電話番号: {{ $contact->phone }}<br>
                                        メールアドレス: {{ $contact->email }}
                                    </div>
                                </div>

                                <div class="contact-actions contact-actions-image-style">
                                    <div class="contact-top-actions">
                                        <a href="{{ route('account.contacts.edit', $contact->user_contact_id) }}"
                                            class="contact-text-action">
                                            編集
                                        </a>

                                        <form action="{{ route('account.contacts.destroy', $contact->user_contact_id) }}"
                                            method="POST" onsubmit="return confirm('この連絡先を削除してもよろしいですか？')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="contact-text-action contact-delete-action">
                                                削除
                                            </button>
                                        </form>
                                    </div>

                                    @if (!$contact->is_main)
                                        <form action="{{ route('account.contacts.setMain', $contact->user_contact_id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')

                                            <button type="submit" class="set-default-btn">
                                                デフォルトに設定
                                            </button>
                                        </form>
                                    @else
                                        <div class="set-default-btn is-current">
                                            デフォルト
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div style="margin-top:20px; color:#777;">
                                登録されている連絡先情報はありません。
                            </div>
                        @endforelse
                    </div>
                </main>

            </div>
        </div>
    </div>
@endsection
