@extends('admin.layouts.app')

@section('title', 'Galleries | Indigo Admin')

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

        .gallery-search-form {
            margin: 0 24px 18px;
        }

        .gallery-search-row {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .gallery-search-input {
            width: 420px;
            max-width: 100%;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            background: #fff;
        }

        .gallery-search-btn,
        .gallery-reset-btn {
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

        .gallery-search-btn {
            border: 0;
            background: var(--accent);
            color: #fff;
            cursor: pointer;
        }

        .gallery-reset-btn {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
        }

        .gallery-cover {
            width: 74px;
            height: 58px;
            border-radius: 8px;
            border: 1px solid var(--border);
            object-fit: cover;
            background: var(--bg);
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
                <div class="table-title">{{ request()->cookie('dev') === '1' ? 'Galleries' : 'ギャラリー' }}</div>
                <div class="showing-text">
                    Manage
                    {{ request()->cookie('dev') === '1' ? 'gallery cover, images, category, material, purpose and date.' : 'ギャラリーのカバー、画像、カテゴリ、素材、目的、日付を管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.galleries.create') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? '+ Add Gallery' : '+ ギャラリーを追加' }}
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.galleries.index') }}" class="gallery-search-form">
            <div class="gallery-search-row">
                <input type="text" name="search" value="{{ request('search') }}" class="gallery-search-input"
                    placeholder="Search by title, purpose, category or material...">

                <button type="submit" class="gallery-search-btn">
                    {{ request()->cookie('dev') == '1' ? 'Search' : '検索' }}
                </button>

                @if (request('search'))
                    <a href="{{ route('admin.galleries.index') }}" class="gallery-reset-btn">
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
                    <th>{{ request()->cookie('dev') == '1' ? 'Cover' : '表紙' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Title' : 'タイトル' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Category' : 'カテゴリ' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Material' : '素材' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Purpose' : '目的' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Date' : '日付' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align:right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($galleries as $gallery)
                    <tr class="{{ !empty($gallery->is_missing_translation) ? 'translation-missing-row' : '' }}">
                        <td>
                            @if ($gallery->cover_image)
                                <img src="{{ asset('storage/' . $gallery->cover_image) }}" class="gallery-cover"
                                    alt="{{ $gallery->title }}">
                            @else
                                <div class="gallery-cover"></div>
                            @endif
                        </td>

                        <td>
                            <div class="product-details">
                                <span class="product-name">{{ $gallery->title }}</span>
                                <span class="product-sku">
                                    ID: {{ $gallery->gallery_id }} | Images: {{ $gallery->images->count() }}
                                </span>
                            </div>
                        </td>

                        <td>{{ $gallery->category->category_name ?? '-' }}</td>

                        <td>{{ $gallery->material->material_name ?? '-' }}</td>

                        <td>{{ Str::limit($gallery->purpose, 60) }}</td>

                        <td>
                            {{ $gallery->gallery_date ? $gallery->gallery_date->format('d/m/Y') : '-' }}
                        </td>

                        <td>
                            @if ($gallery->is_active)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') == '1' ? 'Active' : '有効' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') == '1' ? 'Inactive' : '無効' }}</span>
                            @endif
                        </td>

                        <td style="text-align:right;">
                            <div class="action-btns" style="justify-content:flex-end;">
                                @if (!empty($gallery->is_missing_translation))
                                    <form
                                        action="{{ route('admin.galleries.duplicate-translation', $gallery->gallery_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf

                                        <button type="submit" class="action-link duplicate"
                                            onclick="return confirm('Duplicate this PT gallery for {{ strtoupper($language) }}?')">
                                            {{ request()->cookie('dev') == '1' ? 'Duplicate' : '複製' }}
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.galleries.edit', $gallery->gallery_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                    </a>

                                    <form action="{{ route('admin.galleries.destroy', $gallery->gallery_id) }}"
                                        method="POST" style="display:inline;"
                                        onsubmit="return confirm('Delete this gallery?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-link delete">
                                            {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:32px;">
                            No galleries found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $galleries->links() }}
        </div>
    </div>

@endsection
