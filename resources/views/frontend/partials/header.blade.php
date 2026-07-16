<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark p-0">
        <div class="container-fluid header-container">

            {{-- Logo --}}
            <a
                href="{{ url('/') }}"
                class="navbar-brand header-brand"
                aria-label="ThaiSilk Home"
            >
                <img
                    src="{{ asset('assets/images/logo/logo_thaisilk.png') }}"
                    alt="ThaiSilk"
                    width="167"
                    height="55"
                >
            </a>

            {{-- Mobile Menu Button --}}
            <button
                class="navbar-toggler border-0 shadow-none"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainNavbar"
                aria-controls="mainNavbar"
                aria-expanded="false"
                aria-label="メニューを開く"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Navigation --}}
            <div
                class="collapse navbar-collapse"
                id="mainNavbar"
            >
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    {{-- Product --}}
                    <li class="nav-item dropdown product-dropdown">
    <a
        href="#"
        class="nav-link dropdown-toggle product-dropdown-toggle"
        role="button"
        data-bs-toggle="dropdown"
        aria-expanded="false"
    >
        <span>商品一覧</span>
    </a>

    <ul class="dropdown-menu product-dropdown-menu">
        <li>
            <a
                href="{{ url('/products/silk-employee-id-case') }}"
                class="dropdown-item"
            >
                シルク製社員証ケース
            </a>
        </li>

        <li>
            <a
                href="{{ url('/products/key-ring') }}"
                class="dropdown-item"
            >
                key ring
            </a>
        </li>

        <li>
            <a
                href="{{ url('/products/business-card-holder') }}"
                class="dropdown-item"
            >
                Business Card Holder
            </a>
        </li>

        <li>
            <a
                href="{{ url('/products/gold-thread-products') }}"
                class="dropdown-item"
            >
                製品金撚
            </a>
        </li>
    </ul>
</li>

                    {{-- About --}}
                    <li class="nav-item">
                        <a
                            href="{{ url('/about') }}"
                            class="nav-link {{ request()->is('about') ? 'active' : '' }}"
                        >
                            私たちについて
                        </a>
                    </li>

                    {{-- Guide --}}
                    <li class="nav-item dropdown">
                        <a
                            href="#"
                            class="nav-link dropdown-toggle"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            ご利用ガイド
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a
                                    href="{{ url('/guide') }}"
                                    class="dropdown-item"
                                >
                                    ご利用方法
                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ url('/shipping') }}"
                                    class="dropdown-item"
                                >
                                    配送について
                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ url('/payment') }}"
                                    class="dropdown-item"
                                >
                                    お支払いについて
                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ url('/faq') }}"
                                    class="dropdown-item"
                                >
                                    よくあるご質問
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Contact --}}
                    <li class="nav-item">
                        <a
                            href="{{ url('/contact') }}"
                            class="nav-link {{ request()->is('contact') ? 'active' : '' }}"
                        >
                            お問い合わせ
                        </a>
                    </li>

                </ul>

                {{-- Account and Cart --}}
                <div class="header-actions">

                    {{-- Account --}}
                    <a
                        href="{{ url('/account') }}"
                        class="header-action-link"
                        aria-label="アカウント"
                    >
                        <svg
                            viewBox="0 0 30 30"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >
                            <circle
                                cx="15"
                                cy="8"
                                r="5.5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />

                            <path
                                d="M4 28C4 20.7 8.7 16.5 15 16.5C21.3 16.5 26 20.7 26 28"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
                        </svg>
                    </a>

                    {{-- Cart --}}
                    <a
                        href="{{ url('/cart') }}"
                        class="header-action-link position-relative"
                        aria-label="ショッピングカート"
                    >
                        <svg
                            viewBox="0 0 30 32"
                            xmlns="http://www.w3.org/2000/svg"
                            aria-hidden="true"
                        >
                            <path
                                d="M6 10H24L25.5 30H4.5L6 10Z"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />

                            <path
                                d="M10 11V6.5C10 3 12 1 15 1C18 1 20 3 20 6.5V11"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.5"
                            />
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