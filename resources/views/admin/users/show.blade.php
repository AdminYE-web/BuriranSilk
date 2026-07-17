@extends('admin.layouts.app')

@section('title', 'User Detail | Indigo Admin')

@section('css')
    <style>
        .user-detail-card {
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        .section-title {
            margin: 34px 0 16px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .btn-outline {
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
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
            font-family: inherit;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .info-table th,
        .info-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        .info-table th {
            width: 220px;
            background: var(--bg);
            color: var(--fg-dark);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-size: 12px;
        }

        .info-table td {
            background: #fff;
        }

        .info-table tr:last-child th,
        .info-table tr:last-child td {
            border-bottom: 0;
        }

        table {
            margin-top: 8px;
        }

        table thead th {
            background: var(--bg);
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .amount-text,
        .order-no {
            font-weight: 700;
            color: var(--fg-dark);
            white-space: nowrap;
        }

        .status-pill {
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

        .sub-text {
            display: block;
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .inline-actions {
            display: flex;
            justify-content: flex-end;
        }

        @media (max-width: 900px) {
            .user-detail-card {
                padding: 18px;
            }

            .table-card {
                overflow-x: auto;
            }

            table {
                min-width: 950px;
            }

            .info-table {
                min-width: 0;
            }
        }

        .email-change-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .current-email {
            font-weight: 700;
            color: var(--fg-dark);
        }

        .email-change-form {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .email-change-form input {
            min-width: 280px;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            outline: none;
        }

        .error-text {
            color: #dc2626;
            font-size: 13px;
        }
    </style>
@endsection

@section('content')

    <div class="table-card user-detail-card">
        <div class="table-header">
            <div>
                <div class="table-title">User Detail</div>
                <div class="showing-text">
                    {{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?: '-') }}
                </div>
            </div>

            <a href="{{ route('admin.users.index') }}" class="btn-outline">
                Back
            </a>
        </div>

        <div class="section-title">Profile</div>

        <table class="info-table">
            <tr>
                <th>User ID</th>
                <td>{{ $user->user_id }}</td>
            </tr>
            <tr>
                <th>Name</th>
                <td>{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: ($user->name ?: '-') }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>
                    <div class="email-change-box">
                        <div class="current-email">
                            {{ $user->email }}
                        </div>

                        <form action="{{ route('admin.users.email-change.send', $user->user_id) }}" method="POST"
                            class="email-change-form">
                            @csrf

                            <input type="email" name="new_email" value="{{ old('new_email') }}"
                                placeholder="New email address" required>

                            <button type="submit" class="btn-outline"
                                onclick="return confirm('Send verification email to this new email address?')">
                                Send Verify Email
                            </button>
                        </form>

                        @error('new_email')
                            <div class="error-text">{{ $message }}</div>
                        @enderror
                    </div>
                </td>
            </tr>
            <tr>
                <th>Phone</th>
                <td>{{ $user->phone ?? (optional($user->mainContact)->phone ?? '-') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="status-pill">
                        {{ (string) $user->status === '1' ? 'Verified' : ((string) $user->status === '2' ? 'Pending Verification' : ((string) $user->status === '0' ? 'Unverified' : $user->status)) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Email Verified</th>
                <td>{{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <th>Registered</th>
                <td>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        </table>

        <div class="section-title">Main Addresses</div>

        <table class="info-table">
            <tr>
                <th>Shipping</th>
                <td>
                    @if ($user->mainShippingAddress)
                        {{ $user->mainShippingAddress->address ?? '' }}
                        {{ $user->mainShippingAddress->apartment ?? '' }}
                        {{ $user->mainShippingAddress->city ?? '' }}
                        {{ $user->mainShippingAddress->state ?? '' }}
                        {{ $user->mainShippingAddress->zip_code ?? '' }}
                        {{ $user->mainShippingAddress->country ?? '' }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>Billing</th>
                <td>
                    @if ($user->mainBillingAddress)
                        {{ $user->mainBillingAddress->address ?? '' }}
                        {{ $user->mainBillingAddress->apartment ?? '' }}
                        {{ $user->mainBillingAddress->city ?? '' }}
                        {{ $user->mainBillingAddress->state ?? '' }}
                        {{ $user->mainBillingAddress->zip_code ?? '' }}
                        {{ $user->mainBillingAddress->country ?? '' }}
                    @else
                        -
                    @endif
                </td>
            </tr>
        </table>

        <div class="section-title">Order History</div>

        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Customer</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Order Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th style="text-align:right;">Manage</th>
                </tr>
            </thead>

            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><span class="order-no">{{ $order->order_no }}</span></td>
                        <td>
                            {{ $order->customer->personal_first_name ?? '-' }}
                            {{ $order->customer->personal_last_name ?? '' }}
                            <span class="sub-text">{{ $order->customer->personal_email ?? '-' }}</span>
                        </td>
                        <td>{{ $order->qty }}</td>
                        <td><span class="amount-text">&yen; {{ number_format($order->grand_total, 2) }}</span></td>
                        <td><span class="status-pill">{{ ucfirst($order->status) }}</span></td>
                        <td>
                            <span class="status-pill">{{ ucfirst($order->payment->payment_status ?? 'pending') }}</span>
                            <span class="sub-text">{{ $order->payment->payment_method ?? '-' }}</span>
                        </td>
                        <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '-' }}</td>
                        <td>
                            <div class="inline-actions">
                                <a href="{{ route('admin.orders.show', $order->order_id) }}" class="btn-outline">
                                    Detail
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:32px;">
                            No orders found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $orders->links() }}
        </div>

        <div class="section-title">Login Logs</div>

        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Method</th>
                    <th>IP Address</th>
                    {{-- <th>User Agent</th> --}}
                </tr>
            </thead>

            <tbody>
                @forelse($loginLogs as $log)
                    <tr>
                        <td>{{ $log->logged_in_at ? $log->logged_in_at->format('d/m/Y H:i') : '-' }}</td>
                        <td><span class="status-pill">{{ $log->login_method }}</span></td>
                        <td>{{ $log->ip_address ?? '-' }}</td>
                        {{-- <td>{{ $log->user_agent ?? '-' }}</td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; padding:32px;">
                            No login logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination-container">
            {{ $loginLogs->links() }}
        </div>
    </div>

@endsection
