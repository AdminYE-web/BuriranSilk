@extends('frontend.layouts.app')

@section('title', '注文履歴')

@section('css')
    <style>
        .account-page {
            background: #f8fafc;
            padding: 32px 0;
            min-height: 720px;
        }

        .account-layout {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 36px;
            align-items: start;
            width: 100%;
            max-width: 100%;
        }

        .account-layout > * {
            min-width: 0;
        }

        .orders-card {
            background: #fff;
            border-radius: 12px;
            padding: 36px 44px;
            min-height: 520px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 100%;
            overflow: hidden;
        }

        .orders-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 24px;
        }

        .orders-header h1 {
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }

        .order-search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .order-search-input {
            width: 260px;
            height: 38px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .order-search-input:focus {
            border-color: #2563eb;
        }

        .order-search-btn {
            height: 38px;
            padding: 0 20px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .order-search-btn:hover {
            background: #1d4ed8;
        }

        .order-tabs {
            display: flex;
            gap: 8px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 32px;
            overflow-x: auto;
            width: 100%;
            max-width: 100%;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: thin;
        }

        .order-tabs a {
            padding: 10px 18px;
            color: #64748b;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            border-bottom: 2px solid transparent;
            white-space: nowrap;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .order-tabs a:hover {
            color: #0f172a;
        }

        .order-tabs a.active {
            color: #2563eb;
            font-weight: 700;
            border-bottom-color: #2563eb;
        }

        .order-card {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 24px;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
            overflow: hidden;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
            max-width: 100%;
        }

        .order-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .order-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 14px 24px;
            background: #f8fafc;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .order-meta-info {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            max-width: 100%;
        }

        .order-no-label {
            font-weight: 600;
            color: #0f172a;
            word-break: break-all;
            overflow-wrap: anywhere;
        }

        .order-date-label {
            color: #64748b;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
            text-transform: capitalize;
        }

        .status-badge.status-pending {
            background: #eff6ff;
            color: #2563eb;
            border: 1px solid #bfdbfe;
        }

        .status-badge.status-processing {
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        .status-badge.status-shipped {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .status-badge.status-completed {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .status-badge.status-cancelled {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .order-card-body {
            padding: 24px;
            display: grid;
            grid-template-columns: 120px 1fr auto;
            gap: 24px;
            align-items: center;
        }

        .order-img {
            width: 120px;
            height: 120px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #f8fafc;
            flex-shrink: 0;
        }

        .order-img img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 6px;
        }

        .no-img-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px;
            color: #94a3b8;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .order-main-info {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .order-category {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .order-product-name {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
        }

        .order-qty-price-row {
            display: flex;
            align-items: center;
            gap: 24px;
            margin-top: 4px;
            font-size: 15px;
        }

        .order-qty {
            color: #475569;
        }

        .order-item-price {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
        }

        .order-action-col {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
        }

        .details-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #2563eb;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .details-btn:hover {
            background: #f8fafc;
            border-color: #93c5fd;
            color: #1d4ed8;
        }

        .details-btn .arrow-icon {
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
            fill: currentColor;
        }

        .details-btn.is-open .arrow-icon {
            transform: rotate(180deg);
        }

        .order-details {
            display: none;
            padding: 20px 24px;
            background: #f8fafc;
            border-top: 1px solid #f1f5f9;
        }

        .order-details.is-open {
            display: block;
        }

        .option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 14px;
        }

        .option-card-item {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 14px;
        }

        .option-label {
            color: #64748b;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .option-value {
            color: #1e293b;
            font-size: 14px;
            font-weight: 500;
        }

        .empty-orders {
            color: #64748b;
            padding: 40px 0;
            text-align: center;
            font-size: 16px;
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

            .orders-card {
                padding: 20px 16px;
                min-height: auto;
                border-radius: 8px;
            }

            .orders-header {
                flex-direction: column;
                align-items: stretch;
                gap: 14px;
                margin-bottom: 20px;
            }

            .orders-header h1 {
                font-size: 24px;
            }

            .order-search-form {
                width: 100%;
            }

            .order-search-input {
                flex: 1;
                width: auto;
            }

            .order-tabs {
                margin-bottom: 20px;
                padding-bottom: 4px;
            }

            .order-tabs a {
                padding: 8px 14px;
                font-size: 14px;
            }

            .order-card-header {
                padding: 12px 16px;
                font-size: 13px;
            }

            .order-card-body {
                padding: 16px;
                grid-template-columns: 90px 1fr;
                gap: 16px;
                align-items: start;
            }

            .order-img {
                width: 90px;
                height: 90px;
                border-radius: 8px;
            }

            .order-product-name {
                font-size: 16px;
            }

            .order-qty-price-row {
                font-size: 14px;
                gap: 16px;
            }

            .order-item-price {
                font-size: 16px;
            }

            .order-action-col {
                grid-column: 1 / -1;
                align-items: stretch;
                width: 100%;
                margin-top: 6px;
            }

            .details-btn {
                width: 100%;
                justify-content: center;
                padding: 10px 16px;
            }

            .order-details {
                padding: 16px;
            }

            .option-grid {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }

        @media (max-width: 576px) {
            .order-card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }

            .order-meta-info {
                width: 100%;
                justify-content: space-between;
                gap: 10px;
            }

            .status-badge {
                align-self: flex-start;
            }

            .order-card-body {
                grid-template-columns: 74px 1fr;
                gap: 12px;
            }

            .order-img {
                width: 74px;
                height: 74px;
            }

            .order-product-name {
                font-size: 15px;
            }

            .order-qty-price-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="account-page">
        <div class="container">
            <div class="account-layout">

                @include('frontend.account.partials.sidebar', ['user' => $user])

                <main class="orders-card">
                    <div class="orders-header">
                        <h1>注文履歴</h1>

                        <form method="GET" action="{{ route('account.orders.index') }}" class="order-search-form">
                            <input type="text" name="search" value="{{ request('search') }}" class="order-search-input"
                                placeholder="注文を検索">

                            @if (request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif

                            <button type="submit" class="order-search-btn">
                                検索
                            </button>
                        </form>
                    </div>

                    @php
                        $tabs = [
                            'all' => 'すべての注文',
                            'pending' => '保留中',
                            'processing' => '処理中',
                            'shipped' => '発送済み',
                            'completed' => '完了',
                            'cancelled' => 'キャンセル',
                        ];

                        $currentStatus = request('status', 'all');
                    @endphp

                    <div class="order-tabs">
                        @foreach ($tabs as $tabKey => $tabLabel)
                            <a href="{{ route(
                                'account.orders.index',
                                array_filter([
                                    'status' => $tabKey === 'all' ? null : $tabKey,
                                    'search' => request('search'),
                                ]),
                            ) }}"
                                class="{{ $currentStatus === $tabKey || (!$currentStatus && $tabKey === 'all') ? 'active' : '' }}">
                                {{ $tabLabel }}
                            </a>
                        @endforeach
                    </div>

                    @forelse($orders as $order)
                        @foreach ($order->items as $item)
                            @php
                                $detailId = 'order-detail-' . $order->order_id . '-' . $item->order_item_id;

                                $rawImage =
                                    $item->product_image ??
                                    ($item->product?->main_image?->image_url ?? $item->product?->image);
                                $imagePath = ltrim(str_replace('\\', '/', (string) $rawImage), '/');
                                $imageUrl = null;

                                if (filled($imagePath)) {
                                    if (\Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://'])) {
                                        $imageUrl = $imagePath;
                                    } elseif (
                                        \Illuminate\Support\Str::startsWith($imagePath, ['storage/', 'assets/'])
                                    ) {
                                        $imageUrl = asset($imagePath);
                                    } else {
                                        $imageUrl = asset('storage/' . $imagePath);
                                    }
                                }

                                $qty = $item->qty ?? ($item->quantity ?? 0);
                                $itemTotal = $item->item_total ?? 0;

                                $statusRaw = strtolower($order->status ?? 'pending');
                                $statusMap = [
                                    'pending' => '保留中',
                                    'processing' => '処理中',
                                    'shipped' => '発送済み',
                                    'completed' => '完了',
                                    'cancelled' => 'キャンセル',
                                ];
                                $statusText = $statusMap[$statusRaw] ?? ucfirst($statusRaw);

                                $optionsGrouped = $item->optionDetails->groupBy('group_name_snapshot');
                            @endphp

                            <div class="order-card">
                                <div class="order-card-header">
                                    <div class="order-meta-info">
                                        <span class="order-no-label">注文番号 #{{ $order->order_no }}</span>
                                        <span class="order-date-label">注文日:
                                            {{ $order->created_at ? $order->created_at->format('Y/m/d') : '-' }}</span>
                                    </div>
                                    <div class="status-badge status-{{ $statusRaw }}">
                                        {{ $statusText }}
                                    </div>
                                </div>

                                <div class="order-card-body">
                                    <div class="order-img">
                                        @if ($imageUrl)
                                            <img src="{{ $imageUrl }}"
                                                alt="{{ $item->product_name_snapshot ?? $item->product_name }}"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="no-img-placeholder" style="display: none;">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.5">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                                    <path d="M21 15l-5-5L5 21" />
                                                </svg>
                                                <span>画像なし</span>
                                            </div>
                                        @else
                                            <div class="no-img-placeholder">
                                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.5">
                                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                                    <path d="M21 15l-5-5L5 21" />
                                                </svg>
                                                <span>画像なし</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="order-main-info">
                                        {{-- <div class="order-category">
                                        {{ $item->category_name_snapshot ?? 'Lanyard' }}
                                    </div> --}}

                                        <div class="order-product-name">
                                            {{ $item->product_name_snapshot ?? $item->product_name }}
                                        </div>

                                        <div class="order-qty-price-row">
                                            <span class="order-qty">数量: <strong>{{ number_format($qty) }}</strong></span>
                                            <span class="order-item-price">¥ {{ number_format($itemTotal, 2) }}</span>
                                        </div>
                                    </div>

                                    <div class="order-action-col">
                                        <button type="button" class="details-btn" data-target="#{{ $detailId }}">
                                            <span>詳細を見る</span>
                                            <svg class="arrow-icon" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="order-details" id="{{ $detailId }}">
                                    @if ($optionsGrouped->count() > 0)
                                        <div class="option-grid">
                                            @foreach ($optionsGrouped as $groupName => $options)
                                                <div class="option-card-item">
                                                    <div class="option-label">
                                                        {{ $groupName ?: 'Option' }}
                                                    </div>
                                                    <div class="option-value">
                                                        @foreach ($options as $option)
                                                            {{ $option->custom_value ?: $option->option_name_snapshot }}
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div style="color: #64748b; font-size: 14px;">
                                            オプション詳細はありません。
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @empty
                        <div class="empty-orders">
                            該当する注文はありません。
                        </div>
                    @endforelse

                    <div style="margin-top: 20px;">
                        {{ $orders->links() }}
                    </div>
                </main>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.details-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const target = document.querySelector(this.dataset.target);

                    if (!target) {
                        return;
                    }

                    target.classList.toggle('is-open');
                    this.classList.toggle('is-open');
                });
            });
        });
    </script>
@endsection
