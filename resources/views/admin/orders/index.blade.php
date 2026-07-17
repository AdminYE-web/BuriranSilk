@extends('admin.layouts.app')

@section('title', 'Orders | Indigo Admin')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .alert-success {
            margin-bottom: 18px;
            padding: 12px 16px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            font-size: 14px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .filter-form input,
        .filter-form select {
            height: 38px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            font-family: inherit;
            font-size: 14px;
        }

        .filter-form input {
            min-width: 280px;
            flex: 1;
        }

        .order-no {
            font-weight: 700;
            color: var(--fg-dark);
        }

        .customer-text {
            line-height: 1.5;
        }

        .customer-sub {
            display: block;
            color: var(--muted);
            font-size: 12px;
        }

        .amount-text {
            font-weight: 700;
            color: var(--fg-dark);
            white-space: nowrap;
        }

        .status-badge {
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
            white-space: nowrap;
        }

        .payment-sub {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .date-text {
            white-space: nowrap;
            color: var(--fg);
            font-size: 13px;
        }

        .btn-outline,
        .btn-primary {
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
            font-family: inherit;
            border: 1px solid transparent;
        }

        .btn-outline {
            background: #fff;
            border-color: var(--border);
            color: var(--fg);
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        @media (max-width: 900px) {
            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 1100px;
            }

            .filter-form {
                flex-direction: column;
            }

            .filter-form input,
            .filter-form select,
            .filter-form .btn-primary,
            .filter-form .btn-outline {
                width: 100%;
            }
        }

        .date-range-filter {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-range-filter input {
            min-width: 145px;
            flex: unset;
        }

        .date-range-filter span {
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
        }

        @media (max-width: 900px) {
            .date-range-filter {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .date-range-filter input {
                width: 100%;
            }

            .date-range-filter span {
                text-align: center;
            }
        }

        .filter-form {
            margin-bottom: 20px;
            padding: 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .filter-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-row+.filter-row {
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid var(--border);
        }

        .filter-form input,
        .filter-form select {
            height: 38px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            font-family: inherit;
            font-size: 14px;
        }

        .filter-search {
            min-width: 280px;
            flex: 1;
        }

        .filter-form select {
            min-width: 190px;
        }

        .advanced-filter {
            display: none;
        }

        .advanced-filter.is-open {
            display: flex;
        }

        .date-range-filter {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-range-filter label {
            font-size: 13px;
            font-weight: 700;
            color: var(--fg-dark);
            white-space: nowrap;
        }

        .date-range-filter input {
            min-width: 145px;
            flex: unset;
        }

        .date-range-filter span {
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
        }

        .btn-advanced {
            background: #fff;
            border-color: var(--border);
            color: var(--fg);
        }

        @media (max-width: 900px) {
            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 1100px;
            }

            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form input,
            .filter-form select,
            .filter-form .btn-primary,
            .filter-form .btn-outline {
                width: 100%;
            }

            .date-range-filter {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .date-range-filter input {
                width: 100%;
            }

            .date-range-filter span {
                text-align: center;
            }
        }

        .filter-form {
            margin-bottom: 20px;
            padding: 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .filter-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-row-main {
            align-items: center;
        }

        .filter-search {
            min-width: 280px;
            flex: 1;
        }

        .filter-form select {
            min-width: 190px;
        }

        .advanced-filter {
            display: none;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--border);
        }

        .advanced-filter.is-open {
            display: grid;
            grid-template-columns: repeat(3, minmax(260px, 1fr));
            gap: 16px;
            align-items: end;
        }

        .date-filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .date-filter-label {
            font-size: 13px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .date-filter-inputs {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 8px;
            align-items: center;
        }

        .date-filter-inputs input {
            width: 100%;
            min-width: 0;
        }

        .date-filter-inputs span {
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }

        .btn-advanced {
            min-width: 135px;
        }

        @media (max-width: 1100px) {
            .advanced-filter.is-open {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 900px) {
            .filter-row {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form input,
            .filter-form select,
            .filter-form .btn-primary,
            .filter-form .btn-outline {
                width: 100%;
            }

            .date-filter-inputs {
                grid-template-columns: 1fr;
            }

            .date-filter-inputs span {
                display: none;
            }
        }
    </style>
@endsection

@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Orders' : '注文管理' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Manage customer orders, payment status and order progress.' : '顧客の注文、支払い状況、注文の進行状況を管理します。' }}
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @php
            $hasAdvancedFilter =
                request()->filled('order_date_from') ||
                request()->filled('order_date_to') ||
                request()->filled('payment_date_from') ||
                request()->filled('payment_date_to') ||
                request()->filled('shipping_date_from') ||
                request()->filled('shipping_date_to');
        @endphp

        <form method="GET" class="filter-form">
            {{-- Row 1: Main Search --}}
            <div class="filter-row filter-row-main">
                <input type="text" name="search" class="filter-search" value="{{ request('search') }}"
                    placeholder="Search order no, email, name, phone">

                <select name="status">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Order Status' : 'すべての注文ステータス' }}
                    </option>

                    @foreach (['order_pending', 'design_in_progress', 'production', 'delivery', 'delivered', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ ucwords(str_replace('_', ' ', $status)) }}
                        </option>
                    @endforeach
                </select>

                <select name="payment_status">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Payment Status' : 'すべての支払いステータス' }}
                    </option>

                    @foreach (['pending', 'paid', 'failed', 'cancelled', 'refunded'] as $paymentStatus)
                        <option value="{{ $paymentStatus }}"
                            {{ request('payment_status') == $paymentStatus ? 'selected' : '' }}>
                            {{ ucfirst($paymentStatus) }}
                        </option>
                    @endforeach
                </select>

                <select name="product_type">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Product Type' : 'すべての製品タイプ' }}
                    </option>

                    <option value="1" {{ request('product_type') == '1' ? 'selected' : '' }}>
                        Hotstrap
                    </option>

                    <option value="2" {{ request('product_type') == '2' ? 'selected' : '' }}>
                        Hotmobily
                    </option>
                </select>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? 'Search' : '検索' }}
                </button>

                <a href="{{ route('admin.orders.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Reset' : 'リセット' }}
                </a>

                <button type="button" class="btn-outline btn-advanced" id="toggleAdvancedFilter">
                    {{ request()->cookie('dev') == '1' ? 'Advanced Search' : '詳細検索' }}
                </button>
            </div>

            {{-- Row 2: Advanced Search --}}
            <div class="advanced-filter {{ $hasAdvancedFilter ? 'is-open' : '' }}" id="advancedFilterBox">
                <div class="date-filter-group">
                    <label class="date-filter-label">Order Date</label>

                    <div class="date-filter-inputs">
                        <input type="text" name="order_date_from" class="js-date-picker"
                            value="{{ request('order_date_from') }}" placeholder="From" autocomplete="off">

                        <span>{{ request()->cookie('dev') == '1' ? 'to' : '〜' }}</span>

                        <input type="text" name="order_date_to" class="js-date-picker"
                            value="{{ request('order_date_to') }}" placeholder="To" autocomplete="off">
                    </div>
                </div>

                <div class="date-filter-group">
                    <label
                        class="date-filter-label">{{ request()->cookie('dev') == '1' ? 'Shipping Date' : '出荷日' }}</label>

                    <div class="date-filter-inputs">
                        <input type="text" name="shipping_date_from" class="js-date-picker"
                            value="{{ request('shipping_date_from') }}" placeholder="From" autocomplete="off">

                        <span>{{ request()->cookie('dev') == '1' ? 'to' : '〜' }}</span>

                        <input type="text" name="shipping_date_to" class="js-date-picker"
                            value="{{ request('shipping_date_to') }}" placeholder="To" autocomplete="off">
                    </div>
                </div>

                <div class="date-filter-group">
                    <label
                        class="date-filter-label">{{ request()->cookie('dev') == '1' ? 'Payment Date' : '支払い日' }}</label>

                    <div class="date-filter-inputs">
                        <input type="text" name="payment_date_from" class="js-date-picker"
                            value="{{ request('payment_date_from') }}" placeholder="From" autocomplete="off">

                        <span>{{ request()->cookie('dev') == '1' ? 'to' : '〜' }}</span>

                        <input type="text" name="payment_date_to" class="js-date-picker"
                            value="{{ request('payment_date_to') }}" placeholder="To" autocomplete="off">
                    </div>
                </div>
            </div>
        </form>

        <table>
            <thead>
                <tr>
                    <th>{{ request()->cookie('dev') == '1' ? 'Order' : '注文' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Customer' : '顧客' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Qty' : '数量' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Total' : '合計' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Order Status' : '注文ステータス' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Payment' : '支払い' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Date' : '注文日' }}</th>
                    <th style="text-align:right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="order-no">
                                {{ $order->order_no }}
                            </span>
                        </td>

                        <td>
                            <div class="customer-text">
                                {{ $order->customer->personal_first_name ?? '-' }}
                                {{ $order->customer->personal_last_name ?? '' }}

                                <span class="customer-sub">
                                    {{ $order->customer->personal_email ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <td>{{ $order->qty }}</td>

                        <td>
                            <span class="amount-text">
                                ¥ {{ number_format($order->grand_total, 2) }}
                            </span>
                        </td>

                        <td>
                            <span class="status-badge">
                                {{ $order->order_status ? ucwords(str_replace('_', ' ', $order->order_status)) : '-' }}
                            </span>
                        </td>

                        <td>
                            <span class="status-badge">
                                {{ ucfirst($order->payment->payment_status ?? 'pending') }}
                            </span>

                            <span class="payment-sub">
                                {{ $order->payment->payment_method ?? '-' }}
                            </span>
                        </td>

                        <td>
                            <span class="date-text">
                                {{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}
                            </span>
                        </td>

                        <td style="text-align:right;">
                            <a href="{{ route('admin.orders.show', $order->order_id) }}" class="btn-outline">
                                {{ request()->cookie('dev') == '1' ? 'Detail' : '詳細' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:32px;">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $orders->links() }}
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr('.js-date-picker', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true,
            locale: 'en'
        });

        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggleAdvancedFilter');
            const advancedBox = document.getElementById('advancedFilterBox');

            if (!toggleButton || !advancedBox) {
                return;
            }

            function updateButtonText() {
                toggleButton.textContent = advancedBox.classList.contains('is-open') ?
                    'Hide Advanced' :
                    'Advanced Search';
            }

            updateButtonText();

            toggleButton.addEventListener('click', function() {
                advancedBox.classList.toggle('is-open');
                updateButtonText();
            });
        });
    </script>
@endsection
