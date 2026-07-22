<!DOCTYPE html>
<html lang="{{ $isEnglish ? 'en' : 'ja' }}">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    @php
        $fontPath = storage_path('fonts/NotoSansJP-Light.ttf');

        $fontUrl = str_replace('\\', '/', $fontPath);

        $logoPath = public_path($company['logo'] ?? 'assets/images/logo/logo_thaisilk.png');

        $logoUrl = str_replace('\\', '/', $logoPath);
    @endphp

    <style>
        @page {
            margin: 20px 22px;
        }

        @font-face {
            font-family: "NotoSansJP";
            src: url("{{ $fontUrl }}") format("truetype");
            font-style: normal;
            font-weight: normal;
        }

        @font-face {
            font-family: "NotoSansJP";
            src: url("{{ $fontUrl }}") format("truetype");
            font-style: normal;
            font-weight: bold;
        }

        * {
            box-sizing: border-box;
            font-family: "NotoSansJP", sans-serif;
        }

        html,
        body {
            margin: 0;
            color: #111;
            font-family: "NotoSansJP", sans-serif;
            font-weight: normal;
            font-size: 9px;
            line-height: 1.35;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 3px 5px;
            border: 1px solid #111;
            vertical-align: top;
        }

        th {
            background: #efefef;
            font-weight: normal;
        }

        .header-table td {
            border: 0;
        }

        .logo {
            width: 180px;
            max-height: 55px;
            object-fit: contain;
        }

        .company-information {
            text-align: right;
            font-size: 8px;
        }

        .quotation-title {
            margin: 8px 0 1px;
            text-align: center;
            font-size: 21px;
            font-weight: normal;
        }

        .quotation-message {
            margin: 0 0 5px;
            text-align: center;
        }

        .section-title {
            margin-top: 10px;
            padding: 3px 5px;
            border: 1px solid #111;
            background: #dedede;
            text-align: center;
            font-size: 11px;
        }

        .customer-name {
            font-size: 11px;
        }

        .total-box {
            margin-top: 10px;
            border: 2px solid #111;
        }

        .total-box td {
            border: 0;
        }

        .total-label {
            width: 35%;
            background: #e5e5e5;
            text-align: center;
            font-size: 13px;
        }

        .total-value {
            text-align: center;
            font-size: 18px;
        }

        .number {
            text-align: right;
            white-space: nowrap;
        }

        .center {
            text-align: center;
        }

        .summary-table {
            width: 42%;
            margin-left: auto;
        }

        .summary-table th {
            text-align: left;
        }

        .summary-table td {
            text-align: right;
        }

        .note {
            margin: 5px 0;
        }

        .bank-grid {
            width: 100%;
        }

        .bank-grid td {
            width: 50%;
            padding: 5px;
        }

        .bank-title {
            background: #efefef;
            text-align: center;
            font-weight: normal;
        }

        .avoid-break {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>
    <table class="header-table">
        <tr>
            <td style="width: 45%;">
                @if (file_exists($logoPath))
                    <img src="{{ $logoUrl }}" class="logo" alt="ThaiSilk">
                @endif
            </td>

            <td class="company-information">
                <strong>
                    {{ $isEnglish ? $company['name'] ?? 'ThaiSilk' : $company['name_ja'] ?? 'ThaiSilk' }}
                </strong><br>

                {{ $company['address'] ?? '' }}<br>
                {{ $company['phone'] ?? '' }}
                {{ $company['email'] ?? '' }}
            </td>
        </tr>
    </table>

    <h1 class="quotation-title">
        {{ $isEnglish ? 'QUOTATION' : '御見積書' }}
    </h1>

    <p class="quotation-message">
        {{ $isEnglish ? 'Thank you very much for your inquiry.' : 'この度はお見積のご請求、誠にありがとうございます。' }}
    </p>

    <div class="section-title">
        {{ $isEnglish ? 'Customer information' : 'お客様情報' }}
    </div>

    <table>
        <tr>
            <th style="width: 18%;">
                {{ $isEnglish ? 'Customer' : 'お客様法人名' }}
            </th>
            <td class="customer-name" style="width: 47%;">
                @if (!empty($customer['company_name']))
                    {{ $customer['company_name'] }}<br>
                @endif

                {{ $customer['last_name'] }}
                {{ $customer['first_name'] }}
                {{ $isEnglish ? '' : '様' }}
            </td>

            <th style="width: 15%;">
                {{ $isEnglish ? 'Issue date' : '発行日' }}
            </th>
            <td style="width: 20%;">
                {{ $issuedAt->format('Y/m/d') }}
            </td>
        </tr>

        <tr>
            <th>{{ $isEnglish ? 'Address' : 'ご住所' }}</th>
            <td>
                〒{{ $customer['postal_code'] }}<br>
                {{ $customer['prefecture'] }}
                {{ $customer['address'] }}
                {{ $customer['building'] ?? '' }}
            </td>

            <th>{{ $isEnglish ? 'Quotation No.' : '見積番号' }}</th>
            <td>{{ $quotationNumber }}</td>
        </tr>

        <tr>
            <th>{{ $isEnglish ? 'Telephone' : '電話' }}</th>
            <td>{{ $customer['phone'] }}</td>

            <th>{{ $isEnglish ? 'Valid until' : '有効期限' }}</th>
            <td>{{ $validUntil->format('Y/m/d') }}</td>
        </tr>

        @if (!empty($customer['memo']))
            <tr>
                <th>{{ $isEnglish ? 'Memo' : 'お客様メモ' }}</th>
                <td colspan="3">{{ $customer['memo'] }}</td>
            </tr>
        @endif
    </table>

    @foreach ($items as $item)
        <div class="avoid-break">
            <div class="section-title">
                {{ $isEnglish ? 'Product specification' : '製作仕様' }}
                - {{ $item['product_name'] }}
            </div>

            <table>
                <tr>
                    <th style="width: 25%;">
                        {{ $isEnglish ? 'Product' : '商品名' }}
                    </th>
                    <td colspan="3">{{ $item['product_name'] }}</td>
                </tr>

                @foreach ($item['selected_options'] ?? [] as $option)
                    <tr>
                        <th>{{ $option['group_name'] }}</th>
                        <td colspan="3">
                            {{ $option['option_name'] }}

                            @if (!empty($option['option_detail']))
                                - {{ $option['option_detail'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endforeach

    <table class="total-box">
        <tr>
            <td class="total-label">
                {{ $isEnglish ? 'Quotation total' : '御見積金額' }}
            </td>
            <td class="total-value">
                ¥ {{ number_format($summary['total']) }}
                {{ $isEnglish ? '(Tax included)' : '（税込）' }}
            </td>
        </tr>
    </table>

    <div class="section-title">
        {{ $isEnglish ? 'Price details' : '製作料金' }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th>
                    {{ $isEnglish ? 'Description' : '商品名' }}
                </th>
                <th style="width: 10%;">
                    {{ $isEnglish ? 'Qty' : '数量' }}
                </th>
                <th style="width: 16%;">
                    {{ $isEnglish ? 'Unit price' : '単価' }}
                </th>
                <th style="width: 18%;">
                    {{ $isEnglish ? 'Amount' : '小計' }}
                </th>
            </tr>
        </thead>

        <tbody>
            @php
                $lineNumber = 1;
            @endphp

            @foreach ($items as $item)
                <tr>
                    <td class="center">{{ $lineNumber++ }}</td>
                    <td>{{ $item['product_name'] }}</td>
                    <td class="number">{{ number_format($item['quantity']) }}</td>
                    <td class="number">
                        ¥ {{ number_format($item['base_unit_price']) }}
                    </td>
                    <td class="number">
                        ¥ {{ number_format($item['base_subtotal']) }}
                    </td>
                </tr>

                @foreach ($item['selected_options'] ?? [] as $option)
                    @php
                        $optionQuantity = ($option['price_type'] ?? 'per_item') === 'per_order' ? 1 : $item['quantity'];
                    @endphp

                    <tr>
                        <td class="center">{{ $lineNumber++ }}</td>
                        <td>
                            {{ $option['group_name'] }}
                            - {{ $option['option_name'] }}
                        </td>
                        <td class="number">
                            {{ number_format($optionQuantity) }}
                        </td>
                        <td class="number">
                            ¥ {{ number_format($option['additional_price']) }}
                        </td>
                        <td class="number">
                            ¥ {{ number_format($option['line_price']) }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <table class="summary-table">
        <tr>
            <th>{{ $isEnglish ? 'Subtotal' : '小計（税別）' }}</th>
            <td>¥ {{ number_format($summary['subtotal']) }}</td>
        </tr>

        <tr>
            <th>{{ $isEnglish ? 'Shipping' : '送料' }}</th>
            <td>¥ {{ number_format($summary['shipping']) }}</td>
        </tr>

        <tr>
            <th>{{ $isEnglish ? 'Tax (10%)' : '消費税（10%）' }}</th>
            <td>¥ {{ number_format($summary['vat']) }}</td>
        </tr>

        <tr>
            <th>{{ $isEnglish ? 'Total' : '合計（税込）' }}</th>
            <td>¥ {{ number_format($summary['total']) }}</td>
        </tr>
    </table>

    <div class="section-title">
        {{ $isEnglish ? 'Other information' : 'その他情報' }}
    </div>

    <table>
        <tr>
            <th style="width: 25%;">
                {{ $isEnglish ? 'Quotation validity' : 'お見積有効期限' }}
            </th>
            <td>
                {{ $isEnglish ? '30 days from the issue date' : '発行日から30日以内' }}
            </td>
        </tr>

        <tr>
            <th>{{ $isEnglish ? 'Delivery' : '納期' }}</th>
            <td>
                {{ $isEnglish
                    ? 'Delivery date will be confirmed after the artwork is approved.'
                    : '原稿確定後に納期をご案内いたします。' }}
            </td>
        </tr>
    </table>

    @if (!empty($company['banks']))
        <div class="section-title">
            {{ $isEnglish ? 'Bank account information' : 'お振込先銀行口座情報' }}
        </div>

        <table class="bank-grid">
            @foreach (array_chunk($company['banks'], 2) as $bankRow)
                <tr>
                    @foreach ($bankRow as $bank)
                        <td>
                            <div class="bank-title">
                                {{ $bank['bank_name'] }}
                            </div>

                            {{ $isEnglish ? 'Branch' : '支店名' }}:
                            {{ $bank['branch'] }}<br>

                            {{ $isEnglish ? 'Account type' : '口座種別' }}:
                            {{ $bank['account_type'] }}<br>

                            {{ $isEnglish ? 'Account number' : '口座番号' }}:
                            {{ $bank['account_number'] }}<br>

                            {{ $isEnglish ? 'Account name' : '口座名義' }}:
                            {{ $bank['account_name'] }}
                        </td>
                    @endforeach

                    @if (count($bankRow) === 1)
                        <td></td>
                    @endif
                </tr>
            @endforeach
        </table>
    @endif
</body>

</html>
