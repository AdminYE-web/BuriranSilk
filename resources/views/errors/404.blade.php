@extends('frontend.layouts.app')

@section('title', 'メンテナンス中 | ThaiSilk')
@section('meta_description', 'ThaiSilkは現在メンテナンス中です。')
@section('body-class', 'maintenance-error-page')

@section('css')
    <meta name="robots" content="noindex, nofollow">
    <style>
        :root {
            --maintenance-background: #f8f7f1;
            --maintenance-text: #3d342b;
            --maintenance-gold: #a7834d;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            min-height: 100%;
        }

        body {
            margin: 0;
            color: var(--maintenance-text);
            background: var(--maintenance-background);
            font-family: "Noto Serif JP", "Yu Mincho", "Hiragino Mincho ProN", serif;
        }

        .maintenance-page {
            position: relative;
            z-index: 0;
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            justify-content: center;
            overflow: hidden;
            padding: 46px 24px 38px;
        }

        .maintenance-content {
            position: relative;
            z-index: 2;
            width: min(100%, 760px);
            text-align: center;
        }

        .maintenance-logo {
            display: block;
            width: 100px;
            height: auto;
            margin: 0 auto;
            filter: brightness(0) saturate(100%) invert(53%) sepia(30%) saturate(820%) hue-rotate(359deg) brightness(92%) contrast(85%);
        }

        .maintenance-illustration {
            display: block;
            width: min(42vw, 220px);
            height: auto;
            margin: 16px auto 18px;
        }

        .maintenance-title {
            margin: 0;
            font-size: 19px;
            font-weight: 600;
            letter-spacing: .12em;
        }

        .maintenance-lead {
            margin: 18px 0 0;
            font-size: 12px;
            font-weight: 300;
            line-height: 2;
            letter-spacing: .06em;
        }

        .maintenance-message {
            width: min(100%, 620px);
            margin: 22px auto 0;
            border: 1px solid var(--maintenance-gold);
            box-shadow:
                inset 0 0 0 3px var(--maintenance-background),
                inset 0 0 0 4px rgba(167, 131, 77, .55);
            padding: 34px 42px;
            font-size: 11px;
            font-weight: 300;
            line-height: 2.15;
            letter-spacing: .045em;
        }

        .maintenance-message p {
            margin: 0;
        }

        .maintenance-message p + p {
            margin-top: 12px;
        }

        .maintenance-contact {
            width: min(100%, 620px);
            margin: 22px auto 0;
            text-align: left;
            font-size: 10px;
            font-style: normal;
            font-weight: 300;
            line-height: 1.9;
            letter-spacing: .035em;
        }

        .maintenance-contact p {
            margin: 0;
        }

        .maintenance-contact a {
            color: inherit;
            text-decoration: none;
        }

        .maintenance-contact a:hover,
        .maintenance-contact a:focus-visible {
            color: var(--maintenance-gold);
            text-decoration: underline;
        }

        .maintenance-decoration {
            position: absolute;
            z-index: 1;
            display: block;
            height: auto;
            opacity: .15;
            pointer-events: none;
            user-select: none;
        }

        .maintenance-decoration-top-left {
            top: 92px;
            left: 14%;
            width: 112px;
        }

        .maintenance-decoration-top-right {
            top: -35px;
            right: 4%;
            width: 145px;
        }

        .maintenance-decoration-middle-right {
            top: 43%;
            right: 8%;
            width: 128px;
        }

        .maintenance-decoration-middle-left {
            top: 47%;
            left: -34px;
            width: 136px;
        }

        .maintenance-decoration-bottom-right {
            right: -28px;
            bottom: 0;
            width: 142px;
        }

        .maintenance-decoration-bottom-center {
            bottom: -78px;
            left: 19%;
            width: 150px;
        }

        @media (max-width: 700px) {
            .maintenance-page {
                padding: 42px 18px 26px;
            }

            .maintenance-logo {
                width: 96px;
            }

            .maintenance-illustration {
                width: min(52vw, 195px);
                margin: 8px auto 24px;
            }

            .maintenance-title {
                font-size: 14px;
                line-height: 1.7;
            }

            .maintenance-lead {
                margin-top: 14px;
                font-size: 9px;
                line-height: 1.8;
            }

            .maintenance-message {
                width: min(100%, 382px);
                margin-top: 30px;
                padding: 27px 28px;
                font-size: 8px;
                line-height: 2;
            }

            .maintenance-message p + p {
                margin-top: 6px;
            }

            .maintenance-contact {
                width: min(100%, 382px);
                margin-top: 14px;
                font-size: 7.5px;
                line-height: 1.8;
            }

            .maintenance-decoration-top-left,
            .maintenance-decoration-middle-right,
            .maintenance-decoration-bottom-center {
                display: none;
            }

            .maintenance-decoration-top-right,
            .maintenance-decoration-middle-left,
            .maintenance-decoration-bottom-right {
                width: 105px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="maintenance-page">
        <img class="maintenance-decoration maintenance-decoration-top-left" src="{{ asset('assets/images/ph_flower-lotus-thin (1).png') }}" alt="" aria-hidden="true">
        <img class="maintenance-decoration maintenance-decoration-top-right" src="{{ asset('assets/images/game-icons_vanilla-flower (1).png') }}" alt="" aria-hidden="true">
        <img class="maintenance-decoration maintenance-decoration-middle-right" src="{{ asset('assets/images/ph_flower-lotus-thin (2).png') }}" alt="" aria-hidden="true">
        <img class="maintenance-decoration maintenance-decoration-middle-left" src="{{ asset('assets/images/game-icons_vanilla-flower (2).png') }}" alt="" aria-hidden="true">
        <img class="maintenance-decoration maintenance-decoration-bottom-right" src="{{ asset('assets/images/ph_flower-tulip-light (1).png') }}" alt="" aria-hidden="true">
        <img class="maintenance-decoration maintenance-decoration-bottom-center" src="{{ asset('assets/images/ph_flower-lotus-thin (1).png') }}" alt="" aria-hidden="true">

        <div class="maintenance-content">
            <img class="maintenance-logo" src="{{ asset('assets/images/logo/logo_thaisilk.png') }}" alt="ThaiSilk">
            <img class="maintenance-illustration" src="{{ asset('assets/images/image-Photoroom (26) 1.png') }}" alt="" aria-hidden="true">

            <h1 class="maintenance-title">ただいまサイトメンテナンス中です</h1>
            <p class="maintenance-lead">より良いサービス向上のため、一時的にサイトを停止しております。</p>

            <section class="maintenance-message" aria-label="メンテナンスのお知らせ">
                <p>日頃よりThaiSilkをご愛顧いただき、誠にありがとうございます。</p>
                <p>伝統を現代のスタイルに、より洗練された美しさと彩りをお届けするため、現在サイトの改修を行っております。</p>
                <p>ご不便をおかけしますが、再オープンまで今しばらくお待ちください。</p>
            </section>

            <address class="maintenance-contact">
                <p>お急ぎの方、またはご質問がある方は、下記よりお問い合わせください。</p>
                <p>メール：<a href="mailto:contact@silicone-wristband-studio.jp">contact@silicone-wristband-studio.jp</a></p>
                <p>電話：<a href="tel:05068657753">050-6865-7753</a>（10:00〜18:00／土日祝休業）</p>
            </address>
        </div>
    </section>
@endsection
