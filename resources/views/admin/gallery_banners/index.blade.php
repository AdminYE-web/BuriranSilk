@extends('admin.layouts.app')

@section('title', 'Gallery Banners | Indigo Admin')

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

        .banner-img {
            width: 120px;
            height: 54px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid var(--border);
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
    </style>
@endsection

@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Gallery Banners' : 'ギャラリーバナー' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Manage gallery page banners for PC and mobile.' : 'PC・モバイルのギャラリーページバナーを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.gallery-banners.create') }}" class="btn-outline">
                    + {{ request()->cookie('dev') == '1' ? 'Add Gallery Banner' : 'ギャラリーバナーを追加' }}
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
                    <th>{{ request()->cookie('dev') == '1' ? 'PC Image' : 'PC画像' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Mobile Image' : 'モバイル画像' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Title' : 'タイトル' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Link' : 'リンク' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Sort' : '表示順' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align:right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($banners as $banner)
                    <tr>
                        <td>
                            @if ($banner->image_pc)
                                <img src="{{ asset('storage/' . $banner->image_pc) }}" class="banner-img"
                                    alt="{{ $banner->title }}">
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            @if ($banner->image_mobile)
                                <img src="{{ asset('storage/' . $banner->image_mobile) }}" class="banner-img"
                                    alt="{{ $banner->title }}">
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $banner->title ?? '-' }}</td>

                        <td>
                            @if ($banner->link_url)
                                <a href="{{ $banner->link_url }}" target="_blank">
                                    {{ $banner->link_url }}
                                </a>
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ $banner->sort_order }}</td>

                        <td>
                            @if ($banner->is_active)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') == '1' ? 'Inactive' : '非アクティブ' }}</span>
                            @endif
                        </td>

                        <td style="text-align:right;">
                            <div class="action-btns" style="justify-content:flex-end;">
                                <a href="{{ route('admin.gallery-banners.edit', $banner->gallery_banner_id) }}"
                                    class="action-link">
                                    {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                </a>

                                <form action="{{ route('admin.gallery-banners.destroy', $banner->gallery_banner_id) }}"
                                    method="POST" style="display:inline;" onsubmit="return confirm('Delete this banner?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="action-link delete">
                                        {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:32px;">
                            {{ request()->cookie('dev') == '1' ? 'No gallery banners found.' : 'ギャラリーバナーが見つかりません。' }}
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
