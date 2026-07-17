@extends('admin.layouts.app')

@section('title', 'Option Price Rules | Indigo Admin')

@section('css')
    <style>
        .alert-success {
            margin: 0 24px 16px;
            padding: 12px 16px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            font-size: 14px;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 9px 18px;
            border-radius: 8px;
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .rule-search-form {
            margin: 0 24px 18px;
        }

        .rule-search-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: center;
        }

        .rule-search-input,
        .rule-filter-select {
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            background: #fff;
            font-size: 14px;
        }

        .rule-search-input {
            width: 300px;
        }

        .rule-filter-select {
            min-width: 180px;
        }

        .rule-search-btn,
        .rule-reset-btn {
            height: 38px;
            border-radius: 8px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .rule-search-btn {
            border: 0;
            background: var(--accent);
            color: #fff;
            cursor: pointer;
        }

        .rule-reset-btn {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
        }

        .mini-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            border-radius: 999px;
            background: var(--bg);
            border: 1px solid var(--border);
            color: var(--fg);
            font-size: 12px;
            margin: 2px;
        }

        .tier-line {
            font-size: 12px;
            color: var(--fg);
            margin-bottom: 3px;
        }

        .action-link {
            border: none;
            background: none;
            color: var(--accent);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
        }

        .action-link.delete {
            color: #dc2626;
        }

        .pagination-container {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
        }

        @media (max-width: 900px) {

            .rule-search-input,
            .rule-filter-select,
            .rule-search-btn,
            .rule-reset-btn {
                width: 100%;
            }

            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 1000px;
            }
        }

        .action-link.duplicate {
            color: #7c3aed;
        }

        .pagination-container {
            padding: 18px 24px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .pagination-info {
            color: #64748b;
            font-size: 13px;
        }

        .custom-pagination {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .custom-pagination li {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .custom-pagination a,
        .custom-pagination span {
            min-width: 36px;
            height: 36px;
            padding: 0 11px;
            border: 1px solid var(--border);
            border-radius: 7px;
            background: #fff;
            color: var(--fg);
            font-size: 13px;
            font-weight: 500;
            line-height: 1;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .custom-pagination a:hover {
            border-color: var(--accent);
            background: var(--accent);
            color: #fff;
        }

        .custom-pagination .active span {
            border-color: var(--accent);
            background: var(--accent);
            color: #fff;
            font-weight: 700;
        }

        .custom-pagination .disabled span {
            background: #f8fafc;
            color: #cbd5e1;
            cursor: not-allowed;
        }

        .custom-pagination .pagination-dots span {
            min-width: 28px;
            padding: 0 4px;
            border-color: transparent;
            background: transparent;
        }

        @media (max-width: 600px) {
            .pagination-container {
                justify-content: center;
                padding: 16px;
            }

            .pagination-info {
                width: 100%;
                text-align: center;
            }

            .custom-pagination {
                justify-content: center;
                flex-wrap: wrap;
            }

            .custom-pagination a,
            .custom-pagination span {
                min-width: 34px;
                height: 34px;
                padding: 0 9px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Option Price Rules' : 'オプション価格ルール' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Manage additional option prices that will be added to product price rules.' : '商品価格ルールに追加されるオプション価格を管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.option-price-rules.create') }}" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? '+ Add Option Price Rule' : '+ オプション価格ルールを追加' }}
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.option-price-rules.index') }}" class="rule-search-form">
            <div class="rule-search-row">
                <input type="text" name="search" value="{{ request('search') }}" class="rule-search-input"
                    placeholder="Search rule, product or option...">

                <select name="type" class="rule-filter-select">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Types' : '全種類' }}</option>
                    <option value="1" {{ request('type') === '1' ? 'selected' : '' }}>Hotstrap</option>
                    <option value="2" {{ request('type') === '2' ? 'selected' : '' }}>Hotmobily</option>
                </select>

                <select name="product_id" class="rule-filter-select">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Products' : '全商品' }}</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->product_id }}"
                            {{ request('product_id') == $product->product_id ? 'selected' : '' }}>
                            {{ $product->product_name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="rule-search-btn">
                    {{ request()->cookie('dev') == '1' ? 'Search' : '検索' }}
                </button>

                @if (request()->hasAny(['search', 'type', 'product_id']))
                    <a href="{{ route('admin.option-price-rules.index') }}" class="rule-reset-btn">
                        {{ request()->cookie('dev') == '1' ? 'Reset' : 'リセット' }}
                    </a>
                @endif
            </div>
        </form>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>{{ request()->cookie('dev') == '1' ? 'Product' : '商品' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Rule Name' : 'ルール名' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Required Options' : '必要なオプション' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Additional Price Tiers' : '追加価格ティア' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : '状態' }}</th>
                    <th style="text-align:right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($rules as $rule)
                    <tr>
                        <td>
                            <div class="product-details">
                                <span class="product-name">
                                    {{ $rule->product->product_name ?? '-' }}
                                </span>
                                <span class="product-sku">
                                    ID: {{ $rule->product->product_id ?? '-' }}
                                    | Code: {{ $rule->product->product_code ?? '-' }}
                                    | Type: {{ $rule->product->product_type ?? '-' }}
                                </span>
                            </div>
                        </td>

                        <td>{{ $rule->rule_name }}</td>

                        <td>
                            @foreach ($rule->options as $option)
                                <span class="mini-badge">
                                    {{ $option->group->group_name ?? '-' }}: {{ $option->option_name }}
                                </span>
                            @endforeach
                        </td>

                        <td>
                            @foreach ($rule->tiers->sortBy('min_qty') as $tier)
                                <div class="tier-line">
                                    {{ $tier->min_qty }}
                                    -
                                    {{ $tier->max_qty ?: '∞' }}
                                    pcs:
                                    +{{ number_format($tier->additional_price, 2) }}
                                </div>
                            @endforeach
                        </td>

                        <td>
                            @if ($rule->is_active)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') == '1' ? 'Active' : '有効' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') == '1' ? 'Inactive' : '無効' }}</span>
                            @endif
                        </td>

                        <td style="text-align:right;">
                            <div class="action-btns" style="justify-content:flex-end;">
                                <a href="{{ route('admin.option-price-rules.edit', $rule->option_price_rule_id) }}"
                                    class="action-link">
                                    {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                </a>

                                <a href="{{ route('admin.option-price-rules.duplicate', $rule->option_price_rule_id) }}"
                                    class="action-link duplicate">
                                    {{ request()->cookie('dev') == '1' ? 'Duplicate' : '複製' }}
                                </a>


                                <form action="{{ route('admin.option-price-rules.destroy', $rule->option_price_rule_id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="action-link delete"
                                        onclick="return confirm('{{ request()->cookie('dev') == '1' ? 'Delete this option price rule?' : 'このオプション価格ルールを削除しますか？' }}')">
                                        {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:32px;">
                            No option price rules found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($rules->hasPages())
            @php
                // เก็บค่า Search และ Filter ไว้เมื่อเปลี่ยนหน้า
                $rules->appends(request()->except('page'));

                $currentPage = $rules->currentPage();
                $lastPage = $rules->lastPage();

                // แสดงเลขหน้ารอบหน้าปัจจุบัน
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
            @endphp

            <div class="pagination-container">
                <div class="pagination-info">
                    Showing
                    <strong>{{ $rules->firstItem() }}</strong>
                    to
                    <strong>{{ $rules->lastItem() }}</strong>
                    of
                    <strong>{{ $rules->total() }}</strong>
                    results
                </div>

                <ul class="custom-pagination">
                    {{-- Previous --}}
                    @if ($rules->onFirstPage())
                        <li class="disabled">
                            <span>‹ Previous</span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $rules->previousPageUrl() }}" rel="prev">
                                ‹ Previous
                            </a>
                        </li>
                    @endif

                    {{-- หน้าแรก --}}
                    @if ($startPage > 1)
                        <li>
                            <a href="{{ $rules->url(1) }}">1</a>
                        </li>

                        @if ($startPage > 2)
                            <li class="pagination-dots">
                                <span>…</span>
                            </li>
                        @endif
                    @endif

                    {{-- เลขหน้า --}}
                    @for ($page = $startPage; $page <= $endPage; $page++)
                        @if ($page === $currentPage)
                            <li class="active">
                                <span>{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $rules->url($page) }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endfor

                    {{-- หน้าสุดท้าย --}}
                    @if ($endPage < $lastPage)
                        @if ($endPage < $lastPage - 1)
                            <li class="pagination-dots">
                                <span>…</span>
                            </li>
                        @endif

                        <li>
                            <a href="{{ $rules->url($lastPage) }}">
                                {{ $lastPage }}
                            </a>
                        </li>
                    @endif

                    {{-- Next --}}
                    @if ($rules->hasMorePages())
                        <li>
                            <a href="{{ $rules->nextPageUrl() }}" rel="next">
                                Next ›
                            </a>
                        </li>
                    @else
                        <li class="disabled">
                            <span>Next ›</span>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
@endsection
