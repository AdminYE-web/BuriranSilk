@extends('frontend.layouts.app')

@section('title', 'ThaiSilk')

@section(
    'meta_description',
    '伝統的なタイシルクを現代のライフスタイルに合わせたバッグや財布、アクセサリーをご紹介します。'
)

@section('body-class', 'home-page')

@section('content')

    <section class="home-hero">

        {{-- Banner Image --}}
        <picture class="home-hero-picture">
            <source
                media="(max-width: 767.98px)"
                srcset="{{ asset('assets/images/home/hero-banner-mobile.jpg') }}"
            >

            <img
                src="{{ asset('assets/images/home/hero-banner.jpg') }}"
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

@endsection