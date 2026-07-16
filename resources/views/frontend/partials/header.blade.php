<header class="site-header">
    <nav class="navbar navbar-expand-lg navbar-dark p-0">
        <div class="container-fluid header-container">

            {{-- Logo --}}
            <a
                href="{{ url('/') }}"
                class="navbar-brand header-brand"
                aria-label="ThaiSilk Home"
            >
                <span class="header-brand-icon">
                    <svg
                        viewBox="0 0 50 50"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-hidden="true"
                    >
                        <path
                            d="M25 44C20 39 11 37 8 29C15 29 21 33 25 39C29 33 35 29 42 29C39 37 30 39 25 44Z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        />

                        <path
                            d="M25 39C19 34 15 26 17 18C22 21 25 27 25 34C25 27 28 21 33 18C35 26 31 34 25 39Z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        />

                        <path
                            d="M25 33C21 27 21 18 25 10C29 18 29 27 25 33Z"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        />

                        <path
                            d="M17 30C12 27 9 22 9 17C15 18 20 22 22 28"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        />

                        <path
                            d="M33 30C38 27 41 22 41 17C35 18 30 22 28 28"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.6"
                        />
                    </svg>
                </span>

                <span class="header-brand-text">
                    <span class="header-brand-name">
                        ThaiSilk
                    </span>

                    <span class="header-brand-subtitle">
                        時を越えて愛される上質な手仕事
                    </span>
                </span>
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
                    <li class="nav-item dropdown">
                        <a
                            href="#"
                            class="nav-link dropdown-toggle"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            商品一覧
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a
                                    href="{{ url('/products') }}"
                                    class="dropdown-item"
                                >
                                    すべての商品
                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ url('/products/bags') }}"
                                    class="dropdown-item"
                                >
                                    バッグ
                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ url('/products/wallets') }}"
                                    class="dropdown-item"
                                >
                                    財布・小物
                                </a>
                            </li>

                            <li>
                                <a
                                    href="{{ url('/products/accessories') }}"
                                    class="dropdown-item"
                                >
                                    アクセサリー
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