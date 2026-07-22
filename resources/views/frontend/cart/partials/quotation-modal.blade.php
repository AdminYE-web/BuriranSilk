@php
    $quotationErrors = $errors->getBag('quotation');
    $user = auth()->user();
@endphp

<div
    class="quotation-modal"
    data-quotation-modal
    data-open-on-load="{{ $quotationErrors->any() ? '1' : '0' }}"
    aria-hidden="true"
>
    <section
        class="quotation-modal-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="quotationModalTitle"
        tabindex="-1"
    >
        <button
            type="button"
            class="quotation-modal-close"
            data-quotation-close
            aria-label="{{ $isEnglish ? 'Close' : '閉じる' }}"
        >×</button>

        <h2 id="quotationModalTitle">
            {{ $isEnglish ? 'Customer information' : '御社情報入力' }}
        </h2>

        <p class="quotation-modal-description">
            {{ $isEnglish
                ? 'Please enter the customer information to appear on the quotation.'
                : '見積書に記載するお客様情報をご入力ください。'
            }}
        </p>

        @if ($quotationErrors->any())
            <div class="quotation-modal-errors" role="alert">
                @foreach ($quotationErrors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form
            action="{{ route('cart.quotation.download') }}"
            method="POST"
            class="quotation-customer-form"
        >
            @csrf

            <div class="quotation-name-grid">
                <label>
                    <span>
                        {{ $isEnglish ? 'Last name' : 'お名前（姓）' }}
                        <b>*</b>
                    </span>
                    <input
                        type="text"
                        name="last_name"
                        value="{{ old('last_name', $user?->last_name) }}"
                        maxlength="100"
                        required
                    >
                </label>

                <label>
                    <span>
                        {{ $isEnglish ? 'First name' : 'お名前（名）' }}
                        <b>*</b>
                    </span>
                    <input
                        type="text"
                        name="first_name"
                        value="{{ old('first_name', $user?->first_name) }}"
                        maxlength="100"
                        required
                    >
                </label>
            </div>

            <label>
                <span>{{ $isEnglish ? 'Company name' : '法人名' }}</span>
                <input
                    type="text"
                    name="company_name"
                    value="{{ old('company_name') }}"
                    maxlength="150"
                >
            </label>

            <div class="quotation-postal-row">
                <label>
                    <span>
                        {{ $isEnglish ? 'Postal code' : '郵便番号' }}
                        <b>*</b>
                    </span>
                    <input
                        type="text"
                        name="postal_code"
                        value="{{ old('postal_code') }}"
                        placeholder="123-4567"
                        inputmode="numeric"
                        maxlength="8"
                        required
                        data-quotation-postal-code
                    >
                </label>

                <button
                    type="button"
                    class="quotation-address-search"
                    data-quotation-address-search
                >
                    {{ $isEnglish ? 'Find address' : '住所に変換' }}
                </button>
            </div>

            <p
                class="quotation-postal-message"
                data-quotation-postal-message
                hidden
            ></p>

            <label>
                <span>
                    {{ $isEnglish ? 'Prefecture' : '都道府県' }}
                    <b>*</b>
                </span>
                <input
                    type="text"
                    name="prefecture"
                    value="{{ old('prefecture') }}"
                    maxlength="100"
                    required
                    data-quotation-prefecture
                >
            </label>

            <label>
                <span>
                    {{ $isEnglish ? 'Address' : '以降の住所' }}
                    <b>*</b>
                </span>
                <input
                    type="text"
                    name="address"
                    value="{{ old('address') }}"
                    maxlength="255"
                    required
                    data-quotation-address
                >
            </label>

            <label>
                <span>
                    {{ $isEnglish
                        ? 'Building, room number'
                        : '番地、建物名、部屋番号'
                    }}
                </span>
                <input
                    type="text"
                    name="building"
                    value="{{ old('building') }}"
                    maxlength="255"
                >
            </label>

            <label>
                <span>
                    {{ $isEnglish
                        ? 'Telephone number'
                        : 'TEL（ハイフンなし）'
                    }}
                    <b>*</b>
                </span>
                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone', $user?->phone) }}"
                    maxlength="30"
                    inputmode="tel"
                    required
                >
            </label>

            <label>
                <span>{{ $isEnglish ? 'Memo' : 'メモ' }}</span>
                <textarea
                    name="memo"
                    rows="3"
                    maxlength="1000"
                >{{ old('memo') }}</textarea>
            </label>

            <button type="submit" class="quotation-submit">
                {{ $isEnglish
                    ? 'Create and download PDF'
                    : '御社情報確定（PDF出力）'
                }}
            </button>

            <p class="quotation-optional-note">
                {{ $isEnglish
                    ? 'Company name is optional.'
                    : '※法人名の入力は任意です。'
                }}
            </p>
        </form>
    </section>
</div>