@extends('frontend.layouts.app')

@section('title', 'マイアカウント')

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



        .account-card {
            background: #fff;
            border-radius: 8px;
            padding: 36px 46px;
            min-height: 360px;
            /* max-width: 780px; */
        }

        .account-card h1 {
            font-size: 32px;
            font-weight: 500;
            margin: 0;
            color: #111;
        }

        .account-subtitle {
            color: #8a8a8a;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .account-divider {
            border-top: 1px solid #e5e5e5;
            margin-bottom: 24px;
        }

        .account-content {
            display: grid;
            grid-template-columns: 1fr 220px;
            gap: 36px;
        }

        .account-info-item {
            margin-bottom: 34px;
        }

        .account-info-label {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
            color: #111;
        }

        .account-info-value {
            font-size: 15px;
            color: #111;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .edit-icon {
            color: #111;
            text-decoration: none;
            font-size: 16px;
        }

        .account-avatar-area {
            text-align: center;
            padding-top: 4px;
        }

        .account-avatar {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            overflow: hidden;
            margin: 0 auto 14px;
            background: #9ca3af;
        }

        .account-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .select-image-btn {
            display: inline-block;
            border: 1px solid #cfcfcf;
            background: #fff;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 13px;
            cursor: pointer;
        }

        .avatar-input {
            display: none;
        }

        .image-note {
            margin-top: 16px;
            color: #9a9a9a;
            font-size: 13px;
            line-height: 1.7;
        }

        .success-message {
            background: #dcfce7;
            color: #166534;
            padding: 10px 14px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .account-layout {
                grid-template-columns: 1fr;
            }

            .account-sidebar {
                min-height: auto;
            }

            .account-content {
                grid-template-columns: 1fr;
            }

            .account-card {
                max-width: 100%;
            }
        }

        .edit-icon {
            border: 0;
            background: transparent;
            padding: 0;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
        }

        .edit-icon img {
            width: 18px;
            height: 18px;
            object-fit: contain;
        }

        .account-modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 9999;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .account-modal-backdrop.is-open {
            display: flex;
        }

        .account-modal {
            position: relative;
            width: 600px;
            max-width: 100%;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #d1d5db;
            padding: 20px 50px 30px;
        }

        .account-modal h2 {
            font-size: 21px;
            font-weight: 800;
            margin: 0 -50px 26px;
            padding: 0 18px 16px;
            border-bottom: 1px solid #d1d5db;
            text-decoration: underline;
        }

        .account-modal-close {
            position: absolute;
            top: 12px;
            right: 14px;
            border: 0;
            background: transparent;
            font-size: 34px;
            line-height: 1;
            color: #777;
            cursor: pointer;
        }

        .account-modal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .account-modal-group label {
            display: block;
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .account-modal-group label span {
            color: #ff0000;
        }

        .account-modal-group input {
            width: 100%;
            height: 46px;
            border: 1px solid #cfd4dc;
            border-radius: 6px;
            padding: 0 16px;
            font-size: 18px;
        }

        .account-modal-save {
            min-width: 130px;
            height: 36px;
            border: 0;
            border-radius: 5px;
            background: #2f70c9;
            color: #fff;
            font-size: 17px;
            font-weight: 800;
            cursor: pointer;
        }

        .account-modal-error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 5px;
        }

        @media (max-width: 600px) {
            .account-modal {
                padding: 20px;
            }

            .account-modal h2 {
                margin: 0 -20px 22px;
                padding: 0 18px 16px;
            }

            .account-modal-grid {
                grid-template-columns: 1fr;
            }
        }

        .account-modal-group.full {
            margin-bottom: 20px;
        }

        .account-modal-group.full input {
            width: 100%;
        }
    </style>
@endsection

@section('content')
    <div class="account-page">
        <div class="container">
            <div class="account-layout">

                @include('frontend.account.partials.sidebar', ['user' => $user])

                <main class="account-card">
                    <h1>マイアカウント</h1>
                    <div class="account-subtitle">会員情報</div>
                    <div class="account-divider"></div>

                    @if (session('success'))
                        <div class="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="account-content">
                        <div class="account-info">
                            <div class="account-info-item">
                                <div class="account-info-label">お名前</div>
                                <div class="account-info-value">
                                    {{ $user->name }}
                                    <button type="button" class="edit-icon edit-name-btn" id="openNameModal">
                                        <img src="{{ asset('assets/images/account/Group.png') }}" alt="Edit">
                                    </button>
                                </div>
                            </div>

                            <div class="account-info-item">
                                <div class="account-info-label">メールアドレス</div>
                                <div class="account-info-value">
                                    {{ $user->email }}
                                </div>
                            </div>

                            <div class="account-info-item">
                                <div class="account-info-label">電話番号</div>
                                <div class="account-info-value">
                                    {{ $user->phone ?? '-' }}
                                </div>
                            </div>

                            <div class="account-info-item">
                                <div class="account-info-label">パスワード</div>
                                <div class="account-info-value">
                                    **********
                                    <button type="button" class="edit-icon edit-password-btn" id="openPasswordModal">
                                        <img src="{{ asset('assets/images/account/Group.png') }}" alt="Edit">
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="account-avatar-area">
                            <div class="account-avatar">
                                @php
                                    $rawAvatar = $user->avatar ?? null;
                                    $avatarPath = ltrim(str_replace('\\', '/', (string) $rawAvatar), '/');
                                    $avatarUrl = null;

                                    if (filled($avatarPath)) {
                                        if (\Illuminate\Support\Str::startsWith($avatarPath, ['http://', 'https://'])) {
                                            $avatarUrl = $avatarPath;
                                        } elseif (\Illuminate\Support\Str::startsWith($avatarPath, ['storage/', 'assets/'])) {
                                            $avatarUrl = asset($avatarPath);
                                        } else {
                                            $avatarUrl = asset('storage/' . $avatarPath);
                                        }
                                    }
                                @endphp

                                @if ($avatarUrl)
                                    <img src="{{ $avatarUrl }}" alt="" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="account-avatar-placeholder" style="display: none;">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="account-avatar-placeholder">
                                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <form action="{{ route('account.avatar.update') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <label class="select-image-btn">
                                    画像を選択
                                    <input type="file" name="avatar" class="avatar-input" accept="image/jpeg,image/png"
                                        onchange="this.form.submit()">
                                </label>
                            </form>

                            <div class="image-note">
                                ファイルサイズ: 最大1MB<br>
                                ファイル形式: JPEG, PNG
                            </div>

                            @error('avatar')
                                <div style="color:red; font-size:13px; margin-top:8px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </main>

            </div>
        </div>
    </div>

    <div class="account-modal-backdrop" id="nameModalBackdrop">
        <div class="account-modal">
            <button type="button" class="account-modal-close" id="closeNameModal">
                ×
            </button>

            <h2>お名前の変更</h2>

            <form action="{{ route('account.name.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="account-modal-grid">
                    <div class="account-modal-group">
                        <label>姓<span>*</span></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                            placeholder="姓" required>
                        @error('last_name')
                            <div class="account-modal-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-modal-group">
                        <label>名<span>*</span></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                            placeholder="名" required>
                        @error('first_name')
                            <div class="account-modal-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="account-modal-save">
                    保存する
                </button>
            </form>
        </div>
    </div>
    <div class="account-modal-backdrop" id="passwordModalBackdrop">
        <div class="account-modal">
            <button type="button" class="account-modal-close" id="closePasswordModal">
                ×
            </button>

            <h2>パスワードの変更</h2>

            <form action="{{ route('account.password.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="account-modal-group full">
                    <label>現在のパスワード<span>*</span></label>
                    <input type="password" name="current_password" placeholder="現在のパスワード" required>
                    @error('current_password')
                        <div class="account-modal-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="account-modal-grid">
                    <div class="account-modal-group">
                        <label>新しいパスワード<span>*</span></label>
                        <input type="password" name="password" placeholder="新しいパスワード" required>
                        @error('password')
                            <div class="account-modal-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="account-modal-group">
                        <label>新しいパスワード（確認）<span>*</span></label>
                        <input type="password" name="password_confirmation" placeholder="新しいパスワード（確認）" required>
                    </div>
                </div>

                <button type="submit" class="account-modal-save">
                    保存する
                </button>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openNameBtn = document.getElementById('openNameModal');
            const closeNameBtn = document.getElementById('closeNameModal');
            const nameBackdrop = document.getElementById('nameModalBackdrop');

            const openPasswordBtn = document.getElementById('openPasswordModal');
            const closePasswordBtn = document.getElementById('closePasswordModal');
            const passwordBackdrop = document.getElementById('passwordModalBackdrop');

            function openModal(backdrop) {
                if (backdrop) {
                    backdrop.classList.add('is-open');
                }
            }

            function closeModal(backdrop) {
                if (backdrop) {
                    backdrop.classList.remove('is-open');
                }
            }

            if (openNameBtn) {
                openNameBtn.addEventListener('click', function() {
                    openModal(nameBackdrop);
                });
            }

            if (closeNameBtn) {
                closeNameBtn.addEventListener('click', function() {
                    closeModal(nameBackdrop);
                });
            }

            if (openPasswordBtn) {
                openPasswordBtn.addEventListener('click', function() {
                    openModal(passwordBackdrop);
                });
            }

            if (closePasswordBtn) {
                closePasswordBtn.addEventListener('click', function() {
                    closeModal(passwordBackdrop);
                });
            }

            [nameBackdrop, passwordBackdrop].forEach(function(backdrop) {
                if (!backdrop) {
                    return;
                }

                backdrop.addEventListener('click', function(e) {
                    if (e.target === backdrop) {
                        closeModal(backdrop);
                    }
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal(nameBackdrop);
                    closeModal(passwordBackdrop);
                }
            });

            @if ($errors->has('first_name') || $errors->has('last_name'))
                openModal(nameBackdrop);
            @endif

            @if ($errors->has('current_password') || $errors->has('password'))
                openModal(passwordBackdrop);
            @endif
        });
    </script>

@endsection
