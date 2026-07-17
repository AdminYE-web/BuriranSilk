@extends('admin.layouts.app')

@section('title', 'Material Home | Indigo Admin')

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

        .material-home-img {
            width: 90px;
            height: 70px;
            border-radius: 10px;
            border: 1px solid var(--border);
            object-fit: cover;
            background: var(--bg);
            display: block;
        }

        .item-title {
            font-weight: 600;
            color: var(--fg-dark);
        }

        .item-sub {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .desc-text {
            max-width: 320px;
            color: var(--fg);
            font-size: 13px;
            line-height: 1.5;
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
                min-width: 1050px;
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

        .translation-missing-row .item-title::after {
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
                <div class="table-title">Material Home</div>
                <div class="showing-text">
                    Manage material section on homepage, image, description, sorting and status.
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.material-homes.create') }}" class="btn-primary">
                    + Add Material Home
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
                    <th>Item</th>
                    <th>Material</th>
                    <th>Description</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th style="text-align:right;">Manage</th>
                </tr>
            </thead>

            <tbody>
                @forelse($items as $item)
                    <tr class="{{ !empty($item->is_missing_translation) ? 'translation-missing-row' : '' }}">
                        <td>
                            <div class="product-cell">
                                @if($item->image_path)
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="material-home-img"
                                        alt="{{ $item->title }}">
                                @else
                                    <div class="material-home-img"></div>
                                @endif

                                <div class="product-details">
                                    <span class="item-title">
                                        {{ $item->title }}
                                    </span>

                                    <span class="item-sub">
                                        ID: {{ $item->material_home_id }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td>
                            {{ $item->material->material_name ?? '-' }}
                        </td>

                        <td>
                            <div class="desc-text">
                                {{ Str::limit($item->description, 80) }}
                            </div>
                        </td>

                        <td>
                            <span class="sort-badge">
                                {{ $item->sort_order }}
                            </span>
                        </td>

                        <td>
                            @if($item->is_active)
                                <span class="status-pill status-active">Active</span>
                            @else
                                <span class="status-pill status-inactive">Inactive</span>
                            @endif
                        </td>

                        <td style="text-align:right;">
                            <div class="action-btns" style="justify-content:flex-end;">
                                @if (!empty($item->is_missing_translation))
                                    <form
                                        action="{{ route('admin.material-homes.duplicate-translation', $item->material_home_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf

                                        <button type="submit" class="action-link duplicate"
                                            onclick="return confirm('Duplicate this PT material home for {{ strtoupper($language) }}?')">
                                            Duplicate
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.material-homes.edit', $item->material_home_id) }}" class="action-link">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.material-homes.destroy', $item->material_home_id) }}"
                                        method="POST" style="display:inline;" onsubmit="return confirm('Delete this item?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-link delete">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:32px;">
                            No data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $items->links() }}
        </div>
    </div>

@endsection