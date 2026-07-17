@extends('admin.layouts.app')

@section('title', 'Option Dependencies | Indigo Admin')

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

        .dependency-text {
            line-height: 1.5;
            font-size: 14px;
        }

        .dependency-sub {
            display: block;
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 2px;
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

        .translation-missing-row {
            opacity: 0.45;
            background: #f8fafc;
        }

        .translation-missing-row .dependency-text strong::after {
            content: " Missing translation";
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

        .pagination-container {
            padding: 18px 24px 24px;
            display: flex;
            justify-content: flex-start;
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
    </style>
@endsection

@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Option Dependencies' : 'オプションの依存関係' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Manage conditional display rules between product options and option groups.' : '製品オプションとオプショングループ間の表示条件付きルールを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Dashboard' : 'ダッシュボード' }}
                </a>

                <a href="{{ route('admin.option-dependencies.create') }}" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? '+ Add Dependency' : '+ 依存関係を追加' }}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th>{{ request()->cookie('dev') == '1' ? 'Trigger Option' : 'トリガーオプション' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Target Type' : 'ターゲットタイプ' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Target' : 'ターゲット' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Sort' : 'ソート' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align: right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($dependencies as $dependency)
                    <tr class="{{ !empty($dependency->is_missing_translation) ? 'translation-missing-row' : '' }}">
                        <td>
                            <div class="dependency-text">
                                <span class="dependency-sub">
                                    ID: {{ $dependency->dependency_id }}
                                </span>

                                {{ $dependency->parentOption->group->group_name ?? '-' }}
                                /
                                <strong>{{ $dependency->parentOption->option_name ?? '-' }}</strong>
                            </div>
                        </td>

                        <td>
                            <span class="mini-badge">
                                {{ $dependency->target_type }}
                            </span>
                        </td>

                        <td>
                            <div class="dependency-text">
                                @if ($dependency->target_type === 'group')
                                    <span
                                        class="dependency-sub">{{ request()->cookie('dev') == '1' ? 'Group' : 'グループ' }}</span>
                                    <strong>{{ $dependency->targetGroup->group_name ?? '-' }}</strong>
                                @else
                                    <span
                                        class="dependency-sub">{{ request()->cookie('dev') == '1' ? 'Option' : 'オプション' }}</span>
                                    {{ $dependency->targetOption->group->group_name ?? '-' }}
                                    /
                                    <strong>{{ $dependency->targetOption->option_name ?? '-' }}</strong>
                                @endif
                            </div>
                        </td>

                        <td>{{ $dependency->sort_order }}</td>

                        <td>
                            @if ($dependency->is_active)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') == '1' ? 'Active' : '有効' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') == '1' ? 'Inactive' : '無効' }}</span>
                            @endif
                        </td>

                        <td style="text-align: right;">
                            <div class="action-btns" style="justify-content: flex-end;">
                                @if (!empty($dependency->is_missing_translation))
                                    <form
                                        action="{{ route('admin.option-dependencies.duplicate-translation', $dependency->dependency_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf

                                        <button type="submit" class="action-link duplicate"
                                            onclick="return confirm('Duplicate this PT dependency for {{ strtoupper($language) }}?')">
                                            {{ request()->cookie('dev') == '1' ? 'Duplicate' : '複製' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.option-dependencies.edit', $dependency->dependency_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                    </a>

                                    <form
                                        action="{{ route('admin.option-dependencies.destroy', $dependency->dependency_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-link delete"
                                            onclick="return confirm('Delete this dependency?')">
                                            {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 32px;">
                            No dependencies found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $dependencies->links() }}
        </div>
    </div>

@endsection
