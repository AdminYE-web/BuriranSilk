@extends('admin.layouts.app')

@section('title', 'Product Artwork Templates | Indigo Admin')

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

        .artwork-image {
            width: 120px;
            height: 70px;
            border-radius: 10px;
            border: 1px solid var(--border);
            object-fit: contain;
            background: #fff;
            padding: 4px;
        }

        .sort-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 28px;
            border-radius: 999px;
            background: var(--bg);
            border: 1px solid var(--border);
            font-size: 12px;
            font-weight: 600;
            color: var(--fg);
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

        .template-name {
            font-weight: 600;
            color: var(--fg-dark);
        }

        .template-sub {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: var(--muted);
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
    </style>
@endsection

@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">
                    {{ request()->cookie('dev') === '1' ? 'Product Artwork Templates' : '製品アートワークテンプレート' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') === '1' ? 'Manage artwork templates, preview images, sorting and status.' : '製品アートワークテンプレート、プレビュー画像、並べ替え、ステータスを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.dashboard') }}" class="btn-outline">
                    {{ request()->cookie('dev') === '1' ? 'Dashboard' : 'ダッシュボード' }}
                </a>

                <a href="{{ route('admin.product-artwork-templates.create') }}" class="btn-primary">
                    {{ request()->cookie('dev') === '1' ? '+ Add Artwork Template' : '+ アートワークテンプレートを追加' }}
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
                    <th>{{ request()->cookie('dev') === '1' ? 'Template' : 'テンプレート' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Product' : '製品' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Preview' : 'プレビュー' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Sort' : '並べ替え' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align:right;">{{ request()->cookie('dev') === '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td>
                            <div class="product-details">
                                <span class="template-name">
                                    {{ $template->template_name }}
                                </span>

                                <span class="template-sub">
                                    ID: {{ $template->template_id }}
                                </span>
                            </div>
                        </td>

                        <td>
                            {{ $template->product->product_name ?? '-' }}
                        </td>

                        <td>
                            @if ($template->image_path)
                                <img src="{{ asset('storage/' . $template->image_path) }}" class="artwork-image"
                                    alt="{{ $template->template_name }}">
                            @else
                                <span class="template-sub">No image</span>
                            @endif
                        </td>

                        <td>
                            <span class="sort-badge">
                                {{ $template->sort_order }}
                            </span>
                        </td>

                        <td>
                            @if ($template->is_active)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') === '1' ? 'Active' : 'アクティブ' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') === '1' ? 'Inactive' : '非アクティブ' }}</span>
                            @endif
                        </td>

                        <td style="text-align:right;">
                            <div class="action-btns" style="justify-content:flex-end;">
                                <a href="{{ route('admin.product-artwork-templates.edit', $template->template_id) }}"
                                    class="action-link">
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.product-artwork-templates.destroy', $template->template_id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="action-link delete"
                                        onclick="return confirm('Delete this template?')">
                                        {{ request()->cookie('dev') === '1' ? 'Delete' : '削除' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:32px;">
                            No artwork templates found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $templates->links() }}
        </div>
    </div>

@endsection
