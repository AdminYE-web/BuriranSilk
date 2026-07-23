@extends('frontend.layouts.app')

@section('title', ($type === 'shipping' ? 'お届け先' : '請求先') . '住所一覧')

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
        margin-bottom: 20px;
        max-width: 560px;
    }

    .add-address-box {
        width: 100%;
        min-height: 86px;
        border: 2px dashed #bdbdbd;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        color: #111;
        text-decoration: none;
        font-weight: 700;
        font-size: 16px;
    }

    .add-address-box:hover {
        color: #111;
        background: #fafafa;
    }

    .add-plus {
        font-size: 36px;
        color: #777;
        font-weight: 300;
    }

    .address-list {
        margin-top: 24px;
        display: grid;
        gap: 14px;
    }

    .address-item {
        border: 1px solid #e5e5e5;
        border-radius: 8px;
        padding: 18px 20px;
        display: flex;
        justify-content: space-between;
        gap: 20px;
        align-items: flex-start;
    }

    .address-label {
        font-weight: 700;
        margin-bottom: 6px;
    }

    .address-text {
        color: #555;
        font-size: 14px;
        line-height: 1.6;
    }

    .main-badge {
        display: inline-block;
        margin-left: 8px;
        padding: 3px 8px;
        border-radius: 999px;
        background: #eaf3ff;
        color: #1683ff;
        font-size: 12px;
        font-weight: 700;
    }

    .address-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        justify-content: flex-end;
    }

    .small-btn {
        border: 1px solid #d1d5db;
        background: #fff;
        border-radius: 6px;
        padding: 6px 10px;
        font-size: 13px;
        cursor: pointer;
    }

    .small-btn.primary {
        background: #2f70c9;
        color: #fff;
        border-color: #2f70c9;
    }

    .small-btn.danger {
        color: #dc2626;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        padding: 10px 14px;
        border-radius: 8px;
        margin-bottom: 16px;
    }

    @media (max-width: 991px) {
        .account-page {
            padding: 16px 0;
            min-height: auto;
        }

        .account-layout {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .address-card {
            padding: 24px 20px;
            min-height: auto;
        }

        .address-item {
            flex-direction: column;
        }
    }
    .address-actions-image-style {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 14px;
}

.address-top-actions {
    display: flex;
    align-items: center;
    gap: 26px;
}

.address-text-action {
    border: 0;
    background: transparent;
    padding: 0;
    color: #2f8cff;
    font-size: 14px;
    line-height: 1;
    font-weight: 400;
    text-decoration: none;
    cursor: pointer;
}

.address-text-action:hover {
    color: #176fd1;
}

.address-delete-action {
    font-family: inherit;
}

.set-default-btn {
    min-width: 112px;
    height: 30px;
    border: 2px solid #777;
    border-radius: 10px;
    background: #fff;
    color: #111;
    font-size: 14px;
    font-weight: 400;
    line-height: 1;
    cursor: pointer;
}

.set-default-btn:hover {
    background: #f8f8f8;
}

.set-default-btn.is-current {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: default;
    color: #1683ff;
    border-color: #1683ff;
    font-size: 14px;
}

@media (max-width: 900px) {
    .address-actions-image-style {
        align-items: flex-start;
    }

    .address-text-action {
        font-size: 22px;
    }

    .set-default-btn {
        min-width: 160px;
        height: 52px;
        font-size: 22px;
    }
}
</style>
@endsection

@section('content')
<div class="account-page">
    <div class="container">
        <div class="account-layout">

            @include('frontend.account.partials.sidebar', ['user' => $user])

            <main class="address-card">
                <h1>{{ $type === 'shipping' ? 'お届け先' : '請求先' }}住所一覧</h1>
                <div class="address-divider"></div>

                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert-error">{{ session('error') }}</div>
                @endif

                @if($addresses->count() < 5)
                    <a href="{{ route('account.addresses.create', $type) }}" class="add-address-box">
                        <span class="add-plus">+</span>
                        <span>新しい住所を追加</span>
                    </a>
                @else
                    <div class="alert-error">
                        登録できる{{ $type === 'shipping' ? 'お届け先' : '請求先' }}住所は最大5件までです。
                    </div>
                @endif

                <div class="address-list">
                    @forelse($addresses as $address)
                        <div class="address-item">
                            <div>
                                <div class="address-label">
                                    {{ $address->label }}

                                    @if($address->is_main)
                                        <span class="main-badge">デフォルト</span>
                                    @endif
                                </div>

                                <div class="address-text">
                                    {{ $address->last_name }} {{ $address->first_name }}<br>
                                    電話番号: {{ $address->phone }}<br>
                                    {{ $address->address }}
                                    @if($address->apartment)
                                        , {{ $address->apartment }}
                                    @endif
                                    <br>
                                    {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}<br>
                                    {{ $address->country }}

                                    @if($address->company_name)
                                        <br>会社名: {{ $address->company_name }}
                                    @endif
                                </div>
                            </div>

                            <div class="address-actions address-actions-image-style">
    <div class="address-top-actions">
        <a href="{{ route('account.addresses.edit', $address->user_address_id) }}" class="address-text-action">
            編集
        </a>

        <form action="{{ route('account.addresses.destroy', $address->user_address_id) }}"
            method="POST"
            onsubmit="return confirm('この住所を削除してもよろしいですか？')">
            @csrf
            @method('DELETE')

            <button type="submit" class="address-text-action address-delete-action">
                削除
            </button>
        </form>
    </div>

    @if(!$address->is_main)
        <form action="{{ route('account.addresses.setMain', $address->user_address_id) }}" method="POST">
            @csrf
            @method('PUT')

            <button type="submit" class="set-default-btn">
                デフォルトに設定
            </button>
        </form>
    @else
        <div class="set-default-btn is-current">
            デフォルト
        </div>
    @endif
</div>
                        </div>
                    @empty
                        <div style="margin-top:20px; color:#777;">
                            登録されている{{ $type === 'shipping' ? 'お届け先' : '請求先' }}住所はありません。
                        </div>
                    @endforelse
                </div>
            </main>

        </div>
    </div>
</div>
@endsection