<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark p-0">
        <div class="container-fluid header-container">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="navbar-brand header-brand" aria-label="ThaiSilk Home">
                <img src="{{ asset('assets/images/logo/logo_thaisilk.png') }}" alt="ThaiSilk" width="167" height="55">
            </a>

            {{-- Mobile Menu Button --}}
            <div class="header-mobile-actions">
                <button type="button" class="header-action-link account-modal-trigger" aria-label="アカウント"
                    aria-haspopup="dialog" aria-controls="accountLoginModal" aria-expanded="false"
                    data-login-modal-open>
                    <svg viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <circle cx="15" cy="8" r="5.5" fill="none" stroke="currentColor"
                            stroke-width="1.5" />
                        <path d="M4 28C4 20.7 8.7 16.5 15 16.5C21.3 16.5 26 20.7 26 28" fill="none"
                            stroke="currentColor" stroke-width="1.5" />
                    </svg>
                </button>
                <a href="{{ url('/cart') }}" class="header-action-link position-relative" aria-label="ショッピングカート">
                    <svg viewBox="0 0 30 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M6 10H24L25.5 30H4.5L6 10Z" fill="none" stroke="currentColor" stroke-width="1.5" />
                        <path d="M10 11V6.5C10 3 12 1 15 1C18 1 20 3 20 6.5V11" fill="none" stroke="currentColor"
                            stroke-width="1.5" />
                    </svg>
                    @if (($cartCount ?? 0) > 0)
                        <span class="header-cart-count">{{ $cartCount }}</span>
                    @endif
                </a>
            </div>
            <button class="navbar-toggler border-0 shadow-none" type="button" aria-controls="mobileNavigation"
                aria-expanded="false" aria-label="メニューを開く" data-mobile-menu-open>
                <span class="mobile-menu-icon" aria-hidden="true"><i></i><i></i><i></i></span>
            </button>

            {{-- Navigation --}}
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item">
                        <a href="{{ url('/products/thaisilk_01') }}"
                            class="nav-link header-pill-link {{ request()->is('contact') ? 'active' : '' }}">
                            シルク製社員証ケース
                        </a>
                    </li>

                    {{-- Product --}}
                    {{-- <li class="nav-item dropdown product-dropdown">
                        <a href="#" class="nav-link dropdown-toggle product-dropdown-toggle" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span>商品一覧</span>
                        </a>

                        <ul class="dropdown-menu product-dropdown-menu">
                            <li>
                                <a href="{{ url('/products/silk-employee-id-case') }}" class="dropdown-item">
                                    シルク製社員証ケース
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('/products/key-ring') }}" class="dropdown-item">
                                    key ring
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('/products/business-card-holder') }}" class="dropdown-item">
                                    Business Card Holder
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('/products') }}" class="dropdown-item">
                                    製品金撚
                                </a>
                            </li>
                        </ul>
                    </li> --}}

                    {{-- About --}}
                    <li class="nav-item">
                        <a href="{{ url('/about') }}"
                            class="nav-link header-pill-link {{ request()->is('about') ? 'active' : '' }}">
                            私たちについて
                        </a>
                    </li>

                    {{-- Guide --}}
                    <li class="nav-item dropdown product-dropdown">
                        <a href="#" class="nav-link dropdown-toggle product-dropdown-toggle" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            ご利用ガイド
                        </a>

                        <ul class="dropdown-menu product-dropdown-menu">
                            <li>
                                <a href="{{ url('/guide') }}" class="dropdown-item">
                                    ご利用方法
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('/shipping') }}" class="dropdown-item">
                                    配送について
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('/payment') }}" class="dropdown-item">
                                    お支払いについて
                                </a>
                            </li>

                            <li>
                                <a href="{{ url('/faq') }}" class="dropdown-item">
                                    よくあるご質問
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Contact --}}
                    <li class="nav-item">
                        <a href="{{ url('/contact') }}"
                            class="nav-link header-pill-link {{ request()->is('contact') ? 'active' : '' }}">
                            お問い合わせ
                        </a>
                    </li>

                </ul>

                {{-- Account and Cart --}}
                <div class="header-actions">

                    {{-- Account --}}
                    <button type="button" class="header-action-link account-modal-trigger" aria-label="アカウント"
                        aria-haspopup="dialog" aria-controls="accountLoginModal" aria-expanded="false"
                        data-login-modal-open>
                        <svg viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="15" cy="8" r="5.5" fill="none" stroke="currentColor"
                                stroke-width="1.5" />

                            <path d="M4 28C4 20.7 8.7 16.5 15 16.5C21.3 16.5 26 20.7 26 28" fill="none"
                                stroke="currentColor" stroke-width="1.5" />
                        </svg>
                    </button>

                    {{-- Cart --}}
                    <a href="{{ url('/cart') }}" class="header-action-link position-relative" aria-label="ショッピングカート">
                        <svg viewBox="0 0 30 32" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path d="M6 10H24L25.5 30H4.5L6 10Z" fill="none" stroke="currentColor"
                                stroke-width="1.5" />

                            <path d="M10 11V6.5C10 3 12 1 15 1C18 1 20 3 20 6.5V11" fill="none"
                                stroke="currentColor" stroke-width="1.5" />
                        </svg>

                        @if (($cartCount ?? 0) > 0)
                            <span class="header-cart-count">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>

                </div>
            </div>

        </div>
    </nav>
</header>
<div class="mobile-navigation" id="mobileNavigation" aria-hidden="true" data-mobile-navigation>
    <div class="mobile-navigation-panel" role="dialog" aria-modal="true" aria-label="メニュー" tabindex="-1">
        <div class="mobile-navigation-top">
            <a href="{{ url('/') }}" class="mobile-navigation-mark" aria-label="ThaiSilk Home"></a>
            <button type="button" class="mobile-navigation-close" aria-label="メニューを閉じる"
                data-mobile-menu-close></button>
        </div>
        <nav aria-label="モバイルメニュー">
            <ul class="mobile-navigation-list">
                <li><a href="{{ url('/products/thaisilk_01') }}">シルク製社員証ケース</a></li>
                <li><a href="{{ url('/about') }}">私たちについて</a></li>
                <li><a href="{{ url('/guide') }}" class="mobile-navigation-guide">ご利用ガイド <span
                            aria-hidden="true">›</span></a></li>
                <li><a href="{{ url('/contact') }}">お問い合わせ</a></li>
            </ul>
        </nav>
    </div>
</div>
@php($loginErrors = $errors->getBag('login'))
@php($passwordResetErrors = $errors->getBag('passwordReset'))

<div id="accountLoginModal" class="account-modal-overlay" data-login-modal role="presentation" aria-hidden="true">
    <section class="account-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="accountLoginTitle"
        tabindex="-1">
        <button type="button" class="account-modal-close" aria-label="閉じる" data-login-modal-close></button>

        @guest
            <h2 id="accountLoginTitle" class="account-modal-title">サインイン</h2>
            <p class="account-modal-description">ご登録のメールアドレスでサインインしてください。</p>

            @if ($loginErrors->any())
                <div class="account-modal-error" role="alert">
                    {{ $loginErrors->first() }}
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="account-login-form">
                @csrf

                <label class="account-login-field">
                    <span class="visually-hidden">メールアドレス</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="メールアドレス"
                        autocomplete="email" required>
                </label>

                <label class="account-login-field account-password-field">
                    <span class="visually-hidden">パスワード</span>
                    <input type="password" name="password" placeholder="パスワード" autocomplete="current-password" required
                        data-login-password>
                    <button type="button" class="account-password-toggle" aria-label="パスワードを表示" aria-pressed="false"
                        data-login-password-toggle>
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z" />
                            <circle cx="12" cy="12" r="2.7" />
                            <path class="account-password-slash" d="M4 4l16 16" />
                        </svg>
                    </button>
                </label>

                <div class="account-login-options">
                    <label class="account-remember">
                        <input type="checkbox" name="remember" value="1">
                        <span>メールアドレスを保存する</span>
                    </label>
                    <button type="button" class="account-forgot-link" data-password-reset-open>パスワードをお忘れですか？</button>
                </div>

                <button type="submit" class="account-login-submit">サインイン</button>
            </form>

            <div class="account-login-divider"><span>または</span></div>

            <div class="account-social-list">
                {{-- <button type="button" class="account-social-button" disabled>
                    <img
                        class="account-social-icon account-social-icon-line"
                        src="{{ asset('assets/images/home/streamline-logos_line-app-logo-block.png') }}"
                        alt="LINE"
                    >
                    <span>LINEでログイン</span>
                </button> --}}
                <a href="{{ route('google.redirect') }}" class="account-social-button account-google-login">
                    <img class="account-social-icon account-social-icon-google"
                        src="{{ asset('assets/images/home/material-icon-theme_google.png') }}" alt="Google">
                    <span>Googleでサインイン</span>
                </a>
            </div>

            <p class="account-register-prompt">
                <span>初めての方は</span>
                <a href="{{ route('register') }}">新規会員登録 &gt;</a>
            </p>
        @else
            <h2 id="accountLoginTitle" class="account-modal-title">アカウント</h2>
            <p class="account-modal-description">{{ auth()->user()->email }}</p>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="account-login-submit">サインアウト</button>
            </form>
        @endguest
    </section>
</div>

<div id="passwordResetModal" class="account-modal-overlay" data-password-reset-modal role="presentation"
    aria-hidden="true">
    <section class="account-modal-dialog password-reset-dialog" role="dialog" aria-modal="true"
        aria-labelledby="passwordResetTitle" tabindex="-1">
        <button type="button" class="account-modal-close" aria-label="閉じる" data-password-reset-close></button>
        @if (session('password_reset_sent'))
            <div class="password-reset-success-icon" aria-hidden="true">✓</div>
            <h2 id="passwordResetTitle" class="account-modal-title">再設定メールを送信しました</h2>
            <p class="account-modal-description">
                ご登録いただいたメールアドレス宛に、パスワード再設定用URLを記載したメールを送信しました。<br>メール内のリンクから、新しいパスワードの設定を行ってください。</p>
            <a href="{{ route('home') }}" class="account-login-submit password-reset-home">トップページへ</a>
        @else
            <h2 id="passwordResetTitle" class="account-modal-title">パスワードの再設定</h2>
            <p class="account-modal-description">ご登録いただいているメールアドレスを入力してください。<br>パスワード再設定用ページのURLをお送りします。</p>
            @if ($passwordResetErrors->any())
                <div class="account-modal-error" role="alert">{{ $passwordResetErrors->first() }}</div>
            @endif
            <form action="{{ route('password.email') }}" method="POST" class="account-login-form">
                @csrf
                <label class="account-login-field">
                    <span>メールアドレス</span>
                    <input type="email" name="email" value="{{ old('email') }}"
                        placeholder="example@sample.com" autocomplete="email" required>
                </label>
                <button type="submit" class="account-login-submit">送信する</button>
            </form>
            <button type="button" class="password-reset-back" data-password-reset-close>＜ ログイン画面に戻る</button>
        @endif
    </section>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const siteHeader = document.querySelector('.site-header');

            if (!siteHeader) {
                return;
            }

            const updateHeader = function() {
                siteHeader.classList.toggle('is-scrolled', window.scrollY > 24);
            };

            updateHeader();
            window.addEventListener('scroll', updateHeader, {
                passive: true
            });
            const mobileNavigation = document.querySelector('[data-mobile-navigation]');
            const mobileNavigationPanel = mobileNavigation?.querySelector('.mobile-navigation-panel');
            const mobileNavigationOpen = document.querySelector('[data-mobile-menu-open]');
            const mobileNavigationClose = mobileNavigation?.querySelector('[data-mobile-menu-close]');
            const openMobileNavigation = function() {
                if (!mobileNavigation) return;
                mobileNavigation.classList.add('is-open');
                mobileNavigation.setAttribute('aria-hidden', 'false');
                mobileNavigationOpen?.setAttribute('aria-expanded', 'true');
                document.body.classList.add('mobile-navigation-open');
                window.requestAnimationFrame(() => mobileNavigationPanel?.focus());
            };
            const closeMobileNavigation = function() {
                if (!mobileNavigation) return;
                mobileNavigation.classList.remove('is-open');
                mobileNavigation.setAttribute('aria-hidden', 'true');
                mobileNavigationOpen?.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('mobile-navigation-open');
                mobileNavigationOpen?.focus();
            };
            mobileNavigationOpen?.addEventListener('click', openMobileNavigation);
            mobileNavigationClose?.addEventListener('click', closeMobileNavigation);
            mobileNavigation?.addEventListener('click', function(event) {
                if (event.target === mobileNavigation) closeMobileNavigation();
            });
            mobileNavigation?.querySelectorAll('a').forEach((link) => link.addEventListener('click',
                closeMobileNavigation));
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && mobileNavigation?.classList.contains('is-open')) {
                    closeMobileNavigation();
                }
            });

            const modal = document.querySelector('[data-login-modal]');
            const dialog = modal?.querySelector('.account-modal-dialog');
            const openButtons = document.querySelectorAll('[data-login-modal-open]');
            const closeButtons = modal?.querySelectorAll('[data-login-modal-close]') ?? [];
            const password = modal?.querySelector('[data-login-password]');
            const passwordToggle = modal?.querySelector('[data-login-password-toggle]');
            const passwordResetModal = document.querySelector('[data-password-reset-modal]');
            const passwordResetDialog = passwordResetModal?.querySelector('.password-reset-dialog');
            const passwordResetOpenButtons = document.querySelectorAll('[data-password-reset-open]');
            const passwordResetCloseButtons = passwordResetModal?.querySelectorAll('[data-password-reset-close]') ??
                [];
            let lastFocusedElement = null;

            const openModal = function() {
                if (!modal || !dialog) {
                    return;
                }

                lastFocusedElement = document.activeElement;
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('account-modal-open');
                openButtons.forEach((button) => button.setAttribute('aria-expanded', 'true'));

                window.requestAnimationFrame(() => dialog.focus());
            };

            const closeModal = function() {
                if (!modal) {
                    return;
                }

                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('account-modal-open');
                openButtons.forEach((button) => button.setAttribute('aria-expanded', 'false'));

                if (lastFocusedElement instanceof HTMLElement) {
                    lastFocusedElement.focus();
                }
            };

            const openPasswordResetModal = function() {
                if (!passwordResetModal || !passwordResetDialog) return;
                modal?.classList.remove('is-open');
                passwordResetModal.classList.add('is-open');
                passwordResetModal.setAttribute('aria-hidden', 'false');
                document.body.classList.add('account-modal-open');
                window.requestAnimationFrame(() => passwordResetDialog.focus());
            };
            const closePasswordResetModal = function() {
                if (!passwordResetModal) return;
                passwordResetModal.classList.remove('is-open');
                passwordResetModal.setAttribute('aria-hidden', 'true');
                document.body.classList.remove('account-modal-open');
            };
            passwordResetOpenButtons.forEach((button) => button.addEventListener('click', openPasswordResetModal));
            passwordResetCloseButtons.forEach((button) => button.addEventListener('click',
            closePasswordResetModal));
            passwordResetModal?.addEventListener('click', function(event) {
                if (event.target === passwordResetModal) closePasswordResetModal();
            });
            openButtons.forEach((button) => button.addEventListener('click', openModal));
            closeButtons.forEach((button) => button.addEventListener('click', closeModal));

            modal?.addEventListener('click', function(event) {
                if (event.target === modal) {
                    closeModal();
                }
            });

            passwordToggle?.addEventListener('click', function() {
                const showPassword = password.type === 'password';

                password.type = showPassword ? 'text' : 'password';
                passwordToggle.setAttribute('aria-pressed', String(showPassword));
                passwordToggle.setAttribute(
                    'aria-label',
                    showPassword ? 'パスワードを隠す' : 'パスワードを表示'
                );
            });

            document.addEventListener('keydown', function(event) {
                if (!modal?.classList.contains('is-open')) {
                    return;
                }

                if (event.key === 'Escape') {
                    closeModal();
                    return;
                }

                if (event.key !== 'Tab' || !dialog) {
                    return;
                }

                const focusableElements = Array.from(dialog.querySelectorAll(
                    'a[href], button:not([disabled]), input:not([disabled]), [tabindex]:not([tabindex="-1"])'
                ));
                const firstElement = focusableElements[0];
                const lastElement = focusableElements[focusableElements.length - 1];

                if (event.shiftKey && document.activeElement === firstElement) {
                    event.preventDefault();
                    lastElement?.focus();
                } else if (!event.shiftKey && document.activeElement === lastElement) {
                    event.preventDefault();
                    firstElement?.focus();
                }
            });

            @if (session('open_password_reset_modal') || session('password_reset_sent') || $passwordResetErrors->any())
                openPasswordResetModal();
            @endif

            @if (session('open_login_modal') || $loginErrors->any())
                openModal();
            @endif

            if (new URLSearchParams(window.location.search).get('login') === '1') {
                openModal();
            }
        });
    </script>
@endpush
