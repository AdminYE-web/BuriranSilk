@extends('frontend.layouts.app')

@section('title', ($type === 'shipping' ? 'お届け先' : '請求先') . '住所の登録')

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

    .address-card {
        background: #fff;
        border-radius: 8px;
        padding: 42px 54px;
        min-height: 420px;
    }

    .address-card h1 {
        font-size: 34px;
        font-weight: 700;
        margin-bottom: 18px;
    }

    .address-divider {
        border-top: 1px solid #e5e5e5;
        margin-bottom: 24px;
        max-width: 560px;
    }

    .address-form {
        max-width: 780px;
    }

    .address-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .address-group {
        margin-bottom: 20px;
    }

    .address-group.full {
        grid-column: 1 / -1;
    }

    .address-group label {
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
        color: #111;
    }

    .address-group label span {
        color: #ff0000;
    }

    .address-group input,
    .address-group select {
        width: 100%;
        height: 38px;
        border: 1px solid #cfd4dc;
        border-radius: 4px;
        padding: 0 14px;
        font-size: 14px;
        background: #fff;
    }

    .address-3-col {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 14px;
    }

    .address-checkbox {
        display: flex;
        gap: 8px;
        align-items: flex-start;
        margin: 14px 0 20px;
        font-size: 14px;
    }

    .address-checkbox input {
        margin-top: 3px;
    }

    .address-actions-row {
        display: flex;
        align-items: center;
        gap: 34px;
    }

    .save-btn {
        min-width: 106px;
        height: 29px;
        border: 0;
        border-radius: 4px;
        background: #2f70c9;
        color: #fff;
        font-weight: 700;
    }

    .cancel-link {
        color: #111;
        text-decoration: none;
        font-weight: 700;
    }

    .error-text {
        color: #dc2626;
        font-size: 12px;
        margin-top: 5px;
    }

    @media (max-width: 900px) {
        .account-layout {
            grid-template-columns: 1fr;
        }

        .address-card {
            padding: 28px 22px;
        }

        .address-form-grid,
        .address-3-col {
            grid-template-columns: 1fr;
        }
    }
    .zip-code-row {
    display: flex;
    gap: 10px;
}

.zip-code-row input {
    flex: 1;
}

.search-address-btn {
    height: 38px;
    padding: 0 18px;
    border: 0;
    border-radius: 4px;
    background: #000;
    color: #fff;
    font-weight: 600;
    white-space: nowrap;
}
</style>
@endsection

@section('content')
<div class="account-page">
    <div class="container">
        <div class="account-layout">

            @include('frontend.account.partials.sidebar', ['user' => $user])

            <main class="address-card">
                <h1>{{ $type === 'shipping' ? 'お届け先' : '請求先' }}住所の登録</h1>
                <div class="address-divider"></div>

                <form action="{{ route('account.addresses.store', $type) }}" method="POST" class="address-form">
                    @csrf

                    <div class="address-group full">
                        <label>ラベル (識別名) <span>*</span></label>
                        <input type="text" name="label" value="{{ old('label') }}" placeholder="例: 自宅、会社">
                        @error('label') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="address-form-grid">
                        <div class="address-group">
                            <label>姓<span>*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="姓">
                            @error('last_name') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="address-group">
                            <label>名<span>*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="名">
                            @error('first_name') <div class="error-text">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="address-group full">
                        <label>電話番号<span>*</span></label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="電話番号">
                        @error('phone') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="address-group full">
                        <label>会社名（任意）</label>
                        <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="会社名">
                        @error('company_name') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="address-group full">
                        <label>住所<span>*</span></label>
                        <input type="text" name="address" value="{{ old('address') }}" placeholder="市区町村・番地">
                        @error('address') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="address-group full">
                        <label>建物名・部屋番号（任意）</label>
                        <input type="text" name="apartment" value="{{ old('apartment') }}" placeholder="建物名・部屋番号">
                        @error('apartment') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="address-group full">
                        <label>国<span>*</span></label>
                        <select name="country">
                            <option value="">国を選択</option>
                            <option value="Japan" {{ old('country') === 'Japan' ? 'selected' : '' }}>日本</option>
                            <option value="Thailand" {{ old('country') === 'Thailand' ? 'selected' : '' }}>タイ</option>
                        </select>
                        @error('country') <div class="error-text">{{ $message }}</div> @enderror
                    </div>

                    <div class="address-3-col">
                        <div class="address-group">
                            <label>市区町村<span>*</span></label>
                            <input
    type="text"
    name="city"
    id="city"
    value="{{ old('city') }}"
    placeholder="市区町村"
>
                            @error('city') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="address-group">
                            <label>都道府県<span>*</span></label>
                            <select name="state" id="state">
                                <option value="">都道府県を選択</option>
                                @foreach(['Tokyo','Osaka','Kyoto','Hokkaido','Fukuoka','Aichi','Kanagawa','Saitama','Chiba'] as $state)
                                    <option value="{{ $state }}" {{ old('state') === $state ? 'selected' : '' }}>
                                        {{ $state }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state') <div class="error-text">{{ $message }}</div> @enderror
                        </div>

                        <div class="address-group">
    <label>郵便番号<span>*</span></label>

    <div class="zip-code-row">
        <input
            type="text"
            name="zip_code"
            id="zip_code"
            class="postcode-input"
            value="{{ old('zip_code') }}"
            placeholder="郵便番号"
        >

        <button type="button" class="search-address-btn" id="searchAddressBtn">
            住所自動入力
        </button>
    </div>

    @error('zip_code')
        <div class="error-text">{{ $message }}</div>
    @enderror
</div>
                    </div>

                    <label class="address-checkbox">
                        <input type="checkbox" name="is_main" value="1" {{ old('is_main') ? 'checked' : '' }}>
                        <span>デフォルトの{{ $type === 'shipping' ? 'お届け先' : '請求先' }}住所に設定する</span>
                    </label>

                    <div class="address-actions-row">
                        <button type="submit" class="save-btn">
                            保存する
                        </button>

                        <a href="{{ route('account.addresses.index', $type) }}" class="cancel-link">
                            キャンセル
                        </a>
                    </div>
                </form>
            </main>

        </div>
    </div>
</div>
@endsection
@section('js')
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
            return null;
        }
    }

document.addEventListener('DOMContentLoaded', function () {
    const zipInput = document.getElementById('zip_code');
    const searchBtn = document.getElementById('searchAddressBtn');
    const cityInput = document.getElementById('city');
    const stateSelect = document.getElementById('state');

    function cleanPostcode(value) {
        return (value || '').replace(/[^\d]/g, '');
    }

    function setSelectValue(select, value) {
        if (!select || !value) {
            return;
        }

        const normalizedValue = String(value).trim();
        let matched = false;

        Array.from(select.options).forEach(function(option) {
            if (option.value === normalizedValue || option.text.trim() === normalizedValue) {
                option.selected = true;
                matched = true;
            }
        });

        if (!matched) {
            const newOption = new Option(normalizedValue, normalizedValue, true, true);
            select.add(newOption);
        }

        select.dispatchEvent(new Event('change'));
    }

    async function fillAddressByZipCode() {
        if (!zipInput) {
            return;
        }

        const postcode = cleanPostcode(zipInput.value);

        if (!postcode) {
            alert('郵便番号を入力してください。');
            return;
        }

        try {
            const response = await LoadGeoCode(postcode);

            console.log('LoadGeoCode response:', response);

            if (!response) {
                alert('住所が見つかりませんでした。');
                return;
            }

            const mainArea = response.mainArea || '';
            const subArea = response.subArea || '';

            setSelectValue(stateSelect, mainArea);

            if (cityInput) {
                cityInput.value = subArea;
            }

        } catch (error) {
            console.error(error);
            alert('住所を取得できませんでした。もう一度お試しください。');
        }
    }

    if (searchBtn) {
        searchBtn.addEventListener('click', fillAddressByZipCode);
    }

    if (zipInput) {
        zipInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^\d]/g, '');
        });

        zipInput.addEventListener('paste', function () {
            setTimeout(() => {
                this.value = this.value.replace(/[^\d]/g, '');
            }, 0);
        });
    }
});
</script>
@endsection