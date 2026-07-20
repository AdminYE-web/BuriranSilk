@extends('frontend.layouts.app')

@section('title', '新規会員登録 | ThaiSilk')
@section('body-class', 'register-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/register.css') }}">
@endpush

@section('content')
    @php
        $account = $registration['account'] ?? [];
        $customer = $registration['customer'] ?? [];
        $email = old('email', $account['email'] ?? '');
        $customerType = old('customer_type', $customer['customer_type'] ?? 'individual');
        $postalDigits = preg_replace('/\D/', '', old('postal_code', $customer['postal_code'] ?? ''));
        $postalFront = old('postal_code_front', substr($postalDigits, 0, 3));
        $postalBack = old('postal_code_back', substr($postalDigits, 3, 4));
    @endphp

    <div class="container register-shell">
        <div class="row justify-content-center w-100">
            <section class="register-card register-card-step-{{ $step }} col-12 col-sm-10 col-md-9 col-lg-8 col-xl-8" aria-labelledby="registerTitle">
                <a href="{{ route('home') }}" class="register-logo" aria-label="ThaiSilk Home">
                    <img src="{{ asset('assets/images/auth/image-Photoroom (12) 1.png') }}" alt="ThaiSilk">
                </a>

                <ol class="register-steps" aria-label="会員登録の進行状況">
                    @foreach ([1 => 'アカウント情報', 2 => 'お客様情報', 3 => '入力内容確認', 4 => '登録完了'] as $stepNumber => $label)
                        <li class="register-step {{ $stepNumber === $step ? 'is-active' : '' }} {{ $stepNumber < $step ? 'is-complete' : '' }}" data-step-indicator="{{ $stepNumber }}">
                            <span class="register-step-number">{{ $stepNumber }}</span>
                            <span class="register-step-label">{{ $label }}</span>
                        </li>
                    @endforeach
                </ol>

                @if ($step === 1)
                    <form action="{{ route('register.step1.store') }}" method="POST" class="register-form">
                        @csrf
                        <section class="register-panel" aria-labelledby="registerTitle">
                            <header class="register-panel-header">
                                <h1 id="registerTitle">アカウント情報</h1>
                                <p>下記の項目をご入力の上、「次へ」ボタンを押してください。</p>
                            </header>

                            <div class="register-fields">
                                <label class="register-field">
                                    <span>メールアドレス <em>※</em></span>
                                    <input type="email" name="email" value="{{ $email }}" placeholder="example@sample.com" autocomplete="email" required>
                                    @error('email') <small class="register-error">{{ $message }}</small> @enderror
                                </label>

                                <label class="register-field register-password-field">
                                    <span>パスワード <em>※</em></span>
                                    <span class="register-input-wrap">
                                        <input type="password" name="password" placeholder="8文字以上の半角英数字" autocomplete="new-password" minlength="8" pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}" title="8文字以上の半角英数字で入力してください。" required data-register-password>
                                        <button type="button" class="register-password-toggle" aria-label="パスワードを表示" data-register-password-toggle>
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z"/><circle cx="12" cy="12" r="2.7"/><path class="register-password-slash" d="M4 4l16 16"/></svg>
                                        </button>
                                    </span>
                                    @error('password') <small class="register-error">{{ $message }}</small> @enderror
                                </label>

                                <label class="register-field register-password-field">
                                    <span>パスワード（確認） <em>※</em></span>
                                    <span class="register-input-wrap">
                                        <input type="password" name="password_confirmation" placeholder="同じパスワードを入力" autocomplete="new-password" minlength="8" required data-register-password>
                                        <button type="button" class="register-password-toggle" aria-label="パスワードを表示" data-register-password-toggle>
                                            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M2.5 12s3.5-5 9.5-5 9.5 5 9.5 5-3.5 5-9.5 5-9.5-5-9.5-5Z"/><circle cx="12" cy="12" r="2.7"/><path class="register-password-slash" d="M4 4l16 16"/></svg>
                                        </button>
                                    </span>
                                    <small class="register-hint">確認のためもう一度入力</small>
                                </label>
                            </div>

                            <label class="register-consent">
                                <input type="checkbox" name="term_policy" value="1" {{ old('term_policy') ? 'checked' : '' }} required>
                                <span>「<a href="{{ url('/terms') }}" target="_blank">利用規約</a>」および「<a href="{{ url('/privacy-policy') }}" target="_blank">プライバシーポリシー</a>」に同意する</span>
                            </label>
                            @error('term_policy') <small class="register-error register-consent-error">{{ $message }}</small> @enderror

                            <button type="submit" class="register-primary-button">次へ</button>
                            <p class="register-login-link">すでにアカウントをお持ちの方は <a href="{{ route('home', ['login' => 1]) }}">こちら &gt;</a></p>
                        </section>
                    </form>
                @elseif ($step === 2)
                    <form action="{{ route('register.step2.store') }}" method="POST" class="register-form">
                        @csrf
                        <section class="register-panel" aria-labelledby="customerInfoTitle">
                            <header class="register-panel-header">
                                <h1 id="customerInfoTitle">お客様情報</h1>
                                <p>下記の項目をご入力の上、「次へ」ボタンを押してください。</p>
                            </header>

                            <div class="register-fields register-customer-fields">
                                <fieldset class="register-choice-field">
                                    <legend>お客様区分</legend>
                                    <label><input type="radio" name="customer_type" value="corporate" {{ $customerType === 'corporate' ? 'checked' : '' }} required><span>法人</span></label>
                                    <label><input type="radio" name="customer_type" value="individual" {{ $customerType === 'individual' ? 'checked' : '' }}><span>個人</span></label>
                                    @error('customer_type') <small class="register-error">{{ $message }}</small> @enderror
                                </fieldset>

                                <div class="register-field-group">
                                    <span class="register-group-label">氏名（漢字） <em>※</em></span>
                                    <div class="register-field-row">
                                        <label class="register-field">
                                            <span class="visually-hidden">姓（漢字）</span>
                                            <input type="text" name="last_name" value="{{ old('last_name', $customer['last_name'] ?? '') }}" placeholder="姓（例：山田）" autocomplete="family-name" required>
                                            @error('last_name') <small class="register-error">{{ $message }}</small> @enderror
                                        </label>
                                        <label class="register-field">
                                            <span class="visually-hidden">名（漢字）</span>
                                            <input type="text" name="first_name" value="{{ old('first_name', $customer['first_name'] ?? '') }}" placeholder="名（例：太郎）" autocomplete="given-name" required>
                                            @error('first_name') <small class="register-error">{{ $message }}</small> @enderror
                                        </label>
                                    </div>
                                </div>

                                <div class="register-field-group">
                                    <span class="register-group-label">フリガナ（カタカナ） <em>※</em></span>
                                    <div class="register-field-row">
                                        <label class="register-field">
                                            <span class="visually-hidden">姓（カタカナ）</span>
                                            <input type="text" name="last_name_kana" value="{{ old('last_name_kana', $customer['last_name_kana'] ?? '') }}" placeholder="セイ（例：ヤマダ）" required>
                                            @error('last_name_kana') <small class="register-error">{{ $message }}</small> @enderror
                                        </label>
                                        <label class="register-field">
                                            <span class="visually-hidden">名（カタカナ）</span>
                                            <input type="text" name="first_name_kana" value="{{ old('first_name_kana', $customer['first_name_kana'] ?? '') }}" placeholder="メイ（例：タロウ）" required>
                                            @error('first_name_kana') <small class="register-error">{{ $message }}</small> @enderror
                                        </label>
                                    </div>
                                </div>

                                <div class="register-corporate-fields" data-corporate-fields {{ $customerType === 'corporate' ? '' : 'hidden' }}>
                                    <label class="register-field">
                                        <span>会社名 <em>※</em></span>
                                        <input type="text" name="company_name" value="{{ old('company_name', $customer['company_name'] ?? '') }}" placeholder="例：株式会社タイシルク" {{ $customerType === 'corporate' ? 'required' : 'disabled' }} data-corporate-input>
                                        @error('company_name') <small class="register-error">{{ $message }}</small> @enderror
                                    </label>
                                    <label class="register-field">
                                        <span>会社名（フリガナ） <em>※</em></span>
                                        <input type="text" name="company_name_kana" value="{{ old('company_name_kana', $customer['company_name_kana'] ?? '') }}" placeholder="例：カブシキガイシャタイシルク" {{ $customerType === 'corporate' ? 'required' : 'disabled' }} data-corporate-input>
                                        @error('company_name_kana') <small class="register-error">{{ $message }}</small> @enderror
                                    </label>
                                </div>

                                <label class="register-field">
                                    <span>電話番号 <em>※</em></span>
                                    <input type="tel" name="phone" value="{{ old('phone', $customer['phone'] ?? '') }}" placeholder="090-1234-5678" autocomplete="tel" required>
                                    @error('phone') <small class="register-error">{{ $message }}</small> @enderror
                                </label>

                                <label class="register-field">
                                    <span>登録メールアドレス</span>
                                    <input type="email" value="{{ $email }}" readonly class="register-readonly-input">
                                </label>

                                <div class="register-field-group">
                                    <span class="register-group-label">郵便番号 <em>※</em></span>
                                    <div class="register-postal-row">
                                        <input type="text" name="postal_code_front" value="{{ $postalFront }}" placeholder="123" inputmode="numeric" pattern="\d{3}" maxlength="3" required data-postal-front>
                                        <span class="register-postal-separator" aria-hidden="true">−</span>
                                        <input type="text" name="postal_code_back" value="{{ $postalBack }}" placeholder="4567" inputmode="numeric" pattern="\d{4}" maxlength="4" required data-postal-back>
                                        <input type="hidden" name="postal_code" value="{{ $postalDigits }}" data-postal-code>
                                        <button type="button" class="register-postal-button" data-postal-search>住所検索</button>
                                    </div>
                                    <small class="register-hint">※郵便番号を入力すると住所が自動入力されます。</small>
                                    @error('postal_code') <small class="register-error">{{ $message }}</small> @enderror
                                    @error('postal_code_front') <small class="register-error">{{ $message }}</small> @enderror
                                    @error('postal_code_back') <small class="register-error">{{ $message }}</small> @enderror
                                </div>

                                <label class="register-field">
                                    <span>都道府県 <em>※</em></span>
                                    <input type="text" name="prefecture" value="{{ old('prefecture', $customer['prefecture'] ?? '') }}" placeholder="例：大阪府" required data-geocode-prefecture>
                                    @error('prefecture') <small class="register-error">{{ $message }}</small> @enderror
                                </label>

                                <label class="register-field">
                                    <span>市区町村 <em>※</em></span>
                                    <input type="text" name="city" value="{{ old('city', $customer['city'] ?? '') }}" placeholder="例：大阪市北区" required data-geocode-city>
                                    @error('city') <small class="register-error">{{ $message }}</small> @enderror
                                </label>

                                <label class="register-field">
                                    <span>町名・番地 <em>※</em></span>
                                    <input type="text" name="address" value="{{ old('address', $customer['address'] ?? '') }}" placeholder="例：梅田1丁目6-8" required>
                                    @error('address') <small class="register-error">{{ $message }}</small> @enderror
                                </label>

                                <label class="register-consent register-newsletter">
                                    <input type="checkbox" name="receive_email" value="1" {{ old('receive_email', $customer['receive_email'] ?? false) ? 'checked' : '' }}>
                                    <span>ThaiSilkからのお知らせ・メールマガジンを受け取る</span>
                                </label>
                            </div>

                            <div class="register-button-row">
                                <a href="{{ route('register.step1') }}" class="register-secondary-button">戻る</a>
                                <button type="submit" class="register-primary-button">次へ</button>
                            </div>
                        </section>
                    </form>
                @else
                    <form action="{{ route('register.store') }}" method="POST" class="register-form">
                        @csrf
                        <section class="register-panel" aria-labelledby="confirmTitle">
                            <header class="register-panel-header">
                                <h1 id="confirmTitle">入力内容確認</h1>
                                <p>下記の内容で登録してよろしいでしょうか。</p>
                            </header>

                            <dl class="register-confirm-list">
                                <div><dt>メールアドレス</dt><dd>{{ $email }}</dd></div>
                                <div><dt>氏名（漢字）</dt><dd>{{ $customer['last_name'] ?? '' }} {{ $customer['first_name'] ?? '' }}</dd></div>
                                <div><dt>フリガナ（カタカナ）</dt><dd>{{ $customer['last_name_kana'] ?? '' }} {{ $customer['first_name_kana'] ?? '' }}</dd></div>
                                @if (($customer['customer_type'] ?? '') === 'corporate')
                                    <div><dt>会社名</dt><dd>{{ $customer['company_name'] ?? '' }}</dd></div>
                                    <div><dt>会社名（フリガナ）</dt><dd>{{ $customer['company_name_kana'] ?? '' }}</dd></div>
                                @endif
                                <div><dt>電話番号</dt><dd>{{ $customer['phone'] ?? '' }}</dd></div>
                                <div><dt>郵便番号</dt><dd>{{ $customer['postal_code'] ?? '' }}</dd></div>
                                <div><dt>都道府県</dt><dd>{{ $customer['prefecture'] ?? '' }}</dd></div>
                                <div><dt>市区町村</dt><dd>{{ $customer['city'] ?? '' }}</dd></div>
                                <div><dt>町名・番地</dt><dd>{{ $customer['address'] ?? '' }}</dd></div>
                            </dl>

                            <div class="register-button-row">
                                <a href="{{ route('register.step2') }}" class="register-secondary-button">戻る</a>
                                <button type="submit" class="register-primary-button">登録する</button>
                            </div>
                        </section>
                    </form>
                @endif
            </section>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        async function LoadGeoCode(zipcode = '') {
            if (!zipcode) return null;

            try {
                const response = await fetch(@json(route('register.postal-code')), {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
                    },
                    body: JSON.stringify({ zipcode: zipcode })
                });

                return await response.json();
            } catch (error) {
                console.error('Geocode error:', error);
                return {
                    mainArea: '',
                    subArea: ''
                };
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-register-password-toggle]').forEach(function (button) {
                button.addEventListener('click', function () {
                    const input = button.closest('.register-input-wrap').querySelector('[data-register-password]');
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    button.classList.toggle('is-visible', show);
                    button.setAttribute('aria-label', show ? 'パスワードを隠す' : 'パスワードを表示');
                });
            });

            const postalFront = document.querySelector('[data-postal-front]');
            const postalBack = document.querySelector('[data-postal-back]');
            const postalCode = document.querySelector('[data-postal-code]');
            const postalButton = document.querySelector('[data-postal-search]');
            const prefecture = document.querySelector('[data-geocode-prefecture]');
            const city = document.querySelector('[data-geocode-city]');

            const normalisePostalInput = function (input, maxLength) {
                input.value = input.value.replace(/\D/g, '').slice(0, maxLength);
            };

            const setPostalCode = function () {
                if (!postalFront || !postalBack || !postalCode) return '';

                postalCode.value = postalFront.value.length === 3 && postalBack.value.length === 4
                    ? postalFront.value + '-' + postalBack.value
                    : '';

                return postalFront.value + postalBack.value;
            };

            const searchAddress = async function () {
                const zipcode = setPostalCode();

                if (zipcode.length !== 7) {
                    (postalFront?.value.length < 3 ? postalFront : postalBack)?.focus();
                    return;
                }

                if (!postalButton) return;

                const originalText = postalButton.textContent;
                postalButton.disabled = true;
                postalButton.textContent = '検索中…';

                const data = await LoadGeoCode(zipcode);

                if (data?.mainArea && prefecture) {
                    prefecture.value = data.mainArea;
                    prefecture.dispatchEvent(new Event('change', { bubbles: true }));
                }

                if (data?.subArea && city) {
                    city.value = data.subArea;
                    city.dispatchEvent(new Event('input', { bubbles: true }));
                }

                postalButton.disabled = false;
                postalButton.textContent = originalText;
            };

            postalFront?.addEventListener('input', function () {
                normalisePostalInput(postalFront, 3);
                setPostalCode();
                if (postalFront.value.length === 3) postalBack?.focus();
            });

            postalBack?.addEventListener('input', function () {
                normalisePostalInput(postalBack, 4);
                setPostalCode();
                if (postalFront?.value.length === 3 && postalBack.value.length === 4) searchAddress();
            });

            postalButton?.addEventListener('click', searchAddress);
            postalButton?.closest('form')?.addEventListener('submit', setPostalCode);

            const corporateFields = document.querySelector('[data-corporate-fields]');
            const customerTypeInputs = document.querySelectorAll('[name="customer_type"]');
            const updateCorporateFields = function () {
                if (!corporateFields) return;

                const isCorporate = document.querySelector('[name="customer_type"]:checked')?.value === 'corporate';
                corporateFields.hidden = !isCorporate;
                corporateFields.querySelectorAll('[data-corporate-input]').forEach(function (input) {
                    input.disabled = !isCorporate;
                    input.required = isCorporate;
                });
            };

            customerTypeInputs.forEach(function (input) {
                input.addEventListener('change', updateCorporateFields);
            });
            updateCorporateFields();
        });
    </script>
@endpush
