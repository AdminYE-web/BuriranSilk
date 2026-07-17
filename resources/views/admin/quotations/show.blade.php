@extends('admin.layouts.app')

@section('title', 'Quotation Detail | Indigo Admin')

@section('css')
    <style>
        .quotation-show-card {
            max-width: 1180px;
            margin: 0 auto;
            padding: 24px;
        }

        .quotation-show-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 22px;
        }

        .quotation-show-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--fg-dark);
            margin: 0;
        }

        .quotation-show-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 14px;
        }

        .quotation-action-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .quotation-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 26px;
        }

        .quotation-info-box {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            background: #fff;
        }

        .quotation-info-box h3 {
            margin: 0 0 14px;
            font-size: 16px;
            font-weight: 800;
            color: var(--fg-dark);
        }

        .quotation-info-row {
            display: grid;
            grid-template-columns: 150px 1fr;
            gap: 12px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .quotation-info-label {
            color: var(--muted);
            font-weight: 600;
        }

        .quotation-info-value {
            color: var(--fg-dark);
            font-weight: 600;
        }

        .quotation-items-title {
            margin: 28px 0 16px;
            font-size: 20px;
            font-weight: 800;
            color: var(--fg-dark);
        }

        .quotation-item-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            margin-bottom: 16px;
            background: #fff;
        }

        .quotation-item-top {
            display: grid;
            grid-template-columns: 1fr 110px 130px 130px 140px;
            gap: 14px;
            align-items: start;
            border-bottom: 1px solid var(--border);
            padding-bottom: 14px;
            margin-bottom: 14px;
        }

        .quotation-item-name {
            font-size: 16px;
            font-weight: 800;
            color: var(--fg-dark);
        }

        .quotation-item-code {
            margin-top: 4px;
            font-size: 13px;
            color: var(--muted);
        }

        .quotation-item-cell-label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 5px;
            font-weight: 700;
        }

        .quotation-item-cell-value {
            font-size: 14px;
            font-weight: 800;
            color: var(--fg-dark);
        }

        .quotation-options-list {
            margin-top: 10px;
        }

        .quotation-option-row {
            display: grid;
            grid-template-columns: 210px 1fr 120px 120px;
            gap: 12px;
            padding: 9px 0;
            border-bottom: 1px solid #f0f2f5;
            font-size: 13px;
        }

        .quotation-option-row:last-child {
            border-bottom: 0;
        }

        .quotation-option-label {
            color: var(--muted);
            font-weight: 700;
        }

        .quotation-summary-box {
            width: 360px;
            margin-left: auto;
            margin-top: 26px;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            background: #f8fafc;
        }

        .quotation-summary-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .quotation-summary-row.total {
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 900;
            color: #0b2d68;
        }

        .quotation-note-box {
            margin-top: 24px;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 18px;
            background: #fff;
        }

        .quotation-note-box h3 {
            margin: 0 0 10px;
            font-size: 16px;
            font-weight: 800;
        }

        .quotation-note-box p {
            margin: 0;
            color: var(--fg);
            line-height: 1.6;
            white-space: pre-line;
        }

        @media (max-width: 900px) {
            .quotation-info-grid {
                grid-template-columns: 1fr;
            }

            .quotation-item-top {
                grid-template-columns: 1fr;
            }

            .quotation-option-row {
                grid-template-columns: 1fr;
                gap: 4px;
            }

            .quotation-summary-box {
                width: 100%;
            }

            .quotation-show-header {
                flex-direction: column;
            }

            .quotation-action-row {
                width: 100%;
                flex-wrap: wrap;
            }
        }
    </style>
@endsection

@section('content')

    <div class="table-card quotation-show-card">

        <div class="quotation-show-header">
            <div>
                <h1 class="quotation-show-title">
                    Quotation Detail
                </h1>
                <div class="quotation-show-subtitle">
                    {{ $quotation->quotation_no }}
                </div>
            </div>

            <div class="quotation-action-row">
                <div class="quotation-action-row">
                    <a href="{{ route('admin.quotations.edit', $quotation->quotation_id) }}" class="btn-outline">
                        Edit
                    </a>

                    <a href="{{ route('admin.quotations.pdf', $quotation->quotation_id) }}" class="btn-outline">
                        Download PDF
                    </a>

                    <a href="{{ route('admin.quotations.index') }}" class="btn-outline">
                        Back
                    </a>
                </div>
            </div>
        </div>

        <div class="quotation-info-grid">
            <div class="quotation-info-box">
                <h3>Quotation Information</h3>

                <div class="quotation-info-row">
                    <div class="quotation-info-label">Quotation No.</div>
                    <div class="quotation-info-value">{{ $quotation->quotation_no }}</div>
                </div>

                <div class="quotation-info-row">
                    <div class="quotation-info-label">Quotation Date</div>
                    <div class="quotation-info-value">
                        {{ $quotation->quotation_date ? \Carbon\Carbon::parse($quotation->quotation_date)->format('d/m/Y') : '-' }}
                    </div>
                </div>

                <div class="quotation-info-row">
                    <div class="quotation-info-label">Status</div>
                    <div class="quotation-info-value">{{ ucfirst($quotation->status ?? 'draft') }}</div>
                </div>

                {{-- <div class="quotation-info-row">
                    <div class="quotation-info-label">Language</div>
                    <div class="quotation-info-value">{{ strtoupper($quotation->language ?? 'pt') }}</div>
                </div> --}}
            </div>

            <div class="quotation-info-box">
                <h3>Customer Information</h3>

                <div class="quotation-info-row">
                    <div class="quotation-info-label">Customer Name</div>
                    <div class="quotation-info-value">{{ $quotation->customer_name }}</div>
                </div>

                <div class="quotation-info-row">
                    <div class="quotation-info-label">Customer Email</div>
                    <div class="quotation-info-value">{{ $quotation->customer_email ?? '-' }}</div>
                </div>

                <div class="quotation-info-row">
                    <div class="quotation-info-label">Customer Address</div>
                    <div class="quotation-info-value" style="white-space: pre-line;">
                        {{ $quotation->customer_address ?? '-' }}</div>
                </div>
            </div>
        </div>

        <h2 class="quotation-items-title">Products</h2>

        @forelse($quotation->items as $item)
            <div class="quotation-item-card">
                <div class="quotation-item-top">
                    <div>
                        <div class="quotation-item-name">
                            {{ $item->product_name_snapshot }}
                        </div>

                        <div class="quotation-item-code">
                            Product Code: {{ $item->product_code_snapshot ?? '-' }}
                        </div>

                        @if (!empty($item->price_rule_snapshot))
                            <div class="quotation-item-code">
                                Price Rule: {{ $item->price_rule_snapshot['rule_name'] ?? '-' }}
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="quotation-item-cell-label">Quantity</div>
                        <div class="quotation-item-cell-value">{{ number_format($item->quantity) }}</div>
                    </div>

                    <div>
                        <div class="quotation-item-cell-label">Unit Price</div>
                        <div class="quotation-item-cell-value">¥{{ number_format($item->unit_price, 2) }}</div>
                    </div>

                    <div>
                        <div class="quotation-item-cell-label">Options</div>
                        <div class="quotation-item-cell-value">¥{{ number_format($item->option_total, 2) }}</div>
                    </div>

                    <div>
                        <div class="quotation-item-cell-label">Item Total</div>
                        <div class="quotation-item-cell-value">¥{{ number_format($item->item_total, 2) }}</div>
                    </div>
                </div>

                @if ($item->options->count())
                    <div class="quotation-options-list">
                        @foreach ($item->options as $option)
                            <div class="quotation-option-row">
                                <div>
                                    <span class="quotation-option-label">Group:</span>
                                    {{ $option->group_name ?? '-' }}
                                </div>

                                <div>
                                    <span class="quotation-option-label">Option:</span>
                                    {{ $option->option_name ?? '-' }}

                                    @if ($option->variant_name)
                                        / {{ $option->variant_name }}
                                    @endif
                                </div>

                                <div>
                                    <span class="quotation-option-label">Price:</span>
                                    ¥{{ number_format($option->additional_price ?? 0, 2) }}
                                </div>

                                <div>
                                    <span class="quotation-option-label">Type:</span>
                                    {{ $option->price_type ?? '-' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="color: var(--muted); font-size: 14px;">
                        No selected options.
                    </div>
                @endif
            </div>
        @empty
            <div class="quotation-item-card">
                No products found.
            </div>
        @endforelse

        <div class="quotation-summary-box">
           <div class="quotation-summary-row">
    <span>Subtotal</span>
    <strong>¥{{ number_format($quotation->subtotal ?? 0, 2) }}</strong>
</div>

<div class="quotation-summary-row">
    <span>Discount</span>
    <strong>-¥{{ number_format($quotation->discount_amount ?? 0, 2) }}</strong>
</div>

<div class="quotation-summary-row">
    <span>Shipping Fee</span>
    <strong>
        @if(($quotation->shipping_fee ?? 0) > 0)
            ¥{{ number_format($quotation->shipping_fee, 2) }}
        @else
            Free
        @endif
    </strong>
</div>

<div class="quotation-summary-row">
    <span>VAT 10%</span>
    <strong>¥{{ number_format($quotation->vat_amount ?? 0, 2) }}</strong>
</div>

<div class="quotation-summary-row total">
    <span>Grand Total</span>
    <strong>¥{{ number_format($quotation->grand_total ?? 0, 2) }}</strong>
</div>
        </div>

        @if ($quotation->note)
            <div class="quotation-note-box">
                <h3>Note</h3>
                <p>{{ $quotation->note }}</p>
            </div>
        @endif

    </div>

@endsection
