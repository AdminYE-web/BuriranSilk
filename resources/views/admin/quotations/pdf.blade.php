<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $quotation->quotation_no }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #111;
        }

        .title {
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .info-table,
        .items-table,
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 4px 0;
        }

        .items-table {
            margin-top: 24px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            vertical-align: top;
        }

        .items-table th {
            background: #f3f6fb;
        }

        .summary-table {
            width: 300px;
            margin-left: auto;
            margin-top: 24px;
        }

        .summary-table td {
            padding: 6px 0;
        }

        .text-right {
            text-align: right;
        }

        .option-line {
            color: #555;
            font-size: 10px;
            margin-top: 3px;
        }
    </style>
</head>
<body>

    <div class="title">Quotation</div>

    <table class="info-table">
        <tr>
            <td><strong>Quotation No:</strong> {{ $quotation->quotation_no }}</td>
            <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($quotation->quotation_date)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td><strong>Customer:</strong> {{ $quotation->customer_name }}</td>
            <td><strong>Email:</strong> {{ $quotation->customer_email ?? '-' }}</td>
        </tr>
        <tr>
            <td colspan="2">
                <strong>Address:</strong> {{ $quotation->customer_address ?? '-' }}
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Product</th>
                <th width="70">Qty</th>
                <th width="100">Unit Price</th>
                <th width="100">Options</th>
                <th width="100">Total</th>
            </tr>
        </thead>

        <tbody>
            @foreach($quotation->items as $item)
                <tr>
                    <td>
                        <strong>{{ $item->product_name_snapshot }}</strong>

                        @foreach($item->options as $option)
                            <div class="option-line">
                                {{ $option->group_name }}:
                                {{ $option->option_name }}
                                @if($option->additional_price > 0)
                                    (+¥{{ number_format($option->additional_price, 2) }})
                                @endif
                            </div>
                        @endforeach
                    </td>

                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">¥{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">¥{{ number_format($item->option_total, 2) }}</td>
                    <td class="text-right">¥{{ number_format($item->item_total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-table">
       <tr>
    <td>Subtotal</td>
    <td class="text-right">¥{{ number_format($quotation->subtotal, 2) }}</td>
</tr>

<tr>
    <td>Discount</td>
    <td class="text-right">-¥{{ number_format($quotation->discount_amount ?? 0, 2) }}</td>
</tr>

<tr>
    <td>Shipping Fee</td>
    <td class="text-right">
        @if(($quotation->shipping_fee ?? 0) > 0)
            ¥{{ number_format($quotation->shipping_fee, 2) }}
        @else
            Free
        @endif
    </td>
</tr>

<tr>
    <td>VAT 10%</td>
    <td class="text-right">¥{{ number_format($quotation->vat_amount ?? 0, 2) }}</td>
</tr>

<tr>
    <td>Grand Total</td>
    <td class="text-right">
        <strong>¥{{ number_format($quotation->grand_total, 2) }}</strong>
    </td>
</tr>
    </table>

    @if($quotation->note)
        <p><strong>Note:</strong> {{ $quotation->note }}</p>
    @endif

</body>
</html>