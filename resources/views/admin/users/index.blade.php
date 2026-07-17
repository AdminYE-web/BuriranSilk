@extends('admin.layouts.app')

@section('title', 'Users | Indigo Admin')

@section('css')
<style>
    .filter-form {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        padding: 16px;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 12px;
    }

    .filter-form input,
    .filter-form select {
        height: 38px;
        padding: 0 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #fff;
        font-family: inherit;
        font-size: 14px;
    }

    .filter-form input {
        min-width: 280px;
        flex: 1;
    }

    .user-name-text {
        font-weight: 700;
        color: var(--fg-dark);
    }

    .user-sub {
        display: block;
        margin-top: 4px;
        color: var(--muted);
        font-size: 12px;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 10px;
        border-radius: 999px;
        background: var(--bg);
        border: 1px solid var(--border);
        font-size: 12px;
        font-weight: 600;
        color: var(--fg);
        text-transform: capitalize;
        white-space: nowrap;
    }

    .btn-outline,
    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 38px;
        padding: 9px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        font-family: inherit;
        border: 1px solid transparent;
    }

    .btn-outline {
        background: #fff;
        border-color: var(--border);
        color: var(--fg);
    }

    .btn-primary {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .date-text {
        white-space: nowrap;
        color: var(--fg);
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .table-card {
            overflow-x: auto;
        }

        table {
            min-width: 1000px;
        }

        .filter-form {
            flex-direction: column;
        }

        .filter-form input,
        .filter-form select,
        .filter-form .btn-primary,
        .filter-form .btn-outline {
            width: 100%;
        }
    }
</style>
@endsection

@section('content')

<div class="table-card">
    <div class="table-header">
        <div>
            <div class="table-title">Users</div>
            <div class="showing-text">
                View registered users, order history and login logs.
            </div>
        </div>
    </div>

    <form method="GET" class="filter-form">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Search name, email or phone"
        >

        <select name="status">
            <option value="">All Status</option>
            @foreach(['1', '2', '0', 'active'] as $status)
                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                    {{ $status === '1' ? 'Verified' : ($status === '2' ? 'Pending Verification' : ($status === '0' ? 'Unverified' : ucfirst($status))) }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-primary">
            Search
        </button>

        <a href="{{ route('admin.users.index') }}" class="btn-outline">
            Reset
        </a>
    </form>

    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Orders</th>
                <th>Last Login</th>
                <th>Registered</th>
                <th style="text-align:right;">Manage</th>
            </tr>
        </thead>

        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div class="user-name-text">
                            {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?: '-') }}
                        </div>
                        <span class="user-sub">{{ $user->email }}</span>
                    </td>

                    <td>{{ $user->phone ?? '-' }}</td>

                    <td>
                        <span class="status-badge">
                            {{ (string) $user->status === '1' ? 'Verified' : ((string) $user->status === '2' ? 'Pending Verification' : ((string) $user->status === '0' ? 'Unverified' : $user->status)) }}
                        </span>
                    </td>

                    <td>{{ $user->orders_count }}</td>

                    <td>
                        @php
                            $lastLoginAt = $user->login_logs_max_logged_in_at
                                ? \Illuminate\Support\Carbon::parse($user->login_logs_max_logged_in_at)
                                : $user->last_login_at;
                        @endphp
                        <span class="date-text">
                            {{ $lastLoginAt ? $lastLoginAt->format('d/m/Y H:i') : '-' }}
                        </span>
                    </td>

                    <td>
                        <span class="date-text">
                            {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}
                        </span>
                    </td>

                    <td style="text-align:right;">
                        <a href="{{ route('admin.users.show', $user->user_id) }}" class="btn-outline">
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:32px;">
                        No users found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="pagination-container">
        {{ $users->links() }}
    </div>
</div>

@endsection
