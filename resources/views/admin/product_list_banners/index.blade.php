@extends('admin.layouts.app')

@section('title', 'Product List Banners | Indigo Admin')

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

    .banner-img {
        width: 120px;
        height: 68px;
        border-radius: 10px;
        border: 1px solid var(--border);
        object-fit: cover;
        background: var(--bg);
        display: block;
    }

    .banner-title {
        font-weight: 600;
        color: var(--fg-dark);
    }

    .banner-sub {
        display: block;
        margin-top: 4px;
        font-size: 12px;
        color: var(--muted);
        max-width: 260px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .link-text {
        display: inline-block;
        max-width: 180px;
        color: var(--accent);
        font-size: 13px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
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

    @media (max-width: 900px) {
        .table-card {
            overflow-x: auto;
        }

        table {
            min-width: 1100px;
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
            <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Product List Banners' : '商品一覧バナー' }}</div>
            <div class="showing-text">
                {{ request()->cookie('dev') == '1' ? 'Manage product listing banners, content, links, sort order and status.' : '商品一覧のバナー、コンテンツ、リンク、並べ替え順序、ステータスを管理します。' }}
            </div>
        </div>

        <div class="table-actions">
            <a href="{{ route('admin.product-list-banners.create') }}" class="btn-primary">
                + {{ request()->cookie('dev') == '1' ? 'Add Banner' : 'バナーを追加' }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Banner' : 'バナー' }}</th>
                <th>{{ request()->cookie('dev') == '1' ? 'Button' : 'ボタン' }}</th>
                <th>{{ request()->cookie('dev') == '1' ? 'Link' : 'リンク' }}</th>
                <th>{{ request()->cookie('dev') == '1' ? 'Sort' : '並び替え' }}</th>
                <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                <th style="text-align:right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}
            </tr>
        </thead>

        <tbody>
            @forelse($banners as $banner)
                <tr>
                    <td>
                        <div class="product-cell">
                            @if($banner->image_path)
                                <div>
                                    <img
                                        src="{{ asset('storage/' . $banner->image_path) }}"
                                        class="banner-img"
                                        alt="{{ $banner->title }}"
                                    >
                                    @if($banner->image_mobile)
                                        <span class="banner-sub">Mobile image uploaded</span>
                                    @endif
                                </div>
                            @else
                                <div class="banner-img"></div>
                            @endif

                            <div class="product-details">
                                <span class="banner-title">
                                    {{ $banner->title }}
                                </span>

                                <span class="banner-sub">
                                    ID: {{ $banner->banner_id }} | {{ $banner->subtitle ?? '-' }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td>
                        {{ $banner->button_text ?? '-' }}
                    </td>

                    <td>
                        @if($banner->link_url)
                            <span class="link-text">
                                {{ $banner->link_url }}
                            </span>
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        <span class="sort-badge">
                            {{ $banner->sort_order }}
                        </span>
                    </td>

                    <td>
                        @if($banner->is_active)
                            <span class="status-pill status-active">Active</span>
                        @else
                            <span class="status-pill status-inactive">Inactive</span>
                        @endif
                    </td>

                    <td style="text-align:right;">
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="{{ route('admin.product-list-banners.edit', $banner->banner_id) }}"
                               class="action-link">
                                {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                            </a>

                            <form action="{{ route('admin.product-list-banners.destroy', $banner->banner_id) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="action-link delete"
                                        onclick="return confirm('Delete this banner?')">
                                    {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:32px;">
                        No banners found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $banners->links() }}
    </div>
</div>

@endsection
