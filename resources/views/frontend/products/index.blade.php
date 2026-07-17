@extends('frontend.layouts.app')

@section('title', '商品一覧 | ThaiSilk')

@section('meta_description', 'タイシルクを使用した社員証ケース、キーホルダー、名刺入れなどの商品一覧です。')

@section('body-class', 'product-list-page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
@endsection

@section('content')
    <div class="product-list-layout">
        <aside
            id="productFilter"
            class="product-filter-panel offcanvas offcanvas-start"
            tabindex="-1"
            aria-labelledby="productFilterLabel"
        >
            <div class="product-filter-mobile-header">
                <h2 id="productFilterLabel">絞り込み</h2>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="offcanvas"
                    data-bs-target="#productFilter"
                    aria-label="閉じる"
                ></button>
            </div>

            <form id="productFilterForm" action="{{ route('products.index') }}" method="GET">
                <input type="hidden" name="sort" value="{{ $sort }}">

                <div class="product-filter-title">
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 6h3m4 0h9M9 4v4M4 12h9m4 0h3m-5-2v4M4 18h3m4 0h9M9 16v4" />
                    </svg>
                    <span>絞り込み</span>
                </div>

                <section class="product-filter-section is-open">
                    <button class="product-filter-section-toggle" type="button" aria-expanded="true">
                        <span>カテゴリー</span>
                        <span class="product-filter-chevron" aria-hidden="true"></span>
                    </button>

                    <div class="product-filter-section-content">
                        @foreach ($categories as $category)
                            <label class="product-filter-checkbox">
                                <input
                                    type="checkbox"
                                    name="categories[]"
                                    value="{{ $category['slug'] }}"
                                    @checked(in_array($category['slug'], $selectedCategories, true))
                                >
                                <span class="product-filter-checkbox-box" aria-hidden="true"></span>
                                <span>{{ $category['name'] }}</span>
                            </label>
                        @endforeach
                    </div>
                </section>

                <section class="product-filter-section is-open">
                    <button class="product-filter-section-toggle" type="button" aria-expanded="true">
                        <span>価格帯</span>
                        <span class="product-filter-chevron" aria-hidden="true"></span>
                    </button>

                    <div class="product-filter-section-content product-price-filter">
                        <div
                            class="product-price-slider"
                            data-price-min="0"
                            data-price-max="{{ $priceLimit }}"
                        >
                            <div class="product-price-slider-track"></div>
                            <div class="product-price-slider-range"></div>

                            <input
                                id="priceRangeMin"
                                type="range"
                                min="0"
                                max="{{ $priceLimit }}"
                                step="100"
                                value="{{ $minPrice }}"
                                aria-label="最低価格"
                            >
                            <input
                                id="priceRangeMax"
                                type="range"
                                min="0"
                                max="{{ $priceLimit }}"
                                step="100"
                                value="{{ $maxPrice }}"
                                aria-label="最高価格"
                            >
                        </div>

                        <div class="product-price-inputs">
                            <label>
                                <span>¥</span>
                                <input
                                    id="priceInputMin"
                                    type="number"
                                    name="min_price"
                                    min="0"
                                    max="{{ $priceLimit }}"
                                    value="{{ $minPrice }}"
                                    aria-label="最低価格"
                                >
                            </label>

                            <span class="product-price-separator">〜</span>

                            <label>
                                <span>¥</span>
                                <input
                                    id="priceInputMax"
                                    type="number"
                                    name="max_price"
                                    min="0"
                                    max="{{ $priceLimit }}"
                                    value="{{ $maxPrice }}"
                                    aria-label="最高価格"
                                >
                            </label>
                        </div>
                    </div>
                </section>
            </form>
        </aside>

        <section class="product-list-content" aria-labelledby="productListHeading">
            <h1 id="productListHeading" class="visually-hidden">商品一覧</h1>

            <div class="product-list-toolbar">
                <button
                    type="button"
                    class="product-filter-mobile-button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#productFilter"
                    aria-controls="productFilter"
                >
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M4 6h3m4 0h9M9 4v4M4 12h9m4 0h3m-5-2v4M4 18h3m4 0h9M9 16v4" />
                    </svg>
                    絞り込み
                </button>

                <form class="product-sort-form" action="{{ route('products.index') }}" method="GET">
                    @foreach ($selectedCategories as $selectedCategory)
                        <input type="hidden" name="categories[]" value="{{ $selectedCategory }}">
                    @endforeach
                    <input type="hidden" name="min_price" value="{{ $minPrice }}">
                    <input type="hidden" name="max_price" value="{{ $maxPrice }}">

                    <label for="productSort">並び替え：</label>
                    <select id="productSort" name="sort" aria-label="商品の並び替え">
                        <option value="newest" @selected($sort === 'newest')>新着順</option>
                        <option value="price_asc" @selected($sort === 'price_asc')>価格が安い順</option>
                        <option value="price_desc" @selected($sort === 'price_desc')>価格が高い順</option>
                    </select>
                </form>
            </div>

            @if ($products->isNotEmpty())
                <div class="product-grid">
                    @foreach ($products as $product)
                        <article class="product-card {{ ! $product['is_available'] ? 'is-placeholder' : '' }}">
                            @if ($product['is_available'])
                                <a
                                    href="{{ url('/products/' . $product['slug']) }}"
                                    class="product-card-image-link"
                                    aria-label="{{ $product['name'] }}"
                                >
                                    <img
                                        src="{{ asset($product['image']) }}"
                                        alt="{{ $product['name'] }}"
                                        class="product-card-image product-card-image-default"
                                    >
                                    @if ($product['hover_image'])
                                        <img
                                            src="{{ asset($product['hover_image']) }}"
                                            alt=""
                                            class="product-card-image product-card-image-hover"
                                            aria-hidden="true"
                                        >
                                    @endif
                                </a>

                                <div class="product-card-body">
                                    <h2 class="product-card-title">
                                        <a href="{{ url('/products/' . $product['slug']) }}">
                                            {{ $product['name'] }}
                                        </a>
                                    </h2>

                                    <p class="product-card-price">
                                        単価：{{ number_format($product['price']) }}円(税込)〜
                                    </p>

                                    <span class="product-card-delivery">
                                        {{ $product['delivery'] }}
                                    </span>
                                </div>
                            @else
                                <div class="product-card-placeholder" aria-label="{{ $product['name'] }} 準備中"></div>
                                <div class="product-card-placeholder-body" aria-hidden="true"></div>
                            @endif
                        </article>
                    @endforeach
                </div>
            @else
                <div class="product-list-empty">
                    条件に一致する商品が見つかりませんでした。
                </div>
            @endif
        </section>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterForm = document.getElementById('productFilterForm');
            const sortSelect = document.getElementById('productSort');
            const rangeMin = document.getElementById('priceRangeMin');
            const rangeMax = document.getElementById('priceRangeMax');
            const inputMin = document.getElementById('priceInputMin');
            const inputMax = document.getElementById('priceInputMax');
            const slider = document.querySelector('.product-price-slider');
            const sliderRange = document.querySelector('.product-price-slider-range');
            let submitTimer;

            const queueSubmit = function (delay = 450) {
                window.clearTimeout(submitTimer);
                submitTimer = window.setTimeout(function () {
                    filterForm.requestSubmit();
                }, delay);
            };

            const clampPriceValues = function (source) {
                const minimum = Number(slider.dataset.priceMin);
                const maximum = Number(slider.dataset.priceMax);
                let low = Number(source === 'number' ? inputMin.value : rangeMin.value);
                let high = Number(source === 'number' ? inputMax.value : rangeMax.value);

                low = Math.max(minimum, Math.min(low || 0, maximum));
                high = Math.max(minimum, Math.min(high || maximum, maximum));

                if (low > high) {
                    if (document.activeElement === rangeMin || document.activeElement === inputMin) {
                        low = high;
                    } else {
                        high = low;
                    }
                }

                rangeMin.value = low;
                rangeMax.value = high;
                inputMin.value = low;
                inputMax.value = high;

                const lowPercent = ((low - minimum) / (maximum - minimum)) * 100;
                const highPercent = ((high - minimum) / (maximum - minimum)) * 100;
                sliderRange.style.left = lowPercent + '%';
                sliderRange.style.right = (100 - highPercent) + '%';
            };

            document.querySelectorAll('.product-filter-section-toggle').forEach(function (button) {
                button.addEventListener('click', function () {
                    const section = button.closest('.product-filter-section');
                    const content = section.querySelector('.product-filter-section-content');
                    const isOpen = ! section.classList.contains('is-open');

                    content.style.height = content.getBoundingClientRect().height + 'px';
                    content.offsetHeight;

                    section.classList.toggle('is-open', isOpen);
                    content.style.height = isOpen ? content.scrollHeight + 'px' : '0px';
                    button.setAttribute('aria-expanded', String(isOpen));

                    content.addEventListener('transitionend', function handleTransition(event) {
                        if (event.propertyName !== 'height') {
                            return;
                        }

                        content.removeEventListener('transitionend', handleTransition);

                        if (section.classList.contains('is-open')) {
                            content.style.height = 'auto';
                        }
                    });
                });
            });

            filterForm.querySelectorAll('input[type="checkbox"]').forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    queueSubmit(250);
                });
            });

            [rangeMin, rangeMax].forEach(function (range) {
                range.addEventListener('input', function () {
                    clampPriceValues('range');
                });
                range.addEventListener('change', function () {
                    queueSubmit(250);
                });
            });

            [inputMin, inputMax].forEach(function (input) {
                input.addEventListener('input', function () {
                    clampPriceValues('number');
                    queueSubmit();
                });
            });

            sortSelect.addEventListener('change', function () {
                sortSelect.form.requestSubmit();
            });

            clampPriceValues('range');
        });
    </script>
@endsection