@extends('frontend.layouts.app')

@section('title', $product['short_name'] . ' | ThaiSilk')

@section('meta_description', $product['description'])

@section('body-class', 'product-detail-page')

@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/product-detail.css') }}">
@endsection

@section('content')
    <div class="product-detail-shell container">
        <section class="product-gallery" aria-label="商品画像">
            <div class="product-gallery-inner">
                <div class="product-gallery-main">
                    <img
                        id="productMainImage"
                        src="{{ asset($product['gallery'][0]) }}"
                        alt="{{ $product['short_name'] }}"
                    >

                    <button
                        type="button"
                        class="product-gallery-arrow product-gallery-arrow-prev"
                        aria-label="前の画像"
                    >
                        <span aria-hidden="true"></span>
                    </button>

                    <button
                        type="button"
                        class="product-gallery-arrow product-gallery-arrow-next"
                        aria-label="次の画像"
                    >
                        <span aria-hidden="true"></span>
                    </button>
                </div>

                <div class="product-gallery-thumbnails" role="list" aria-label="商品画像一覧">
                    @foreach ($product['gallery'] as $index => $image)
                        <button
                            type="button"
                            class="product-gallery-thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                            data-image="{{ asset($image) }}"
                            data-index="{{ $index }}"
                            aria-label="商品画像 {{ $index + 1 }}"
                            aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                        >
                            <img src="{{ asset($image) }}" alt="">
                        </button>
                    @endforeach

                    @for ($placeholder = count($product['gallery']); $placeholder < 9; $placeholder++)
                        <span class="product-gallery-thumbnail-placeholder" aria-hidden="true"></span>
                    @endfor
                </div>

                <p class="product-gallery-description">
                    {{ $product['description'] }}
                </p>
            </div>
        </section>

        <section class="product-options-column" aria-labelledby="productDetailTitle">
            <form id="productCustomizeForm" class="product-options-form" action="{{ url('/cart') }}" method="GET" novalidate>
                <input type="hidden" name="product" value="{{ $product['slug'] }}">
                <input id="productTotalInput" type="hidden" name="total_price" value="16830">

                <h1 id="productDetailTitle" class="product-detail-title">
                    {{ $product['name'] }}
                </h1>

                <fieldset class="product-option-group">
                    <legend class="product-option-heading">
                        <span class="product-option-number">1</span>
                        <span>以前のご注文と同じデザインでの製作ですか？</span>
                    </legend>

                    <div class="product-option-stack">
                        <label class="product-option-line">
                            <input type="radio" name="repeat_design" value="no" checked>
                            <span>いいえ</span>
                        </label>
                        <label class="product-option-line">
                            <input type="radio" name="repeat_design" value="yes">
                            <span>はい</span>
                        </label>
                    </div>
                </fieldset>

                <fieldset class="product-option-group" data-required-group="shape">
                    <legend class="product-option-heading">
                        <span class="product-option-number">2</span>
                        <span>形状</span>
                    </legend>

                    <div class="product-option-image-grid product-option-image-grid-two">
                        <label class="product-option-image-card">
                            <input type="radio" name="shape" value="horizontal" required>
                            <span class="product-option-image-box">
                                <svg viewBox="0 0 100 78" aria-hidden="true">
                                    <path d="M44 14v-5c0-5 12-5 12 0v5" />
                                    <rect x="22" y="14" width="56" height="48" rx="3" />
                                    <rect x="29" y="22" width="42" height="31" rx="2" />
                                </svg>
                            </span>
                            <span class="product-option-image-label">横型</span>
                        </label>

                        <label class="product-option-image-card">
                            <input type="radio" name="shape" value="vertical" required>
                            <span class="product-option-image-box">
                                <svg class="is-vertical" viewBox="0 0 100 78" aria-hidden="true">
                                    <path d="M44 9V5c0-5 12-5 12 0v4" />
                                    <rect x="31" y="9" width="38" height="58" rx="3" />
                                    <rect x="37" y="17" width="26" height="42" rx="2" />
                                </svg>
                            </span>
                            <span class="product-option-image-label">縦型</span>
                        </label>
                    </div>

                    <p class="product-option-error" data-error-for="shape">
                        ※タイプを選択してください。
                    </p>
                </fieldset>

                <fieldset class="product-option-group">
                    <legend class="product-option-heading">
                        <span class="product-option-number">3</span>
                        <span>印刷面</span>
                    </legend>

                    <div class="product-option-stack">
                        <label class="product-option-line">
                            <input type="radio" name="printing" value="both" data-price="2200" checked>
                            <span>両面印刷</span>
                        </label>
                        <label class="product-option-line">
                            <input type="radio" name="printing" value="one" data-price="1100">
                            <span>片面印刷</span>
                        </label>
                        <label class="product-option-line">
                            <input type="radio" name="printing" value="none" data-price="0">
                            <span>印刷なし</span>
                        </label>
                    </div>

                    <p class="product-option-help">
                        Adobe Illustrator等で作成した入稿用データをお持ちですか？
                    </p>
                    <p class="product-option-note">
                        ご入稿データはアウトライン化し、画像を埋め込んだ状態でご用意ください。
                    </p>

                    <div class="product-option-stack">
                        <label class="product-option-line">
                            <input type="radio" name="has_artwork" value="yes" checked>
                            <span>はい</span>
                        </label>
                        <label class="product-option-line">
                            <input type="radio" name="has_artwork" value="no">
                            <span>いいえ</span>
                        </label>
                    </div>

                    <label class="product-upload-field">
                        <span>データ入稿（印刷データをお持ちの方）</span>
                        <input id="artworkFile" type="file" name="artwork" accept=".ai,.pdf,.eps,.psd,.png,.jpg,.jpeg">
                        <span class="product-upload-dropzone">
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M7 3h7l5 5v13H7z" />
                                <path d="M14 3v5h5M12 17v-6m0 0-3 3m3-3 3 3" />
                            </svg>
                            <span id="artworkFileName">ここにファイルをドラッグ＆ドロップ、またはクリックしてファイルを選択</span>
                        </span>
                    </label>

                    <p class="product-option-note">
                        対応形式：ai、pdf、eps、psd、png、jpg。大容量データは別途ご相談ください。
                    </p>

                    <p class="product-option-help">
                        デザイン内に文字・テキストは含まれますか？
                    </p>
                    <div class="product-option-stack">
                        <label class="product-option-line">
                            <input type="radio" name="contains_text" value="yes" checked>
                            <span>はい</span>
                        </label>
                        <label class="product-option-line">
                            <input type="radio" name="contains_text" value="no">
                            <span>いいえ</span>
                        </label>
                    </div>
                </fieldset>

                <fieldset class="product-option-group">
                    <legend class="product-option-heading">
                        <span class="product-option-number">4</span>
                        <span>数量</span>
                    </legend>

                    <label class="product-quantity-field">
                        <input
                            id="productQuantity"
                            type="number"
                            name="quantity"
                            min="10"
                            max="1000"
                            step="10"
                            value="100"
                            data-unit-price="{{ $product['unit_price'] }}"
                        >
                        <span>個</span>
                    </label>
                    <p class="product-option-note">ご注文数量は10個単位で入力してください。</p>
                </fieldset>

                <fieldset class="product-option-group" data-required-group="ring_type">
                    <legend class="product-option-heading">
                        <span class="product-option-number">5</span>
                        <span>金具 Combination Metal Type</span>
                    </legend>

                    <div class="product-option-image-grid product-option-image-grid-three">
                        @foreach (['丸型1', '丸型2', '丸型3', '丸型4', '丸型5', '丸型6'] as $ringIndex => $ringName)
                            <label class="product-option-image-card product-ring-card">
                                <input
                                    type="radio"
                                    name="ring_type"
                                    value="ring-{{ $ringIndex + 1 }}"
                                    data-price="{{ $ringIndex < 2 ? 0 : 300 }}"
                                    required
                                >
                                <span class="product-ring-icon" aria-hidden="true">
                                    <span class="product-ring-circle"></span>
                                    <span class="product-ring-stem"></span>
                                </span>
                                <span class="product-option-image-label">{{ $ringName }}</span>
                            </label>
                        @endforeach
                    </div>

                    <p class="product-option-error" data-error-for="ring_type">
                        ※タイプを選択してください。
                    </p>
                </fieldset>

                <fieldset class="product-option-group">
                    <legend class="product-option-heading product-option-heading-en">
                        <span class="product-option-number">6</span>
                        <span>Select Stitching Thread Color</span>
                    </legend>

                    <div class="product-option-stack">
                        <label class="product-option-line">
                            <input type="radio" name="thread_color" value="match" data-price="0">
                            <span>Match Fabric Color</span>
                        </label>
                        <label class="product-option-line">
                            <input type="radio" name="thread_color" value="custom" data-price="1130" checked>
                            <span>Custom Color</span>
                        </label>
                        <label class="product-option-line">
                            <input type="radio" name="thread_color" value="code" data-price="500">
                            <span>Enter Color Code</span>
                        </label>
                    </div>
                </fieldset>

                <section id="orderSummary" class="product-order-summary" aria-labelledby="orderSummaryTitle">
                    <h2 id="orderSummaryTitle">注文内容</h2>
                    <dl>
                        <div><dt>商品</dt><dd>{{ $product['short_name'] }}</dd></div>
                        <div><dt>数量</dt><dd><span data-summary-quantity>100</span>個</dd></div>
                        <div><dt>単価</dt><dd>{{ number_format($product['unit_price']) }}円〜</dd></div>
                    </dl>
                </section>
            </form>
        </section>

        <button id="orderSummaryButton" type="button" class="product-order-tab">
            <span>注文内容</span>
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="M7 3h10v18H7zM9.5 8h5M9.5 12h5M9.5 16h3" />
            </svg>
        </button>
    </div>

    <section class="product-detail-banner container" aria-label="タイシルクカードケース">
        <div class="product-detail-banner-inner">
            <img
                src="{{ asset('assets/images/product/Rectangle 218.png') }}"
                alt="雅 Miyabi Thai Silk Card Box"
                class="product-detail-banner-image"
            >
        </div>
    </section>

    <section class="product-feature-accordion container is-open" aria-labelledby="productFeatureTitle">
        <button
            id="productFeatureToggle"
            type="button"
            class="product-feature-toggle"
            aria-expanded="true"
            aria-controls="productFeatureContent"
        >
            <span class="product-feature-chevron" aria-hidden="true"></span>
            <span id="productFeatureTitle">タイシルクの特徴！</span>
        </button>

        <div id="productFeatureContent" class="product-feature-content">
            <div class="product-feature-content-inner">
                <h3>特徴①上品な光沢×フルカラー印刷が放つ、さりげない高級感</h3>
                <p>
                    タイシルクの魅力のひとつは、目立ちすぎない上品な光沢です。光の当たり方によって表情を変える上質な生地に、お客様のオリジナルデザインをフルカラーで鮮やかにプリント。ワンランク上の洗練された印象を演出します。いつものビジネススタイルにさりげない高級感を取り入れられることで、社員証ケースなどオフィスで使う備品が、さりげないフォーマルなデザインに仕上がります。
                </p>

                <h3>特徴②毎日身につけても疲れない「軽さ」と「しなやかさ」</h3>
                <p>
                    IDカードホルダーは、長時間身につけることもあるアイテムです。このため、着用する人の負担を少なくすることも重要です。タイシルクは非常に軽いため、首や肩への負担になりません。そのうえ優れた耐久性も備えており、毎日のハードな使用にも最適です。さらに、使い込むほどに繊維が柔らかくなり質感が変わっていくので、その変化も含め手触りを長く楽しむことができます。
                </p>

                <h3>特徴③周囲と差がつく、圧倒的なオリジナリティと希少性</h3>
                <p>
                    タイシルク製のIDカードホルダーは市場でもまだ珍しく、一般的なプラスチックや合成皮革のケースとは一線を画します。周囲と差が付く素材選びで、企業のこだわりや高いセンスを表現することが可能です。他にはない特別なアイテムは、自社のブランディングはもちろん、周年記念品など特別なノベルティとしても大活躍！
                </p>

                <a href="{{ url('/') }}" class="product-feature-link">
                    タイシルクの詳細！生産地や生産工程など
                </a>
            </div>
        </div>
    </section>

    <section
    class="container"
    aria-labelledby="productItemDescriptionTitle"
>
    <h2
        id="productItemDescriptionTitle"
        class="product-item-description-title"
    >
        アイテム説明
    </h2>

    <div class="product-item-description-grid">
        {{-- คำอธิบายด้านซ้าย --}}
        <div class="product-item-description-copy">
            <p>
                IDカードホルダーは、以下のような用途でご利用頂けます。
                タイシルク特有の光沢や柄があるため、実用品でありながら、
                ファッションアイテムとしても楽しめる点が魅力です。
            </p>

            <div class="product-item-use">
                <h3>・交通系カード・IDカードの収納</h3>

                <p>
                    SuicaやPASMOなどのカードを入れて、
                    改札でスムーズに使えます。
                    薄くて軽いので持ち運びやすいのが特徴です。
                </p>
            </div>

            <div class="product-item-use">
                <h3>・社員証・学生証の携帯</h3>

                <p>
                    首から下げたりバッグにつけたりして、
                    日常的に提示が必要なカードをおしゃれに持ち歩けます。
                </p>
            </div>

            <div class="product-item-use">
                <h3>・名刺やショップカードの保管</h3>

                <p>
                    ちょっとした名刺入れ代わりや、
                    お気に入りのショップカードを入れて
                    整理する用途にも使えます。
                </p>
            </div>
        </div>

        {{-- รายละเอียดสินค้าด้านขวา --}}
        <div class="product-specification-card">
            <dl class="product-specification-list">
                <div>
                    <dt>材料</dt>
                    <dd>タイシルク、PVC</dd>
                </div>

                <div>
                    <dt>色</dt>
                    <dd>紺色、山吹色、深緑色、緑色</dd>
                </div>

                <div>
                    <dt>形状</dt>
                    <dd>
                        縦型と横型の規定形状を提供しております。
                    </dd>
                </div>

                <div>
                    <dt>厚さ</dt>
                    <dd>1.2mm/シート程度</dd>
                </div>

                <div>
                    <dt>加工方法</dt>
                    <dd>縫製</dd>
                </div>

                <div>
                    <dt>印刷</dt>
                    <dd>カラー印刷（インクジェット印刷）</dd>
                </div>

                <div>
                    <dt>サイズ</dt>
                    <dd>
                        名刺の入るサイズ。
                        縦約89mm×横約103mm（外寸）
                    </dd>
                </div>

                <div>
                    <dt>重量</dt>
                    <dd>16g</dd>
                </div>

                <div>
                    <dt>ポケット数</dt>
                    <dd>
                        表面、裏面に各名刺サイズの紙が入る
                        ポケット1つ
                    </dd>
                </div>

                <div>
                    <dt>最低製作枚数</dt>
                    <dd>1枚</dd>
                </div>

                <div>
                    <dt>包装</dt>
                    <dd>
                        個包装（有料）<br>
                        ※輸送の都合上、有料の個包装と
                        させて頂いております。
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</section>

    <div class="product-cart-bar">
        <div class="product-cart-bar-inner container">
            <div class="product-cart-price">
                <strong id="productTotalPrice">16,830</strong>
                <span>円（税込）</span>
            </div>

            <button type="submit" form="productCustomizeForm" class="product-add-cart-button">
                カートに入れる
            </button>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('productCustomizeForm');
            const mainImage = document.getElementById('productMainImage');
            const thumbnails = Array.from(document.querySelectorAll('.product-gallery-thumbnail'));
            const previousButton = document.querySelector('.product-gallery-arrow-prev');
            const nextButton = document.querySelector('.product-gallery-arrow-next');
            const quantityInput = document.getElementById('productQuantity');
            const totalPrice = document.getElementById('productTotalPrice');
            const totalPriceInput = document.getElementById('productTotalInput');
            const fileInput = document.getElementById('artworkFile');
            const fileName = document.getElementById('artworkFileName');
            const orderSummaryButton = document.getElementById('orderSummaryButton');
            const featureToggle = document.getElementById('productFeatureToggle');
            const featureContent = document.getElementById('productFeatureContent');
            let activeImageIndex = 0;

            const selectGalleryImage = function (index) {
                if (!thumbnails.length) return;

                activeImageIndex = (index + thumbnails.length) % thumbnails.length;
                const activeThumbnail = thumbnails[activeImageIndex];
                mainImage.classList.add('is-changing');

                window.setTimeout(function () {
                    mainImage.src = activeThumbnail.dataset.image;
                    mainImage.classList.remove('is-changing');
                }, 120);

                thumbnails.forEach(function (thumbnail, thumbnailIndex) {
                    const isActive = thumbnailIndex === activeImageIndex;
                    thumbnail.classList.toggle('is-active', isActive);
                    thumbnail.setAttribute('aria-current', String(isActive));
                });
            };

            const getSelectedPrice = function (name) {
                const selected = form.querySelector('input[name="' + name + '"]:checked');
                return selected ? Number(selected.dataset.price || 0) : 0;
            };

            const updatePrice = function () {
                let quantity = Number(quantityInput.value || 10);
                quantity = Math.max(10, Math.min(quantity, 1000));

                const basePrice = quantity * Number(quantityInput.dataset.unitPrice);
                const optionPrice = getSelectedPrice('printing')
                    + getSelectedPrice('ring_type')
                    + getSelectedPrice('thread_color')
                    + 300;
                const price = basePrice + optionPrice;

                totalPrice.textContent = new Intl.NumberFormat('ja-JP').format(price);
                totalPriceInput.value = price;
                document.querySelector('[data-summary-quantity]').textContent = quantity;
            };

            const validateRequiredGroup = function (name) {
                const checked = form.querySelector('input[name="' + name + '"]:checked');
                const group = form.querySelector('[data-required-group="' + name + '"]');
                group.classList.toggle('has-error', !checked);
                return Boolean(checked);
            };

            thumbnails.forEach(function (thumbnail, index) {
                thumbnail.addEventListener('click', function () {
                    selectGalleryImage(index);
                });
            });

            previousButton.addEventListener('click', function () {
                selectGalleryImage(activeImageIndex - 1);
            });

            nextButton.addEventListener('click', function () {
                selectGalleryImage(activeImageIndex + 1);
            });

            form.querySelectorAll('input[type="radio"]').forEach(function (radio) {
                radio.addEventListener('change', function () {
                    if (radio.name === 'shape' || radio.name === 'ring_type') {
                        validateRequiredGroup(radio.name);
                    }
                    updatePrice();
                });
            });

            quantityInput.addEventListener('input', updatePrice);

            fileInput.addEventListener('change', function () {
                fileName.textContent = fileInput.files.length
                    ? fileInput.files[0].name
                    : 'ここにファイルをドラッグ＆ドロップ、またはクリックしてファイルを選択';
            });

            orderSummaryButton.addEventListener('click', function () {
                document.getElementById('orderSummary').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });

            form.addEventListener('submit', function (event) {
                const shapeIsValid = validateRequiredGroup('shape');
                const ringIsValid = validateRequiredGroup('ring_type');

                if (!shapeIsValid || !ringIsValid) {
                    event.preventDefault();
                    const firstError = form.querySelector('.product-option-group.has-error');
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            });

            featureToggle.addEventListener('click', function () {
                const accordion = featureToggle.closest('.product-feature-accordion');
                const isOpen = ! accordion.classList.contains('is-open');

                featureContent.style.height = featureContent.getBoundingClientRect().height + 'px';
                featureContent.offsetHeight;

                accordion.classList.toggle('is-open', isOpen);
                featureContent.style.height = isOpen ? featureContent.scrollHeight + 'px' : '0px';
                featureToggle.setAttribute('aria-expanded', String(isOpen));

                featureContent.addEventListener('transitionend', function handleTransition(event) {
                    if (event.propertyName !== 'height') {
                        return;
                    }

                    featureContent.removeEventListener('transitionend', handleTransition);

                    if (accordion.classList.contains('is-open')) {
                        featureContent.style.height = 'auto';
                    }
                });
            });

            updatePrice();
        });
    </script>
@endsection