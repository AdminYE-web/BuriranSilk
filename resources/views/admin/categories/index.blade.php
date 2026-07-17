@extends('admin.layouts.app')

@section('title', 'Categories | Indigo Admin')

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

        .category-img {
            width: 58px;
            height: 58px;
            border-radius: 8px;
            border: 1px solid var(--border);
            object-fit: contain;
            background: var(--bg);
            padding: 4px;
        }

        .sort-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 34px;
            height: 28px;
            border-radius: 999px;
            background: var(--bg);
            border: 1px solid var(--border);
            font-size: 12px;
            font-weight: 600;
            color: var(--fg);
        }

        @media (max-width: 900px) {
            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 900px;
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

        .drag-handle {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--muted);
            cursor: grab;
            user-select: none;
            font-size: 16px;
        }

        .drag-handle:active {
            cursor: grabbing;
        }

        .sortable-ghost {
            opacity: 0.4;
            background: #f1f5f9;
        }

        .sortable-chosen {
            background: #f8fafc;
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
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Categories' : 'カテゴリ' }}</div>
                <div class="showing-text">
                    {{ request()->cookie('dev') == '1' ? 'Manage product categories, images, sort order and status.' : '商品カテゴリ、画像、並べ替え、ステータスを管理します。' }}
                </div>
            </div>

            <div class="table-actions">
                <a href="{{ route('admin.categories.create', ['product_type' => $productType]) }}" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? '+ Add Category' : '+ カテゴリを追加' }}
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
                <a href="{{ route('admin.categories.index', array_merge(request()->except('page'), ['product_type' => $typeValue])) }}"
                    class="type-tab {{ (int) $productType === (int) $typeValue ? 'active' : '' }}">
                    {{ $typeLabel }}
                </a>
            @endforeach
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50px;"></th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Category' : 'カテゴリ' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Code' : 'コード' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Sort' : '並べ替え' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                    <th style="text-align: right;">{{ request()->cookie('dev') == '1' ? 'Manage' : '管理' }}</th>
                </tr>
            </thead>

            <tbody id="category-sortable-body">
                @forelse ($categories as $category)
                    <tr data-id="{{ $category->base_category_id ?? $category->category_id }}"
                        class="{{ !empty($category->is_missing_translation) ? 'translation-missing-row' : '' }}">
                        <td>
                            <span class="drag-handle">☰</span>
                        </td>
                        <td>
                            <div class="product-cell">
                                @if ($category->image_path)
                                    <img src="{{ asset('storage/' . $category->image_path) }}" class="category-img"
                                        alt="{{ $category->category_name }}">
                                @else
                                    <div class="category-img"></div>
                                @endif

                                <div class="product-details">
                                    <span class="product-name">
                                        {{ $category->category_name }}
                                    </span>
                                    <span class="product-sku">
                                        ID: {{ $category->category_id }}
                                    </span>
                                </div>
                            </div>
                        </td>

                        <td>{{ $category->category_code }}</td>

                        <td>
                            <span class="sort-badge">
                                {{ $category->sort_order }}
                            </span>
                        </td>

                        <td>
                            @if ($category->is_active)
                                <span
                                    class="status-pill status-active">{{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}</span>
                            @else
                                <span
                                    class="status-pill status-inactive">{{ request()->cookie('dev') == '1' ? 'Inactive' : '非アクティブ' }}</span>
                            @endif
                        </td>

                        <td style="text-align: right;">
                            <div class="action-btns" style="justify-content: flex-end;">
                                @if (!empty($category->is_missing_translation))
                                    <form
                                        action="{{ route('admin.categories.duplicate-translation', $category->category_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf

                                        <button type="submit" class="action-link duplicate"
                                            onclick="return confirm('Duplicate this PT category for {{ strtoupper($language) }}?')">
                                            Duplicate
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('admin.categories.edit', $category->category_id) }}"
                                        class="action-link">
                                        {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category->category_id) }}"
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="action-link delete"
                                            onclick="return confirm('{{ request()->cookie('dev') == '1' ? 'Delete this category?' : 'このカテゴリを削除してもよろしいですか？' }}')">
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
                            No categories found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $categories->links() }}
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tbody = document.getElementById('category-sortable-body');

            if (!tbody) {
                return;
            }

            const baseSort = {{ ($categories->currentPage() - 1) * $categories->perPage() }};

            new Sortable(tbody, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',

                onEnd: function() {
                    saveCategorySort();
                }
            });

            function saveCategorySort() {
                const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));

                const orders = rows.map(function(row, index) {
                    return {
                        id: row.dataset.id,
                        sort_order: baseSort + index + 1
                    };
                });

                rows.forEach(function(row, index) {
                    const badge = row.querySelector('.sort-badge');

                    if (badge) {
                        badge.textContent = baseSort + index + 1;
                    }
                });

                fetch("{{ route('admin.categories.sort') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            orders: orders
                        })
                    })
                    .then(async function(response) {
                        if (!response.ok) {
                            throw new Error(await response.text());
                        }

                        return response.json();
                    })
                    .catch(function(error) {
                        console.error(error);
                        alert('บันทึกลำดับไม่สำเร็จ กรุณารีเฟรชแล้วลองใหม่');
                    });
            }
        });
    </script>
@endsection
