@extends('admin.layouts.app')

@section('title', 'Option Variants | Indigo Admin')

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

        .variant-img {
            width: 58px;
            height: 58px;
            border-radius: 8px;
            border: 1px solid var(--border);
            object-fit: contain;
            background: var(--bg);
            padding: 4px;
        }

        .color-dot-wrap {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .color-dot {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1px solid var(--border);
            display: inline-block;
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
                <div class="table-title">{{ request()->cookie('dev') === '1' ? 'Option Variants' : 'オプションバリエーション' }}
                </div>
                <div class="showing-text">
                    {{ request()->cookie('dev') === '1' ? 'Option' : 'オプション' }}:
                    <strong>{{ $option->option_name }}</strong>
                    |
                    {{ request()->cookie('dev') === '1' ? 'Group' : 'グループ' }}:
                    {{ $option->group->group_name ?? '-' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.product-options.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
                </a>

                <a href="{{ route('admin.product-options.variants.create', $option->option_id) }}" class="btn-primary">
                    {{ request()->cookie('dev') === '1' ? '+ Add Variant' : '+ バリエーション追加' }}
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
                    <th>{{ request()->cookie('dev') === '1' ? 'Variant' : 'バリエーション' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Color' : 'カラー' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Additional Price' : '追加料金' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Sort' : '並び順' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Default' : 'デフォルト' }}</th>
                    <th>{{ request()->cookie('dev') === '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align: right;">{{ request()->cookie('dev') === '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($option->variants as $variant)
                    <tr>
                        <td>
                            <div class="product-cell">
                                @if ($variant->image_path)
                                    <img src="{{ asset('storage/' . $variant->image_path) }}" class="variant-img"
                                        alt="{{ $variant->variant_name }}">
                                @else
                                    <div class="variant-img"></div>
                                @endif

                                <div class="product-details">
                                    <span class="product-name">
                                        {{ $variant->variant_name }}
                                    </span>
                                    <span class="product-sku">
                                        ID: {{ $variant->variant_id }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if ($variant->color_code)
                                <span class="color-dot-wrap">
                                    <span class="color-dot" style="background: {{ $variant->color_code }}"></span>
                                    {{ $variant->color_code }}
                                </span>
                            @else
                                -
                            @endif
                        </td>

                        <td>{{ number_format($variant->additional_price, 2) }}</td>

                        <td>{{ $variant->sort_order }}</td>

                        <td>{{ $variant->is_default ? 'Yes' : 'No' }}</td>

                        <td>
                            @if ($variant->is_active)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') === '1' ? 'Active' : '有効' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') === '1' ? 'Inactive' : '無効' }}</span>
                            @endif
                        </td>

                        <td style="text-align: right;">
                            <div class="action-btns" style="justify-content: flex-end;">
                                <a href="{{ route('admin.product-option-variants.edit', $variant->variant_id) }}"
                                    class="action-link">
                                    {{ request()->cookie('dev') === '1' ? 'Edit' : '編集' }}
                                </a>

                                <form action="{{ route('admin.product-option-variants.destroy', $variant->variant_id) }}"
                                    method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="action-link delete"
                                        onclick="return confirm('{{ request()->cookie('dev') === '1' ? 'Delete this variant?' : 'このバリエーションを削除しますか？' }}')">
                                        {{ request()->cookie('dev') === '1' ? 'Delete' : '削除' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 32px;">
                            {{ request()->cookie('dev') === '1' ? 'No variants found.' : 'バリエーションが見つかりません。' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
