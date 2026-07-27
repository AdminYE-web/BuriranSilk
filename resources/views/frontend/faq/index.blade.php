@extends('frontend.layouts.app')

@section('title', 'よくある質問 - ThaiSilk')
@section('body-class', 'faq-page')
@push('styles')
    <link rel=stylesheet href=/assets/css/faq.css>
@endpush
@section('content')
<section class=faq-main>
    <div class=faq-container>
        <header class=faq-intro>
            <h1>よくある質問</h1>
            <p>ご不明な点がございましたら、まずはこちらをご確認ください。</p>
            <p>通常1〜2営業日以内に回答をご案内しております。</p>
            <small>お急ぎの場合はこちら：<a href=mailto:contact@silicone-wristband-studio.jp>contact@silicone-wristband-studio.jp</a></small>
        </header>
        <div class=faq-card>
            <h2>よくある質問</h2>
            <div class=faq-list>
                @foreach ($faqs as $faq)
                    <article class=faq-item>
                        <h3><button type=button class=faq-trigger aria-expanded=false>
                            <span>@php echo e(data_get($faq, 'question')); @endphp</span>
                            <span class=faq-plus aria-hidden=true></span>
                        </button></h3>
                        <div class=faq-panel aria-hidden=true>
                            <div class=faq-answer>@php echo e(data_get($faq, 'answer')); @endphp</div>
                        </div>
                    </article>
                @endforeach
            </div>
            @if ($faqs->hasPages())
                <nav class=faq-pagination aria-label=FAQページ>
                    @php echo $faqs->onEachSide(1)->links(); @endphp
                </nav>
            @endif
            <div class=faq-contact>
                <p>解決しない場合はお気軽にお問い合わせください。</p>
                <a href=/contact>お問い合わせフォームへ <span>→</span></a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.faq-trigger').forEach((trigger) => {
            trigger.addEventListener('click', () => {
                const item = trigger.closest('.faq-item');
                const panel = item.querySelector('.faq-panel');
                const willOpen = !item.classList.contains('is-open');

                document.querySelectorAll('.faq-item.is-open').forEach((openItem) => {
                    if (openItem === item) return;

                    openItem.classList.remove('is-open');
                    openItem.querySelector('.faq-trigger').setAttribute('aria-expanded', 'false');
                    openItem.querySelector('.faq-panel').setAttribute('aria-hidden', 'true');
                });

                item.classList.toggle('is-open', willOpen);
                trigger.setAttribute('aria-expanded', String(willOpen));
                panel.setAttribute('aria-hidden', String(!willOpen));
            });
        });
    </script>
@endpush
