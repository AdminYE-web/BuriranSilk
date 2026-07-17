@extends('admin.layouts.app')

@section('title', 'Product Price Rules | Indigo Admin')
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
            cursor: pointer;
            font-family: inherit;
            line-height: 1;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
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

        .action-link:hover {
            text-decoration: underline;
        }

        .action-link.delete {
            color: #dc2626;
        }

        .action-link.duplicate {
            color: #7c3aed;
        }

        .mini-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            margin: 2px 4px 2px 0;
            padding: 5px 9px;
            border-radius: 999px;
            background: var(--bg);
            border: 1px solid var(--border);
            font-size: 12px;
            color: var(--fg);
            white-space: nowrap;
        }

        .tier-line {
            font-size: 13px;
            line-height: 1.6;
            color: var(--fg);
            white-space: nowrap;
        }

        .muted-text {
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 900px) {
            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 900px;
            }

            .table-header {
                align-items: flex-start;
                gap: 14px;
                flex-direction: column;
            }

            .table-actions {
                width: 100%;
                flex-wrap: wrap;
            }
        }

        .rule-search-form {
            margin: 0 24px 18px;
        }

        .rule-search-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .rule-search-input {
            width: 420px;
            max-width: 100%;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            background: #fff;
        }

        .rule-search-btn,
        .rule-reset-btn {
            height: 38px;
            border-radius: 8px;
            padding: 0 16px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
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

        .pagination-container {
            padding: 18px 24px 24px;
            display: flex;
            justify-content: flex-end;
        }

        .pagination {
            display: flex;
            gap: 6px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .page-item .page-link,
        .page-link {
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--fg);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-size: 14px;
        }

        .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .page-item.disabled .page-link {
            opacity: .45;
            pointer-events: none;
        }

        .pagination .page-item:first-child,
        .pagination .page-item:last-child {
            display: none;
        }

        .selected-product-bar {
            margin: 0 24px 18px;
            padding: 14px 16px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .selected-product-info strong {
            display: block;
            color: var(--fg-dark);
            font-size: 15px;
            margin-bottom: 4px;
        }

        .selected-product-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
        }

        .back-products-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: auto;
            min-width: auto;
            height: 36px;
            padding: 0 14px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
        }

        .back-products-btn:hover {
            background: var(--bg);
        }

        @media (max-width: 700px) {
            .selected-product-bar {
                flex-direction: column;
                align-items: flex-start;
            }

            .selected-product-actions {
                width: 100%;
            }

            .back-products-btn {
                width: 100%;
            }
        }
    </style>
@endsection
@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Product Price Rules' : '価格設定ルール' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Manage pricing rules by product, required options and quantity tiers.' : '商品、必須オプション、数量ティアごとの価格ルールを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Dashboard' : 'ダッシュボード' }}
                </a>

                <a href="{{ route('admin.product-price-rules.create') }}" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? '+ Add Price Rule' : '+ 価格設定ルール追加' }}
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.product-price-rules.index') }}" class="rule-search-form">
            <div class="rule-search-row">
                @if ($selectedProduct)
                    <input type="hidden" name="product_id" value="{{ $selectedProduct->product_id }}">

                    <input type="text" name="search" value="{{ request('search') }}" class="rule-search-input"
                        placeholder="Search rules for this product...">
                @else
                    <input type="text" name="search" value="{{ request('search') }}" class="rule-search-input"
                        placeholder="Search by product name or product code...">
                @endif

                <button type="submit" class="rule-search-btn">
                    {{ request()->cookie('dev') == '1' ? 'Search' : '検索' }}
                </button>

                @if (request('search'))
                    @if ($selectedProduct)
                        <a href="{{ route('admin.product-price-rules.index', ['product_id' => $selectedProduct->product_id]) }}"
                            class="rule-reset-btn">
                            {{ request()->cookie('dev') == '1' ? 'Reset' : 'リセット' }}
                        </a>
                    @else
                        <a href="{{ route('admin.product-price-rules.index') }}" class="rule-reset-btn">
                            {{ request()->cookie('dev') == '1' ? 'Reset' : 'リセット' }}
                        </a>
                    @endif
                @endif
            </div>
        </form>
        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (!$selectedProduct)
            <table>
                <thead>
                    <tr>
                        <th>{{ request()->cookie('dev') == '1' ? 'Product' : '商品' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Type' : 'タイプ' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Category' : 'カテゴリ' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Material' : '素材' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Price Rules' : '価格設定ルール' }}</th>
                        <th style="text-align: right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                <div class="product-details">
                                    <span class="product-name">
                                        {{ $product->product_name }}
                                    </span>
                                    <span class="product-sku">
                                        ID: {{ $product->product_id }} | Code: {{ $product->product_code }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                @if ($product->product_type == 1)
                                    Hotstrap
                                @elseif ($product->product_type == 2)
                                    Hotmobily
                                @else
                                    -
                                @endif
                            </td>

                            <td>
                                {{ $product->category->category_name ?? '-' }}
                            </td>

                            <td>
                                {{ $product->material->material_name ?? '-' }}
                            </td>

                            <td>
                                <span class="mini-badge">
                                    {{ $product->price_rules_count }} rules
                                </span>
                            </td>

                            <td style="text-align: right;">
                                <div class="action-btns" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.product-price-rules.index', ['product_id' => $product->product_id]) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') == '1' ? 'View Rules' : 'ルール確認' }}
                                    </a>

                                    <a href="{{ route('admin.product-price-rules.create', ['product_id' => $product->product_id]) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') == '1' ? '+ Add Rule' : '+ ルール追加' }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 32px;">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="pagination-container">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="selected-product-bar">
                <div class="selected-product-info">
                    <strong>{{ $selectedProduct->product_name }}</strong>
                    <div class="muted-text">
                        ID: {{ $selectedProduct->product_id }} | Code: {{ $selectedProduct->product_code }}
                    </div>
                </div>

                <div class="selected-product-actions">
                    <a href="{{ route('admin.product-price-rules.index') }}" class="back-products-btn">
                        ← {{ request()->cookie('dev') == '1' ? 'Back to Products' : '商品一覧へ戻る' }}
                    </a>

                    <a href="{{ route('admin.product-price-rules.create', ['product_id' => $selectedProduct->product_id]) }}"
                        class="btn-primary">
                        + {{ request()->cookie('dev') == '1' ? 'Add Rule' : 'ルール追加' }}
                    </a>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>{{ request()->cookie('dev') == '1' ? 'Rule' : 'ルール' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Required Options' : '必須オプション' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Tiers' : 'ティア' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                        <th style="text-align: right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($rules as $rule)
                        <tr>
                            <td>
                                <div class="product-details">
                                    <span class="product-name">
                                        {{ $rule->rule_name ?? '-' }}
                                    </span>
                                    <span class="product-sku">
                                        ID: {{ $rule->rule_id }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                @forelse ($rule->options as $option)
                                    <div class="mini-badge">
                                        {{ $option->group->group_name ?? '-' }}
                                        /
                                        <strong>{{ $option->option_name }}</strong>
                                    </div>
                                @empty
                                    <span class="muted-text">No options</span>
                                @endforelse
                            </td>

                            <td>
                                @forelse ($rule->tiers as $tier)
                                    <div class="tier-line">
                                        {{ $tier->min_qty }}
                                        @if ($tier->max_qty)
                                            - {{ $tier->max_qty }}
                                        @else
                                            +
                                        @endif
                                        pcs : ¥{{ number_format($tier->unit_price, 2) }}
                                    </div>
                                @empty
                                    <span class="muted-text">No tiers</span>
                                @endforelse
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

                            <td style="text-align: right;">
                                <div class="action-btns" style="justify-content: flex-end;">
                                    <a href="{{ route('admin.product-price-rules.show', $rule->rule_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') == '1' ? 'Detail' : '詳細' }}
                                    </a>

                                    <a href="{{ route('admin.product-price-rules.edit', $rule->rule_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                    </a>

                                    <a href="{{ route('admin.product-price-rules.duplicate', $rule->rule_id) }}"
                                        class="action-link duplicate">
                                        {{ request()->cookie('dev') == '1' ? 'Duplicate' : '複製' }}
                                    </a>

                                    <form action="{{ route('admin.product-price-rules.destroy', $rule->rule_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-link delete"
                                            onclick="return confirm('{{ request()->cookie('dev') == '1' ? 'Delete this rule?' : 'このルールを削除しますか？' }}')">
                                            {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 32px;">
                                No price rules found for this product.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        @endif

        @if ($selectedProduct && $rules)
            <div class="pagination-container">
                {{ $rules->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>

@endsection
