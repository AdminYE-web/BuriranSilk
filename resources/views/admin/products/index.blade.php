@extends('admin.layouts.app')

@section('title', 'Products | Indigo Admin')

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

        .status-alert-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 6px;
            background: #fff1f2;
            color: #e11d48;
            border: 1px solid #ffe4e6;
            text-transform: uppercase;
        }

        .lang-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .lang-pt {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }

        .lang-jp {
            background: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
        }

        .lang-en {
            background: #e2e8f0;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .product-search-form {
            padding: 0 24px 18px;
        }

        .product-search-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .product-search-input {
            width: 360px;
            max-width: 100%;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            outline: none;
        }

        .product-filter-select {
            min-width: 150px;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 10px;
            background: #fff;
            color: var(--fg);
            font-size: 14px;
            outline: none;
        }

        .product-search-btn,
        .product-reset-btn {
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

        .product-search-btn {
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #fff;
            cursor: pointer;
        }

        .product-reset-btn {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
        }

        .product-search-btn:hover {
            background: var(--accent-hover);
        }

        .product-reset-btn:hover {
            background: var(--bg);
        }

        @media (max-width: 900px) {

            .product-search-input,
            .product-filter-select {
                width: 100%;
                min-width: 100%;
            }

            .product-search-btn,
            .product-reset-btn {
                width: 100%;
            }
        }

        .action-btns {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: nowrap;
        }

        .action-link {
            background: none;
            border: 0;
            padding: 0;
            color: #4f46e5;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }

        .action-link:hover {
            text-decoration: underline;
        }

        .action-link.delete,
        .action-menu .delete {
            color: red;
        }

        .action-dropdown {
            position: relative;
            display: inline-block;
        }

        .more-btn {
            color: #4f46e5;
        }

        .action-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 22px;
            min-width: 150px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            z-index: 20;
            padding: 6px 0;
            text-align: left;
        }

        .action-dropdown {
            position: relative;
            display: inline-block;
        }

        .action-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 24px;
            min-width: 150px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            z-index: 999;
            padding: 6px 0;
            text-align: left;
        }

        .action-dropdown.open .action-menu {
            display: block;
        }

        .action-menu a,
        .action-menu button {
            display: block;
            width: 100%;
            padding: 8px 12px;
            font-size: 13px;
            color: #374151;
            background: none;
            border: 0;
            text-align: left;
            text-decoration: none;
            cursor: pointer;
        }

        .action-menu a:hover,
        .action-menu button:hover {
            background: #f3f4f6;
        }

        .action-menu .delete {
            color: red;
        }

        .action-menu a,
        .action-menu button {
            display: block;
            width: 100%;
            padding: 8px 12px;
            font-size: 13px;
            color: #374151;
            background: none;
            border: 0;
            text-align: left;
            text-decoration: none;
            cursor: pointer;
        }

        .action-menu a:hover,
        .action-menu button:hover {
            background: #f3f4f6;
        }

        .table-card {
            overflow: visible;
        }

        table,
        tbody,
        tr,
        td {
            overflow: visible;
        }

        td {
            position: relative;
        }

        .pagination-container {
            padding: 16px 24px 24px;
        }

        .pagination-container ul,
        .pagination-container li {
            list-style: none !important;
            margin: 0;
            padding: 0;
        }

        .pagination-container nav>div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pagination-container nav ul {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pagination-container nav a,
        .pagination-container nav span {
            text-decoration: none;
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-top: 24px;
            padding: 16px 24px;
            border-top: 1px solid var(--border);
            flex-wrap: wrap;
        }

        .pagination-container nav {
            width: 100%;
        }

        .pagination-container .hidden {
            display: none !important;
        }

        .pagination-container svg {
            width: 16px !important;
            height: 16px !important;
        }

        .pagination-container nav>div:first-child {
            display: none;
        }

        .pagination-container nav>div:last-child {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        .pagination-container p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .pagination-container .relative.z-0 {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .pagination-container .relative.inline-flex {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
            text-decoration: none;
            font-size: 14px;
            transition: 0.2s;
        }

        .pagination-container .relative.inline-flex:hover {
            background: var(--bg);
        }

        .pagination-container span[aria-current="page"] span {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .pagination-container .cursor-default {
            opacity: .5;
            pointer-events: none;
        }

        /* กัน bullet จุดดำ */
        .pagination-container ul,
        .pagination-container li {
            list-style: none !important;
            margin: 0;
            padding: 0;
        }

        .pagination-container {
            margin-top: 24px;
            padding: 16px 24px;
            border-top: 1px solid var(--border);
        }

        .pagination-container nav {
            width: 100%;
        }

        .pagination-container nav>div {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .pagination-container p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        /* สำหรับ Bootstrap pagination */
        .pagination-container .pagination {
            display: flex !important;
            align-items: center;
            justify-content: flex-end;
            gap: 6px;
            margin: 0;
            padding: 0;
            list-style: none !important;
        }

        .pagination-container .page-item {
            list-style: none !important;
            margin: 0;
            padding: 0;
        }

        .pagination-container .page-link {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            padding: 0 12px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: var(--fg);
            font-size: 14px;
            font-weight: 500;
            line-height: 1;
            text-decoration: none !important;
        }

        .pagination-container .page-link:hover {
            background: var(--bg);
        }

        .pagination-container .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .pagination-container .page-item.disabled .page-link {
            color: #cbd5e1;
            background: #fff;
            opacity: 1;
            pointer-events: none;
        }

        /* กัน bullet จุดดำ */
        .pagination-container ul,
        .pagination-container li {
            list-style: none !important;
        }
    </style>
@endsection
@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Products' : '製品' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Manage product data, options, details, images and status.' : '製品データ、オプション、詳細、画像、ステータスを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.products.create') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? '+ Add Product' : '+ 製品追加' }}
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.products.index') }}" class="product-search-form">
            <div class="product-search-row">
                <input type="text" name="search" value="{{ request('search') }}" class="product-search-input"
                    placeholder="{{ request()->cookie('dev') == '1' ? 'Search by product name or product code...' : '製品名または製品コードで検索...' }}">

                <select name="type" class="product-filter-select">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Types' : '全てのタイプ' }}</option>
                    <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>
                        Hotstrap
                    </option>
                    <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>
                        Hotmobily
                    </option>
                </select>

                <select name="category_id" class="product-filter-select">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Categories' : '全てのカテゴリ' }}</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->category_id }}"
                            {{ request('category_id') == $category->category_id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>

                <select name="material_id" class="product-filter-select">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Materials' : '全ての素材' }}</option>
                    @foreach ($materials as $material)
                        <option value="{{ $material->material_id }}"
                            {{ request('material_id') == $material->material_id ? 'selected' : '' }}>
                            {{ $material->material_name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="product-filter-select">
                    <option value="">{{ request()->cookie('dev') == '1' ? 'All Status' : 'すべてのステータス' }}</option>

                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                        {{ request()->cookie('dev') == '1' ? 'Public' : '公開' }}
                    </option>

                    <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>
                        {{ request()->cookie('dev') == '1' ? 'Draft' : 'ドラフト' }}
                    </option>

                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                        {{ request()->cookie('dev') == '1' ? 'Inactive' : '非公開' }}
                    </option>
                </select>

                <button type="submit" class="product-search-btn">
                    {{ request()->cookie('dev') == '1' ? 'Search' : '検索' }}
                </button>

                @if (request()->hasAny(['search', 'type', 'category_id', 'material_id', 'status']))
                    <a href="{{ route('admin.products.index') }}" class="product-reset-btn">
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
                    {{-- <th>{{ request()->cookie('dev') == '1' ? 'Status Alert' : 'ステータスアラート' }}</th> --}}
                    <th>{{ request()->cookie('dev') == '1' ? 'Product' : '製品' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Type' : 'タイプ' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Category' : 'カテゴリ' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Material' : 'マテリアル' }}</th>

                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                    {{-- <th>{{ request()->cookie('dev') == '1' ? 'Lang' : '言語' }}</th> --}}
                    <th style="text-align: right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                    <tr class="{{ !empty($product->is_missing_translation) ? 'translation-missing-row' : '' }}">
                        {{-- <td>
                            @if (!empty($product->is_missing_translation))
                                <span class="status-alert-badge">missing
                                    {{ strtolower($language) === 'ja' ? 'jp' : strtolower($language) }}</span>
                            @else
                                <span style="color: #cbd5e1;">-</span>
                            @endif
                        </td> --}}
                        <td>
                            <div class="product-cell">
                                @if ($product->mainImage)
                                    <img src="{{ asset('storage/' . $product->mainImage->image_path) }}"
                                        class="product-img" alt="{{ $product->product_name }}">
                                @else
                                    <div class="product-img"></div>
                                @endif

                                <div class="product-details">
                                    <span class="product-name">{{ $product->product_name }}</span>
                                    <span class="product-sku">
                                        ID: {{ $product->product_id }} | Code: {{ $product->product_code }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if ($product->product_type == 1)
                                hotstrap
                            @elseif ($product->product_type == 2)
                                hotmobily
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            <span class="category-text">
                                {{ $product->category->category_name ?? '-' }}
                            </span>
                        </td>

                        <td>
                            {{ $product->material->material_name ?? '-' }}
                        </td>



                        <td>
                            @if ((int) $product->is_active === 1)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') == '1' ? 'Public' : '公開' }}</span>
                            @elseif ((int) $product->is_active === 3)
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') == '1' ? 'Draft' : 'ドラフト' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') == '1' ? 'Inactive' : '非公開' }}</span>
                            @endif
                        </td>
                        {{-- <td>
                            <div style="display: flex; gap: 6px; align-items: center;">
                                @foreach (explode(' ', $product->all_languages) as $lang)
                                    <span class="lang-badge lang-{{ strtolower($lang) }}">{{ strtoupper($lang) }}</span>
                                @endforeach
                            </div>
                        </td> --}}

                        <td style="text-align: right;">
                            <div class="action-btns" style="justify-content: flex-end;">
                                @if (!empty($product->is_missing_translation))
                                    <form
                                        action="{{ route('admin.products.duplicate-translation', $product->product_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf

                                        <button type="submit" class="action-link duplicate"
                                            onclick="return confirm('Duplicate this {{ strtoupper($product->language) }} product for {{ strtoupper($language) }}?')">
                                            {{ request()->cookie('dev') == '1' ? 'Add language' : '言語を追加' }}
                                        </button>
                                    </form>
                                @else
                                    {{-- Preview Dropdown --}}
                                    <div class="action-dropdown">
                                        <button type="button" class="action-link dropdown-btn">
                                            {{ request()->cookie('dev') == '1' ? 'Preview ▾' : 'プレビュー ▾' }}
                                        </button>

                                        <div class="action-menu">
                                            <a href="{{ route('admin.products.preview', $product->product_id) }}"
                                                target="_blank">
                                                {{ request()->cookie('dev') == '1' ? 'Product Preview' : '製品プレビュー' }}
                                            </a>

                                            <a href="{{ route('admin.products.preview-order', $product->product_id) }}"
                                                target="_blank">
                                                {{ request()->cookie('dev') == '1' ? 'Order Preview' : '注文プレビュー' }}
                                            </a>
                                        </div>
                                    </div>

                                    {{-- More Dropdown --}}
                                    <div class="action-dropdown">
                                        <button type="button" class="action-link dropdown-btn">
                                            {{ request()->cookie('dev') == '1' ? 'More ▾' : 'その他 ▾' }}
                                        </button>

                                        <div class="action-menu">
                                            <a href="{{ route('admin.products.edit', $product->product_id) }}"
                                                class="action-link">
                                                {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                            </a>

                                            @if ($product->detail)
                                                <a href="{{ route('admin.product-details.edit', $product->detail->product_detail_id) }}"
                                                    class="action-link">
                                                    {{ request()->cookie('dev') == '1' ? 'Detail' : '詳細' }}
                                                </a>
                                            @else
                                                <a href="{{ route('admin.product-details.create', ['product_id' => $product->product_id]) }}"
                                                    class="action-link">
                                                    {{ request()->cookie('dev') == '1' ? 'Add Detail' : '詳細を追加' }}
                                                </a>
                                            @endif

                                            <a href="{{ route('admin.products.options.edit', $product->product_id) }}">
                                                {{ request()->cookie('dev') == '1' ? 'Options' : 'オプション' }}
                                            </a>

                                            <form action="{{ route('admin.products.duplicate', $product->product_id) }}"
                                                method="POST">
                                                @csrf

                                                <button type="submit" onclick="return confirm('Duplicate this product?')">
                                                    {{ request()->cookie('dev') == '1' ? 'Duplicate' : '複製' }}
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.products.destroy', $product->product_id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="delete"
                                                    onclick="return confirm('Delete this product?')">
                                                    {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 32px;">
                            No products found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
    <script>
        document.addEventListener('click', function(e) {
            const dropdownBtn = e.target.closest('.dropdown-btn');

            if (dropdownBtn) {
                e.preventDefault();

                const dropdown = dropdownBtn.closest('.action-dropdown');

                document.querySelectorAll('.action-dropdown.open').forEach(function(item) {
                    if (item !== dropdown) {
                        item.classList.remove('open');
                    }
                });

                dropdown.classList.toggle('open');
                return;
            }

            if (!e.target.closest('.action-dropdown')) {
                document.querySelectorAll('.action-dropdown.open').forEach(function(item) {
                    item.classList.remove('open');
                });
            }
        });
    </script>
@endsection
