@extends('admin.layouts.app')

@section('title', 'Product Options | Indigo Admin')

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

        .mini-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 5px 9px;
            border-radius: 999px;
            background: var(--bg);
            border: 1px solid var(--border);
            font-size: 12px;
            color: var(--fg);
            white-space: nowrap;
        }

        @media (max-width: 900px) {
            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 1000px;
            }

            .table-header {
                align-items: flex-start;
                gap: 14px;
                flex-direction: column;
            }
        }

        .pagination-container {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
        }

        .pagination {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .page-item {
            list-style: none;
        }

        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 34px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--fg);
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

        .pagination-container nav>div:first-child {
            display: none !important;
        }

        .pagination-container nav>div:last-child {
            display: block !important;
        }

        .pagination-container .pagination {
            margin-top: 8px;
        }

        .product-option-search-form {
            margin: 0 24px 18px;
        }

        .product-option-search-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .product-option-search-input {
            width: 420px;
            max-width: 100%;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            background: #fff;
        }

        .product-option-search-btn,
        .product-option-reset-btn {
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

        .product-option-search-btn {
            border: 0;
            background: var(--accent);
            color: #fff;
            cursor: pointer;
        }

        .product-option-reset-btn {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
        }

        .translation-missing-row {
            opacity: 0.45;
            background: #f8fafc;
        }

        .translation-missing-row .product-name::after {
            content: "Missing translation";
            display: inline-block;
            margin-left: 8px;
            padding: 2px 7px;
            border-radius: 999px;
            background: #fef3c7;
            color: #92400e;
            font-size: 11px;
            font-weight: 600;
        }

        .action-link.duplicate {
            color: #2563eb;
        }
    </style>
@endsection

@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') === '1' ? 'Product Options' : '商品オプション' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') === '1'
                        ? 'Manage option choices, prices, variants, images and status.'
                        : 'オプションの選択肢、価格、バリエーション、画像、ステータスを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.product-options.create') }}" class="btn-primary">
                    {{ request()->cookie('dev') === '1' ? '+ Add Product Option' : '+ 商品オプションを追加' }}
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.product-options.index') }}" class="product-option-search-form">
            <div class="product-option-search-row">
                <input type="text" name="search" value="{{ request('search') }}" class="product-option-search-input"
                    placeholder="{{ request()->cookie('dev') === '1' ? 'Search by option name, option code or group...' : 'オプション名、オプションコード、またはグループで検索...' }}">

                <button type="submit" class="product-option-search-btn">
                    {{ request()->cookie('dev') === '1' ? 'Search' : '検索' }}
                </button>

                @if (request('search'))
                    <a href="{{ route('admin.product-options.index') }}" class="product-option-reset-btn">
                        {{ request()->cookie('dev') === '1' ? 'Reset' : 'リセット' }}
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
                    <th>{{ request()->cookie('dev') === '1' ? 'Option' : 'オプション' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Group' : 'グループ' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Additional Price' : '追加料金' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Price Type' : '価格タイプ' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align: right;">
                        {{ request()->cookie('dev') === '1' ? 'Manage' : '管理' }}
                    </th>
                </tr>
            </thead>

            <tbody>
                @forelse ($options as $option)
                    <tr class="{{ !empty($option->is_missing_translation) ? 'translation-missing-row' : '' }}">
                        <td>
                            <div class="product-cell">
                                @if ($option->mainImage)
                                    <img src="{{ asset('storage/' . $option->mainImage->image_path) }}" class="product-img"
                                        alt="{{ $option->option_name }}">
                                @else
                                    <div class="product-img"></div>
                                @endif

                                <div class="product-details">
                                    <span class="product-name">
                                        {{ $option->option_name }}
                                    </span>
                                    <span class="product-sku">
                                        ID: {{ $option->option_id }} | Code: {{ $option->option_code }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td>
                            {{ $option->group->group_name ?? '-' }}
                        </td>

                        <td>
                            {{ number_format($option->additional_price, 2) }}
                        </td>

                        <td>
                            <span class="mini-badge">
                                {{ $option->price_type }}
                            </span>
                        </td>

                        <td>
                            @if ($option->is_active)
                                <span class="status-pill status-active">
                                    {{ request()->cookie('dev') === '1' ? 'Active' : '有効' }}
                                </span>
                            @else
                                <span class="status-pill status-inactive">
                                    {{ request()->cookie('dev') === '1' ? 'Inactive' : '無効' }}
                                </span>
                            @endif
                        </td>

                        <td style="text-align: right;">
                            <div class="action-btns" style="justify-content: flex-end;">
                                @if (!empty($option->is_missing_translation))
                                    <form
                                        action="{{ route('admin.product-options.duplicate-translation', $option->option_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf

                                        <button type="submit" class="action-link duplicate"
                                            onclick="return confirm('Duplicate this PT option for {{ strtoupper($language) }}?')">
                                            {{ request()->cookie('dev') === '1' ? 'Duplicate' : '複製' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.product-options.edit', $option->option_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') === '1' ? 'Edit' : '編集' }}
                                    </a>

                                    <a href="{{ route('admin.product-options.variants.index', $option->option_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') === '1' ? 'Variants' : 'バリエーション' }}
                                    </a>

                                    <form action="{{ route('admin.product-options.destroy', $option->option_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-link delete"
                                            onclick="return confirm('Delete this product option?')">
                                            {{ request()->cookie('dev') === '1' ? 'Delete' : '削除' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 32px;">
                            No product options found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $options->links() }}
        </div>
    </div>

@endsection
