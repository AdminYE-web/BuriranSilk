<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quotation {{ $order->order_no }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111; font-size: 12px; line-height: 1.45; }
        .document-header { border-bottom: 2px solid #111; padding-bottom: 14px; margin-bottom: 22px; }
        .document-title { font-size: 26px; font-weight: bold; margin-bottom: 6px; }
        .document-subtitle { color: #555; }
        .top-info { width: 100%; margin-bottom: 24px; }
        .top-info td { width: 50%; vertical-align: top; }
        .section-title { font-size: 15px; font-weight: bold; margin: 20px 0 10px; padding-bottom: 6px; border-bottom: 1px solid #ddd; }
        .info-table { width: 100%; border-collapse: collapse; }
        .info-table td { padding: 4px 0; vertical-align: top; }
        .label { color: #666; width: 130px; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .items-table th { background: #f3f6fb; border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 11px; }
        .items-table td { border: 1px solid #ddd; padding: 8px; vertical-align: top; }
        .text-right { text-align: right; }
        .summary-table { width: 300px; margin-left: auto; margin-top: 20px; border-collapse: collapse; }
        .summary-table td { padding: 6px 0; border-bottom: 1px solid #eee; }
        .summary-table .grand-total td { font-size: 15px; font-weight: bold; border-bottom: 0; padding-top: 10px; }
        .option-line { color: #555; font-size: 10px; margin-top: 3px; }
        .footer { margin-top: 34px; padding-top: 12px; border-top: 1px solid #ddd; color: #777; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="document-header">
        <div class="document-title">Quotation</div>
        <div class="document-subtitle">Order quotation / estimated total</div>
    </div>

    <table class="top-info">
        <tr>
            <td>
                <div class="section-title">Order Information</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Order Number</td>
                        <td>{{ $order->order_no }}</td>
                    </tr>
                    <tr>
                        <td class="label">Date</td>
                        <td>{{ $order->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Order Status</td>
                        <td>{{ ucwords(str_replace('_', ' ', $order->order_status ?? 'order_pending')) }}</td>
                    </tr>
                </table>
            </td>

            <td>
                <div class="section-title">Customer Information</div>
                <table class="info-table">
                    <tr>
                        <td class="label">Name</td>
                        <td>{{ $order->customer->personal_first_name ?? '-' }} {{ $order->customer->personal_last_name ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Email</td>
                        <td>{{ $order->customer->personal_email ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Phone</td>
                        <td>{{ $order->customer->personal_phone ?? '-' }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <div class="section-title">Items</div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 45%;">Product</th>
                <th style="width: 15%;">Qty</th>
                <th style="width: 20%;">Unit Price</th>
                <th style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                @php
                    $options = is_array($item->options) ? $item->options : json_decode($item->options ?? '[]', true);
                    $qty = $item->qty ?? $item->quantity ?? 0;
                    $itemTotal = $item->item_total ?? ($qty * ($item->unit_price ?? 0));
                    $unitPrice = $qty > 0 ? ($itemTotal / $qty) : ($item->unit_price ?? 0);
                @endphp
                <tr>
                    <td>
                        <strong>{{ $item->product_name_snapshot ?? $item->product_name }}</strong>
                        @if(!empty($options))
                            @foreach($options as $option)
                                <div class="option-line">
                                    {{ $option['group_name'] ?? '-' }}:
                                    {{ $option['option_name'] ?? '-' }}
                                    @if(!empty($option['variant_name']))
                                        {{ $option['variant_name'] }}
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </td>
                    <td>{{ $qty }}</td>
                    <td class="text-right">&yen;{{ number_format($unitPrice, 2) }}</td>
                    <td class="text-right">&yen;{{ number_format($itemTotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <td>Subtotal</td>
            <td class="text-right">&yen;{{ number_format($order->subtotal ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>Shipping</td>
            <td class="text-right">&yen;{{ number_format($order->shipping_fee ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td>VAT</td>
            <td class="text-right">&yen;{{ number_format($order->vat_amount ?? 0, 2) }}</td>
        </tr>
        <tr class="grand-total">
            <td>Estimated Total</td>
            <td class="text-right">&yen;{{ number_format($order->grand_total ?? 0, 2) }}</td>
        </tr>
    </table>

    <div class="footer">
        This quotation is generated from the current order details.
    </div>
</body>
</html>
