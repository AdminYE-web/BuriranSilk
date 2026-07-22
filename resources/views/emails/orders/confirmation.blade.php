<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ご注文確認</title>
</head>
<body style="margin:0;padding:0;background:#f4f2ed;color:#444;font-family:'Yu Mincho','Hiragino Mincho ProN',serif;">
    <div style="width:100%;padding:32px 12px;box-sizing:border-box;">
        <div style="max-width:720px;margin:0 auto;padding:32px;background:#fff;border:1px solid #d5bd95;border-radius:8px;box-sizing:border-box;">
            <h1 style="margin:0 0 18px;color:#8d682f;font-size:22px;">ご注文ありがとうございます</h1>
            <p>{{ $order->customer->personal_name ?: $order->customer->personal_first_name }} 様</p>
            <p>以下の内容でご注文を承りました。</p>
            <p style="padding:14px;background:#faf7f1;border-left:4px solid #b58a46;"><strong>ご注文番号：</strong>{{ $order->order_no }}</p>

            <h2 style="margin-top:28px;color:#8d682f;font-size:17px;">ご注文製品情報</h2>
            @foreach ($order->items as $index => $item)
                <div style="padding:16px 0;border-bottom:1px solid #eee;">
                    <p style="margin:0 0 7px;"><strong>【商品 {{ $index + 1 }}】{{ $item->product_name ?: $item->product_name_snapshot }}</strong></p>
                    <p style="margin:3px 0;">数量：{{ number_format($item->quantity ?: $item->qty) }}</p>
                    @foreach ($item->optionDetails as $option)
                        <p style="margin:3px 0;">{{ $option->group_name_snapshot }}：{{ $option->option_name_snapshot }}</p>
                    @endforeach
                    @foreach ($item->configuration['previous_order_numbers'] ?? [] as $number)
                        <p style="margin:3px 0;">前回ご注文管理番号：{{ $number }}</p>
                    @endforeach
                    @foreach ($item->configuration['font_entries'] ?? [] as $entries)
                        @foreach ($entries as $entry)
                            <p style="margin:3px 0;">印刷文字：{{ $entry['text'] ?? '-' }} / 書体：{{ $entry['font'] ?? '-' }} / {{ $entry['size'] ?? '-' }}pt</p>
                        @endforeach
                    @endforeach
                    @foreach ($order->artworks->where('order_item_id', $item->order_item_id) as $artwork)
                        <p style="margin:3px 0;">入稿データ：{{ $artwork->original_name ?: '-' }}</p>
                    @endforeach
                    @foreach ($item->configuration['custom_fields'] ?? [] as $field => $value)
                        <p style="margin:3px 0;">{{ \Illuminate\Support\Str::headline($field) }}：{{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</p>
                    @endforeach
                    <p style="margin:7px 0 0;text-align:right;">{{ number_format($item->item_total) }} 円</p>
                </div>
            @endforeach

            <h2 style="margin-top:28px;color:#8d682f;font-size:17px;">お客様情報</h2>
            <p style="margin:3px 0;">お名前：{{ $order->customer->personal_name ?: $order->customer->personal_first_name }}</p>
            <p style="margin:3px 0;">フリガナ：{{ $order->customer->personal_name_kana ?: '-' }}</p>
            @if ($order->customer->company_name)<p style="margin:3px 0;">会社名：{{ $order->customer->company_name }}</p>@endif
            <p style="margin:3px 0;">E-mail：{{ $order->customer->personal_email }}</p>
            <p style="margin:3px 0;">TEL：{{ $order->customer->personal_phone }}</p>
            <p style="margin:3px 0;">郵便番号：{{ $order->customer->personal_postcode ?: '-' }}</p>
            <p style="margin:3px 0;">住所：{{ $order->customer->personal_province }}{{ $order->customer->personal_city }}{{ $order->customer->personal_area }}</p>

            <h2 style="margin-top:28px;color:#8d682f;font-size:17px;">お届け先情報</h2>
            <p style="margin:3px 0;">お名前：{{ $order->customer->shipping_name ?: '-' }}</p>
            <p style="margin:3px 0;">フリガナ：{{ $order->customer->shipping_name_kana ?: '-' }}</p>
            <p style="margin:3px 0;">郵便番号：{{ $order->customer->shipping_postcode ?: '-' }}</p>
            <p style="margin:3px 0;">住所：{{ $order->customer->shipping_province }}{{ $order->customer->shipping_city }}{{ $order->customer->shipping_area }}</p>

            <h2 style="margin-top:28px;color:#8d682f;font-size:17px;">請求先情報</h2>
            <p style="margin:3px 0;">お名前：{{ $order->customer->billing_name ?: '-' }}</p>
            <p style="margin:3px 0;">フリガナ：{{ $order->customer->billing_name_kana ?: '-' }}</p>
            <p style="margin:3px 0;">郵便番号：{{ $order->customer->billing_postcode ?: '-' }}</p>
            <p style="margin:3px 0;">住所：{{ $order->customer->billing_province }}{{ $order->customer->billing_city }}{{ $order->customer->billing_area }}</p>

            <h2 style="margin-top:28px;color:#8d682f;font-size:17px;">お支払い・合計</h2>
            <p style="margin:3px 0;">お支払い方法：{{ $order->payment->payment_method === 'bank_transfer' ? '銀行振込' : $order->payment->payment_method }}</p>
            <p style="margin:3px 0;">商品小計：{{ number_format($order->subtotal) }} 円</p>
            <p style="margin:3px 0;">送料：{{ number_format($order->shipping_fee) }} 円</p>
            <p style="margin:3px 0;">消費税：{{ number_format($order->vat_amount) }} 円</p>
            <p style="margin:9px 0;font-size:18px;"><strong>合計（税込）：{{ number_format($order->grand_total) }} 円</strong></p>

            <h2 style="margin-top:28px;color:#8d682f;font-size:17px;">その他情報</h2>
            <p style="margin:3px 0;">情報入力方法：{{ $order->info_method ?: '-' }}</p>
            @if ($order->signature_text)<p style="margin:3px 0;white-space:pre-line;">メール署名：{{ $order->signature_text }}</p>@endif
            <p style="margin:3px 0;">個別発送：{{ $order->delivery_option ? '希望する' : '希望しない' }}</p>
            <p style="margin:3px 0;">製作実績掲載：{{ $order->publish_website ? '希望する' : '希望しない' }}</p>
            <p style="margin:3px 0;">メールマガジン：{{ $order->newsletter ? '受け取る' : '受け取らない' }}</p>
            @if ($order->notes)<p style="margin:3px 0;white-space:pre-line;">ご連絡事項：{{ $order->notes }}</p>@endif

            <p style="margin-top:30px;color:#777;font-size:12px;">本メールはご注文時に自動送信されています。</p>
        </div>
    </div>
</body>
</html>