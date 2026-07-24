@extends('frontend.layouts.app')

@section('title', '私たちについて | ThaiSilk')
@section('body-class', 'about-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
@endpush

@section('content')
    <div class="about-page-wrapper">
        {{-- Decorative Floating Flower Background Patterns --}}
        <img src="{{ asset('assets/images/ph_flower-lotus-thin (1).png') }}" alt=""
            class="about-flower-decor about-flower-decor-1">
        <img src="{{ asset('assets/images/ph_flower-tulip-light (1).png') }}" alt=""
            class="about-flower-decor about-flower-decor-2">
        <img src="{{ asset('assets/images/ph_flower-lotus-thin (2).png') }}" alt=""
            class="about-flower-decor about-flower-decor-3">
        <img src="{{ asset('assets/images/game-icons_vanilla-flower (1).png') }}" alt=""
            class="about-flower-decor about-flower-decor-4">

        <div class="about-container">
            {{-- Page Header --}}
            <header class="about-header">
                <h1 class="about-title">私たちについて</h1>
                <p class="about-subtitle">会社情報</p>
            </header>

            {{-- Executive Profile Block --}}
            <section class="about-profile-card">
                <div class="about-profile-top">
                    <div class="about-profile-photo">
                        <img src="{{ asset('assets/images/about/image 312.png') }}" alt="代表取締役 門田 正徳">
                    </div>
                    <div class="about-profile-meta">
                        <div class="about-company-name">ユー・アンド・アース株式会社</div>
                        <div class="about-role-title">代表取締役</div>
                        <h2 class="about-exec-name">門田 正徳</h2>
                    </div>
                </div>

                {{-- Biography Section --}}
                <div class="about-bio-container">
                    <div class="about-bio-heading">&lt;&lt;略歴&gt;&gt;</div>

                    <p class="about-bio-paragraph">
                        HOYA株式会社ビジョンケアカンパニーにて、眼鏡レンズの新製品立ち上げや社内情報システム構築に携わる。製品は立ち上げからタイ国アユタヤの工場で生産されたり、システム構築はタイバンコクの開発センターに数百人規模の開発拠点があったりと、何かとタイとの関わりの多い日々を過ごした縁で、タイで会社を設立するきっかけとなった。
                    </p>

                    <p class="about-bio-paragraph">
                        株式会社ブリヂストンにて、自動車用タイヤの製造技術開発に携わる。彦根工場配属で、福岡出身の会社らしい熱い思いの先輩達に囲まれる。ブリストンの社風は、今の会社の基礎となっている。
                    </p>

                    <ul class="about-bio-list">
                        <li>米国オハイオ州立アクロン大学高分子工学科修士卒</li>
                        <li>東京工業大学有機材料工学科卒</li>
                        <li>私立海城高等学校</li>
                        <li>大田区立石川台中学校、大田区立小池小学校</li>
                    </ul>
                </div>
            </section>

            {{-- Company Details Table Section (販売元) --}}
            <section class="about-seller-section">
                <div class="about-section-heading">
                    <div class="about-grid-icon">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                    販売元
                </div>

                <div class="about-table-wrapper">
                    <table class="about-info-table">
                        <tbody>
                            <tr>
                                <th>会社名</th>
                                <td>
                                    <a href="https://youandearth.japan-website.jp/" target="_blank"
                                        rel="noopener noreferrer">
                                        ユー・アンド・アース株式会社
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>所在地</th>
                                <td>〒135-0064 東京都江東区青海2-4-32 TIME24ビル 10階 理1</td>
                            </tr>
                            <tr>
                                <th>大阪オフィス</th>
                                <td>
                                    〒559-0034 大阪府大阪市住之江区南港北2-1-10 ATCビル ITM棟 3階 C-1<br>
                                    TEL：06-7875-6018
                                </td>
                            </tr>
                            <tr>
                                <th>事業内容</th>
                                <td>ノベルティ商品の企画・設計・製造・販売。オリジナルネックストラップの販売。</td>
                            </tr>
                            <tr>
                                <th>連絡先</th>
                                <td>
                                    toiawase@hotstrap.jp<br>
                                    お問い合わせは、上記メールアドレスにお送り頂くか、<a href="{{ route('contact.index') }}">お問い合わせ画面</a>をご利用下さい。
                                </td>
                            </tr>
                            <tr>
                                <th>設立</th>
                                <td>2006年7月</td>
                            </tr>
                            <tr>
                                <th>所属団体</th>
                                <td>東京商工会議所</td>
                            </tr>
                            <tr>
                                <th>適格請求書発行事業者登録番号</th>
                                <td>T4-0106-0104-0555 (令和5年10月1日登録)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Google Maps Embed Section --}}
            <section class="about-map-section">
                <div class="about-map-wrapper">
                    <iframe class="about-map-iframe"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3243.4125519789877!2d139.7770439!3d35.61755480000001!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x601889fe107a837f%3A0x8114b420fe355d44!2z44Ob44OD44OI44K544OI44Op44OD44OX!5e0!3m2!1sth!2sth!4v1784879911802!5m2!1sth!2sth"
                        allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                        title="ユー・アンド・アース株式会社 所在地マップ"></iframe>
                </div>
            </section>

            {{-- Office Photos Grid (東京オフィス / 大阪オフィス) --}}
            <section class="about-offices-grid">
                <div class="about-office-card">
                    <h3 class="about-office-title">東京オフィス</h3>
                    <div class="about-office-img-wrapper">
                        <img src="{{ asset('assets/images/about/b5ffdcbf28db94c43106ae46cead8d7256d6425c.png') }}"
                            alt="東京オフィス スタッフ集合写真">
                    </div>
                </div>
                <div class="about-office-card">
                    <h3 class="about-office-title">大阪オフィス</h3>
                    <div class="about-office-img-wrapper">
                        <img src="{{ asset('assets/images/about/f3bd623c58ad75a7e77ae04a320733e506be8d0c.png') }}"
                            alt="大阪オフィス スタッフ集合写真">
                    </div>
                </div>
            </section>

            {{-- Manufacturing Entity Table Section (製品設計・製造) --}}
            <section class="about-manufacturing-section">
                <div class="about-section-heading">
                    <div class="about-grid-icon">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                    製品設計・製造
                </div>

                <div class="about-table-wrapper">
                    <table class="about-info-table">
                        <tbody>
                            <tr>
                                <th>会社名</th>
                                <td>ユーアンドアース（タイランド）株式会社</td>
                            </tr>
                            <tr>
                                <th>会社名<br>(英語表記)</th>
                                <td>YOU AND EARTH (THAILAND) CO., LTD.</td>
                            </tr>
                            <tr>
                                <th>所在地</th>
                                <td>23/34-35 The Prime Hua Lamphong, Building A, 4Floor, Room No. 404, Soi Sukorn, Trimit
                                    Road, Talat Noi, Samphanthawong, Bangkok 10100</td>
                            </tr>
                            <tr>
                                <th>事業内容</th>
                                <td>オリジナルネックストラップの設計、製造、販売 情報システムの保守管理</td>
                            </tr>
                            <tr>
                                <th>連絡先</th>
                                <td>
                                    toiawase@hotstrap.jp<br>
                                    お問い合わせは、上記メールアドレスにお送り頂くか、<a href="{{ route('contact.index') }}">お問い合わせ画面</a>をご利用下さい。
                                </td>
                            </tr>
                            <tr>
                                <th>設立</th>
                                <td>2020年（2007年7月設立の YOU AND EARTH SYSTEM CO., LTD.が、BOI取得により会社変更）</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Large Thailand Team Beach Photo Section --}}
            <section class="about-large-team-section">
                <div class="about-large-team-wrapper">
                    <img src="{{ asset('assets/images/about/af1661a6c6545e8dea6fac86a897176105be0922.png') }}"
                        alt="製品設計・製造 チーム集合写真">
                </div>
            </section>

            {{-- Manufacturing Entity Table Section 2 (製品製造) --}}
            <section class="about-manufacturing-section">
                <div class="about-section-heading">
                    <div class="about-grid-icon">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                    製品製造
                </div>

                <div class="about-table-wrapper">
                    <table class="about-info-table">
                        <tbody>
                            <tr>
                                <th>会社名</th>
                                <td>优安阿斯工艺品制造（东莞）有限公司</td>
                            </tr>
                            <tr>
                                <th>会社名<br>(英語表記)</th>
                                <td>YOU AND EARTH CRAFT MANUFACTURING (DONGGUAN) CO., LTD.</td>
                            </tr>
                            <tr>
                                <th>所在地</th>
                                <td>Room 501, Floor 5, Xinxin industrial park, No.33, Huaide liyuan Road , Humen Town,
                                    Dongguan city, Guangdong Province, China 523926</td>
                            </tr>
                            <tr>
                                <th>設立</th>
                                <td>2020年（2007年7月設立の YOU AND EARTH SYSTEM CO., LTD.が、BOI取得により会社変更）</td>
                            </tr>
                            <tr>
                                <th>連絡先</th>
                                <td>+86-769-81285936</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Interactive Image Gallery Section (製品製造 ギャラリー) --}}
            <section class="about-gallery-section">
                {{-- Main Display Image --}}
                <div class="about-gallery-main">
                    <img id="mainGalleryImage" src="{{ asset('assets/images/about/china_office_2025-01.jpg') }}"
                        alt="製品製造 集合写真 メイン">
                </div>

                {{-- 5 Small Thumbnails Row --}}
                <div class="about-gallery-thumbs">
                    <div class="about-gallery-thumb-item active"
                        onclick="switchGalleryImage(this, '{{ asset('assets/images/about/china_office_2025-01.jpg') }}')">
                        <img src="{{ asset('assets/images/about/china_office_2025-01.jpg') }}" alt="サムネイル 1">
                    </div>
                    <div class="about-gallery-thumb-item"
                        onclick="switchGalleryImage(this, '{{ asset('assets/images/about/china_office_2025-02.jpg') }}')">
                        <img src="{{ asset('assets/images/about/china_office_2025-02.jpg') }}" alt="サムネイル 2">
                    </div>
                    <div class="about-gallery-thumb-item"
                        onclick="switchGalleryImage(this, '{{ asset('assets/images/about/company-china-2.jpg') }}')">
                        <img src="{{ asset('assets/images/about/company-china-2.jpg') }}" alt="サムネイル 3">
                    </div>
                    <div class="about-gallery-thumb-item"
                        onclick="switchGalleryImage(this, '{{ asset('assets/images/about/company-china-3.jpg') }}')">
                        <img src="{{ asset('assets/images/about/company-china-3.jpg') }}" alt="サムネイル 4">
                    </div>
                    <div class="about-gallery-thumb-item"
                        onclick="switchGalleryImage(this, '{{ asset('assets/images/about/company-china-4.jpg') }}')">
                        <img src="{{ asset('assets/images/about/company-china-4.jpg') }}" alt="サムネイル 5">
                    </div>
                </div>
            </section>

            {{-- Customer Support Table Section (カスタマーサポート) --}}
            <section class="about-manufacturing-section">
                <div class="about-section-heading">
                    <div class="about-grid-icon">
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                        <span></span><span></span><span></span>
                    </div>
                    カスタマーサポート
                </div>

                <div class="about-table-wrapper">
                    <table class="about-info-table">
                        <tbody>
                            <tr>
                                <th>会社名</th>
                                <td>ユーアンドアース（ブラジル）株式会社</td>
                            </tr>
                            <tr>
                                <th>会社名<br>(英語表記)</th>
                                <td>YOU AND EARTH DO BRASIL LTDA</td>
                            </tr>
                            <tr>
                                <th>所在地</th>
                                <td>iSpaces Coworking - São Paulo Ed. Praça Pamplona - R. Pamplona, 145 - sala 1 e 2 -
                                    Jardim Paulista, São Paulo - SP, 01405-900</td>
                            </tr>
                            <tr>
                                <th>設立</th>
                                <td>2025年4月1日</td>
                            </tr>
                            <tr>
                                <th>連絡先</th>
                                <td>+55 11-4193-1982</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Large Brazil Team Photo Section --}}
            <section class="about-large-team-section">
                <div class="about-large-team-wrapper">
                    <img src="{{ asset('assets/images/about/0b69b9a0b204c9ad85e426096232e6c15ab6e703.png') }}"
                        alt="カスタマーサポート チーム集合写真">
                </div>
            </section>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        function switchGalleryImage(thumbElement, fullSrc) {
            const mainImg = document.getElementById('mainGalleryImage');
            if (!mainImg) return;

            mainImg.style.opacity = '0.3';
            setTimeout(() => {
                mainImg.src = fullSrc;
                mainImg.style.opacity = '1';
            }, 120);

            document.querySelectorAll('.about-gallery-thumb-item').forEach(function(item) {
                item.classList.remove('active');
            });
            if (thumbElement) {
                thumbElement.classList.add('active');
            }
        }
    </script>
@endpush
