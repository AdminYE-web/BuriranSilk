@extends('frontend.layouts.app')

@section('title', 'お問い合わせ | ThaiSilk')
@section('body-class', 'contact-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
@endpush

@section('content')
    <section class="contact-main">
        <div class="contact-container">
            <header class="contact-intro">
                <h1>お問い合わせ</h1>
                <p>不明な点がございましたら、お気軽にお問い合わせください。</p>
                <p>通常1〜2営業日以内にご返信いたします。</p>
                <small>お急ぎの場合はこちら：contact@silicone-wristband-studio.jp</small>
            </header>

            <div class="contact-content-card">
                <section class="contact-faq" id="contactFaq" aria-labelledby="contactFaqTitle">
                    <h2 id="contactFaqTitle">よくある質問</h2>

                    <div class="contact-faq-list">
                        @foreach ($faqs as $faq)
                            <div class="contact-faq-item">
                                <button type="button" class="contact-faq-trigger" aria-expanded="false">
                                    <span>{{ data_get($faq, 'question') }}</span>
                                    <span class="contact-faq-plus" aria-hidden="true"></span>
                                </button>
                                <div class="contact-faq-panel" aria-hidden="true">
                                    <div class="contact-faq-answer">{{ data_get($faq, 'answer') }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="#contactForm" class="contact-faq-more">お問い合わせフォームへ <span>→</span></a>
                </section>

                <section class="contact-form-card" id="contactForm" aria-labelledby="contactFormTitle">
                    <h2 id="contactFormTitle">お問い合わせフォーム</h2>

                    @if (session('contact_success'))
                        <div class="contact-alert contact-alert-success" role="status">
                            {{ session('contact_success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="contact-alert contact-alert-error" role="alert">
                            <p>入力内容をご確認ください。</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                        @csrf
                        <input type="text" name="website" value="" class="contact-honeypot" tabindex="-1"
                            autocomplete="off" aria-hidden="true">

                        <div class="contact-form-row">
                            <div class="contact-form-label">お名前 <em>※必須</em></div>
                            <div class="contact-name-fields">
                                <input type="text" name="last_name"
                                    value="{{ old('last_name', auth()->user()?->last_name) }}" placeholder="姓（例：山田）"
                                    aria-label="姓" required>
                                <input type="text" name="first_name"
                                    value="{{ old('first_name', auth()->user()?->first_name) }}" placeholder="名（例：太郎）"
                                    aria-label="名" required>
                            </div>
                        </div>

                        <div class="contact-form-row">
                            <label class="contact-form-label" for="contactEmail">メールアドレス <em>※必須</em></label>
                            <input id="contactEmail" type="email" name="email"
                                value="{{ old('email', auth()->user()?->email) }}" autocomplete="email" required>
                        </div>

                        <div class="contact-form-row">
                            <label class="contact-form-label" for="contactInquiryType">お問い合わせ種別 <em>※必須</em></label>
                            <div class="contact-select-wrap">
                                <select id="contactInquiryType" name="inquiry_type" required>
                                    <option value="">選択してください</option>
                                    @foreach ($inquiryTypes as $value => $label)
                                        <option value="{{ $value }}" @selected(old('inquiry_type') === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="contact-form-row">
                            <label class="contact-form-label" for="contactOrderNumber">注文番号 <span>（任意）</span></label>
                            <input id="contactOrderNumber" type="text" name="order_number"
                                value="{{ old('order_number') }}">
                        </div>

                        <div class="contact-form-row contact-form-message-row">
                            <label class="contact-form-label" for="contactMessage">お問い合わせ内容 <em>※必須</em></label>
                            <textarea id="contactMessage" name="message" rows="8" required>{{ old('message') }}</textarea>
                        </div>

                        <label class="contact-privacy">
                            <input type="checkbox" name="privacy" value="1" @checked(old('privacy')) required>
                            <span class="contact-checkbox" aria-hidden="true"></span>
                            <span>お問い合わせの前に、当社の<a href="{{ url('/privacy-policy') }}" target="_blank">プライバシーポリシー</a>をご確認ください。<br>
                                プライバシーポリシーに同意する <em>※必須</em></span>
                        </label>

                        <div class="contact-submit-wrap">
                            <button type="submit" class="contact-submit">送信する <span>→</span></button>
                            <p>送信後、確認メールをお送りいたします。</p>
                        </div>
                    </form>

                    <div class="contact-information">
                        <section class="contact-information-methods">
                            <h3>その他の連絡方法</h3>
                            <div class="contact-method-list">
                                <div class="contact-method-item">
                                    <img src="{{ asset('assets/images/contact/mdi-light_email.png') }}" alt="">
                                    <div>
                                        <h4>メールでのお問い合わせ</h4>
                                        <a href="mailto:contact@silicone-wristband-studio.jp">contact@silicone-wristband-studio.jp</a>
                                    </div>
                                </div>
                                <div class="contact-method-item">
                                    <img src="{{ asset('assets/images/contact/solar_phone-linear.png') }}" alt="">
                                    <div>
                                        <h4>専用電話番号</h4>
                                        <a href="tel:05068657753">050-6865-7753</a>
                                    </div>
                                </div>
                                <div class="contact-method-item">
                                    <img src="{{ asset('assets/images/contact/mingcute_time-line.png') }}" alt="">
                                    <div>
                                        <h4>営業時間：<span>10:00〜18:00</span></h4>
                                        <p>（土日祝休業）</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section class="contact-information-location">
                            <h3>所在地</h3>
                            <div class="contact-location-company">
                                <img src="{{ asset('assets/images/contact/mingcute_location-line.png') }}" alt="">
                                <p>ユー・アンド・アース株式会社</p>
                            </div>
                            <address class="contact-offices">
                                <div class="contact-office">
                                    <strong>【東京オフィス】</strong>
                                    <p>〒135-0064 東京都江東区青海<br>2-4-32 TIME24ビル 10階 東1</p>
                                </div>
                                <div class="contact-office">
                                    <strong>【大阪オフィス】</strong>
                                    <p>〒559-0034 大阪府大阪市住之江区南港北<br>2-1-10 ATCビル ITM棟 3階 E-3C</p>
                                </div>
                            </address>
                        </section>
                    </div>
                </section>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.contact-faq-trigger').forEach((trigger) => {
            trigger.addEventListener('click', () => {
                const item = trigger.closest('.contact-faq-item');
                const panel = item?.querySelector('.contact-faq-panel');
                const isOpen = item?.classList.toggle('is-open') ?? false;

                trigger.setAttribute('aria-expanded', String(isOpen));
                panel?.setAttribute('aria-hidden', String(!isOpen));
            });
        });
    </script>
@endpush