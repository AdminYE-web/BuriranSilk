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
    </style>
@endsection

@section('content')

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

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Order Information' : '注文情報' }}</div>

        <table class="info-table">
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Order No' : '注文番号' }}</th>
                <td>{{ $order->order_no }}</td>
            </tr>

            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Order Date' : '注文日' }}</th>
                <td>
                    {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                </td>
            </tr>


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

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Customer Information' : '顧客情報' }}</div>

        <table class="info-table">
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Name' : '名前' }}</th>
                <td>
                    {{ $order->customer->personal_first_name ?? '-' }}
                    {{ $order->customer->personal_last_name ?? '' }}
                </td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Email' : 'メールアドレス' }}</th>
                <td>{{ $order->customer->personal_email ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Phone' : '電話番号' }}</th>
                <td>{{ $order->customer->personal_phone ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Shipping Address' : '配送先住所' }}</div>

        <table class="info-table">
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Postcode' : '郵便番号' }}</th>
                <td>{{ $order->customer->shipping_postcode ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Province' : '都道府県' }}</th>
                <td>{{ $order->customer->shipping_province ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'City' : '市区町村' }}</th>
                <td>{{ $order->customer->shipping_city ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Area' : '市区町村以下' }}</th>
                <td>{{ $order->customer->shipping_area ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Building / Room' : '建物名・部屋番号' }}</th>
                <td>{{ $order->customer->shipping_building_room ?? '-' }}</td>
            </tr>
        </table>

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
                    @foreach ($order->items as $index => $item)
                        @php
                            $options = is_array($item->options)
                                ? $item->options
                                : json_decode($item->options ?? '[]', true);

                            $options = is_array($options) ? $options : [];

                            $leftOptions = [];
                            $rightOptions = [];

                            foreach ($options as $i => $option) {
                                if ($i % 2 === 0) {
                                    $leftOptions[] = $option;
                                } else {
                                    $rightOptions[] = $option;
                                }
                            }

                            $isOpen = $index === 0;
                        @endphp

                        <tr class="order-main-row {{ $isOpen ? 'is-open' : '' }}">
                            <td>
                                @if ($item->product_image)
                                    <img src="{{ asset('storage/' . $item->product_image) }}" class="order-product-img"
                                        alt="">
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                <div class="order-product-name">
                                    {{ $item->product_name_snapshot ?? $item->product_name }}
                                </div>
                            </td>

                            <td>
                                <span class="order-qty">
                                    {{ $item->qty ?? $item->quantity }}
                                </span>
                            </td>

                            <td>
                                <span class="order-unit-price">
                                    ¥ {{ number_format($item->unit_price, 2) }}
                                </span>
                            </td>

                            <td>
                                <span class="order-item-total-text">
                                    ¥ {{ number_format($item->item_total, 2) }}
                                </span>
                            </td>

                            <td style="text-align: right;">
                                <button type="button" class="order-toggle-btn">
                                    <img src="{{ asset('assets/images/icon/weui_arrow-filled (1).png') }}"
                                        class="order-toggle-icon" alt="">
                                    <span>{{ request()->cookie('dev') == '1' ? 'View Details' : '詳細を表示' }}</span>
                                </button>
                            </td>
                        </tr>

                        <tr class="order-detail-row {{ $isOpen ? '' : 'is-hidden' }}">
                            <td colspan="6">
                                @if (!empty($options))
                                    <div class="order-detail-content">
                                        <div>
                                            @foreach ($leftOptions as $option)
                                                <div class="order-option-line">
                                                    <strong>{{ $option['group_name'] ?? '-' }}:</strong>

                                                    @if (!empty($option['color_code']))
                                                        <span class="order-option-color"
                                                            style="background: {{ $option['color_code'] }}"></span>
                                                    @endif

                                                    {{ $option['option_name'] ?? '-' }}

                                                    @if (!empty($option['variant_name']))
                                                        : {{ $option['variant_name'] }}
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>

                                        <div>
                                            @foreach ($rightOptions as $option)
                                                <div class="order-option-line">
                                                    <strong>{{ $option['group_name'] ?? '-' }}:</strong>

                                                    @if (!empty($option['color_code']))
                                                        <span class="order-option-color"
                                                            style="background: {{ $option['color_code'] }}"></span>
                                                    @endif

                                                    {{ $option['option_name'] ?? '-' }}

                                                    @if (!empty($option['variant_name']))
                                                        : {{ $option['variant_name'] }}
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="order-detail-empty">
                                        No option details.
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="section-title">
            {{ request()->cookie('dev') == '1' ? 'Artwork / Template Information' : 'アートワーク/テンプレート情報' }}</div>

        <table>
            <thead>
                <tr>
                    <th>{{ request()->cookie('dev') == '1' ? 'Product ID' : '商品ID' }}</th>
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
                        <td>{{ $artwork->product_id }}</td>

                        <td>
                            @if ($artwork->file_path)
                                <a href="{{ asset('storage/' . $artwork->file_path) }}" target="_blank" class="file-link">
                                    {{ $artwork->original_name ?? request()->cookie('dev') == '1' ? 'View file' : 'ファイルを表示' }}
                                </a>
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $artwork->no_artwork ? 'Yes' : 'No' }}</td>

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
                            No artwork data.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Payment' : '支払い' }}</div>

        <table class="info-table">
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Method' : '方法' }}</th>
                <td>{{ $order->payment->payment_method ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                <td>
                    <span class="status-pill">
                        {{ $order->payment_status ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Transaction ID' : '取引ID' }}</th>
                <td>{{ $order->payment->transaction_id ?? '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Amount' : '金額' }}</th>
                <td>¥ {{ number_format($order->payment->amount ?? 0, 2) }}</td>
            </tr>
        </table>

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Summary' : '概要' }}</div>

        <table class="info-table">
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Subtotal' : '小計' }}</th>
                <td>¥ {{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Shipping' : '配送料' }}</th>
                <td>¥ {{ number_format($order->shipping_fee, 2) }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'VAT' : 'VAT' }}</th>
                <td>¥ {{ number_format($order->vat_amount, 2) }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Grand Total' : '合計金額' }}</th>
                <td>
                    <span class="summary-total">
                        ¥ {{ number_format($order->grand_total, 2) }}
                    </span>
                </td>
            </tr>
        </table>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.order-item-toggle').forEach(function(button) {
                button.addEventListener('click', function() {
                    const card = this.closest('.order-item-card');
                    card.classList.toggle('is-open');
                });
            });
        });
    </script>

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

                    mainRow.classList.toggle('is-open');
                    detailRow.classList.toggle('is-hidden');
                });
            });
        });
    </script>
@endsection
