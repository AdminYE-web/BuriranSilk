@extends('admin.layouts.app')

@section('title', 'FAQs | Indigo Admin')

@section('css')
<style>
    .faq-card {
        max-width: 1280px;
        margin: 0 auto;
        padding: 24px;
    }

    .faq-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 18px;
    }

    .faq-title {
        font-size: 22px;
        font-weight: 800;
        color: var(--fg-dark);
        margin: 0;
    }

    .faq-subtitle {
        color: var(--muted);
        font-size: 14px;
        margin-top: 4px;
    }

    .faq-filter {
        display: flex;
        gap: 12px;
        margin: 18px 0 22px;
        padding: 16px;
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--bg);
    }

    .faq-filter input,
    .faq-filter select {
        height: 42px;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0 14px;
        outline: none;
        background: #fff;
    }

    .faq-filter input {
        width: 360px;
    }

    .faq-table {
        width: 100%;
        border-collapse: collapse;
    }

    .faq-table th {
        padding: 14px 16px;
        background: var(--bg);
        color: var(--muted);
        font-size: 12px;
        text-transform: uppercase;
        text-align: left;
        border-bottom: 1px solid var(--border);
    }

    .faq-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        font-size: 14px;
        vertical-align: middle;
    }

    .faq-badge {
        display: inline-flex;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .faq-badge.show {
        background: #ecfdf5;
        color: #047857;
    }

    .faq-badge.hide {
        background: #fef2f2;
        color: #b91c1c;
    }

    .faq-place {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .faq-place span {
        display: inline-flex;
        padding: 4px 8px;
        border-radius: 999px;
        background: #e8f0ff;
        color: #17439a;
        font-size: 12px;
        font-weight: 700;
    }

    .faq-actions {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .faq-action-link {
        color: var(--accent);
        text-decoration: none;
        font-weight: 700;
    }

    .faq-delete-btn {
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
    .faq-search-btn {
    height: 42px;
    min-width: 105px;
    border: 1px solid var(--accent);
    border-radius: 10px;
    background: var(--accent);
    color: #fff;
    font-size: 14px;
    font-weight: 700;
    font-family: inherit;
    cursor: pointer;
    padding: 0 18px;
    transition: .2s ease;
}

.faq-search-btn:hover {
    opacity: .92;
    transform: translateY(-1px);
}

.faq-reset-btn {
    height: 42px;
    min-width: 92px;
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

.faq-reset-btn:hover {
    border-color: var(--accent);
    color: var(--accent);
    background: #f8fafc;
}
.faq-drag-handle {
    cursor: grab;
    color: #64748b;
    font-size: 18px;
    user-select: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.faq-drag-handle:active {
    cursor: grabbing;
}

.faq-sortable-ghost {
    background: #eef4ff !important;
    opacity: 0.7;
}

.faq-sortable-chosen {
    background: #f8fafc;
}

.faq-sort-saving {
    opacity: 0.6;
    pointer-events: none;
}
</style>
@endsection

@section('content')

<div class="table-card faq-card">

    <div class="faq-header">
        <div>
            <h1 class="faq-title">FAQs</h1>
            <div class="faq-subtitle">
                {{ request()->cookie('dev') == '1' ? 'Manage questions and answers. Current language:' : '質問と回答を管理します。現在の言語:' }} {{ strtoupper($language) }}
            </div>
        </div>

        <a href="{{ route('admin.faqs.create') }}" class="btn-primary">
            {{ request()->cookie('dev') == '1' ? 'Add FAQ' : 'FAQを追加' }}
        </a>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" action="{{ route('admin.faqs.index') }}" class="faq-filter">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search question, answer or product..."
        >

        <select name="status">
            <option value="">{{ request()->cookie('dev') == '1' ? 'All Status' : 'すべてのステータス' }}</option>
            <option value="show" {{ request('status') === 'show' ? 'selected' : '' }}>Show</option>
            <option value="hide" {{ request('status') === 'hide' ? 'selected' : '' }}>Hide</option>
        </select>

       <button type="submit" class="faq-search-btn">
    {{ request()->cookie('dev') == '1' ? 'Search' : '検索' }}
</button>

<a href="{{ route('admin.faqs.index') }}" class="faq-reset-btn">
    {{ request()->cookie('dev') == '1' ? 'Reset' : 'リセット' }}
</a>
    </form>

    <table class="faq-table">
        <thead>
           <tr>
    <th width="50"></th>
    <th width="70">{{ request()->cookie('dev') == '1' ? 'Sort' : 'ソート' }}</th>
    <th>{{ request()->cookie('dev') == '1' ? 'Question' : '質問' }}</th>
    <th>{{ request()->cookie('dev') == '1' ? 'Product' : '製品' }}</th>
    <th>{{ request()->cookie('dev') == '1' ? 'Display' : '表示' }}</th>
    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
    <th width="150">{{ request()->cookie('dev') == '1' ? 'Action' : 'アクション' }}</th>
</tr>
        </thead>

        <tbody id="faq-sortable-body">
            @forelse($faqs as $faq)
                <tr data-faq-id="{{ $faq->faq_id }}">
                    <td>
    <span class="faq-drag-handle" title="Drag to reorder">
        ☰
    </span>
</td>
                    <td>{{ $faq->sort_order }}</td>

                    <td>
                        <strong>{{ $faq->question }}</strong>
                    </td>

                    <td>
                        {{ $faq->product->product_name ?? 'All / No product' }}
                    </td>

                    <td>
                        <div class="faq-place">
                            @if($faq->show_main)
                                <span>Main</span>
                            @endif

                            @if($faq->show_product)
                                <span>Product</span>
                            @endif

                            @if(!$faq->show_main && !$faq->show_product)
                                -
                            @endif
                        </div>
                    </td>

                    <td>
                        <span class="faq-badge {{ $faq->status }}">
                            {{ ucfirst($faq->status) }}
                        </span>
                    </td>

                    <td>
                        <div class="faq-actions">
                            <a href="{{ route('admin.faqs.edit', $faq->faq_id) }}" class="faq-action-link">
                                {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
                            </a>

                            <form action="{{ route('admin.faqs.destroy', $faq->faq_id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this FAQ?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="faq-delete-btn">
                                    {{ request()->cookie('dev') == '1' ? 'Delete' : '削除' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:32px;">
                        No FAQs found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:18px;">
        {{ $faqs->links() }}
    </div>

</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('faq-sortable-body');

    if (!tbody) {
        return;
    }

    new Sortable(tbody, {
        animation: 150,
        handle: '.faq-drag-handle',
        ghostClass: 'faq-sortable-ghost',
        chosenClass: 'faq-sortable-chosen',

        onEnd: function () {
            updateFaqSortOrder();
        }
    });

    function updateFaqSortOrder() {
        const rows = tbody.querySelectorAll('tr[data-faq-id]');

        const items = Array.from(rows).map(function (row, index) {
            return {
                faq_id: row.dataset.faqId,
                sort_order: index + 1
            };
        });

        tbody.classList.add('faq-sort-saving');

        fetch('{{ route('admin.faqs.updateSort') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                items: items
            })
        })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Sort update failed');
            }

            return response.json();
        })
        .then(function () {
            rows.forEach(function (row, index) {
                const sortCell = row.querySelector('.faq-sort-number');

                if (sortCell) {
                    sortCell.textContent = index + 1;
                }
            });
        })
        .catch(function (error) {
            console.error(error);
            alert('Unable to update sort order.');
        })
        .finally(function () {
            tbody.classList.remove('faq-sort-saving');
        });
    }
});
</script>
@endsection