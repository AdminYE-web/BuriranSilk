@extends('admin.layouts.app')

@section('title', 'Articles | Indigo Admin')

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .article-index-card {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        .article-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .article-title {
            margin: 0;
            font-size: 22px;
            font-weight: 800;
            color: var(--fg-dark);
        }

        .article-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 14px;
        }

        .article-filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0 22px;
            padding: 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .article-filter-bar input {
            width: 320px;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            background: #fff;
            font-size: 14px;
            outline: none;
        }

        .article-filter-bar input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .article-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        .article-table th {
            padding: 14px 16px;
            background: var(--bg);
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }

        .article-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            color: var(--fg-dark);
            font-size: 14px;
        }

        .article-thumb {
            width: 76px;
            height: 54px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
            background: var(--bg);
            display: block;
        }

        .article-no-image {
            width: 76px;
            height: 54px;
            border-radius: 8px;
            border: 1px dashed var(--border);
            background: var(--bg);
            color: var(--muted);
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .article-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .article-status.is-active {
            background: #ecfdf5;
            color: #047857;
        }

        .article-status.is-inactive {
            background: #fef2f2;
            color: #b91c1c;
        }

        .article-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .article-action-link {
            color: var(--accent);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .article-action-link:hover {
            text-decoration: underline;
        }

        .article-delete-btn {
            border: 0;
            background: transparent;
            color: #dc2626;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
            font-family: inherit;
        }

        .article-delete-btn:hover {
            text-decoration: underline;
        }

        .article-empty {
            text-align: center;
            padding: 38px 16px !important;
            color: var(--muted);
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

        @media (max-width: 900px) {
            .article-index-card {
                padding: 16px;
            }

            .article-header {
                flex-direction: column;
            }

            .article-filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .article-filter-bar input,
            .article-filter-bar .btn-primary,
            .article-filter-bar .btn-outline {
                width: 100%;
            }

            .article-table-wrap {
                overflow-x: auto;
            }

            .article-table {
                min-width: 850px;
            }
        }

        .article-filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0 22px;
            padding: 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .article-filter-bar input {
            width: 360px;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            background: #fff;
            font-size: 14px;
            outline: none;
        }

        .article-filter-bar input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .article-filter-bar .btn-search {
            height: 42px;
            min-width: 110px;
            border: 1px solid var(--accent);
            border-radius: 10px;
            background: var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            padding: 0 18px;
            transition: .2s ease;
        }

        .article-filter-bar .btn-search:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .article-filter-bar .btn-reset {
            height: 42px;
            min-width: 90px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            color: var(--fg);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 16px;
            transition: .2s ease;
        }

        .article-filter-bar .btn-reset:hover {
            background: #f8fafc;
            border-color: var(--accent);
            color: var(--accent);
        }

        .pagination-container {
            margin-top: 18px;
            padding-top: 18px;
            border-top: 1px solid var(--border);
        }

        .pagination-container nav>div:first-child {
            display: none !important;
        }

        .pagination-container nav>div:last-child {
            display: block !important;
        }

        .pagination-container nav>div:last-child>div:first-child {
            margin-bottom: 10px;
            color: var(--muted);
            font-size: 13px;
        }

        .pagination-container .pagination {
            display: flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .pagination-container .page-item {
            list-style: none;
        }

        .pagination-container .page-link {
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
            line-height: 1;
        }

        .pagination-container .page-link:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        .pagination-container .page-item.active .page-link {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .pagination-container .page-item.disabled .page-link {
            opacity: .45;
            pointer-events: none;
        }

        .pagination-container svg {
            width: 16px;
            height: 16px;
        }

        .translation-missing-row {
            opacity: 0.45;
            background: #f8fafc;
        }

        .translation-missing-row strong::after {
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

        .article-action-link.duplicate {
            color: #2563eb;
        }

        .article-filter-bar {
            flex-wrap: wrap;
        }

        .article-filter-bar select {
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            background: #fff;
            font-size: 14px;
            outline: none;
        }

        .article-filter-bar select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .article-filter-bar .filter-search {
            width: 260px;
        }

        .article-filter-bar .filter-select {
            width: 170px;
        }

        .article-filter-bar .filter-date {
            width: 150px;
        }

        .article-date-range {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .article-date-range span {
            color: var(--muted);
            font-size: 13px;
            font-weight: 600;
        }

        .article-status.is-draft {
            background: #fffbeb;
            color: #92400e;
        }

        @media (max-width: 900px) {

            .article-filter-bar .filter-search,
            .article-filter-bar .filter-select,
            .article-filter-bar .filter-date,
            .article-filter-bar .btn-search,
            .article-filter-bar .btn-reset {
                width: 100%;
            }

            .article-date-range {
                width: 100%;
                flex-direction: column;
                align-items: stretch;
            }

            .article-date-range span {
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')

    <div class="table-card article-index-card">

        <div class="article-header">
            <div>
                <h1 class="article-title">{{ request()->cookie('dev') === '1' ? 'Articles' : '記事' }}</h1>
                <div class="article-subtitle">
                    {{ request()->cookie('dev') === '1' ? 'Manage website articles.' : 'ウェブサイトの記事を管理します。' }}
                </div>
            </div>

            <a href="{{ route('admin.articles.create') }}" class="btn-primary">
                {{ request()->cookie('dev') == '1' ? '+ Add Article' : '+ 記事の追加' }}
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.articles.index') }}" class="article-filter-bar">
            <input type="text" name="search" class="filter-search" value="{{ request('search') }}"
                placeholder="{{ request()->cookie('dev') == '1' ? 'Search title or category...' : 'タイトルまたはカテゴリで検索...' }}">

            <select name="category" class="filter-select">
                <option value="">{{ request()->cookie('dev') == '1' ? 'All Categories' : 'すべてのカテゴリ' }}</option>

                @foreach ($categories as $cat)
                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                        {{ $cat }}
                    </option>
                @endforeach
            </select>

            <div class="article-date-range">
                <input type="text" name="date_from" class="filter-date js-date-picker"
                    value="{{ request('date_from') }}" placeholder="dd/mm/yyyy" autocomplete="off">

                <span>{{ request()->cookie('dev') == '1' ? 'to' : 'から' }}</span>

                <input type="text" name="date_to" class="filter-date js-date-picker" value="{{ request('date_to') }}"
                    placeholder="dd/mm/yyyy" autocomplete="off">
            </div>

            <select name="status" class="filter-select">
                <option value="">{{ request()->cookie('dev') == '1' ? 'All Status' : 'すべてのステータス' }}</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Public' : '公開' }}</option>
                <option value="3" {{ request('status') === '3' ? 'selected' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Draft' : 'ドラフト' }}</option>
            </select>

            <button type="submit" class="btn-search">
                {{ request()->cookie('dev') == '1' ? 'Search' : '検索' }}
            </button>

            <a href="{{ route('admin.articles.index') }}" class="btn-reset">
                {{ request()->cookie('dev') == '1' ? 'Reset' : 'リセット' }}
            </a>
        </form>

        <div class="article-table-wrap">
            <table class="article-table">
                <thead>
                    <tr>
                        <th width="140">{{ request()->cookie('dev') == '1' ? 'Cover' : 'カバー' }}</th>
                        <th>{{ request()->cookie('dev') == '1' ? 'Title' : 'タイトル' }}</th>
                        <th width="180">{{ request()->cookie('dev') == '1' ? 'Category' : 'カテゴリ' }}</th>
                        <th width="150">{{ request()->cookie('dev') == '1' ? 'Date' : '日付' }}</th>
                        <th width="130">{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                        <th width="160">{{ request()->cookie('dev') == '1' ? 'Action' : 'アクション' }}</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($articles as $article)
                        <tr class="{{ !empty($article->is_missing_translation) ? 'translation-missing-row' : '' }}">
                            <td>
                                @if ($article->cover_image)
                                    <img src="{{ asset('storage/' . $article->cover_image) }}" alt="{{ $article->title }}"
                                        class="article-thumb">
                                @else
                                    <div class="article-no-image">No image</div>
                                @endif
                            </td>

                            <td>
                                <strong>{{ $article->title }}</strong>
                            </td>

                            <td>
                                {{ $article->category ?? '-' }}
                            </td>

                            <td>
                                {{ $article->article_date ? \Carbon\Carbon::parse($article->article_date)->format('d/m/Y') : '-' }}
                            </td>

                            <td>
                                @if ((int) $article->is_active === 1)
                                    <span
                                        class="article-status is-active">{{ request()->cookie('dev') == '1' ? 'Public' : '公開' }}</span>
                                @elseif((int) $article->is_active === 3)
                                    <span
                                        class="article-status is-draft">{{ request()->cookie('dev') == '1' ? 'Draft' : 'ドラフト' }}</span>
                                @else
                                    <span
                                        class="article-status is-inactive">{{ request()->cookie('dev') == '1' ? 'Inactive' : '非公開' }}</span>
                                @endif
                            </td>

                            <td>
                                <div class="article-actions">
                                    @if (!empty($article->is_missing_translation))
                                        <form
                                            action="{{ route('admin.articles.duplicate-translation', $article->article_id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf

                                            <button type="submit" class="article-action-link duplicate"
                                                style="border:0;background:transparent;padding:0;cursor:pointer;"
                                                onclick="return confirm('Duplicate this PT article for {{ strtoupper($language) }}?')">
                                                {{ request()->cookie('dev') == '1' ? 'Duplicate' : '複製' }}
                                            </button>
                                        </form>
                                        <a href="{{ route('admin.articles.preview', $article->article_id) }}"
                                            class="article-action-link" target="_blank">
                                            {{ request()->cookie('dev') == '1' ? 'Preview' : 'プレビュー' }}
                                        </a>
                                    @else
                                        <a href="{{ route('admin.articles.preview', $article->article_id) }}"
                                            class="article-action-link" target="_blank">
                                            {{ request()->cookie('dev') == '1' ? 'Preview' : 'プレビュー' }}
                                        </a>
                                        <a href="{{ route('admin.articles.edit', $article->article_id) }}"
                                            class="article-action-link">
                                            {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                        </a>

                                        <form action="{{ route('admin.articles.destroy', $article->article_id) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ request()->cookie('dev') == '1' ? 'Delete this article?' : 'この記事を削除しますか？' }}')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="article-delete-btn">
                                                {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="article-empty">
                                No articles found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-container">
            {{ $articles->links('pagination::bootstrap-5') }}
        </div>

    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr('.js-date-picker', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true,
            locale: 'en'
        });
    </script>
@endsection
