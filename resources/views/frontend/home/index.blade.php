@extends('frontend.layouts.app')

@section('title', 'ThaiSilk')

@section(
    'meta_description',
    '伝統的なタイシルクを現代のライフスタイルに合わせたバッグや財布、アクセサリーをご紹介します。'
)

@section('body-class', 'home-page')

@section('css')
  {{-- FullCalendar 7 --}}
    <link
        href="https://cdn.jsdelivr.net/npm/fullcalendar@7.0.0/skeleton.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/fullcalendar@7.0.0/themes/monarch/theme.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.jsdelivr.net/npm/fullcalendar@7.0.0/themes/monarch/palettes/purple.css"
        rel="stylesheet"
    >
    <link
        rel="stylesheet"
        href="{{ asset('assets/css/home.css') }}"
    >
@endsection

@section('content')

    <section class="home-hero">

        {{-- Banner Image --}}
        <picture class="home-hero-picture">
            <source
                media="(max-width: 767.98px)"
                srcset="{{ asset('assets/images/home/Rectangle 1.png') }}"
            >

            <img
                src="{{ asset('assets/images/home/Rectangle 1.png') }}"
                alt="タイシルクを使用したバッグや財布"
                class="home-hero-image"
            >
        </picture>

        {{-- Overlay --}}
        <div class="home-hero-overlay"></div>

        {{-- Content --}}
        <div class="container home-hero-container">
            <div class="row h-100 align-items-center">
                <div class="col-12 col-md-9 col-lg-7">

                    <div class="home-hero-content">

                        <h1 class="home-hero-title">
                            伝統を現代のスタイルに。
                        </h1>

                        <p class="home-hero-description">
                            タイシルクが紡ぐ、新しいエレガンスの形。
                        </p>

                        <a
                            href="{{ url('/products') }}"
                            class="btn home-hero-button"
                        >
                            今すぐ見る
                        </a>

                    </div>

                </div>
            </div>
        </div>

    </section>

       <section class="home-concept">

        {{-- ลายตกแต่งมุมขวาบน --}}
        <div
            class="home-concept-decoration"
            aria-hidden="true"
        >
            <img
                src="{{ asset('assets/images/home/game-icons_vanilla-flower.png') }}"
                alt=""
                width="220"
                height="220"
            >
        </div>

        <div class="container">

            {{-- หัวข้อ Section --}}
            <div class="home-concept-heading text-center">

                <p class="home-concept-eyebrow">
                    CONCEPT
                </p>

                <h2 class="home-concept-title">
                    コンセプト
                </h2>

            </div>

            <div class="row justify-content-center align-items-center home-concept-row">

                {{-- รูปภาพ --}}
                <div class="col-12 col-lg-5">

                    <div class="home-concept-images">
                        <img
                            src="{{ asset('assets/images/home/Group 1659.png') }}"
                            alt="タイシルクのコンセプト"
                            class="home-concept-image"
                            width="370"
                            height="292"
                        >
                    </div>



                </div>

                {{-- กล่องข้อความ --}}
                <div class="col-12 col-lg-5">

                    <div class="home-concept-card">

                        <h3 class="home-concept-card-title">
                            日常にプレミアムな輝きを
                        </h3>

                        <div class="home-concept-divider"></div>

                        <div class="home-concept-description">

                            <p>
                                タイシルクならではの美しい光沢と豊かな風合いを、
                                誰もが日常で楽しめる上質なアイテムに仕上げました。
                            </p>

                            <p>
                                日々のあらゆるシーンで個性を引き立て、
                                洗練された大人の気品とプレミアムな佇まいを演出します。
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- ========================================
    Our Collection Section
======================================== --}}

<section class="home-collection">

    {{-- ลายตกแต่งมุมซ้ายล่าง --}}
    <div
        class="home-collection-decoration home-collection-decoration-left"
        aria-hidden="true"
    >
        <img
            src="{{ asset('assets/images/home/ph_flower-lotus-thin.png') }}"
            alt=""
            width="227"
            height="250"
        >
    </div>

    {{-- ลายตกแต่งด้านขวา --}}
    <div
        class="home-collection-decoration home-collection-decoration-right"
        aria-hidden="true"
    >
        <img
            src="{{ asset('assets/images/home/game-icons_vanilla-flower.png') }}"
            alt=""
            width="220"
            height="220"
        >
    </div>

    <div class="container position-relative">

        {{-- Section Heading --}}
        <div class="home-collection-heading text-center">

            <p class="home-collection-eyebrow">
                OUR COLLECTION
            </p>

            <h2 class="home-collection-title">
                私たちのコレクション
            </h2>

        </div>

        {{-- Product List --}}
        <div class="home-collection-grid">

            <div class="row g-3 justify-content-center">

                {{-- Product 1 --}}
                <div class="col-12 col-sm-6 col-lg-4">

                    <article class="collection-product-card h-100">

                        <a
                            href="{{ url('/products/id-case') }}"
                            class="collection-product-image-link"
                        >
                            <img
                                src="{{ asset('assets/images/home/Rectangle 158.png') }}"
                                alt="シルク製IDケース"
                                class="collection-product-image collection-product-image-default"
                            >
                            {{-- Mockup hover image: replace src later --}}
                            <img
                                src="{{ asset('assets/images/home/Rectangle 158 (1).png') }}"
                                alt=""
                                class="collection-product-image collection-product-image-hover"
                                aria-hidden="true"
                            >
                        </a>

                        <div class="collection-product-body">

                            <h3 class="collection-product-title">
                                <a href="{{ url('/products/id-case') }}">
                                    ID case
                                </a>
                            </h3>

                            <p class="collection-product-price">
                                単価：132円(税込)〜
                            </p>

                            <span class="collection-product-delivery">
                                10営業日〜20営業日
                            </span>

                        </div>

                    </article>

                </div>

                {{-- Product 2 --}}
                <div class="col-12 col-sm-6 col-lg-4">

                    <article class="collection-product-card h-100">

                        <a
                            href="{{ url('/products/key-ring') }}"
                            class="collection-product-image-link"
                        >
                            <img
                                src="{{ asset('assets/images/home/Rectangle 160.png') }}"
                                alt="タイシルクのキーホルダー"
                                class="collection-product-image collection-product-image-default"
                            >
                            {{-- Mockup hover image: replace src later --}}
                            <img
                                src="{{ asset('assets/images/home/Rectangle 160 (1).png') }}"
                                alt=""
                                class="collection-product-image collection-product-image-hover"
                                aria-hidden="true"
                            >
                        </a>

                        <div class="collection-product-body">

                            <h3 class="collection-product-title">
                                <a href="{{ url('/products/key-ring') }}">
                                    key ring
                                </a>
                            </h3>

                            <p class="collection-product-price">
                                単価：132円(税込)〜
                            </p>

                            <span class="collection-product-delivery">
                                10営業日〜20営業日
                            </span>

                        </div>

                    </article>

                </div>

                {{-- Product 3 --}}
                <div class="col-12 col-sm-6 col-lg-4">

                    <article class="collection-product-card h-100">

                        <a
                            href="{{ url('/products/business-card-holder') }}"
                            class="collection-product-image-link"
                        >
                            <img
                                src="{{ asset('assets/images/home/Rectangle 162.png') }}"
                                alt="タイシルクの名刺入れ"
                                class="collection-product-image collection-product-image-default"
                            >
                            {{-- Mockup hover image: replace src later --}}
                            <img
                                src="{{ asset('assets/images/home/Rectangle 162 (1).png') }}"
                                alt=""
                                class="collection-product-image collection-product-image-hover"
                                aria-hidden="true"
                            >
                        </a>

                        <div class="collection-product-body">

                            <h3 class="collection-product-title">
                                <a href="{{ url('/products/business-card-holder') }}">
                                    Business Card Holder
                                </a>
                            </h3>

                            <p class="collection-product-price">
                                単価：132円(税込)〜
                            </p>

                            <span class="collection-product-delivery">
                                10営業日〜20営業日
                            </span>

                        </div>

                    </article>

                </div>

            </div>

        </div>

    </div>

</section>

{{-- Product Stories --}}
<section class="home-product-stories">
    <img
        src="{{ asset('assets/images/home/game-icons_vanilla-flower.png') }}"
        alt=""
        class="home-product-stories-decoration"
        aria-hidden="true"
    >

    <div class="container home-product-stories-inner">
        <article class="home-product-story-row">
            <a
                href="{{ url('/products/id-case') }}"
                class="home-product-story-image"
            >
                <img
                    src="{{ asset('assets/images/home/Rectangle 158.png') }}"
                    alt="シルク製社員証ケース"
                >
            </a>

            <div class="home-product-story-content">
                <h2 class="home-product-story-title">
                    シルク製社員証ケース
                </h2>

                <p>
                    上質なタイシルクを使用した贅沢な社員証ケース。クリア窓付きで実用性も抜群。
                    毎日の社員証をスタイリッシュに彩ります。
                </p>
            </div>
        </article>

        <article class="home-product-story-row home-product-story-row-reverse">
            <div class="home-product-story-content">
                <h2 class="home-product-story-title">
                    key ring
                </h2>

                <p>
                    美しい光沢のタイシルクと、耐久性に優れた高品質な金具を組み合わせたキーホルダー。
                    シンプルなデザインながらも存在感があり、鍵やバッグに洗練されたアクセントを加えます。
                </p>
            </div>

            <a
                href="{{ url('/products/key-ring') }}"
                class="home-product-story-image"
            >
                <img
                    src="{{ asset('assets/images/home/Rectangle 160.png') }}"
                    alt="タイシルクのキーホルダー"
                >
            </a>
        </article>

        <article class="home-product-story-row">
            <a
                href="{{ url('/products/business-card-holder') }}"
                class="home-product-story-image"
            >
                <img
                    src="{{ asset('assets/images/home/Rectangle 162.png') }}"
                    alt="タイシルクの名刺入れ"
                >
            </a>

            <div class="home-product-story-content">
                <h2 class="home-product-story-title">
                    Business Card Holder
                </h2>

                <p>
                    上質なタイシルクを贅沢に使用し、丁寧に仕立てた布製の名刺入れ。シルクならではの
                    柔らかな手触りと美しい光沢が、手に取るたびに優雅な印象を与えます。
                </p>

                <p>
                    スリムで携帯性に優れながらも、大切な名刺やカードを優しく守る、
                    ビジネスライフの上質なパートナーです。
                </p>
            </div>
        </article>
    </div>
</section>
{{-- ========================================
    Business Calendar Section
======================================== --}}

<section class="home-calendar">

    {{-- ลายตกแต่งด้านขวา --}}
    <div
        class="home-calendar-decoration"
        aria-hidden="true"
    >
        <svg
            viewBox="0 0 180 180"
            xmlns="http://www.w3.org/2000/svg"
        >
            <g
                fill="none"
                stroke="currentColor"
                stroke-width="9"
                stroke-linecap="round"
                stroke-linejoin="round"
            >
                <path
                    d="M87 101C58 96 38 73 32 43C61 43 84 61 93 89"
                />

                <path
                    d="M93 89C98 54 119 31 148 26C151 58 138 84 111 101"
                />

                <path
                    d="M111 101C133 116 145 137 143 156"
                />

                <path
                    d="M103 148H153"
                />
            </g>
        </svg>
    </div>

    <div class="container">

        {{-- Heading --}}
        <div class="home-calendar-heading text-center">

            <p class="home-calendar-eyebrow">
                CALENDAR
            </p>

            <h2 class="home-calendar-title">
                営業日カレンダー
            </h2>

            <p class="home-calendar-subtitle">
                タイシルク製品のカスタムオーダー及び発送スケジュールをご確認ください
            </p>

        </div>

        {{-- Calendars --}}
        <div class="business-calendar-wrapper">

            <div class="row justify-content-center g-4 g-lg-5">

                {{-- June --}}
                <div class="col-12 col-md-6">
                    <div class="business-calendar-column">

                        <h3
                            id="businessCalendarCurrentMonth"
                            class="business-calendar-month"
                        >
                        </h3>

                        <div
                            id="businessCalendarCurrent"
                            class="business-calendar"
                        ></div>

                        <div class="calendar-legend calendar-legend-left">

                            <div class="calendar-legend-item">
                                <span class="calendar-legend-color legend-closed"></span>

                                <span>
                                    営業＋生産休業日
                                </span>
                            </div>

                            <div class="calendar-legend-item">
                                <span class="calendar-legend-color legend-production"></span>

                                <span>
                                    営業休業日（生産はあり）
                                </span>
                            </div>

                            <div class="calendar-legend-item">
                                <span class="calendar-legend-color legend-production-open"></span>

                                <span>
                                    生産休業日（営業はあり）
                                </span>
                            </div>

                        </div>

                    </div>
                </div>

                {{-- July --}}
                <div class="col-12 col-md-6">
                    <div class="business-calendar-column">

                        <h3
                            id="businessCalendarNextMonth"
                            class="business-calendar-month"
                        >
                        </h3>

                        <div
                            id="businessCalendarNext"
                            class="business-calendar"
                        ></div>

                        <div class="calendar-information">

                            <p class="calendar-deadline">
                                データ入稿期限：データ入稿完了
                            </p>

                            <div class="calendar-information-row">
                                <span>営業日：</span>

                                <span class="calendar-legend-color legend-closed"></span>
                                <span class="calendar-legend-color legend-production"></span>

                                <span>を除く日</span>
                            </div>

                            <div class="calendar-information-row">
                                <span>営業日：</span>

                                <span class="calendar-legend-color legend-closed"></span>
                                <span class="calendar-legend-color legend-production-open"></span>

                                <span>を除く日</span>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</section>
@endsection

@section('js')
    {{-- FullCalendar --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@7.0.0/all/global.js"></script>

    {{-- Japanese Locale --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@7.0.0/locales/ja/global.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const currentMonthDate = new Date();
            currentMonthDate.setDate(1);
            currentMonthDate.setHours(12, 0, 0, 0);

            const nextMonthDate = new Date(
                currentMonthDate.getFullYear(),
                currentMonthDate.getMonth() + 1,
                1,
                12
            );

            function formatMonthLabel(date) {
                return `${date.getMonth() + 1}月 ${date.getFullYear()}`;
            }

            document.getElementById('businessCalendarCurrentMonth').textContent =
                formatMonthLabel(currentMonthDate);
            document.getElementById('businessCalendarNextMonth').textContent =
                formatMonthLabel(nextMonthDate);
            /*
            |--------------------------------------------------------------------------
            | วันที่หยุด
            |--------------------------------------------------------------------------
            */

            const closedDates = [
                // June 2026
                '2026-06-07',
                '2026-06-14',
                '2026-06-21',
                '2026-06-28',

                // July 2026
                '2026-07-05',
                '2026-07-12',
                '2026-07-19',
                '2026-07-26',
            ];

            const productionDates = [
                // June 2026
                '2026-06-06',
                '2026-06-13',
                '2026-06-20',
                '2026-06-27',

                // July 2026
                '2026-07-04',
                '2026-07-11',
                '2026-07-18',
                '2026-07-20',
                '2026-07-25',
            ];

            const productionOpenDates = [
                '2026-06-19',
            ];

            /*
            |--------------------------------------------------------------------------
            | แปลง Date เป็น YYYY-MM-DD
            |--------------------------------------------------------------------------
            */

            function formatLocalDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');

                return `${year}-${month}-${day}`;
            }

            /*
            |--------------------------------------------------------------------------
            | ใส่สีให้ช่องวันที่
            |--------------------------------------------------------------------------
            */

            function decorateDayCell(info) {
                const dateKey = formatLocalDate(info.date);
                const dayOfWeek = info.date.getDay();
                const dayNumberElement = info.el.querySelector(
                    '.fc-daygrid-day-number'
                );

                if (dayNumberElement) {
                    dayNumberElement.textContent = String(info.date.getDate());
                }

                if (dayOfWeek === 0 || closedDates.includes(dateKey)) {
                    info.el.classList.add('calendar-day-closed');
                }

                if (dayOfWeek === 6 || productionDates.includes(dateKey)) {
                    info.el.classList.add('calendar-day-production');
                }

                if (productionOpenDates.includes(dateKey)) {
                    info.el.classList.add('calendar-day-production-open');
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Config ร่วมของปฏิทิน
            |--------------------------------------------------------------------------
            */

            const commonOptions = {
                initialView: 'dayGridMonth',
                locale: 'ja',
                firstDay: 0,

                headerToolbar: false,
                footerToolbar: false,

                height: 'auto',
                contentHeight: 'auto',
                aspectRatio: 1,

                fixedWeekCount: false,
                showNonCurrentDates: false,

                dayHeaders: true,
                dayHeaderFormat: {
                    weekday: 'narrow',
                },

                dayCellTopContent: function (info) {
                    return String(info.date.getDate());
                },

                dayCellDidMount: decorateDayCell,
            };

            /*
            |--------------------------------------------------------------------------
            | June Calendar
            |--------------------------------------------------------------------------
            */

            const currentCalendarElement = document.getElementById(
                'businessCalendarCurrent'
            );

            if (currentCalendarElement) {
                const currentCalendar = new FullCalendar.Calendar(
                    currentCalendarElement,
                    {
                        ...commonOptions,
                        initialDate: formatLocalDate(currentMonthDate),
                    }
                );

                currentCalendar.render();
            }

            /*
            |--------------------------------------------------------------------------
            | July Calendar
            |--------------------------------------------------------------------------
            */

            const nextCalendarElement = document.getElementById(
                'businessCalendarNext'
            );

            if (nextCalendarElement) {
                const nextCalendar = new FullCalendar.Calendar(
                    nextCalendarElement,
                    {
                        ...commonOptions,
                        initialDate: formatLocalDate(nextMonthDate),
                    }
                );

                nextCalendar.render();
            }
        });
    </script>
@endsection