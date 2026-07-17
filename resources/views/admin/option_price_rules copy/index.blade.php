@extends('admin.layouts.app')

@section('title', 'Option Price Rules | Indigo Admin')

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
    }

    .rule-search-form {
        margin: 0 24px 18px;
    }

    .rule-search-row {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .rule-search-input,
    .rule-filter-select {
        height: 38px;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 0 12px;
        background: #fff;
        font-size: 14px;
    }

    .rule-search-input {
        width: 300px;
    }

    .rule-filter-select {
        min-width: 180px;
    }

    .rule-search-btn,
    .rule-reset-btn {
        height: 38px;
        border-radius: 8px;
        padding: 0 16px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .rule-search-btn {
        border: 0;
        background: var(--accent);
        color: #fff;
        cursor: pointer;
    }

    .rule-reset-btn {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--fg);
    }

    .mini-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 999px;
        background: var(--bg);
        border: 1px solid var(--border);
        color: var(--fg);
        font-size: 12px;
        margin: 2px;
    }

    .tier-line {
        font-size: 12px;
        color: var(--fg);
        margin-bottom: 3px;
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

    .pagination-container {
        padding: 16px 24px;
        border-top: 1px solid var(--border);
    }

    @media (max-width: 900px) {
        .rule-search-input,
        .rule-filter-select,
        .rule-search-btn,
        .rule-reset-btn {
            width: 100%;
        }

        .table-card {
            overflow-x: auto;
        }

        table {
            min-width: 1000px;
        }
    }
</style>
@endsection

@section('content')
<div class="table-card">
    <div class="table-header">
        <div>
            <div class="table-title">Option Price Rules</div>
            <div class="showing-text">
                Manage additional option prices that will be added to product price rules.
            </div>
        </div>

        <div class="table-actions">
            <a href="{{ route('admin.option-price-rules.create') }}" class="btn-primary">
                + Add Option Price Rule
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.option-price-rules.index') }}" class="rule-search-form">
        <div class="rule-search-row">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   class="rule-search-input"
                   placeholder="Search rule, product or option...">

            <select name="type" class="rule-filter-select">
                <option value="">All Types</option>
                <option value="1" {{ request('type') === '1' ? 'selected' : '' }}>Hotstrap</option>
                <option value="2" {{ request('type') === '2' ? 'selected' : '' }}>Hotmobily</option>
            </select>

            <select name="product_id" class="rule-filter-select">
                <option value="">All Products</option>
                @foreach ($products as $product)
                    <option value="{{ $product->product_id }}"
                        {{ request('product_id') == $product->product_id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="rule-search-btn">
                Search
            </button>

            @if(request()->hasAny(['search', 'type', 'product_id']))
                <a href="{{ route('admin.option-price-rules.index') }}" class="rule-reset-btn">
                    Reset
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
                <th>Product</th>
                <th>Rule Name</th>
                <th>Required Options</th>
                <th>Additional Price Tiers</th>
                <th>Status</th>
                <th style="text-align:right;">Manage</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($rules as $rule)
                <tr>
                    <td>
                        <div class="product-details">
                            <span class="product-name">
                                {{ $rule->product->product_name ?? '-' }}
                            </span>
                            <span class="product-sku">
                                ID: {{ $rule->product->product_id ?? '-' }}
                                | Code: {{ $rule->product->product_code ?? '-' }}
                                | Type: {{ $rule->product->product_type ?? '-' }}
                            </span>
                        </div>
                    </td>

                    <td>{{ $rule->rule_name }}</td>

                    <td>
                        @foreach ($rule->options as $option)
                            <span class="mini-badge">
                                {{ $option->group->group_name ?? '-' }}: {{ $option->option_name }}
                            </span>
                        @endforeach
                    </td>

                    <td>
                        @foreach ($rule->tiers->sortBy('min_qty') as $tier)
                            <div class="tier-line">
                                {{ $tier->min_qty }}
                                -
                                {{ $tier->max_qty ?: '∞' }}
                                pcs:
                                +{{ number_format($tier->additional_price, 2) }}
                            </div>
                        @endforeach
                    </td>

                    <td>
                        @if ($rule->is_active)
                            <span class="status-pill status-active">Active</span>
                        @else
                            <span class="status-pill status-inactive">Inactive</span>
                        @endif
                    </td>

                    <td style="text-align:right;">
                        <div class="action-btns" style="justify-content:flex-end;">
                            <a href="{{ route('admin.option-price-rules.edit', $rule->option_price_rule_id) }}"
                               class="action-link">
                                Edit
                            </a>

                            <form action="{{ route('admin.option-price-rules.destroy', $rule->option_price_rule_id) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="action-link delete"
                                        onclick="return confirm('Delete this option price rule?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:32px;">
                        No option price rules found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $rules->links() }}
    </div>
</div>
@endsection