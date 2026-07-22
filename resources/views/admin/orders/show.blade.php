@extends('admin.layouts.app')

@section('title', 'Order Detail | Indigo Admin')

@section('css')
    <style>
        .order-detail-card {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        .alert-success {
            margin-bottom: 18px;
            padding: 12px 16px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            font-size: 14px;
        }

        .section-title {
            margin: 34px 0 16px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .status-box {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
        }

        .status-form {
            max-width: 760px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .form-group select {
            width: 100%;
            height: 40px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            background: #fff;
            font-family: inherit;
            font-size: 14px;
        }

        .btn-primary,
        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            font-family: inherit;
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
            margin-top: 16px;
        }

        .btn-outline {
            background: #fff;
            border-color: var(--border);
            color: var(--fg);
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .info-table th,
        .info-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        .info-table th {
            width: 220px;
            background: var(--bg);
            color: var(--fg-dark);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-size: 12px;
        }

        .info-table td {
            background: #fff;
        }

        .info-table tr:last-child th,
        .info-table tr:last-child td {
            border-bottom: 0;
        }

        table {
            margin-top: 8px;
        }

        table thead th {
            background: var(--bg);
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .product-img {
            width: 72px;
            height: 72px;
            border-radius: 10px;
            border: 1px solid var(--border);
            object-fit: cover;
            background: var(--bg);
        }

        .product-name {
            font-weight: 700;
            color: var(--fg-dark);
        }

        .option-box {
            margin-bottom: 8px;
            padding: 8px 10px;
            background: var(--bg);
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.5;
        }

        .amount-text,
        .summary-total {
            font-weight: 700;
            color: var(--fg-dark);
            white-space: nowrap;
        }

        .summary-total {
            font-size: 18px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            border-radius: 999px;
            background: var(--bg);
            border: 1px solid var(--border);
            font-size: 12px;
            font-weight: 600;
            color: var(--fg);
            text-transform: capitalize;
        }

        .file-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .file-link:hover {
            text-decoration: underline;
        }

        .document-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        @media (max-width: 900px) {
            .order-detail-card {
                padding: 18px;
            }

            .status-form {
                grid-template-columns: 1fr;
            }

            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 1000px;
            }

            .info-table {
                min-width: 0;
            }

            .document-actions {
                justify-content: flex-start;
                width: 100%;
            }
        }

        /* ================= ORDER ITEMS TABLE DETAIL ================= */
        .order-items-table-wrap {
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            background: #fff;
        }

        .order-items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }

        .order-items-table thead th {
            height: 46px;
            padding: 12px 18px;
            background: #f8fafc;
            color: #6b7280;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        .order-items-table tbody td {
            padding: 18px;
            background: #f1f3f6;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            font-size: 14px;
            color: var(--fg-dark);
        }

        .order-items-table .order-main-row td {
            height: 118px;
        }

        .order-items-table .order-detail-row td {
            background: #fff;
            padding: 24px 24px 20px;
            border-bottom: 1px solid var(--border);
        }

        .order-product-img {
            width: 86px;
            height: 86px;
            border-radius: 6px;
            border: 1px solid var(--border);
            background: #fff;
            object-fit: contain;
            display: block;
        }

        .order-product-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--fg-dark);
            line-height: 1.4;
        }

        .order-qty,
        .order-unit-price {
            font-size: 15px;
            color: var(--fg-dark);
            white-space: nowrap;
        }

        .order-item-total-text {
            font-size: 16px;
            font-weight: 800;
            color: var(--fg-dark);
            white-space: nowrap;
        }

        .order-toggle-btn {
            border: 0;
            background: transparent;
            color: #1f4e79;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 0;
            white-space: nowrap;
        }

        .order-toggle-icon {
            width: 12px;
            height: 12px;
            object-fit: contain;
            transition: transform .2s ease;
        }

        .order-main-row.is-open .order-toggle-icon {
            transform: rotate(180deg);
        }

        .order-detail-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px 70px;
        }

        .order-option-line {
            font-size: 14px;
            line-height: 1.75;
            color: var(--fg-dark);
        }

        .order-option-line strong {
            font-weight: 800;
        }

        .order-option-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 4px;
            border: 1px solid rgba(0, 0, 0, .14);
            vertical-align: middle;
            margin: 0 6px;
        }

        .order-detail-empty {
            color: var(--muted);
            font-size: 14px;
        }

        .order-detail-row.is-hidden {
            display: none;
        }

        @media (max-width: 900px) {
            .order-items-table-wrap {
                overflow-x: auto;
            }

            .order-items-table {
                min-width: 860px;
            }

            .order-detail-content {
                grid-template-columns: 1fr;
                gap: 6px;
            }
        }

        .alert-error {
            margin-bottom: 18px;
            padding: 12px 16px;
            color: #b42318;
            background: #fff4f2;
            border: 1px solid #f3b7af;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-error ul {
            margin: 6px 0 0;
            padding-left: 20px;
        }

        .information-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .information-grid .section-title {
            margin-top: 34px;
        }

        .info-table td {
            overflow-wrap: anywhere;
        }

        .address-text,
        .pre-line {
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .muted-text {
            color: var(--muted);
            font-size: 12px;
        }

        .item-detail-sections {
            display: grid;
            gap: 20px;
        }

        .item-detail-block {
            min-width: 0;
        }

        .item-detail-heading {
            margin: 0 0 10px;
            color: var(--fg-dark);
            font-size: 13px;
            font-weight: 800;
        }

        .item-meta-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .item-meta-box {
            padding: 12px;
            background: var(--bg);
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.55;
        }

        .item-meta-box strong {
            display: block;
            margin-bottom: 3px;
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
        }

        .json-details {
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
        }

        .json-details summary {
            padding: 11px 14px;
            color: var(--fg-dark);
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
        }

        .json-code {
            max-height: 360px;
            margin: 0;
            padding: 14px;
            overflow: auto;
            background: #111827;
            color: #e5e7eb;
            border-radius: 0 0 8px 8px;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 12px;
            line-height: 1.6;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .configuration-body {
            display: grid;
            gap: 18px;
            padding: 16px;
            border-top: 1px solid var(--border);
        }

        .configuration-section-title {
            margin: 0 0 9px;
            color: var(--fg-dark);
            font-size: 12px;
            font-weight: 800;
        }

        .configuration-entry,
        .configuration-row {
            display: grid;
            grid-template-columns: minmax(120px, 180px) 1fr;
            gap: 14px;
            padding: 11px 12px;
            background: var(--bg);
            border-radius: 7px;
            font-size: 13px;
        }

        .configuration-entry + .configuration-entry,
        .configuration-row + .configuration-row {
            margin-top: 8px;
        }

        .configuration-label {
            color: var(--muted);
            font-weight: 700;
        }

        .configuration-value {
            min-width: 0;
            color: var(--fg-dark);
            overflow-wrap: anywhere;
        }

        .configuration-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 5px;
        }

        .configuration-chip {
            padding: 3px 8px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 999px;
            color: var(--fg);
            font-size: 11px;
        }

        .file-meta {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 11px;
        }

        .boolean-pill {
            display: inline-flex;
            padding: 4px 9px;
            border-radius: 999px;
            background: #f3f4f6;
            color: #4b5563;
            font-size: 12px;
            font-weight: 600;
        }

        .table-empty {
            padding: 32px !important;
            color: var(--muted);
            text-align: center !important;
        }

        @media (max-width: 900px) {
            .information-grid,
            .item-meta-grid {
                grid-template-columns: 1fr;
            }

            .configuration-entry,
            .configuration-row {
                grid-template-columns: 1fr;
                gap: 5px;
            }
        }
    </style>
@endsection

@section('content')

    @php
        $customer = $order->customer;
        $payment = $order->payment;
        $checkoutData = is_array($order->checkout_data) ? $order->checkout_data : [];
        $isEnglish = request()->cookie('dev') === '1';
        $yesNo = fn ($value) => is_null($value) ? '-' : ($value ? ($isEnglish ? 'Yes' : 'はい') : ($isEnglish ? 'No' : 'いいえ'));
        $billingTypeLabels = [
            'same_as_customer' => $isEnglish ? 'Same as customer' : 'お客様情報と同じ住所',
            'same_as_shipping' => $isEnglish ? 'Same as shipping' : 'お届け先情報と同じ住所',
            'different' => $isEnglish ? 'Different billing address' : '別の請求先住所',
        ];
    @endphp

    <div class="table-card order-detail-card">

        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Order Detail' : '注文詳細' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Order No' : '注文番号' }}:
                    <strong>{{ $order->order_no }}</strong>


                </div>
            </div>

            <div class="document-actions">
                <a href="{{ route('admin.orders.quotation', $order->order_id) }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Download Quotation' : '見積書をダウンロード' }}
                </a>

                <a href="{{ route('admin.orders.invoice', $order->order_id) }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Download Invoice' : '請求書をダウンロード' }}
                </a>

                <a href="{{ route('admin.orders.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert-error" role="alert">
                <strong>{{ $isEnglish ? 'Please check the submitted information.' : '入力内容をご確認ください。' }}</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Order Information' : '注文情報' }}</div>

        <table class="info-table">
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Order No' : '注文番号' }}</th>
                <td>{{ $order->order_no }}</td>
            </tr>

            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Order Date' : '注文日' }}</th>
                <td>
                    {{ $order->created_at ? $order->created_at->format('Y/m/d H:i:s') : '-' }}
                </td>
            </tr>
            <tr><th>{{ $isEnglish ? 'Order Status' : '注文ステータス' }}</th><td><span class="status-pill">{{ $order->order_status ?? $order->status ?? '-' }}</span></td></tr>
            <tr><th>{{ $isEnglish ? 'Payment Status' : '支払いステータス' }}</th><td><span class="status-pill">{{ $payment?->payment_status ?? $order->payment_status ?? '-' }}</span></td></tr>
            <tr><th>{{ $isEnglish ? 'Information Method' : '情報入力方法' }}</th><td>{{ $order->info_method ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Payment Date' : '支払日' }}</th><td>{{ $order->payment_date?->format('Y/m/d H:i:s') ?? $payment?->paid_at?->format('Y/m/d H:i:s') ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Shipping Date' : '発送日' }}</th><td>{{ $order->shipping_date?->format('Y/m/d H:i:s') ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Confirmation Email' : '注文確認メール' }}</th><td>{{ $order->confirmation_email_sent_at ? ($isEnglish ? 'Sent: ' : '送信済み：').$order->confirmation_email_sent_at->format('Y/m/d H:i:s') : ($isEnglish ? 'Not sent' : '未送信') }}</td></tr>
        </table>

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Order Status' : '注文ステータス' }}</div>

        <form action="{{ route('admin.orders.updateStatus', $order->order_id) }}" method="POST" class="status-box">
            @csrf
            @method('PUT')

            <div class="status-form">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Order Status' : '注文ステータス' }}</label>

                    <select name="status">
                        @foreach (['order_pending', 'design_in_progress', 'production', 'delivery', 'delivered', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" {{ $order->order_status == $status ? 'selected' : '' }}>
                                {{ ucwords(str_replace('_', ' ', $status)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Payment Status' : '支払いステータス' }}</label>

                    <select name="payment_status">
                        @foreach (['pending', 'paid', 'failed', 'cancelled', 'refunded'] as $paymentStatus)
                            <option value="{{ $paymentStatus }}"
                                {{ $order->payment_status == $paymentStatus ? 'selected' : '' }}>
                                {{ ucfirst($paymentStatus) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" class="btn-primary">
                {{ request()->cookie('dev') == '1' ? 'Update Status' : 'ステータスを更新' }}
            </button>
        </form>

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Customer Information' : 'お客様情報' }}</div>

        <table class="info-table">
            <tr><th>{{ $isEnglish ? 'Customer Type' : 'お客様区分' }}</th><td>{{ $customer?->customer_type === 'corporate' ? ($isEnglish ? 'Corporate' : '法人') : ($customer?->customer_type === 'individual' ? ($isEnglish ? 'Individual' : '個人') : '-') }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Name' : 'お名前' }}</th><td>{{ $customer?->personal_name ?? trim(($customer?->personal_first_name ?? '').' '.($customer?->personal_last_name ?? '')) ?: '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Name (Kana)' : 'お名前（フリガナ）' }}</th><td>{{ $customer?->personal_name_kana ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Company' : '会社名' }}</th><td>{{ $customer?->company_name ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Company (Kana)' : '会社名（フリガナ）' }}</th><td>{{ $customer?->company_name_kana ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Email' : 'メールアドレス' }}</th><td>{{ $customer?->personal_email ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Phone' : '電話番号' }}</th><td>{{ $customer?->personal_phone ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Postcode' : '郵便番号' }}</th><td>{{ $customer?->personal_postcode ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Prefecture' : '都道府県' }}</th><td>{{ $customer?->personal_province ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'City' : '市区町村' }}</th><td>{{ $customer?->personal_city ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Street Address' : '町名・番地' }}</th><td>{{ $customer?->personal_area ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Full Address' : '住所（全文）' }}</th><td class="address-text">{{ trim(($customer?->personal_province ?? '').($customer?->personal_city ?? '').($customer?->personal_area ?? '')) ?: '-' }}</td></tr>
        </table>
        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Shipping Address' : 'お届け先情報' }}</div>

        <div class="information-grid">
            <section>
                <table class="info-table">
                    <tr><th>{{ $isEnglish ? 'Same as customer' : 'お客様情報と同じ' }}</th><td><span class="boolean-pill">{{ $yesNo($customer?->same_as_customer) }}</span></td></tr>
                    <tr><th>{{ $isEnglish ? 'Name' : 'お名前' }}</th><td>{{ $customer?->shipping_name ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Name (Kana)' : 'お名前（フリガナ）' }}</th><td>{{ $customer?->shipping_name_kana ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Postcode' : '郵便番号' }}</th><td>{{ $customer?->shipping_postcode ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Prefecture' : '都道府県' }}</th><td>{{ $customer?->shipping_province ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'City' : '市区町村' }}</th><td>{{ $customer?->shipping_city ?? $customer?->shipping_district ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Street Address' : '町名・番地' }}</th><td>{{ $customer?->shipping_area ?? $customer?->shipping_subdistrict ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Building / Room' : '建物名・部屋番号' }}</th><td>{{ $customer?->shipping_building_room ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Full Address' : '住所（全文）' }}</th><td class="address-text">{{ $customer?->shipping_address ?? '-' }}</td></tr>
                </table>
            </section>

            <section>
                <div class="section-title" style="margin-top: 0; padding-top: 0; border-top: 0;">{{ $isEnglish ? 'Billing Address' : '請求先情報' }}</div>
                <table class="info-table">
                    <tr><th>{{ $isEnglish ? 'Address Type' : '請求先区分' }}</th><td>{{ $billingTypeLabels[$customer?->billing_address_type] ?? $customer?->billing_address_type ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Name' : 'お名前' }}</th><td>{{ $customer?->billing_name ?? trim(($customer?->billing_first_name ?? '').' '.($customer?->billing_last_name ?? '')) ?: '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Name (Kana)' : 'お名前（フリガナ）' }}</th><td>{{ $customer?->billing_name_kana ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Phone' : '電話番号' }}</th><td>{{ $customer?->billing_phone ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Email' : 'メールアドレス' }}</th><td>{{ $customer?->billing_email ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Postcode' : '郵便番号' }}</th><td>{{ $customer?->billing_postcode ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Prefecture' : '都道府県' }}</th><td>{{ $customer?->billing_province ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'City' : '市区町村' }}</th><td>{{ $customer?->billing_city ?? $customer?->billing_district ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Street Address' : '町名・番地' }}</th><td>{{ $customer?->billing_area ?? $customer?->billing_subdistrict ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Building / Room' : '建物名・部屋番号' }}</th><td>{{ $customer?->billing_building_room ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Full Address' : '住所（全文）' }}</th><td class="address-text">{{ $customer?->billing_address ?? '-' }}</td></tr>
                </table>
            </section>
        </div>
        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Order Items' : '注文商品' }}</div>

        <div class="order-items-table-wrap">
            <table class="order-items-table">
                <thead>
                    <tr>
                        <th style="width: 130px;">{{ request()->cookie('dev') == '1' ? 'Image' : '画像' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Product' : '商品' }}</th>
                        <th style="width: 95px;">{{ request()->cookie('dev') == '1' ? 'Qty' : '数量' }}</th>
                        <th style="width: 140px;">{{ request()->cookie('dev') == '1' ? 'Unit Price' : '単価' }}</th>
                        <th style="width: 150px;">{{ request()->cookie('dev') == '1' ? 'Total' : '合計' }}</th>
                        <th style="width: 150px; text-align: right;">
                            <img src="{{ asset('assets/images/icon/weui_arrow-filled (1).png') }}"
                                class="order-toggle-icon" alt="">
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($order->items as $index => $item)
                        @php
                            $options = is_array($item->options) ? $item->options : (json_decode($item->options ?? '[]', true) ?: []);
                            $configuration = is_array($item->configuration) ? $item->configuration : [];
                            $customColors = is_array($item->custom_colors) ? $item->custom_colors : [];
                            $itemArtworks = $order->artworks->where('order_item_id', $item->order_item_id);
                            $imagePath = ltrim(str_replace('\\', '/', (string) $item->product_image), '/');
                            $imageUrl = filled($imagePath)
                                ? (Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])
                                    ? $imagePath
                                    : asset(Illuminate\Support\Str::startsWith($imagePath, ['storage/', 'assets/'])
                                        ? $imagePath
                                        : 'storage/'.$imagePath))
                                : null;
                            $isOpen = $index === 0;
                        @endphp

                        <tr class="order-main-row {{ $isOpen ? 'is-open' : '' }}">
                            <td>
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" class="order-product-img" alt="{{ $item->product_name_snapshot ?? '' }}">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <div class="order-product-name">{{ $item->product_name_snapshot ?? $item->product_name ?? '-' }}</div>
                                <span class="muted-text">Product ID: {{ $item->product_id ?? '-' }} / Item ID: {{ $item->order_item_id }}</span>
                            </td>
                            <td><span class="order-qty">{{ $item->quantity ?? $item->qty ?? 0 }}</span></td>
                            <td><span class="order-unit-price">¥ {{ number_format((float) ($item->unit_price ?? $item->base_unit_price ?? 0)) }}</span></td>
                            <td><span class="order-item-total-text">¥ {{ number_format((float) ($item->item_total ?? 0)) }}</span></td>
                            <td style="text-align: right;">
                                <button type="button" class="order-toggle-btn" aria-expanded="{{ $isOpen ? 'true' : 'false' }}">
                                    <img src="{{ asset('assets/images/icon/weui_arrow-filled (1).png') }}" class="order-toggle-icon" alt="">
                                    <span>{{ $isEnglish ? 'View Details' : '詳細を表示' }}</span>
                                </button>
                            </td>
                        </tr>

                        <tr class="order-detail-row {{ $isOpen ? '' : 'is-hidden' }}">
                            <td colspan="6">
                                <div class="item-detail-sections">
                                    <div class="item-meta-grid">
                                        <div class="item-meta-box"><strong>{{ $isEnglish ? 'Base total' : '商品小計' }}</strong>¥ {{ number_format((float) ($item->base_total ?? $item->product_total ?? 0)) }}</div>
                                        <div class="item-meta-box"><strong>{{ $isEnglish ? 'Option total' : 'オプション合計' }}</strong>¥ {{ number_format((float) ($item->option_total ?? 0)) }}</div>
                                        <div class="item-meta-box"><strong>{{ $isEnglish ? 'Item total' : '商品合計' }}</strong>¥ {{ number_format((float) ($item->item_total ?? 0)) }}</div>
                                        <div class="item-meta-box"><strong>{{ $isEnglish ? 'Quantity' : '数量' }}</strong>{{ $item->quantity ?? $item->qty ?? 0 }}</div>
                                    </div>

                                    <div class="item-detail-block">
                                        <div class="item-detail-heading">{{ $isEnglish ? 'Selected Options' : '選択オプション' }}</div>
                                        @if ($item->optionDetails->isNotEmpty())
                                            <div class="order-detail-content">
                                                @foreach ($item->optionDetails as $option)
                                                    <div class="order-option-line">
                                                        <strong>{{ $option->group_name_snapshot ?? ($isEnglish ? 'Option' : 'オプション') }}:</strong>
                                                        {{ $option->option_name_snapshot ?? '-' }}
                                                        @if (filled($option->custom_value))
                                                            / {{ $option->custom_value }}
                                                        @endif
                                                        <span class="muted-text">({{ $option->price_type }} / +¥ {{ number_format((float) $option->additional_price) }} / {{ $isEnglish ? 'Total' : '合計' }} ¥ {{ number_format((float) $option->total_price) }})</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif (!empty($options))
                                            <div class="order-detail-content">
                                                @foreach ($options as $option)
                                                    <div class="order-option-line">
                                                        <strong>{{ $option['group_name'] ?? ($isEnglish ? 'Option' : 'オプション') }}:</strong>
                                                        {{ $option['option_name'] ?? '-' }}
                                                        @if (!empty($option['variant_name'])) / {{ $option['variant_name'] }} @endif
                                                        @if (!empty($option['option_detail'])) / {{ $option['option_detail'] }} @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="order-detail-empty">{{ $isEnglish ? 'No option details.' : 'オプション情報はありません。' }}</div>
                                        @endif
                                    </div>

                                    @if (!empty($configuration['previous_order_numbers']) || !empty($configuration['font_entries']) || !empty($configuration['custom_fields']))
                                        <details class="json-details" open>
                                            <summary>{{ $isEnglish ? 'Product Configuration' : '商品設定情報' }}</summary>
                                            <div class="configuration-body">
                                                @if (!empty($configuration['previous_order_numbers']))
                                                    <section>
                                                        <div class="configuration-section-title">{{ $isEnglish ? 'Previous Order Numbers' : '前回ご注文管理番号' }}</div>
                                                        @foreach ($configuration['previous_order_numbers'] as $number)
                                                            <div class="configuration-row">
                                                                <span class="configuration-label">{{ $isEnglish ? 'Order No.' : '管理番号' }}</span>
                                                                <strong class="configuration-value">{{ $number }}</strong>
                                                            </div>
                                                        @endforeach
                                                    </section>
                                                @endif

                                                @if (!empty($configuration['font_entries']))
                                                    <section>
                                                        <div class="configuration-section-title">{{ $isEnglish ? 'Text / Font' : '印刷文字・書体' }}</div>
                                                        @foreach ($configuration['font_entries'] as $groupId => $entries)
                                                            @foreach ($entries as $entryIndex => $entry)
                                                                <div class="configuration-entry">
                                                                    <span class="configuration-label">{{ $isEnglish ? 'Text' : 'テキスト' }} {{ $entryIndex + 1 }}</span>
                                                                    <div class="configuration-value">
                                                                        <strong>{{ filled($entry['text'] ?? null) ? $entry['text'] : ($isEnglish ? 'Not entered' : '未入力') }}</strong>
                                                                        <div class="configuration-meta">
                                                                            <span class="configuration-chip">{{ $isEnglish ? 'Font' : '書体' }}: {{ filled($entry['font'] ?? null) ? $entry['font'] : '-' }}</span>
                                                                            <span class="configuration-chip">{{ $isEnglish ? 'Size' : 'サイズ' }}: {{ $entry['size'] ?? '-' }}pt</span>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endforeach
                                                    </section>
                                                @endif

                                                @if (!empty($configuration['custom_fields']))
                                                    <section>
                                                        <div class="configuration-section-title">{{ $isEnglish ? 'Additional Fields' : '追加情報' }}</div>
                                                        @foreach ($configuration['custom_fields'] as $field => $value)
                                                            <div class="configuration-row">
                                                                <span class="configuration-label">{{ Illuminate\Support\Str::headline($field) }}</span>
                                                                <strong class="configuration-value">{{ is_array($value) ? implode(', ', Illuminate\Support\Arr::flatten($value)) : $value }}</strong>
                                                            </div>
                                                        @endforeach
                                                    </section>
                                                @endif
                                            </div>
                                        </details>
                                    @endif

                                    @if (!empty($customColors))
                                        <details class="json-details">
                                            <summary>{{ $isEnglish ? 'Custom Colors' : 'カスタムカラー' }}</summary>
                                            <pre class="json-code">{{ json_encode($customColors, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </details>
                                    @endif

                                    @if ($itemArtworks->isNotEmpty())
                                        <div class="item-detail-block">
                                            <div class="item-detail-heading">{{ $isEnglish ? 'Attached Artwork' : '添付アートワーク' }}</div>
                                            @foreach ($itemArtworks as $artwork)
                                                @if ($artwork->file_path)
                                                    <div><a href="{{ asset('storage/'.$artwork->file_path) }}" target="_blank" rel="noopener" class="file-link">{{ $artwork->original_name ?: ($isEnglish ? 'View file' : 'ファイルを表示') }}</a></div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="table-empty">{{ $isEnglish ? 'No order items.' : '注文商品がありません。' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="section-title">
            {{ request()->cookie('dev') == '1' ? 'Artwork / Template Information' : 'アートワーク/テンプレート情報' }}</div>

        <table>
            <thead>
                <tr>
                    <th>{{ $isEnglish ? 'Item / Product' : '明細・商品ID' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'File' : 'ファイル' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'No Artwork' : 'ノーアートワーク' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Text' : 'テキスト' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Font' : 'フォント' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Template ID' : 'テンプレートID' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($order->artworks as $artwork)
                    <tr>
                        <td>#{{ $artwork->order_item_id ?? '-' }} / #{{ $artwork->product_id ?? '-' }}</td>

                        <td>
                            @if ($artwork->file_path)
                                <a href="{{ asset('storage/' . $artwork->file_path) }}" target="_blank" class="file-link">
                                    {{ $artwork->original_name ?: ($isEnglish ? 'View file' : 'ファイルを表示') }}
                                    <span class="file-meta">
                                        {{ $artwork->mime_type ?? '-' }}
                                        @if ($artwork->file_size)
                                            / {{ number_format($artwork->file_size / 1024, 1) }} KB
                                        @endif
                                    </span>
                                </a>
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $yesNo((bool) $artwork->no_artwork) }}</td>

                        <td>{{ $artwork->print_text ?? '-' }}</td>

                        <td>
                            {{ $artwork->font_option ?? '-' }}
                            @if ($artwork->font_other)
                                / {{ $artwork->font_other }}
                            @endif
                        </td>

                        <td>{{ $artwork->template_id ?? '-' }}</td>

                        <td>
                            <span class="status-pill">
                                {{ ucfirst($artwork->status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:32px;">
                            {{ $isEnglish ? 'No artwork data.' : 'アートワーク情報はありません。' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="information-grid">
            <section>
                <div class="section-title">{{ $isEnglish ? 'Payment' : 'お支払い情報' }}</div>
                <table class="info-table">
                    <tr><th>{{ $isEnglish ? 'Method' : 'お支払い方法' }}</th><td>{{ $payment?->payment_method === 'bank_transfer' ? ($isEnglish ? 'Bank transfer' : '銀行振込') : ($payment?->payment_method ?? '-') }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Status' : 'ステータス' }}</th><td><span class="status-pill">{{ $payment?->payment_status ?? $order->payment_status ?? '-' }}</span></td></tr>
                    <tr><th>{{ $isEnglish ? 'Transaction ID' : '取引ID' }}</th><td>{{ $payment?->transaction_id ?? '-' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Amount' : '金額' }}</th><td class="amount-text">¥ {{ number_format((float) ($payment?->amount ?? 0)) }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Currency' : '通貨' }}</th><td>{{ $payment?->currency ?? $order->currency ?? 'JPY' }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Paid At' : '支払日時' }}</th><td>{{ $payment?->paid_at?->format('Y/m/d H:i:s') ?? '-' }}</td></tr>
                </table>
            </section>

            <section>
                <div class="section-title">{{ $isEnglish ? 'Summary' : '金額概要' }}</div>
                <table class="info-table">
                    <tr><th>{{ $isEnglish ? 'Items' : '商品種類数' }}</th><td>{{ $order->total_items ?? $order->items->count() }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Quantity' : '合計数量' }}</th><td>{{ $order->total_quantity ?? $order->qty ?? 0 }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Option Total' : 'オプション合計' }}</th><td>¥ {{ number_format((float) ($order->option_total ?? 0)) }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Subtotal' : '商品小計' }}</th><td>¥ {{ number_format((float) ($order->subtotal ?? 0)) }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Shipping' : '送料' }}</th><td>¥ {{ number_format((float) ($order->shipping_fee ?? 0)) }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Tax / VAT' : '消費税' }}</th><td>¥ {{ number_format((float) ($order->vat_amount ?? $order->tax_amount ?? 0)) }}</td></tr>
                    <tr><th>{{ $isEnglish ? 'Grand Total' : '合計金額' }}</th><td><span class="summary-total">¥ {{ number_format((float) ($order->grand_total ?? 0)) }}</span></td></tr>
                </table>
            </section>
        </div>

        <div class="section-title">{{ $isEnglish ? 'Additional Information' : 'その他情報' }}</div>
        <table class="info-table">
            <tr><th>{{ $isEnglish ? 'Delivery Option' : '配送オプション' }}</th><td>{{ $yesNo(is_null($order->delivery_option) ? null : (bool) $order->delivery_option) }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Publish on Website' : 'WEBサイトへの掲載' }}</th><td>{{ $yesNo($order->publish_website) }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Newsletter' : 'メルマガ' }}</th><td>{{ $yesNo($order->newsletter) }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Signature Text' : 'メール署名等' }}</th><td class="pre-line">{{ $order->signature_text ?? '-' }}</td></tr>
            <tr><th>{{ $isEnglish ? 'Notes' : 'ご連絡事項' }}</th><td class="pre-line">{{ $order->notes ?? '-' }}</td></tr>
        </table>

        @if (!empty($checkoutData))
            <details class="json-details" style="margin-top: 18px;">
                <summary>{{ $isEnglish ? 'Original Checkout Data' : '注文時の入力データ（原本）' }}</summary>
                <pre class="json-code">{{ json_encode($checkoutData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
            </details>
        @endif

    </div>

@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.order-toggle-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const mainRow = this.closest('.order-main-row');
                    const detailRow = mainRow.nextElementSibling;

                    if (!detailRow || !detailRow.classList.contains('order-detail-row')) {
                        return;
                    }

                    const isOpen = mainRow.classList.toggle('is-open');
                    detailRow.classList.toggle('is-hidden', !isOpen);
                    this.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                });
            });
        });
    </script>
@endsection
