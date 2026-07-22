@extends('frontend.layouts.app')

@section('title', 'お客様情報の入力 | ThaiSilk')
@section('body-class', 'checkout-info-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">

    <style>
        /* ========================================
                               Checkout Information Page
                            ======================================== */

        body.checkout-info-page {
            margin: 0;
            background-color: #f4f2ed;
            color: #333333;
        }

        body.checkout-info-page main {
            background-color: #f4f2ed;
        }

        /*
                             * Main background area
                             */
        .checkout-info-main {
            position: relative;
            min-height: calc(100vh - 120px);
            padding: 48px 0 90px;
            background-color: #f4f2ed;
            overflow: hidden;
        }

        /*
                             * Page container
                             */
        .checkout-info-main .checkout-choice-container {
            position: relative;
            z-index: 1;
            width: min(100% - 64px, 1200px);
            margin: 0 auto;
        }

        /*
                             * Decorative flower at top right
                             */
        .checkout-info-main .checkout-choice-container::before {
            position: absolute;
            top: -92px;
            right: 25px;
            z-index: -1;
            width: 170px;
            height: 170px;
            content: "";
            background-image:
                url('{{ asset('assets/images/home/ph_flower-lotus-thin.png') }}');
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.16;
            pointer-events: none;
        }

        /*
                             * Layout like reference image:
                             * title on top and form indented below
                             */
        .checkout-layout {
            display: block;
        }

        .page-title {
            width: auto;
            margin: 0 0 28px;
            padding: 0;
            color: #222222;
            font-size: 18px;
            font-weight: 600;
            line-height: 1.6;
            letter-spacing: 0.03em;
        }

        .checkout-form {
            width: calc(100% - 64px);
            max-width: 1080px;
            margin-left: 64px;
        }

        /*
                             * White form panel
                             */
        .checkout-panel {
            width: 100%;
            padding: 48px clamp(55px, 10vw, 150px) 70px;
            box-sizing: border-box;
            background-color: #ffffff;
            border-radius: 4px;
            box-shadow: none;
        }

        .checkout-validation-alert {
            margin: 0 0 28px;
            padding: 18px 22px;
            color: #8f2d24;
            background-color: #fff7f5;
            border: 1px solid #d7a49d;
            border-radius: 4px;
        }

        .checkout-validation-alert>p {
            margin: 0 0 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .checkout-validation-alert ul {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            line-height: 1.8;
        }

        .checkout-panel>h2 {
            margin: 0 0 25px;
            color: #333333;
            font-size: 15px;
            font-weight: 600;
            line-height: 1.6;
        }

        .registered-customer-card {
            margin-bottom: 28px;
            padding: 20px 28px;
            background-color: #ffffff;
            border: 1px solid #e3ddd3;
            border-radius: 4px;
        }

        .registered-customer-list {
            display: grid;
            gap: 6px;
            margin: 0;
        }

        .registered-customer-row {
            display: grid;
            grid-template-columns: 140px minmax(0, 1fr);
            gap: 14px;
            font-size: 13px;
            line-height: 1.65;
        }

        .registered-customer-row dt {
            color: #666666;
        }

        .registered-customer-row dd {
            min-width: 0;
            margin: 0;
            color: #555555;
            overflow-wrap: anywhere;
        }

        @media (max-width: 640px) {
            .registered-customer-card {
                padding: 18px;
            }

            .registered-customer-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }

        /*
                             * Customer type
                             */
        .customer-type-row {
            display: flex;
            align-items: center;
            margin-bottom: 22px;
        }

        .customer-type-label {
            flex-shrink: 0;
            margin-right: 24px;
            color: #444444;
            font-size: 13px;
            font-weight: 500;
        }

        .styled-radio-group {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .styled-radio-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            color: #777777;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
        }

        .styled-radio-label input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .radio-custom {
            position: relative;
            display: inline-block;
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            background-color: #ffffff;
            border: 1px solid #d8d3cb;
            border-radius: 50%;
        }

        .styled-radio-label input:checked+.radio-custom {
            border-color: #918476;
        }

        .styled-radio-label input:checked+.radio-custom::after {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 9px;
            height: 9px;
            content: "";
            background-color: #918476;
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }

        /*
                             * Main input fields
                             */
        .styled-input-group {
            position: relative;
            margin-bottom: 12px;
        }

        .styled-input-group input {
            display: block;
            width: 100%;
            height: 42px;
            padding: 0 62px 0 18px;
            box-sizing: border-box;
            color: #333333;
            font-family: inherit;
            font-size: 13px;
            background-color: #ffffff;
            border: 1px solid #dedbd5;
            border-radius: 4px;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .styled-input-group input::placeholder {
            color: #8a8a8a;
            opacity: 1;
        }

        .styled-input-group input:focus {
            border-color: #aa9985;
            box-shadow: 0 0 0 2px rgba(170, 153, 133, 0.1);
        }

        .required-badge {
            position: absolute;
            top: 50%;
            right: 16px;
            color: #b29a7d;
            font-size: 12px;
            line-height: 1;
            transform: translateY(-50%);
            pointer-events: none;
        }

        /*
                             * Information method dropdown
                             */
        .custom-dropdown {
            position: relative;
            margin-bottom: 16px;
            background-color: #ffffff;
            border: 1px solid #dedbd5;
            border-radius: 4px;
        }

        .custom-dropdown-header {
            display: flex;
            min-height: 42px;
            padding: 0 16px;
            box-sizing: border-box;
            align-items: center;
            justify-content: space-between;
            color: #444444;
            font-size: 13px;
            cursor: pointer;
        }

        .dropdown-icon {
            flex-shrink: 0;
            color: #777777;
            transition: transform 0.25s ease;
        }

        .custom-dropdown.is-open .dropdown-icon {
            transform: rotate(180deg);
        }

        .custom-dropdown-list {
            position: absolute;
            top: calc(100% + 4px);
            right: -1px;
            left: -1px;
            z-index: 20;
            display: none;
            overflow: hidden;
            background-color: #ffffff;
            border: 1px solid #dedbd5;
            border-radius: 4px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        }

        .custom-dropdown.is-open .custom-dropdown-list {
            display: block;
        }

        .custom-dropdown-item {
            padding: 12px 16px;
            color: #444444;
            font-size: 13px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .custom-dropdown-item:hover {
            background-color: #f7f5f1;
        }

        /*
                             * Detailed information
                             */
        .detailed-info-fields {
            margin-top: 28px;
        }

        .checkout-field-standard {
            margin-bottom: 22px;
        }

        .field-label {
            display: block;
            margin-bottom: 8px;
            color: #555555;
            font-size: 13px;
            line-height: 1.5;
        }

        .field-label em {
            margin-left: 3px;
            color: #b29a7d;
            font-style: normal;
        }

        .postal-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
        }

        .postal-input,
        .postal-input-long,
        .standard-input {
            height: 42px;
            padding: 0 14px;
            box-sizing: border-box;
            color: #333333;
            font-family: inherit;
            font-size: 13px;
            background-color: #ffffff;
            border: 1px solid #dedbd5;
            border-radius: 4px;
            outline: none;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .postal-input {
            width: 100px;
        }

        .postal-input-long {
            width: 130px;
        }

        .standard-input {
            width: 100%;
        }

        .postal-input:focus,
        .postal-input-long:focus,
        .standard-input:focus,
        .standard-textarea:focus {
            border-color: #aa9985;
            box-shadow: 0 0 0 2px rgba(170, 153, 133, 0.1);
        }

        .standard-textarea {
            width: 100%;
            padding: 14px 18px;
            box-sizing: border-box;
            color: #333333;
            font-family: inherit;
            font-size: 13px;
            background-color: #ffffff;
            border: 1px solid #dedbd5;
            border-radius: 4px;
            outline: none;
            resize: vertical;
            transition:
                border-color 0.2s ease,
                box-shadow 0.2s ease;
        }

        .standard-input::placeholder,
        .standard-textarea::placeholder {
            color: #aaaaaa;
        }

        .postal-separator {
            color: #555555;
            font-size: 14px;
        }

        .btn-address-search {
            min-height: 40px;
            margin-left: 8px;
            padding: 0 26px;
            color: #ffffff;
            font-family: inherit;
            font-size: 13px;
            background-color: #a99172;
            border: 0;
            border-radius: 22px;
            cursor: pointer;
            transition:
                opacity 0.2s ease,
                transform 0.2s ease;
        }

        .btn-address-search:hover {
            opacity: 0.88;
        }

        .btn-address-search:active {
            transform: translateY(1px);
        }

        .btn-address-search:disabled {
            cursor: wait;
            opacity: 0.6;
        }

        .field-hint {
            margin: 0;
            color: #999999;
            font-size: 12px;
            line-height: 1.6;
        }

        /*
                             * Shipping section
                             */
        .shipping-info-section {
            /* margin-top: 38px; */
            padding: 30px 0;
            /* border-top: 1px dotted #cfcac2; */
            border-bottom: 1px dotted #cfcac2;
        }

        .shipping-info-title {
            margin-bottom: 20px;
            color: #333333;
            font-size: 15px;
            font-weight: 600;
        }

        .styled-checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #444444;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
        }

        .styled-checkbox-label input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .checkbox-custom {
            display: inline-flex;
            width: 20px;
            height: 20px;
            box-sizing: border-box;
            align-items: center;
            justify-content: center;
            background-color: #ae9673;
            border: 1px solid #ae9673;
            border-radius: 3px;
            transition:
                background-color 0.2s ease,
                border-color 0.2s ease;
        }

        .checkbox-custom svg {
            width: 13px;
            height: 13px;
            color: #ffffff;
            opacity: 0;
            transform: scale(0.6);
            transition:
                opacity 0.2s ease,
                transform 0.2s ease;
        }

        .styled-checkbox-label input:checked+.checkbox-custom svg {
            opacity: 1;
            transform: scale(1);
        }

        .styled-checkbox-label input:not(:checked)+.checkbox-custom {
            background-color: #ffffff;
            border-color: #cfcfcf;
        }

        .styled-radio-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: #444444;
            font-size: 13px;
            cursor: pointer;
            user-select: none;
        }

        .styled-radio-label input {
            position: absolute;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }

        .radio-custom {
            display: inline-block;
            width: 20px;
            height: 20px;
            box-sizing: border-box;
            background-color: #ffffff;
            border: 1px solid #cfcfcf;
            border-radius: 50%;
            position: relative;
            transition: border-color 0.2s ease;
        }

        .styled-radio-label input:checked+.radio-custom {
            border-color: #a88b5d;
        }

        .styled-radio-label input:checked+.radio-custom::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 12px;
            height: 12px;
            background-color: #a88b5d;
            border-radius: 50%;
        }

        /*
                             * Submit button
                             */
        .checkout-submit-row {
            margin-top: 40px;
            text-align: center;
        }

        .checkout-submit-button {
            min-width: 220px;
            min-height: 50px;
            padding: 12px 35px;
            color: #ffffff;
            font-family: inherit;
            font-size: 15px;
            background-color: #333333;
            border: 0;
            border-radius: 3px;
            cursor: pointer;
            transition:
                background-color 0.2s ease,
                opacity 0.2s ease;
        }

        .checkout-submit-button:hover {
            background-color: #555555;
        }

        /*
                     * Extra options (Delivery, Publish, Notes, Payment)
                     */
        .publish-options-row {
            display: flex;
            gap: 16px;
        }

        .publish-option-label {
            flex: 1;
            cursor: pointer;
        }

        .publish-option-label input {
            display: none;
        }

        .publish-option-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 60px;
            border-radius: 4px;
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            background-color: #8e8880;
            transition: background-color 0.2s, box-shadow 0.2s;
        }

        .publish-option-label input:checked+.publish-option-btn {
            background-color: #a88b5d;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .payment-method-box {
            border: 1px solid #dedbd5;
            border-radius: 4px;
            padding: 16px 20px;
            background: #fff;
        }

        /*
                             * Tablet
                             */
        @media (max-width: 900px) {
            .checkout-info-main {
                padding: 40px 0 70px;
            }

            .checkout-info-main .checkout-choice-container {
                width: min(100% - 40px, 1200px);
            }

            .checkout-info-main .checkout-choice-container::before {
                top: -75px;
                right: -15px;
                width: 140px;
                height: 140px;
            }

            .page-title {
                margin-bottom: 22px;
                font-size: 17px;
            }

            .checkout-form {
                width: calc(100% - 30px);
                margin-left: 30px;
            }

            .checkout-panel {
                padding: 42px 55px 60px;
            }
        }

        /*
                             * Mobile
                             */
        @media (max-width: 600px) {
            .checkout-info-main {
                padding: 30px 0 50px;
            }

            .checkout-info-main .checkout-choice-container {
                width: min(100% - 28px, 1200px);
            }

            .checkout-info-main .checkout-choice-container::before {
                top: -58px;
                right: -30px;
                width: 115px;
                height: 115px;
                opacity: 0.12;
            }

            .page-title {
                margin-bottom: 18px;
                font-size: 16px;
            }

            .checkout-form {
                width: 100%;
                margin-left: 0;
            }

            .checkout-panel {
                padding: 30px 18px 45px;
                border-radius: 3px;
            }

            .customer-type-row {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
            }

            .customer-type-label {
                margin-right: 0;
            }

            .postal-row {
                flex-wrap: wrap;
            }

            .postal-input {
                width: calc(35% - 10px);
            }

            .postal-input-long {
                width: calc(55% - 10px);
            }

            .btn-address-search {
                width: 100%;
                margin: 4px 0 0;
            }

            .checkout-submit-button {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
@endpush

@section('content')
    <header class="checkout-choice-header">
        <div class="checkout-choice-container checkout-choice-header-inner">
            <h1>ご注文・見積もり手続きの進め方</h1>

            <ol class="checkout-choice-progress" aria-label="ご注文手順">
                <li>カート</li>
                <li class="is-current">情報入力</li>
                <li>内容確認</li>
                <li>ご注文完了</li>
            </ol>
        </div>
    </header>

    <section class="checkout-info-main">
        <div class="checkout-choice-container checkout-layout">

            <h1 class="page-title">お客様情報の入力</h1>

            <form action="{{ route('checkout.confirmation') }}" method="POST" class="checkout-form">
                @csrf

                <div class="checkout-panel">
                    @if ($errors->any())
                        <div class="checkout-validation-alert" role="alert" tabindex="-1">
                            <p>入力内容をご確認ください。</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <h2>お客様情報</h2>

                    @if ($showRegisteredCustomerCard)
                        <div class="registered-customer-card">
                            <dl class="registered-customer-list">
                                <div class="registered-customer-row">
                                    <dt>E-mail：</dt>
                                    <dd>{{ $registeredCustomer['email'] ?: '-' }}</dd>
                                </div>
                                <div class="registered-customer-row">
                                    <dt>お名前：</dt>
                                    <dd>{{ $registeredCustomer['name'] ?: '-' }}</dd>
                                </div>
                                <div class="registered-customer-row">
                                    <dt>お名前（フリガナ）：</dt>
                                    <dd>{{ $registeredCustomer['name_kana'] ?: '-' }}</dd>
                                </div>
                                @if (!empty($registeredCustomer['company_name']))
                                    <div class="registered-customer-row">
                                        <dt>会社名：</dt>
                                        <dd>{{ $registeredCustomer['company_name'] }}</dd>
                                    </div>
                                @endif
                                <div class="registered-customer-row">
                                    <dt>TEL：</dt>
                                    <dd>{{ $registeredCustomer['phone'] ?: '-' }}</dd>
                                </div>
                                <div class="registered-customer-row">
                                    <dt>郵便番号：</dt>
                                    <dd>{{ filled($registeredCustomer['postal_code_front']) && filled($registeredCustomer['postal_code_back']) ? $registeredCustomer['postal_code_front'].'-'.$registeredCustomer['postal_code_back'] : '-' }}</dd>
                                </div>
                                <div class="registered-customer-row">
                                    <dt>都道府県：</dt>
                                    <dd>{{ $registeredCustomer['prefecture'] ?: '-' }}</dd>
                                </div>
                                <div class="registered-customer-row">
                                    <dt>市区町村：</dt>
                                    <dd>{{ $registeredCustomer['city'] ?: '-' }}</dd>
                                </div>
                                <div class="registered-customer-row">
                                    <dt>町名・番地：</dt>
                                    <dd>{{ $registeredCustomer['address'] ?: '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        @foreach (['customer_type', 'name', 'name_kana', 'company_name', 'company_name_kana', 'email', 'phone', 'postal_code_front', 'postal_code_back', 'prefecture', 'city', 'address'] as $field)
                            <input type="hidden" name="{{ $field }}"
                                value="{{ old($field, $registeredCustomer[$field] ?? '') }}">
                        @endforeach
                        <input type="hidden" name="postal_code" data-postal-code
                            value="{{ filled($registeredCustomer['postal_code_front']) && filled($registeredCustomer['postal_code_back']) ? $registeredCustomer['postal_code_front'].'-'.$registeredCustomer['postal_code_back'] : '-' }}">
                        <input type="hidden" name="info_method" id="infoMethodInput" value="詳細情報を全て入力する">
                    @else
                        <div class="customer-type-row">
                            <span class="customer-type-label">
                                お客様区分
                            </span>

                            <div class="styled-radio-group">
                                <label class="styled-radio-label">
                                    <input type="radio" name="customer_type" value="corporate"
                                        @checked(old('customer_type', $registeredCustomer['customer_type'] ?? 'individual') === 'corporate')>

                                    <span class="radio-custom"></span>
                                    法人
                                </label>

                                <label class="styled-radio-label">
                                    <input type="radio" name="customer_type" value="individual"
                                        @checked(old('customer_type', $registeredCustomer['customer_type'] ?? 'individual') === 'individual')>

                                    <span class="radio-custom"></span>
                                    個人
                                </label>
                            </div>
                        </div>

                        <div class="styled-input-group">
                            <input type="text" name="name" placeholder="お名前"
                                value="{{ old('name', $registeredCustomer['name'] ?? '') }}" required>

                            <span class="required-badge">必須</span>
                        </div>

                        <div class="styled-input-group">
                            <input type="text" name="name_kana" placeholder="フリガナ"
                                value="{{ old('name_kana', $registeredCustomer['name_kana'] ?? '') }}" required>

                            <span class="required-badge">必須</span>
                        </div>

                        <div class="styled-input-group">
                            <input type="email" name="email" placeholder="メールアドレス"
                                value="{{ old('email', $registeredCustomer['email'] ?? '') }}" required>

                            <span class="required-badge">必須</span>
                        </div>

                        <div class="styled-input-group">
                            <input type="tel" name="phone" placeholder="電話番号(ハイフン無し)"
                                value="{{ old('phone', $registeredCustomer['phone'] ?? '') }}" required>

                            <span class="required-badge">必須</span>
                        </div>

                        <div class="custom-dropdown" id="infoMethodDropdown">
                            <div class="custom-dropdown-header" role="button" tabindex="0" aria-expanded="false"
                                onclick="toggleInfoMethodDropdown(this)" onkeydown="handleDropdownKeydown(event, this)">
                                <span class="selected-text" id="infoMethodSelected">
                                    詳細情報を全て入力する
                                </span>

                                <svg class="dropdown-icon" viewBox="0 0 24 24" width="20" height="20" aria-hidden="true">
                                    <path d="M16 14l-4-4-4 4" fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>

                            <div class="custom-dropdown-list">
                                <div class="custom-dropdown-item" onclick="selectDropdownOption('詳細情報を全て入力する')">
                                    詳細情報を全て入力する
                                </div>

                                <div class="custom-dropdown-item" onclick="selectDropdownOption('入力省略(営業より連絡します)')">
                                    入力省略(営業より連絡します)
                                </div>

                                <div class="custom-dropdown-item" onclick="selectDropdownOption('メールの署名等をコピーする')">
                                    メールの署名等をコピーする
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="info_method" id="infoMethodInput"
                            value="{{ old('info_method', '詳細情報を全て入力する') }}">

                        <div id="signatureCopyFields" style="display: none; margin-top: 20px;">
                            <textarea name="signature_text" class="standard-textarea" rows="6">{{ old('signature_text') }}</textarea>
                            <p class="field-hint" style="margin-top: 8px;">
                                会社名やご住所の入ったメールの署名等をコピーする<br>
                                (後程営業よりご連絡致しますので、不完全でも構いません。)
                            </p>
                        </div>

                        <div id="detailedInfoFields" class="detailed-info-fields">
                            <div id="companyNameFields" style="display: none;">
                                <div class="styled-input-group">
                                    <input type="text" name="company_name" placeholder="会社名"
                                        value="{{ old('company_name', $registeredCustomer['company_name'] ?? '') }}" disabled>

                                    <span class="required-badge">必須</span>
                                </div>

                                <div class="styled-input-group">
                                    <input type="text" name="company_name_kana" placeholder="会社名（フリガナ）"
                                        value="{{ old('company_name_kana', $registeredCustomer['company_name_kana'] ?? '') }}" disabled>

                                    <span class="required-badge">必須</span>
                                </div>
                            </div>
                            <div class="checkout-field-standard">
                                <span class="field-label">
                                    郵便番号
                                    <em>※</em>
                                </span>

                                <div class="postal-row">
                                    <input type="text" name="postal_code_front" maxlength="3" inputmode="numeric"
                                        class="postal-input" data-postal-front
                                        value="{{ old('postal_code_front', $registeredCustomer['postal_code_front'] ?? '') }}">

                                    <span class="postal-separator">-</span>

                                    <input type="text" name="postal_code_back" maxlength="4" inputmode="numeric"
                                        class="postal-input-long" data-postal-back
                                        value="{{ old('postal_code_back', $registeredCustomer['postal_code_back'] ?? '') }}">

                                    <input type="hidden" name="postal_code" data-postal-code>

                                    <button type="button" class="btn-address-search" data-postal-search>
                                        住所検索
                                    </button>
                                </div>

                                <p class="field-hint">
                                    ※ 郵便番号を入力すると住所が自動入力されます。
                                </p>
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="prefecture">
                                    都道府県
                                    <em>※</em>
                                </label>

                                <input type="text" id="prefecture" name="prefecture" placeholder="選択してください"
                                    class="standard-input" data-geocode-prefecture
                                    value="{{ old('prefecture', $registeredCustomer['prefecture'] ?? '') }}">
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="city">
                                    市区町村
                                    <em>※</em>
                                </label>

                                <input type="text" id="city" name="city" placeholder="(例：大阪市北区)"
                                    class="standard-input" data-geocode-city
                                    value="{{ old('city', $registeredCustomer['city'] ?? '') }}">
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="address">
                                    町名・番地
                                    <em>※</em>
                                </label>

                                <input type="text" id="address" name="address" placeholder="(例：西梅田1丁目6-8)"
                                    class="standard-input"
                                    value="{{ old('address', $registeredCustomer['address'] ?? '') }}">
                            </div>
                        </div>
                    @endif

                    <div id="shippingDestinationSection" class="shipping-info-section">
                        <div class="shipping-info-title">
                            お届け先情報
                        </div>

                        <label class="styled-checkbox-label">
                            <input type="checkbox" name="same_as_customer" value="1" checked>

                            <span class="checkbox-custom">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </span>

                            お客様情報と同じ住所を使用する
                        </label>

                        <div id="shippingInfoFields" style="display: none; margin-top: 24px;">
                            <div class="styled-input-group">
                                <input type="text" name="shipping_name" placeholder="お名前">

                                <span class="required-badge">必須</span>
                            </div>

                            <div class="styled-input-group">
                                <input type="text" name="shipping_name_kana" placeholder="フリガナ">

                                <span class="required-badge">必須</span>
                            </div>

                            <div class="checkout-field-standard">
                                <span class="field-label">
                                    郵便番号
                                    <em>※</em>
                                </span>

                                <div class="postal-row">
                                    <input type="text" name="shipping_postal_code_front" maxlength="3"
                                        inputmode="numeric" class="postal-input" data-shipping-postal-front>

                                    <span class="postal-separator">-</span>

                                    <input type="text" name="shipping_postal_code_back" maxlength="4"
                                        inputmode="numeric" class="postal-input-long" data-shipping-postal-back>

                                    <input type="hidden" name="shipping_postal_code" data-shipping-postal-code>

                                    <button type="button" class="btn-address-search" data-shipping-postal-search>
                                        住所検索
                                    </button>
                                </div>

                                <p class="field-hint">
                                    ※ 郵便番号を入力すると住所が自動入力されます。
                                </p>
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="shipping_prefecture">
                                    都道府県
                                    <em>※</em>
                                </label>

                                <input type="text" id="shipping_prefecture" name="shipping_prefecture"
                                    placeholder="選択してください" class="standard-input" data-shipping-geocode-prefecture>
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="shipping_city">
                                    市区町村
                                    <em>※</em>
                                </label>

                                <input type="text" id="shipping_city" name="shipping_city" placeholder="(例：大阪市北区)"
                                    class="standard-input" data-shipping-geocode-city>
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="shipping_address">
                                    町名・番地
                                    <em>※</em>
                                </label>

                                <input type="text" id="shipping_address" name="shipping_address"
                                    placeholder="(例：西梅田1丁目6-8)" class="standard-input">
                            </div>
                        </div>
                    </div>

                    <div id="billingAddressSection" class="shipping-info-section">
                        <div class="shipping-info-title">
                            請求先情報
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <label class="styled-radio-label">
                                <input type="radio" name="billing_address_type" value="same_as_customer" checked>
                                <span class="radio-custom"></span>
                                お客様情報と同じ住所を使用する
                            </label>

                            <label class="styled-radio-label">
                                <input type="radio" name="billing_address_type" value="same_as_shipping">
                                <span class="radio-custom"></span>
                                お届け先情報と同じ住所を使用する
                            </label>

                            <label class="styled-radio-label">
                                <input type="radio" name="billing_address_type" value="different">
                                <span class="radio-custom"></span>
                                別の請求先住所を使用する
                            </label>
                        </div>

                        <div id="billingInfoFields" style="display: none; margin-top: 24px;">
                            <div class="styled-input-group">
                                <input type="text" name="billing_name" placeholder="お名前">
                                <span class="required-badge">必須</span>
                            </div>

                            <div class="styled-input-group">
                                <input type="text" name="billing_name_kana" placeholder="フリガナ">
                                <span class="required-badge">必須</span>
                            </div>

                            <div class="checkout-field-standard">
                                <span class="field-label">
                                    郵便番号
                                    <em>※</em>
                                </span>

                                <div class="postal-row">
                                    <input type="text" name="billing_postal_code_front" maxlength="3"
                                        inputmode="numeric" class="postal-input" data-billing-postal-front>

                                    <span class="postal-separator">-</span>

                                    <input type="text" name="billing_postal_code_back" maxlength="4"
                                        inputmode="numeric" class="postal-input-long" data-billing-postal-back>

                                    <input type="hidden" name="billing_postal_code" data-billing-postal-code>

                                    <button type="button" class="btn-address-search" data-billing-postal-search>
                                        住所検索
                                    </button>
                                </div>

                                <p class="field-hint">
                                    ※ 郵便番号を入力すると住所が自動入力されます。
                                </p>
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="billing_prefecture">
                                    都道府県
                                    <em>※</em>
                                </label>

                                <input type="text" id="billing_prefecture" name="billing_prefecture"
                                    placeholder="選択してください" class="standard-input" data-billing-geocode-prefecture>
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="billing_city">
                                    市区町村
                                    <em>※</em>
                                </label>

                                <input type="text" id="billing_city" name="billing_city" placeholder="(例：大阪市北区)"
                                    class="standard-input" data-billing-geocode-city>
                            </div>

                            <div class="checkout-field-standard">
                                <label class="field-label" for="billing_address">
                                    町名・番地
                                    <em>※</em>
                                </label>

                                <input type="text" id="billing_address" name="billing_address"
                                    placeholder="(例：西梅田1丁目6-8)" class="standard-input">
                            </div>
                        </div>
                    </div>

                    <div class="shipping-info-section">
                        <div class="shipping-info-title">
                            配送オプション
                        </div>
                        <label class="styled-checkbox-label">
                            <input type="checkbox" name="delivery_option" value="1">
                            <span class="checkbox-custom">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </span>
                            お届け先に届く荷物の差出人を「購入者情報（会社名）」にする
                        </label>
                        <p class="field-hint" style="margin-top: 12px; margin-bottom: 0;">
                            ※チェックを入れた場合、金額の記載された納品書等は同梱されず、当ブランド名やロゴも外装・伝票に一切記載されません。
                        </p>
                    </div>

                    <div class="shipping-info-section">
                        <div class="shipping-info-title">
                            製品の本WEBサイトへの掲載
                        </div>
                        <div class="publish-options-row">
                            <label class="publish-option-label">
                                <input type="radio" name="publish_website" value="yes" checked>
                                <div class="publish-option-btn">
                                    掲載を希望する
                                </div>
                            </label>
                            <label class="publish-option-label">
                                <input type="radio" name="publish_website" value="no">
                                <div class="publish-option-btn">
                                    掲載を希望しない
                                </div>
                            </label>
                        </div>
                        <p class="field-hint"
                            style="margin-top: 16px; margin-bottom: 0; color: #333333; font-size: 13px;">
                            ご希望があれば会社やお店の宣伝やリンクもさせて頂きます。
                        </p>
                    </div>

                    <div class="shipping-info-section" style="border-bottom: none; padding-bottom: 0;">
                        <div class="shipping-info-title">
                            ご連絡事項
                        </div>
                        <textarea name="notes" class="standard-textarea" rows="4"
                            placeholder="製作実績掲載ご許可のお客様で、ハンドルネームでの掲載をご希望のお客様はこちらに記載してください。"></textarea>
                    </div>



                    <div class="shipping-info-section" style="border-top: none; padding-top: 20px;">
                        <div class="shipping-info-title">
                            お支払い情報
                        </div>
                        <div class="payment-method-box">
                            <label class="styled-radio-label" style="display: flex; width: 100%;">
                                <input type="radio" name="payment_method" value="bank_transfer" checked>
                                <span class="radio-custom"></span>
                                <span style="margin-left: 5px; font-size: 14px; color: #666;">銀行振込</span>
                            </label>
                            <label class="styled-radio-label" style="display: flex; width: 100%; margin-top: 16px; cursor: not-allowed; opacity: 0.55;">
                                <input type="radio" name="payment_method" value="paypal_credit_card" disabled>
                                <span class="radio-custom"></span>
                                <span style="margin-left: 5px; font-size: 14px; color: #666;">PayPalクレジットカード決済（メンテナンス中）</span>
                            </label>
                        </div>

                        <label class="styled-checkbox-label" style="margin-top: 24px;">
                            <input type="checkbox" name="newsletter" value="1">
                            <span class="checkbox-custom">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </span>
                            <span style="font-size: 14px; font-weight: 500; color: #333;">製品やキャンペーンの情報（メルマガ）を受け取る</span>
                        </label>
                    </div>

                    <div class="checkout-submit-row">
                        <button type="submit" class="checkout-submit-button">
                            次へ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        function toggleInfoMethodDropdown(header) {
            const dropdown = header.closest('.custom-dropdown');
            const isOpen = dropdown.classList.toggle('is-open');

            header.setAttribute(
                'aria-expanded',
                isOpen ? 'true' : 'false'
            );
        }

        function handleDropdownKeydown(event, header) {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleInfoMethodDropdown(header);
            }

            if (event.key === 'Escape') {
                const dropdown = header.closest('.custom-dropdown');

                dropdown.classList.remove('is-open');
                header.setAttribute('aria-expanded', 'false');
            }
        }

        function selectDropdownOption(value) {
            const selectedText = document.getElementById(
                'infoMethodSelected'
            );

            const hiddenInput = document.getElementById(
                'infoMethodInput'
            );

            const dropdown = document.getElementById(
                'infoMethodDropdown'
            );

            const dropdownHeader = dropdown?.querySelector(
                '.custom-dropdown-header'
            );

            const detailedFields = document.getElementById(
                'detailedInfoFields'
            );

            const signatureFields = document.getElementById(
                'signatureCopyFields'
            );


            const shippingDestinationSection = document.getElementById(
                'shippingDestinationSection'
            );

            const billingAddressSection = document.getElementById(
                'billingAddressSection'
            );

            if (selectedText) {
                selectedText.textContent = value;
            }

            if (hiddenInput) {
                hiddenInput.value = value;
            }

            dropdown?.classList.remove('is-open');
            dropdownHeader?.setAttribute('aria-expanded', 'false');

            if (detailedFields) {
                detailedFields.style.display =
                    value === '詳細情報を全て入力する' ?
                    'block' :
                    'none';
            }

            if (signatureFields) {
                signatureFields.style.display =
                    value === 'メールの署名等をコピーする' ?
                    'block' :
                    'none';
            }


            [shippingDestinationSection, billingAddressSection].forEach((section) => {
                if (section) {
                    section.style.display =
                        value === '入力省略(営業より連絡します)' ?
                        'none' :
                        'block';
                }
            });

            document.dispatchEvent(new CustomEvent('checkout:method-changed'));
        }

        async function LoadGeoCode(zipcode = '') {
            if (!zipcode) {
                return null;
            }

            try {
                const response = await fetch(
                    @json(route('register.postal-code')), {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]'
                            )?.content ?? ''
                        },
                        body: JSON.stringify({
                            zipcode: zipcode
                        })
                    }
                );

                if (!response.ok) {
                    throw new Error(
                        `Postal code request failed: ${response.status}`
                    );
                }

                return await response.json();
            } catch (error) {
                console.error('Geocode error:', error);

                return {
                    mainArea: '',
                    subArea: ''
                };
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const oldCheckoutInput = @json(session()->getOldInput());
            const hasOldCheckoutInput = Object.keys(oldCheckoutInput).length > 0;

            if (hasOldCheckoutInput) {
                Object.entries(oldCheckoutInput).forEach(([name, value]) => {
                    const fields = document.querySelectorAll(`[name="${name}"]`);

                    fields.forEach(field => {
                        if (field.type === 'radio') {
                            field.checked = field.value === String(value);
                        } else if (field.type !== 'checkbox') {
                            field.value = value ?? '';
                        }
                    });
                });

                ['same_as_customer', 'delivery_option', 'newsletter'].forEach(name => {
                    const checkbox = document.querySelector(`input[name="${name}"]`);

                    if (checkbox) {
                        checkbox.checked = Object.prototype.hasOwnProperty.call(oldCheckoutInput, name) &&
                            String(oldCheckoutInput[name]) === '1';
                    }
                });
            }

            const sameAsCustomerCheckbox = document.querySelector(
                'input[name="same_as_customer"]'
            );

            const customerTypeRadios = document.querySelectorAll(
                'input[name="customer_type"]'
            );

            const companyNameFields = document.getElementById(
                'companyNameFields'
            );

            const shippingInfoFields = document.getElementById(
                'shippingInfoFields'
            );

            const billingAddressRadios = document.querySelectorAll(
                'input[name="billing_address_type"]'
            );

            const billingInfoFields = document.getElementById(
                'billingInfoFields'
            );

            const infoMethodInput = document.getElementById('infoMethodInput');

            const setFieldsRequired = function(names, isRequired) {
                names.forEach(name => {
                    const field = document.querySelector(`[name="${name}"]`);

                    if (field) {
                        field.required = isRequired;
                    }
                });
            };

            const syncConditionalFields = function() {
                const infoMethod = infoMethodInput?.value ?? '';
                const isDetailed = infoMethod === '詳細情報を全て入力する';
                const addressesEnabled = infoMethod !== '入力省略(営業より連絡します)';
                const isCorporate = document.querySelector(
                    'input[name="customer_type"]:checked'
                )?.value === 'corporate';
                const companyRequired = isDetailed && isCorporate;
                const shippingRequired = addressesEnabled && !sameAsCustomerCheckbox?.checked;
                const billingRequired = addressesEnabled && document.querySelector(
                    'input[name="billing_address_type"]:checked'
                )?.value === 'different';

                if (companyNameFields) {
                    companyNameFields.style.display = isCorporate ? 'block' : 'none';

                    companyNameFields.querySelectorAll('input').forEach(input => {
                        input.disabled = !companyRequired;
                        input.required = companyRequired;
                    });
                }

                if (shippingInfoFields) {
                    shippingInfoFields.style.display = shippingRequired ? 'block' : 'none';
                }

                if (billingInfoFields) {
                    billingInfoFields.style.display = billingRequired ? 'block' : 'none';
                }

                setFieldsRequired([
                    'postal_code_front',
                    'postal_code_back',
                    'prefecture',
                    'city',
                    'address',
                ], isDetailed);
                setFieldsRequired(['signature_text'], infoMethod === 'メールの署名等をコピーする');
                setFieldsRequired([
                    'shipping_name',
                    'shipping_name_kana',
                    'shipping_postal_code_front',
                    'shipping_postal_code_back',
                    'shipping_prefecture',
                    'shipping_city',
                    'shipping_address',
                ], shippingRequired);
                setFieldsRequired([
                    'billing_name',
                    'billing_name_kana',
                    'billing_postal_code_front',
                    'billing_postal_code_back',
                    'billing_prefecture',
                    'billing_city',
                    'billing_address',
                ], billingRequired);
            };

            customerTypeRadios.forEach(radio => {
                radio.addEventListener('change', syncConditionalFields);
            });

            if (sameAsCustomerCheckbox && shippingInfoFields) {
                sameAsCustomerCheckbox.addEventListener(
                    'change',
                    syncConditionalFields
                );
            }

            if (billingAddressRadios.length > 0 && billingInfoFields) {
                billingAddressRadios.forEach(radio => {
                    radio.addEventListener('change', syncConditionalFields);
                });
            }

            document.addEventListener('checkout:method-changed', syncConditionalFields);
            selectDropdownOption(infoMethodInput?.value || '詳細情報を全て入力する');
            syncConditionalFields();

            document.querySelector('.checkout-validation-alert')?.focus();
            const setupAddressSearch = function(prefix = '') {
                const getAttributeSelector = function(name) {
                    return prefix ?
                        `[data-${prefix}-${name}]` :
                        `[data-${name}]`;
                };

                const postalFront = document.querySelector(
                    getAttributeSelector('postal-front')
                );

                const postalBack = document.querySelector(
                    getAttributeSelector('postal-back')
                );

                const postalCode = document.querySelector(
                    getAttributeSelector('postal-code')
                );

                const postalButton = document.querySelector(
                    getAttributeSelector('postal-search')
                );

                const prefecture = document.querySelector(
                    getAttributeSelector('geocode-prefecture')
                );

                const city = document.querySelector(
                    getAttributeSelector('geocode-city')
                );

                if (
                    !postalFront ||
                    !postalBack ||
                    !postalCode ||
                    !postalButton
                ) {
                    return;
                }

                const normalisePostalInput = function(
                    input,
                    maxLength
                ) {
                    input.value = input.value
                        .replace(/\D/g, '')
                        .slice(0, maxLength);
                };

                const setPostalCode = function() {
                    const frontValue = postalFront.value;
                    const backValue = postalBack.value;

                    postalCode.value =
                        frontValue.length === 3 &&
                        backValue.length === 4 ?
                        `${frontValue}-${backValue}` :
                        '';

                    return frontValue + backValue;
                };

                const searchAddress = async function() {
                    const zipcode = setPostalCode();

                    if (zipcode.length !== 7) {
                        if (postalFront.value.length < 3) {
                            postalFront.focus();
                        } else {
                            postalBack.focus();
                        }

                        return;
                    }

                    const originalText = postalButton.textContent;

                    postalButton.disabled = true;
                    postalButton.textContent = '検索中…';

                    try {
                        const data = await LoadGeoCode(zipcode);

                        if (data?.mainArea && prefecture) {
                            prefecture.value = data.mainArea;

                            prefecture.dispatchEvent(
                                new Event('change', {
                                    bubbles: true
                                })
                            );
                        }

                        if (data?.subArea && city) {
                            city.value = data.subArea;

                            city.dispatchEvent(
                                new Event('input', {
                                    bubbles: true
                                })
                            );
                        }
                    } finally {
                        postalButton.disabled = false;
                        postalButton.textContent = originalText;
                    }
                };

                postalFront.addEventListener(
                    'input',
                    function() {
                        normalisePostalInput(postalFront, 3);
                        setPostalCode();

                        if (postalFront.value.length === 3) {
                            postalBack.focus();
                        }
                    }
                );

                postalBack.addEventListener(
                    'input',
                    function() {
                        normalisePostalInput(postalBack, 4);
                        setPostalCode();

                        if (
                            postalFront.value.length === 3 &&
                            postalBack.value.length === 4
                        ) {
                            searchAddress();
                        }
                    }
                );

                postalButton.addEventListener(
                    'click',
                    searchAddress
                );

                postalButton
                    .closest('form')
                    ?.addEventListener('submit', setPostalCode);
            };

            setupAddressSearch();
            setupAddressSearch('shipping');
            setupAddressSearch('billing');

            /*
             * Close dropdown when clicking outside
             */
            document.addEventListener('click', function(event) {
                const dropdown = document.getElementById(
                    'infoMethodDropdown'
                );

                if (
                    dropdown &&
                    !dropdown.contains(event.target)
                ) {
                    dropdown.classList.remove('is-open');

                    dropdown
                        .querySelector('.custom-dropdown-header')
                        ?.setAttribute('aria-expanded', 'false');
                }
            });
        });
    </script>
@endpush
