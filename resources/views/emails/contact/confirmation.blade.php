<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>お問い合わせを受け付けました</title>
</head>
<body style="margin:0;padding:30px;background:#f7f4f2;color:#403834;font-family:Arial,'Noto Sans JP',sans-serif;line-height:1.8;">
    <div style="max-width:640px;margin:0 auto;padding:34px;background:#ffffff;border:1px solid #e3d9d3;">
        <h1 style="margin:0 0 24px;font-size:20px;">お問い合わせを受け付けました</h1>
        <p>{{ $submission->name }} 様</p>
        <p>お問い合わせいただき、ありがとうございます。通常1〜2営業日以内に担当者よりご返信いたします。</p>

        <table style="width:100%;margin:26px 0;border-collapse:collapse;font-size:14px;">
            <tr>
                <th style="width:150px;padding:10px;text-align:left;background:#f8f4f2;border:1px solid #e7dfda;">お問い合わせ種別</th>
                <td style="padding:10px;border:1px solid #e7dfda;">{{ $submission->subject }}</td>
            </tr>
            <tr>
                <th style="padding:10px;text-align:left;background:#f8f4f2;border:1px solid #e7dfda;">注文番号</th>
                <td style="padding:10px;border:1px solid #e7dfda;">{{ $submission->order_number ?: '-' }}</td>
            </tr>
            <tr>
                <th style="padding:10px;text-align:left;background:#f8f4f2;border:1px solid #e7dfda;">お問い合わせ内容</th>
                <td style="padding:10px;border:1px solid #e7dfda;">{!! nl2br(e($submission->message)) !!}</td>
            </tr>
        </table>

        <p style="margin-bottom:0;color:#786d66;font-size:12px;">※このメールはお問い合わせの受付確認として自動送信されています。</p>
    </div>
</body>
</html>
