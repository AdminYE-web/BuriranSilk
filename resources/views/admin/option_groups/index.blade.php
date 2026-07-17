@extends('admin.layouts.app')

@section('title', 'Option Groups | Indigo Admin')
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
                min-width: 950px;
            }

            .table-header {
                align-items: flex-start;
                gap: 14px;
                flex-direction: column;
            }
        }

        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-top: 24px;
            padding-top: 18px;
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

        .pagination-container {
            padding: 16px 24px;
            border-top: 1px solid var(--border);
        }

        .pagination {
            display: flex;
            gap: 6px;
            margin: 0;
            padding: 0;
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

        .option-group-search-form {
            margin: 0 24px 18px;
        }

        .option-group-search-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .option-group-search-input {
            width: 360px;
            max-width: 100%;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            background: #fff;
        }

        .option-group-search-btn,
        .option-group-reset-btn {
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

        .option-group-search-btn {
            border: 0;
            background: var(--accent);
            color: #fff;
            cursor: pointer;
        }

        .option-group-reset-btn {
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

        .option-group-filter-select {
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

        @media (max-width: 900px) {

            .option-group-search-input,
            .option-group-filter-select {
                width: 100%;
                min-width: 100%;
            }

            .option-group-search-btn,
            .option-group-reset-btn {
                width: 100%;
            }
        }
    </style>
@endsection
@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Option Groups' : '商品オプショングループ管理' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') === '1'
                        ? 'Manage option groups, display type, parent group and status.'
                        : 'オプショングループ、表示形式、親グループ、ステータスを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.option-groups.create') }}" class="btn-primary">
                    +
                    {{ request()->cookie('dev') === '1' ? 'Add Option Group' : 'オプショングループを追加' }}
                </a>
            </div>
        </div>
        <form method="GET" action="{{ route('admin.option-groups.index') }}" class="option-group-search-form">
            <div class="option-group-search-row">
                <input type="text" name="search" value="{{ request('search') }}" class="option-group-search-input"
                    placeholder="Search by group name or group code...">

                <select name="type" class="option-group-filter-select">
                    <option value="">
                        {{ request()->cookie('dev') === '1' ? 'All Types' : 'すべてのタイプ' }}
                    </option>

                    <option value="1" {{ request('type') == '1' ? 'selected' : '' }}>
                        Hotstrap
                    </option>

                    <option value="2" {{ request('type') == '2' ? 'selected' : '' }}>
                        Hotmobily
                    </option>
                </select>

                <button type="submit" class="option-group-search-btn">
                    {{ request()->cookie('dev') === '1' ? 'Search' : '検索' }}
                </button>

                @if (request()->hasAny(['search', 'type']))
                    <a href="{{ route('admin.option-groups.index') }}" class="option-group-reset-btn">
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
                    <th>{{ request()->cookie('dev') === '1' ? 'Group' : 'グループ' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Parent Group' : '親グループ' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Display Type' : '表示タイプ' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Main Price' : 'メインプライス' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Sort' : '並び順' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Required' : '必須' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align: right;">{{ request()->cookie('dev') === '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($groups as $group)
                    <tr class="{{ !empty($group->is_missing_translation) ? 'translation-missing-row' : '' }}">
                        <td>
                            <div class="product-details">
                                <span class="product-name">
                                    {{ $group->group_name }}
                                </span>
                                <span class="product-sku">
                                    ID: {{ $group->option_group_id }} | Code: {{ $group->group_code }}
                                </span>
                            </div>
                        </td>

                        <td>{{ $group->parent->group_name ?? '-' }}</td>

                        <td>
                            <span class="mini-badge">
                                {{ $group->display_type }}
                            </span>
                        </td>

                        <td>
                            {{ $group->option_group_main ? 'Yes' : 'No' }}
                        </td>

                        <td>{{ $group->sort_order }}</td>

                        <td>
                            {{ $group->is_required ? 'Yes' : 'No' }}
                        </td>

                        <td>
                            @if ($group->is_active)
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
                                @if (!empty($group->is_missing_translation))
                                    <form
                                        action="{{ route('admin.option-groups.duplicate-translation', $group->option_group_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf

                                        <button type="submit" class="action-link duplicate"
                                            onclick="return confirm(
            '{{ request()->cookie('dev') === '1'
                ? 'Duplicate this PT option group for ' . strtoupper($language) . '?'
                : strtoupper($language) . ' 用にこのPTオプショングループを複製しますか？' }}'
        )">
                                            {{ request()->cookie('dev') === '1' ? 'Duplicate' : '複製' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.option-groups.edit', $group->option_group_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') === '1' ? 'Edit' : '編集' }}
                                    </a>

                                    <form action="{{ route('admin.option-groups.destroy', $group->option_group_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-link delete"
                                            onclick="return confirm('Delete this option group?')">
                                            {{ request()->cookie('dev') === '1' ? 'Delete' : '削除' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 32px;">
                            No option groups found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $groups->links() }}
        </div>
    </div>

@endsection
