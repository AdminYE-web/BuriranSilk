<footer class="site-footer">
    <div class="container footer-container">

        <div class="row g-0 footer-main-row">

            {{-- Logo --}}
            <div class="col-12 col-lg-2 footer-column footer-brand-column">

                <a href="{{ url('/') }}" class="footer-brand" aria-label="ThaiSilk Home">
                    <img src="{{ asset('assets/images/logo/logo_thaisilk.png') }}" alt="ThaiSilk" class="footer-brand-logo"
                        width="167" height="55">
                </a>

            </div>

            {{-- Desktop Products / Guide Menu --}}
            <div class="col-6 col-md-4 col-lg-2 footer-column footer-desktop-menu">

                <div class="footer-menu-group">
                    <h2 class="footer-heading">
                        商品一覧
                    </h2>

                    <ul class="footer-link-list">
                        <li>
                            <a href="{{ url('/products/id-case') }}">
                                シルク製社員証ケース
                            </a>
                        </li>

                        {{-- <li>
                            <a href="{{ url('/products/key-ring') }}">
                                key ring
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/products/business-card-holder') }}">
                                Business Card Holder
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/products/gold-thread-products') }}">
                                製品金撚
                            </a>
                        </li> --}}
                    </ul>
                </div>

                <div class="footer-menu-group footer-menu-group-secondary">
                    <h2 class="footer-heading">
                        ご利用ガイド
                    </h2>

                    <ul class="footer-link-list">
                        <li>
                            <a href="{{ url('/guide/order') }}">
                                ご注文の流れ
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/guide/payment') }}">
                                お支払い方法
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/guide/shipping') }}">
                                配送・送料
                            </a>
                        </li>

                        <li>
                            <a href="{{ url('/guide/returns') }}">
                                返品・交換
                            </a>
                        </li>
                    </ul>
                </div>

            </div>

            {{-- Desktop Help Menu --}}
            <div class="col-6 col-md-4 col-lg-2 footer-column footer-desktop-menu">

                <h2 class="footer-heading">
                    ヘルプ / サポート
                </h2>

                <ul class="footer-link-list">
                    <li>
                        <a href="{{ url('/faq') }}">
                            よくあるご質問
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/contact') }}">
                            お問い合わせ
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/sample-request') }}">
                            サンプル依頼
                        </a>
                    </li>
                </ul>

            </div>

            {{-- Mobile Accordion Menu (Visible on Mobile) --}}
            <div class="col-12 footer-mobile-accordion">
                <div class="footer-accordion-item">
                    <button type="button" class="footer-accordion-header" aria-expanded="false">
                        <span>商品一覧</span>
                        <svg class="footer-accordion-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                    </button>
                    <div class="footer-accordion-body">
                        <ul class="footer-accordion-list">
                            <li><a href="{{ url('/products/id-case') }}">シルク製社員証ケース</a></li>
                            <li><a href="{{ url('/products/key-ring') }}">key ring</a></li>
                            <li><a href="{{ url('/products/business-card-holder') }}">Business Card Holder</a></li>
                            <li><a href="{{ url('/products/gold-thread-products') }}">製品金撚</a></li>
                        </ul>
                    </div>
                </div>

                <div class="footer-accordion-item">
                    <button type="button" class="footer-accordion-header" aria-expanded="false">
                        <span>ご利用ガイド</span>
                        <svg class="footer-accordion-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                    </button>
                    <div class="footer-accordion-body">
                        <ul class="footer-accordion-list">
                            <li><a href="{{ url('/guide/order') }}">ご注文の流れ</a></li>
                            <li><a href="{{ url('/guide/payment') }}">お支払い方法</a></li>
                            <li><a href="{{ url('/guide/shipping') }}">配送・送料</a></li>
                            <li><a href="{{ url('/guide/returns') }}">返品・交換</a></li>
                        </ul>
                    </div>
                </div>

                <div class="footer-accordion-item">
                    <button type="button" class="footer-accordion-header" aria-expanded="false">
                        <span>ヘルプ / サポート</span>
                        <svg class="footer-accordion-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                    </button>
                    <div class="footer-accordion-body">
                        <ul class="footer-accordion-list">
                            <li><a href="{{ url('/faq') }}">よくあるご質問</a></li>
                            <li><a href="{{ url('/contact') }}">お問い合わせ</a></li>
                            <li><a href="{{ url('/sample-request') }}">サンプル依頼</a></li>
                        </ul>
                    </div>
                </div>

                <div class="footer-accordion-item">
                    <button type="button" class="footer-accordion-header" aria-expanded="false">
                        <span>アクセス</span>
                        <svg class="footer-accordion-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="18 15 12 9 6 15"></polyline>
                        </svg>
                    </button>
                    <div class="footer-accordion-body">
                        <div class="footer-accordion-access">
                            <p class="footer-accordion-company">ユー・アンド・アース株式会社</p>
                            <div class="footer-accordion-office">
                                <span class="footer-accordion-office-title">【東京オフィス】</span>
                                <p class="footer-accordion-address">〒135-0064 東京都江東区青海2-4-32 TIME24ビル 10階 東1</p>
                            </div>
                            <div class="footer-accordion-office">
                                <span class="footer-accordion-office-title">【大阪オフィス】</span>
                                <p class="footer-accordion-address">〒559-0034 大阪府大阪市住之江区南港北2-1-10 ATCビル ITM棟 3階 E-3C
                                </p>
                            </div>
                            <p class="footer-accordion-phone">
                                用電話番号：<a href="tel:05068657753">050-6865-7753</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Access (Desktop) --}}
            <div class="col-12 col-md-4 col-lg-3 footer-column footer-access-column footer-desktop-menu">

                <h2 class="footer-heading">
                    アクセス
                </h2>

                <p class="footer-company-name">
                    ユー・アンド・アース株式会社
                </p>

                <address class="footer-address">

                    <div class="footer-office">
                        <span class="footer-office-label">
                            【東京オフィス】
                        </span>

                        <p>
                            〒135-0064 東京都江東区青海<br>
                            2-4-32 TIME24ビル 10階 東1
                        </p>
                    </div>

                    <div class="footer-office">
                        <span class="footer-office-label">
                            【大阪オフィス】
                        </span>

                        <p>
                            〒559-0034 大阪府大阪市住之江区<br>
                            南港北2-1-10 ATCビル ITM棟 3階 E-3C
                        </p>
                    </div>

                    <p class="footer-phone">
                        用電話番号：
                        <a href="tel:05068657753">
                            050-6865-7753
                        </a>
                    </p>

                </address>

            </div>

            {{-- Social --}}
            <div class="col-12 col-lg-3 footer-column footer-social-column">

                <h2 class="footer-heading footer-social-heading">
                    フォローする
                </h2>

                <div class="footer-social-links">

                    {{-- Twitter / X --}}
                    <a href="#" class="footer-social-link" target="_blank" rel="noopener noreferrer"
                        aria-label="X">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path
                                d="M18.5 5.5L13.3 11.4L19.5 18.5H15.9L11.3 13.2L6.7 18.5H4.5L10.3 11.9L4.8 5.5H8.5L12.4 10L16.3 5.5H18.5Z"
                                fill="currentColor" />
                        </svg>
                    </a>

                    {{-- Facebook --}}
                    <a href="#" class="footer-social-link" target="_blank" rel="noopener noreferrer"
                        aria-label="Facebook">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <path
                                d="M14.2 8H17V4.6C16.5 4.5 14.9 4.4 13.3 4.4C10.1 4.4 7.9 6.3 7.9 9.9V13H4.5V16.8H7.9V24H12.1V16.8H15.5L16 13H12.1V10.3C12.1 9.2 12.4 8 14.2 8Z"
                                fill="currentColor" />
                        </svg>
                    </a>

                    {{-- Instagram --}}
                    <a href="#" class="footer-social-link" target="_blank" rel="noopener noreferrer"
                        aria-label="Instagram">
                        <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <rect x="4" y="4" width="16" height="16" rx="4" fill="none"
                                stroke="currentColor" stroke-width="1.8" />

                            <circle cx="12" cy="12" r="3.5" fill="none" stroke="currentColor"
                                stroke-width="1.8" />

                            <circle cx="17.3" cy="6.8" r="1" fill="currentColor" />
                        </svg>
                    </a>

                </div>

            </div>

        </div>

        {{-- Footer Bottom Navigation --}}
        <nav class="footer-bottom-navigation" aria-label="Footer Navigation">
            <ul>
                <li>
                    <a href="{{ url('/about') }}">
                        私たちについて
                    </a>
                </li>

                <li>
                    <a href="{{ url('/company') }}">
                        運営会社
                    </a>
                </li>

                <li>
                    <a href="{{ url('/privacy-policy') }}">
                        プライバシーポリシー
                    </a>
                </li>

                <li>
                    <a href="{{ url('/terms') }}">
                        利用規約
                    </a>
                </li>
            </ul>
        </nav>

        {{-- Copyright --}}
        <div class="footer-copyright">
            Copyright &copy; 2023-{{ date('Y') }}
            ホットモバイリーオリジナルグッズ.
            Powered by YOU AND EARTH Co.,Ltd.
        </div>

    </div>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.footer-accordion-header').forEach(function(header) {
            header.addEventListener('click', function() {
                const item = this.closest('.footer-accordion-item');
                const isOpen = item.classList.contains('is-open');

                item.classList.toggle('is-open');
                this.setAttribute('aria-expanded', String(!isOpen));
            });
        });
    });
</script>
