@extends('frontend.layouts.app')

@section('title', '私たちについて | ThaiSilk')
@section('meta_description', 'ThaiSilkの想いと、タイシルクに受け継がれる職人の技と物語をご紹介します。')
@section('body-class', 'about-us-page')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/about-us.css') }}?v={{ filemtime(public_path('assets/css/about-us.css')) }}">
@endpush

@section('content')
    @php
        $bannerDesktop = $aboutUsPage?->banner_desktop
            ? asset('storage/'.$aboutUsPage->banner_desktop)
            : asset('assets/images/about/about-silk-hero.jpg');
        $bannerMobile = $aboutUsPage?->banner_mobile
            ? asset('storage/'.$aboutUsPage->banner_mobile)
            : $bannerDesktop;
        $introImage = $aboutUsPage?->intro_image
            ? asset('storage/'.$aboutUsPage->intro_image)
            : asset('assets/images/about/about-silk-story.jpg');
    @endphp

    <section class="about-us-hero" aria-labelledby="aboutUsTitle">
        <picture>
            <source media="(max-width: 767px)" srcset="{{ $bannerMobile }}">
            <img src="{{ $bannerDesktop }}" alt="伝統的な木製織機で織られる青と金のタイシルク">
        </picture>
        <div class="about-us-hero-shade" aria-hidden="true"></div>
      
    </section>

    <section class="about-us-story" aria-labelledby="aboutSilkTitle">
        <div class="about-us-story-inner">
            <figure class="about-us-story-image">
                <img src="{{ $introImage }}" alt="伝統的な織機でタイシルクを織る職人">
            </figure>
            <div class="about-us-story-content">
                @if (filled($aboutUsPage?->intro_content))
                    <div class="about-us-rich-content ck-content">{!! $aboutUsPage->intro_content !!}</div>
                @else
                    <h2 id="aboutSilkTitle">タイシルクについて</h2>
                    <p>
                    ホットストラップを運営するユー・アンド・アース株式会社では、タイの現地法人がブリーラム県にシルク製品の加工工場を持ち、現地で日本向けにタイシルクを使ったカードケースやキーホルダー等の生産を行っております。<br><br>

ブリーラム県はタイ国東北部に位置し、バンコクから自動車で約5時間程度の距離にあります。農業を中心とした地域ですが、近年MOTO GPの開催や、ブリーラム・ユナイテッドFCのホーム拠点として、タイの地方都市の中でも存在感を現し、発展著しい都市です。<br><br>

タイのシルク生地生産は、養蚕（絹生地の元となる繭（まゆ）を作り出す蚕（かいこ）を育てる為に必要な桑の木の育成から始まります。ブリーラム県は特にこの桑の木の育成で有名な地域です。
                    </p>
                @endif
            </div>
        </div>
        <span class="about-us-story-mark" aria-hidden="true"></span>
    </section>

    @if (filled($aboutUsPage?->detail_content))
        <section class="about-us-detail">
            <span class="about-us-detail-flower about-us-detail-flower-one" aria-hidden="true"></span>
            <span class="about-us-detail-flower about-us-detail-flower-two" aria-hidden="true"></span>
            <span class="about-us-detail-flower about-us-detail-flower-three" aria-hidden="true"></span>
            <span class="about-us-detail-flower about-us-detail-flower-four" aria-hidden="true"></span>
            <span class="about-us-detail-flower about-us-detail-flower-five" aria-hidden="true"></span>
            <span class="about-us-detail-flower about-us-detail-flower-six" aria-hidden="true"></span>
            <div class="about-us-detail-inner about-us-rich-content ck-content">
                {!! $aboutUsPage->detail_content !!}
            </div>
        </section>
    @else
    <section class="about-us-process" aria-labelledby="silkProcessTitle">
        <span class="about-us-process-flower about-us-process-flower-one" aria-hidden="true"></span>
        <span class="about-us-process-flower about-us-process-flower-two" aria-hidden="true"></span>
        <span class="about-us-process-flower about-us-process-flower-three" aria-hidden="true"></span>

        <div class="about-us-process-inner">
            <h2 id="silkProcessTitle">タイシルク生地の生産工程</h2>

            <article class="about-us-process-step">
                <header class="about-us-process-heading">
                    <span class="about-us-process-number">1</span>
                    <h3>蚕（かいこ）の餌となる桑の木の育成</h3>
                </header>
                <div class="about-us-process-gallery">
                    <figure>
                        <figcaption>桑の木</figcaption>
                        <img src="{{ asset('assets/images/about/process-mulberry-tree.jpg') }}" alt="タイの農園で育つ若い桑の木">
                    </figure>
                    <figure>
                        <figcaption>桑の葉</figcaption>
                        <img src="{{ asset('assets/images/about/process-mulberry-leaves.jpg') }}" alt="蚕の餌となる新鮮な桑の葉">
                    </figure>
                </div>
                <p class="about-us-process-description">蚕（かいこ）は桑の葉を食べて育ちます。餌となる桑の木の育成を行います。桑の木はマルベリーという実がなる木です。マルベリーはもちろん、人間が食べることもできます。桑の木は植樹から約1年で葉を摘むことができます。</p>
            </article>

            <article class="about-us-process-step">
                <header class="about-us-process-heading">
                    <span class="about-us-process-number">2</span>
                    <h3>蚕（かいこ）の飼育</h3>
                </header>
                <div class="about-us-process-gallery">
                    <figure>
                        <figcaption>蚕（かいこ）の飼育風景</figcaption>
                        <img src="{{ asset('assets/images/about/process-silkworm-feeding.jpg') }}" alt="桑の葉を与えながら蚕を育てる職人">
                    </figure>
                    <figure>
                        <figcaption>専用のトレーで繭を作ります</figcaption>
                        <img src="{{ asset('assets/images/about/process-silkworm-cocoons.jpg') }}" alt="竹製の枠の中で繭を作る蚕">
                    </figure>
                </div>
                <p class="about-us-process-description">他の虫の侵入を防ぐため、閉鎖された空間で蚕の飼育を行います。蚕は体がやや黄色くなってきたら、繭（まゆ）を作り出すサインですので、専用のトレーに移動します。</p>
            </article>
        </div>
    </section>
    @endif
@endsection

@push('scripts')
    <script>
        document.querySelectorAll(
            '.about-us-detail-inner > h2, .about-us-detail-inner > h3, .about-us-detail-inner > h4, .about-us-detail-inner > p'
        ).forEach((heading) => {
            const match = heading.textContent.trim().match(/^(\d+)\s+(.+)$/s);
            if (!match) return;

            const number = document.createElement('span');
            const label = document.createElement('span');

            number.className = 'about-us-rich-step-number';
            number.textContent = match[1];

            label.className = 'about-us-rich-step-label';
            label.textContent = match[2];

            heading.textContent = '';
            heading.classList.add('about-us-rich-step-heading');
            heading.append(number, label);
        });

        document.querySelectorAll('.about-us-rich-content oembed[url]').forEach((embed) => {
            const url = embed.getAttribute('url');
            const match = url?.match(/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([A-Za-z0-9_-]{6,})/);
            if (!match) return;

            const wrapper = document.createElement('div');
            const isPortrait = url.includes('youtube.com/shorts/') || url.includes('portrait=1');
            wrapper.className = `about-us-youtube${isPortrait ? ' is-portrait' : ''}`;
            wrapper.innerHTML = `<iframe src="https://www.youtube-nocookie.com/embed/${match[1]}" title="YouTube video" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>`;
            embed.closest('figure.media')?.replaceWith(wrapper) || embed.replaceWith(wrapper);
        });
    </script>
@endpush
