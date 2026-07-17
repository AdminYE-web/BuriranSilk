@extends('admin.layouts.app')

@section('title', 'Product Templates | Indigo Admin')

@section('css')
    <style>
        .template-card {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        .template-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 18px;
        }

        .template-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--fg-dark);
            margin: 0;
        }

        .template-subtitle {
            color: var(--muted);
            font-size: 14px;
            margin-top: 4px;
        }

        .template-filter {
            display: flex;
            gap: 12px;
            margin: 18px 0 22px;
            padding: 16px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--bg);
        }

        .template-filter input {
            width: 360px;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            outline: none;
            background: #fff;
        }

        .template-filter button,
        .template-filter a {
            height: 42px;
            min-width: 88px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            cursor: pointer;
            transition: .2s ease;
        }

        .template-filter button {
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #fff;
        }

        .template-filter button:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .template-filter a {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
        }

        .template-filter a:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: #f8fafc;
        }

        .template-table {
            width: 100%;
            border-collapse: collapse;
        }

        .template-table th {
            padding: 14px 16px;
            background: var(--bg);
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .template-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            vertical-align: middle;
        }

        .template-badge {
            display: inline-flex;
            padding: 5px 10px;
            border-radius: 999px;
            background: #e8f0ff;
            color: #17439a;
            font-size: 12px;
            font-weight: 700;
        }

        .template-file-link {
            color: var(--accent);
            font-weight: 700;
            text-decoration: none;
        }

        .template-file-link:hover {
            text-decoration: underline;
        }

        .template-actions {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .template-action-link {
            color: var(--accent);
            text-decoration: none;
            font-weight: 700;
            font-size: 14px;
        }

        .template-delete-btn {
            border: 0;
            background: transparent;
            color: #dc2626;
            font-weight: 700;
            cursor: pointer;
            padding: 0;
        }

        .alert-success {
            margin: 16px 0;
            padding: 12px 16px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            font-size: 14px;
        }
    </style>
@endsection

@section('content')

    <div class="table-card template-card">

        <div class="template-header">
            <div>
                <h1 class="template-title">{{ request()->cookie('dev') === '1' ? 'Product Templates' : '製品テンプレート' }}</h1>
                <div class="template-subtitle">
                    {{ request()->cookie('dev') === '1' ? 'Manage PDF / AI templates for each product.' : '各製品のPDF / AIテンプレートを管理します。' }}
                    Current language: {{ strtoupper($language) }}
                </div>
            </div>

            <a href="{{ route('admin.product-templates.create') }}" class="btn-primary">
                {{ request()->cookie('dev') === '1' ? '+ Add Template' : '+ 新規テンプレート' }}
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.product-templates.index') }}" class="template-filter">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="{{ request()->cookie('dev') === '1' ? 'Search product, size or file...' : '製品、サイズ、またはファイルを検索...' }}">

            <button type="submit" class="btn-primary">
                {{ request()->cookie('dev') === '1' ? 'Search' : '検索' }}
            </button>

            <a href="{{ route('admin.product-templates.index') }}" class="btn-outline">
                {{ request()->cookie('dev') === '1' ? 'Reset' : 'リセット' }}
            </a>
        </form>

        <table class="template-table">
            <thead>
                <tr>
                    <th>{{ request()->cookie('dev') === '1' ? 'Product' : '製品' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Size' : 'サイズ' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Language' : '言語' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'File' : 'ファイル' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Type' : 'タイプ' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Status' : 'ステータス' }}</th>
                    <th width="160">{{ request()->cookie('dev') === '1' ? 'Action' : 'アクション' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($templates as $template)
                    <tr>
                        <td>
                            <strong>{{ $template->product->product_name ?? '-' }}</strong>
                        </td>

                        <td>{{ $template->template_size ?: '-' }}</td>

                        <td>{{ strtoupper($template->language ?? 'pt') }}</td>

                        <td>
                            <a href="{{ asset('storage/' . $template->file_path) }}" target="_blank"
                                class="template-file-link">
                                {{ $template->original_name ?? 'View file' }}
                            </a>
                        </td>

                        <td>
                            <span class="template-badge">
                                {{ strtoupper($template->file_type) }}
                            </span>
                        </td>

                        <td>
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </td>

                        <td>
                            <div class="template-actions">
                                <a href="{{ route('admin.product-templates.edit', $template->template_id) }}"
                                    class="template-action-link">
                                    {{ request()->cookie('dev') === '1' ? 'Edit' : '編集' }}
                                </a>

                                <form action="{{ route('admin.product-templates.destroy', $template->template_id) }}"
                                    method="POST" onsubmit="return confirm('Delete this template?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="template-delete-btn">
                                        {{ request()->cookie('dev') === '1' ? 'Delete' : '削除' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:32px;">
                            No templates found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:18px;">
            {{ $templates->links() }}
        </div>

    </div>

@endsection
