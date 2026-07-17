@extends('admin.layouts.app')

@section('title', 'Quotations | Indigo Admin')
@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .quotation-status-select {
            min-width: 120px;
            height: 34px;
            border-radius: 999px;
            border: 1px solid #d1d5db;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 700;
            outline: none;
            cursor: pointer;
        }

        .quotation-status-select.active {
            background: #ecfdf5;
            color: #047857;
            border-color: #a7f3d0;
        }

        .quotation-status-select.not_active {
            background: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }

        .quotation-status-select.is-saving {
            opacity: 0.6;
            pointer-events: none;
        }
        .quotation-action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: nowrap;   /* บังคับให้อยู่แถวเดียว */
    white-space: nowrap;
}

.quotation-action-btn {
    min-width: 64px;
    height: 32px;
    border-radius: 8px;
    padding: 0 12px;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: .2s ease;
    flex-shrink: 0;      /* ไม่ให้ปุ่มหดจนตกบรรทัด */
}

.quotation-action-btn.view {
    background: #eef4ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.quotation-action-btn.edit {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #fed7aa;
}

.quotation-action-btn.pdf {
    background: #ecfdf5;
    color: #047857;
    border: 1px solid #a7f3d0;
}

.quotation-action-btn:hover {
    transform: translateY(-1px);
    opacity: .9;
}
.quotation-filter-form {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    margin: 18px 0 20px;
    padding: 16px;
    background: var(--bg);
    border: 1px solid var(--border);
    border-radius: 12px;
}

.quotation-filter-form input,
.quotation-filter-form select {
    height: 40px;
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 0 12px;
    background: #fff;
    font-family: inherit;
    font-size: 14px;
    outline: none;
}

.quotation-filter-form input {
    min-width: 320px;
    flex: 1;
}

.quotation-filter-form select {
    min-width: 160px;
}

.quotation-filter-form input:focus,
.quotation-filter-form select:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
}

@media (max-width: 900px) {
    .quotation-filter-form {
        flex-direction: column;
        align-items: stretch;
    }

    .quotation-filter-form input,
    .quotation-filter-form select,
    .quotation-filter-form .btn-primary,
    .quotation-filter-form .btn-outline {
        width: 100%;
    }
}
.date-range-filter {
    display: flex;
    align-items: center;
    gap: 8px;
}

.date-range-filter input {
    min-width: 145px;
    flex: unset;
}

.date-range-filter span {
    color: var(--muted);
    font-size: 13px;
    font-weight: 600;
}

@media (max-width: 900px) {
    .date-range-filter {
        width: 100%;
        flex-direction: column;
        align-items: stretch;
    }

    .date-range-filter input {
        width: 100%;
    }

    .date-range-filter span {
        text-align: center;
    }
}
    </style>
@endsection
@section('content')

    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Quotations' : '引用' }}</div>
                <div class="showing-text">{{ request()->cookie('dev') == '1' ? 'Manage customer quotations.' : '管理客户引用' }}</div>
            </div>

            <a href="{{ route('admin.quotations.create') }}" class="btn-primary">
                {{ request()->cookie('dev') == '1' ? '+ Add Quotation' : '＋ 引用を追加' }}
            </a>
        </div>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form method="GET" action="{{ route('admin.quotations.index') }}" class="quotation-filter-form">
    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search quotation no, customer, email..."
    >

    <div class="date-range-filter">
        <input
            type="text"
            name="date_from"
            class="js-date-picker"
            value="{{ request('date_from') }}"
            placeholder="dd/mm/yyyy"
            autocomplete="off"
        >

        <span>{{ request()->cookie('dev') == '1' ? 'to' : 'から' }}</span>

        <input
            type="text"
            name="date_to"
            class="js-date-picker"
            value="{{ request('date_to') }}"
            placeholder="dd/mm/yyyy"
            autocomplete="off"
        >
    </div>

    <select name="status">
        <option value="">All Status</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>
            Active
        </option>
        <option value="not_active" {{ request('status') === 'not_active' ? 'selected' : '' }}>
            Not Active
        </option>
    </select>

    <button type="submit" class="btn-primary">
        {{ request()->cookie('dev') == '1' ? 'Search' : '検索' }}
    </button>

    <a href="{{ route('admin.quotations.index') }}" class="btn-outline">
        {{ request()->cookie('dev') == '1' ? 'Reset' : 'リセット' }}
    </a>
</form>

        <table>
            <thead>
                <tr>
                    <th>{{ request()->cookie('dev') == '1' ? 'Quotation No.' : '引用番号' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Date' : '日付' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Customer' : '顧客' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Email' : 'メール' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Total' : '合計' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</th>
                    <th width="220">{{ request()->cookie('dev') == '1' ? 'Action' : 'アクション' }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($quotations as $quotation)
                    <tr>
                        <td>{{ $quotation->quotation_no }}</td>
                        <td>{{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d/m/Y') }}</td>
                        <td>{{ $quotation->customer_name }}</td>
                        <td>{{ $quotation->customer_email ?? '-' }}</td>
                        <td>¥{{ number_format($quotation->grand_total, 2) }}</td>
                        <td>
                            <select class="quotation-status-select {{ $quotation->status }}"
                                data-update-url="{{ route('admin.quotations.updateStatus', $quotation->quotation_id) }}">
                                <option value="active" {{ $quotation->status === 'active' ? 'selected' : '' }}>
                                    {{ request()->cookie('dev') == '1' ? 'Active' : '有効' }}
                                </option>

                                <option value="not_active" {{ $quotation->status === 'not_active' ? 'selected' : '' }}>
                                    {{ request()->cookie('dev') == '1' ? 'Not Active' : '無効' }}
                                </option>
                            </select>
                        </td>
                        <td>
    <div class="quotation-action-buttons">
        <a href="{{ route('admin.quotations.show', $quotation->quotation_id) }}"
           class="quotation-action-btn view">
            {{ request()->cookie('dev') == '1' ? 'View' : '表示' }}
        </a>

        <a href="{{ route('admin.quotations.edit', $quotation->quotation_id) }}"
           class="quotation-action-btn edit">
            {{ request()->cookie('dev') == '1' ? 'Edit' : '編集' }}
        </a>

        <a href="{{ route('admin.quotations.pdf', $quotation->quotation_id) }}"
           class="quotation-action-btn pdf">
            {{ request()->cookie('dev') == '1' ? 'PDF' : 'PDF' }}
        </a>
    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;padding:32px;">
                            No quotations found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $quotations->links() }}
    </div>

@endsection
@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.quotation-status-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    const updateUrl = this.dataset.updateUrl;
                    const status = this.value;
                    const selectEl = this;

                    selectEl.classList.add('is-saving');

                    fetch(updateUrl, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({
                                status: status,
                            }),
                        })
                        .then(function(response) {
                            if (!response.ok) {
                                throw new Error('Unable to update status.');
                            }

                            return response.json();
                        })
                        .then(function() {
                            selectEl.classList.remove('active', 'not_active');

                            if (status === 'active') {
                                selectEl.classList.add('active');
                            } else {
                                selectEl.classList.add('not_active');
                            }
                        })
                        .catch(function(error) {
                            console.error(error);
                            alert('Unable to update quotation status.');
                        })
                        .finally(function() {
                            selectEl.classList.remove('is-saving');
                        });
                });
            });
        });
    </script>
     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        flatpickr('.js-date-picker', {
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true,
            locale: 'en'
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.quotation-status-select').forEach(function(select) {
                select.addEventListener('change', function() {
                    const updateUrl = this.dataset.updateUrl;
                    const status = this.value;
                    const selectEl = this;

                    selectEl.classList.add('is-saving');

                    fetch(updateUrl, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            status: status,
                        }),
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Unable to update status.');
                        }

                        return response.json();
                    })
                    .then(function() {
                        selectEl.classList.remove('active', 'not_active');

                        if (status === 'active') {
                            selectEl.classList.add('active');
                        } else {
                            selectEl.classList.add('not_active');
                        }
                    })
                    .catch(function(error) {
                        console.error(error);
                        alert('Unable to update quotation status.');
                    })
                    .finally(function() {
                        selectEl.classList.remove('is-saving');
                    });
                });
            });
        });
    </script>
@endsection
