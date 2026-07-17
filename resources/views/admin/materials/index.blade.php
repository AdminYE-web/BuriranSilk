@extends('admin.layouts.app')

@section('title', 'Materials | Indigo Admin')

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

        .material-code {
            display: inline-flex;
            align-items: center;
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
                min-width: 760px;
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

        .type-tabs {
            display: flex;
            gap: 8px;
            margin: 0 24px 18px;
            flex-wrap: wrap;
        }

        .type-tab {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 8px 16px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .type-tab:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .type-tab.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
    </style>
@endsection

@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Materials' : 'マテリアル' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Manage product materials, codes and active status.' : '製品マテリアル、コード、アクティブステータスを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.materials.create', ['product_type' => $productType]) }}" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? '+ Add Material' : '+ 追加マテリアル' }}
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="type-tabs">
            @foreach ($typeTabs as $typeValue => $typeLabel)
                <a href="{{ route('admin.materials.index', array_merge(request()->except('page'), ['product_type' => $typeValue])) }}"
                    class="type-tab {{ (int) $productType === (int) $typeValue ? 'active' : '' }}">
                    {{ $typeLabel }}
                </a>
            @endforeach
        </div>

        <table>
            <thead>
                <tr>
                    <th>{{ request()->cookie('dev') == '1' ? 'Material' : 'マテリアル' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Code' : 'コード' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align: right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($materials as $material)
                    <tr class="{{ !empty($material->is_missing_translation) ? 'translation-missing-row' : '' }}">
                        <td>
                            <div class="product-details">
                                <span class="product-name">
                                    {{ $material->material_name }}
                                </span>
                                <span class="product-sku">
                                    ID: {{ $material->material_id }}
                                </span>
                            </div>
                        </td>

                        <td>
                            <span class="material-code">
                                {{ $material->material_code }}
                            </span>
                        </td>

                        <td>
                            @if ($material->is_active)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') == '1' ? 'Inactive' : '非アクティブ' }}</span>
                            @endif
                        </td>

                        <td style="text-align: right;">
                            <div class="action-btns" style="justify-content: flex-end;">
                                @if (!empty($material->is_missing_translation))
                                    <form
                                        action="{{ route('admin.materials.duplicate-translation', $material->material_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf

                                        <button type="submit" class="action-link duplicate"
                                            onclick="return confirm('{{ request()->cookie('dev') == '1' ? 'Duplicate this PT material for ' . strtoupper($language) . '?' : '重複このPTマテリアル' }}')">
                                            {{ request()->cookie('dev') == '1' ? 'Duplicate' : '重複' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.materials.edit', $material->material_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                    </a>

                                    <form action="{{ route('admin.materials.destroy', $material->material_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-link delete"
                                            onclick="return confirm('{{ request()->cookie('dev') == '1' ? 'Delete this material?' : '削除このマテリアル' }}')">
                                            {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 32px;">
                            No materials found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $materials->links() }}
        </div>
    </div>

@endsection
