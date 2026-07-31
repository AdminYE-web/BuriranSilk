@extends('frontend.layouts.app')

@section('title', 'ご注文の流れ | ThaiSilk')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/how-to-order.css') }}">
@endsection

@section('content')
    @php
        $steps = [
            [
                'title' => '商品を選ぶ',
                'icon' => 'assets/images/icon/solar_box-bold-duotone (1).png',
                'lines' => [
                    '商品一覧からご希望の商品をお選びください。',
                    '商品ページで特徴、サイズ、仕上がりイメージをご確認いただけます。',
                ],
            ],
            [
                'title' => '仕様・数量を選ぶ',
                'icon' => 'assets/images/icon/clarity_design-solid (1).png',
                'lines' => [
                    'カラーや印刷方法など、ご希望の仕様と数量を選択してください。',
                    '選択内容に応じて、商品価格と納期の目安をご確認いただけます。',
                ],
            ],
            [
                'title' => '注文内容を確認する',
                'icon' => 'assets/images/icon/box-return (1) (1).png',
                'lines' => [
                    '商品をカートに入れ、お届け先とお支払い情報をご入力ください。',
                    '注文内容を確認して「注文を確定する」ボタンを押すと完了です。',
                ],
            ],
        ];
    @endphp

    <section class="how-order-page">
        <div class="how-order-container">
            <header class="how-order-header">
                <h1 class="how-order-title">ご注文の流れ</h1>
                <p class="how-order-intro">
                    ご不明な点がございましたら、まずはこちらをご確認ください。<br>
                    通常1〜2営業日以内に回答をご案内しております。
                </p>
            </header>

            <div class="how-order-steps">
                @foreach ($steps as $step)
                    <article class="how-order-card {{ !$loop->first ? 'has-previous' : '' }} {{ !$loop->last ? 'has-next' : '' }}">
                        <div class="how-order-number" aria-label="ステップ {{ $loop->iteration }}">
                            <span>STEP</span>
                            <strong>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</strong>
                        </div>

                        <div class="how-order-content">
                            <img class="how-order-icon" src="{{ asset($step['icon']) }}" alt="" aria-hidden="true">
                            <h2>{{ $step['title'] }}</h2>
                            <div class="how-order-description">
                                @foreach ($step['lines'] as $line)
                                    <p>{{ $line }}</p>
                                @endforeach
                            </div>
                        </div>
                    </article>

                    @unless ($loop->last)
                        <div class="how-order-connector" aria-hidden="true"></div>
                    @endunless
                @endforeach
            </div>
        </div>
    </section>
@endsection
