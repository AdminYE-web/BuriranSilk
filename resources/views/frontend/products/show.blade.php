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
                    <img id="productMainImage" src="{{ asset($product['gallery'][0]) }}" alt="{{ $product['short_name'] }}">

                    <button type="button" class="product-gallery-arrow product-gallery-arrow-prev" aria-label="前の画像">
                        <span aria-hidden="true"></span>
                    </button>

                    <button type="button" class="product-gallery-arrow product-gallery-arrow-next" aria-label="次の画像">
                        <span aria-hidden="true"></span>
                    </button>
                </div>

                <div class="product-gallery-thumbnails" role="list" aria-label="商品画像一覧">
                    @foreach ($product['gallery'] as $index => $image)
                        <button type="button" class="product-gallery-thumbnail {{ $index === 0 ? 'is-active' : '' }}"
                            data-image="{{ asset($image) }}" data-index="{{ $index }}"
                            aria-label="商品画像 {{ $index + 1 }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}">
                            <img src="{{ asset($image) }}" alt="">
                        </button>
                    @endforeach

                    @for ($placeholder = count($product['gallery']); $placeholder < 9; $placeholder++)
                        <span class="product-gallery-thumbnail-placeholder" aria-hidden="true"></span>
                    @endfor

                    <span class="product-gallery-thumbnail-indicator" aria-hidden="true"></span>
                </div>

                <p class="product-gallery-description">
                    {{ $product['description'] }}
                </p>
            </div>
        </section>

        <section class="product-options-column" aria-labelledby="productDetailTitle">
            @php
                $editingSelectedOptionIds = collect($editingCartItem['selected_options'] ?? [])
                    ->pluck('option_id')
                    ->map(fn($optionId) => (int) $optionId);
                $editingPreviousOrderNumbers = $editingCartItem['previous_order_numbers'] ?? [];
                $editingFontEntries = $editingCartItem['font_entries'] ?? [];
                $editingArtworkNames = collect($editingCartItem['artworks'] ?? [])
                    ->mapWithKeys(
                        fn($artwork) => [
                            (string) ($artwork['group_id'] ?? '') => $artwork['original_name'] ?? '',
                        ],
                    )
                    ->all();
            @endphp
            <form id="productCustomizeForm" class="product-options-form" action="{{ route('cart.store') }}" method="POST"
                enctype="multipart/form-data" novalidate>
                @csrf
                <input type="hidden" name="product" value="{{ $product['slug'] }}">
                @if ($editingCartItemId)
                    <input type="hidden" name="cart_item_id" value="{{ $editingCartItemId }}">
                @endif
                <input id="productTotalInput" type="hidden" name="total_price" value="{{ $product['unit_price'] * 100 }}">

                <h1 id="productDetailTitle" class="product-detail-title">
                    {{ $product['name'] }}
                </h1>

                @php
                    $displayOptionNumber = 0;
                @endphp

                @if (!empty($optionGroups))
                    @foreach ($optionGroups as $optionGroup)
                        @php
                            $displayType = $optionGroup['display_type'];
                            $groupInputName = 'options[' . $optionGroup['id'] . ']';
                            $hasDefaultOption = collect($optionGroup['options'])->contains('is_default', true);
                        @endphp

                        <fieldset class="product-option-group"
                            @if ($optionGroup['is_required']) data-required-group="{{ $optionGroup['id'] }}" @endif
                            data-option-group="{{ $optionGroup['id'] }}" data-display-type="{{ $displayType }}">
                            @if (!in_array($displayType, ['upload_option', 'font_option'], true))
                                @php
                                    $displayOptionNumber++;
                                @endphp
                                <legend class="product-option-heading">
                                    <span class="product-option-number">{{ $displayOptionNumber }}</span>
                                    <span>{{ $optionGroup['name'] }}</span>
                                </legend>
                            @endif

                            @if ($displayType === 'previous_order_design')
                                <div class="product-option-stack">
                                    @foreach ($optionGroup['options'] as $optionIndex => $option)
                                        @php
                                            $normalizedOption = \Illuminate\Support\Str::lower(
                                                trim($option['code'] ?: $option['name']),
                                            );
                                            $showsOrderNumber = in_array(
                                                $normalizedOption,
                                                ['yes', 'はい', 'sim', 'true', '1'],
                                                true,
                                            );
                                            $isChecked = $editingCartItem
                                                ? $editingSelectedOptionIds->contains((int) $option['id'])
                                                : $option['is_default'] || (!$hasDefaultOption && $optionIndex === 0);
                                        @endphp
                                        <label class="product-option-line">
                                            <input type="radio" name="{{ $groupInputName }}" value="{{ $option['id'] }}"
                                                data-option-id="{{ $option['id'] }}"
                                                data-option-name="{{ $option['name'] }}"
                                                data-option-price="{{ $option['additional_price'] }}"
                                                data-price-type="{{ $option['price_type'] }}"
                                                data-free-from-qty="{{ $option['free_from_qty'] }}"
                                                data-show-previous-order-detail="{{ $showsOrderNumber ? 'true' : 'false' }}"
                                                @checked($isChecked) @required($optionGroup['is_required'])>
                                            <span>{{ $option['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="product-option-stack product-repeat-order-details" data-previous-order-details
                                    hidden>
                                    <label for="previousOrderNumber-{{ $optionGroup['id'] }}">
                                        前回ご注文の管理番号等がもしおわかりでしたら、ご入力ください。
                                    </label>
                                    <input id="previousOrderNumber-{{ $optionGroup['id'] }}" type="text"
                                        name="previous_order_number[{{ $optionGroup['id'] }}]"
                                        value="{{ $editingPreviousOrderNumbers[$optionGroup['id']] ?? '' }}"
                                        autocomplete="off" disabled>
                                </div>
                            @elseif ($displayType === 'upload_option')
                                <p class="product-option-help">{{ $optionGroup['name'] }}</p>
                                <p class="product-option-note">
                                    ご入稿データはアウトライン化し、画像を埋め込んだ状態でご用意ください。
                                </p>

                                <div class="product-option-stack">
                                    @foreach ($optionGroup['options'] as $optionIndex => $option)
                                        @php
                                            $isChecked = $editingCartItem
                                                ? $editingSelectedOptionIds->contains((int) $option['id'])
                                                : $option['is_default'] || (!$hasDefaultOption && $optionIndex === 0);
                                        @endphp
                                        <label class="product-option-line">
                                            <input type="radio" name="{{ $groupInputName }}" value="{{ $option['id'] }}"
                                                data-option-id="{{ $option['id'] }}"
                                                data-option-name="{{ $option['name'] }}"
                                                data-option-price="{{ $option['additional_price'] }}"
                                                data-price-type="{{ $option['price_type'] }}"
                                                data-free-from-qty="{{ $option['free_from_qty'] }}"
                                                @checked($isChecked) @required($optionGroup['is_required'])>
                                            <span>{{ $option['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <label class="product-upload-field">
                                    <span>データ入稿（印刷データをお持ちの方）</span>
                                    <input id="artworkFile-{{ $optionGroup['id'] }}" type="file"
                                        name="artwork[{{ $optionGroup['id'] }}]"
                                        accept=".ai,.pdf,.eps,.psd,.png,.jpg,.jpeg" data-artwork-file>
                                    <span class="product-upload-dropzone">
                                        <svg viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M7 3h7l5 5v13H7z" />
                                            <path d="M14 3v5h5M12 17v-6m0 0-3 3m3-3 3 3" />
                                        </svg>
                                        <span data-artwork-file-name>
                                            ここにファイルをドラッグ＆ドロップ、またはクリックしてファイルを選択
                                        </span>
                                    </span>
                                </label>

                                <p class="product-option-note">
                                    対応形式：ai、pdf、eps、psd、png、jpg。大容量データは別途ご相談ください。
                                </p>
                            @elseif ($displayType === 'font_option')
                                <p class="product-option-help">{{ $optionGroup['name'] }}</p>

                                <div class="product-option-stack">
                                    @foreach ($optionGroup['options'] as $optionIndex => $option)
                                        @php
                                            $normalizedOption = \Illuminate\Support\Str::lower(
                                                trim($option['code'] ?: $option['name']),
                                            );
                                            $showsFontDetails = in_array(
                                                $normalizedOption,
                                                ['yes', 'はい', 'sim', 'true', '1'],
                                                true,
                                            );
                                            $isChecked = $editingCartItem
                                                ? $editingSelectedOptionIds->contains((int) $option['id'])
                                                : $option['is_default'] || (!$hasDefaultOption && $optionIndex === 0);
                                        @endphp
                                        <label class="product-option-line">
                                            <input type="radio" name="{{ $groupInputName }}"
                                                value="{{ $option['id'] }}" data-option-id="{{ $option['id'] }}"
                                                data-option-name="{{ $option['name'] }}"
                                                data-option-price="{{ $option['additional_price'] }}"
                                                data-price-type="{{ $option['price_type'] }}"
                                                data-free-from-qty="{{ $option['free_from_qty'] }}"
                                                data-show-font-details="{{ $showsFontDetails ? 'true' : 'false' }}"
                                                @checked($isChecked) @required($optionGroup['is_required'])>
                                            <span>{{ $option['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="product-font-option-details" data-font-option-details
                                    data-font-group-id="{{ $optionGroup['id'] }}" hidden>
                                    <div data-font-entries>
                                        <div class="product-font-entry" data-font-entry>
                                            <div class="product-font-entry-header">
                                                <span class="product-font-entry-number" data-font-entry-number>1</span>
                                                <button type="button" class="product-font-entry-remove"
                                                    data-remove-font-entry hidden>
                                                    削除
                                                </button>
                                            </div>

                                            <label class="product-font-field">
                                                <span>名入れ文字・テキスト</span>
                                                <input type="text"
                                                    name="font_entries[{{ $optionGroup['id'] }}][0][text]"
                                                    placeholder="配置する文字をご入力ください" data-font-field="text">
                                            </label>

                                            <label class="product-font-field">
                                                <span>ご希望の書体・フォント</span>
                                                <input type="text"
                                                    name="font_entries[{{ $optionGroup['id'] }}][0][font]"
                                                    placeholder="例: ゴシック体 / 明朝体" data-font-field="font">
                                            </label>

                                            <div class="product-font-field">
                                                <span>フォントサイズ</span>
                                                <div class="product-font-size-control">
                                                    <button type="button" data-font-size-action="decrease"
                                                        aria-label="フォントサイズを小さくする">−</button>
                                                    <input type="number"
                                                        name="font_entries[{{ $optionGroup['id'] }}][0][size]"
                                                        min="1" max="200" value="12"
                                                        data-font-field="size">
                                                    <button type="button" data-font-size-action="increase"
                                                        aria-label="フォントサイズを大きくする">＋</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <button type="button" class="product-font-entry-add" data-add-font-entry>
                                        ＋ テキストを追加する
                                    </button>

                                    <template data-font-entry-template>
                                        <div class="product-font-entry" data-font-entry>
                                            <div class="product-font-entry-header">
                                                <span class="product-font-entry-number" data-font-entry-number></span>
                                                <button type="button" class="product-font-entry-remove"
                                                    data-remove-font-entry>
                                                    削除
                                                </button>
                                            </div>

                                            <label class="product-font-field">
                                                <span>名入れ文字・テキスト</span>
                                                <input type="text" placeholder="配置する文字をご入力ください"
                                                    data-font-field="text">
                                            </label>

                                            <label class="product-font-field">
                                                <span>ご希望の書体・フォント</span>
                                                <input type="text" placeholder="例: ゴシック体 / 明朝体"
                                                    data-font-field="font">
                                            </label>

                                            <div class="product-font-field">
                                                <span>フォントサイズ</span>
                                                <div class="product-font-size-control">
                                                    <button type="button" data-font-size-action="decrease"
                                                        aria-label="フォントサイズを小さくする">−</button>
                                                    <input type="number" min="1" max="200" value="12"
                                                        data-font-field="size">
                                                    <button type="button" data-font-size-action="increase"
                                                        aria-label="フォントサイズを大きくする">＋</button>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            @elseif ($displayType === 'image_card')
                                <div class="product-option-image-grid product-option-image-grid-two">
                                    @foreach ($optionGroup['options'] as $option)
                                        <label class="product-option-image-card">
                                            <input type="radio" name="{{ $groupInputName }}"
                                                value="{{ $option['id'] }}" data-option-id="{{ $option['id'] }}"
                                                data-option-name="{{ $option['name'] }}"
                                                data-option-price="{{ $option['additional_price'] }}"
                                                data-price-type="{{ $option['price_type'] }}"
                                                data-free-from-qty="{{ $option['free_from_qty'] }}"
                                                @checked($editingCartItem ? $editingSelectedOptionIds->contains((int) $option['id']) : $option['is_default']) @required($optionGroup['is_required'])>
                                            <span class="product-option-image-box">
                                                @if ($option['image'])
                                                    <img src="{{ asset($option['image']) }}"
                                                        alt="{{ $option['image_alt'] ?: $option['name'] }}">
                                                @endif
                                            </span>
                                            <span class="product-option-image-label">{{ $option['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @elseif ($displayType === 'image_grid_compact')
                                <div class="product-option-image-grid product-option-image-grid-three">
                                    @foreach ($optionGroup['options'] as $option)
                                        <label class="product-option-image-card product-ring-card">
                                            <input type="radio" name="{{ $groupInputName }}"
                                                value="{{ $option['id'] }}" data-option-id="{{ $option['id'] }}"
                                                data-option-name="{{ $option['name'] }}"
                                                data-option-price="{{ $option['additional_price'] }}"
                                                data-price-type="{{ $option['price_type'] }}"
                                                data-free-from-qty="{{ $option['free_from_qty'] }}"
                                                @checked($editingCartItem ? $editingSelectedOptionIds->contains((int) $option['id']) : $option['is_default']) @required($optionGroup['is_required'])>
                                            <span class="product-ring-icon" aria-hidden="true">
                                                @if ($option['image'])
                                                    <img src="{{ asset($option['image']) }}" alt="">
                                                @else
                                                    <span class="product-ring-circle"></span>
                                                    <span class="product-ring-stem"></span>
                                                @endif
                                            </span>
                                            <span class="product-option-image-label">{{ $option['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="product-option-stack">
                                    @foreach ($optionGroup['options'] as $option)
                                        <label class="product-option-line">
                                            <input type="radio" name="{{ $groupInputName }}"
                                                value="{{ $option['id'] }}" data-option-id="{{ $option['id'] }}"
                                                data-option-name="{{ $option['name'] }}"
                                                data-option-price="{{ $option['additional_price'] }}"
                                                data-price-type="{{ $option['price_type'] }}"
                                                data-free-from-qty="{{ $option['free_from_qty'] }}"
                                                @checked($editingCartItem ? $editingSelectedOptionIds->contains((int) $option['id']) : $option['is_default']) @required($optionGroup['is_required'])>
                                            <span>{{ $option['name'] }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            @endif

                            @if (!in_array($displayType, ['upload_option', 'font_option'], true) && filled($optionGroup['help_text']))
                                <p class="product-option-help">{{ $optionGroup['help_text'] }}</p>
                            @endif

                            @if ($optionGroup['is_required'])
                                <p class="product-option-error" data-error-for="{{ $optionGroup['id'] }}">
                                    ※タイプを選択してください。
                                </p>
                            @endif
                        </fieldset>
                    @endforeach
                @endif

                @if (empty($optionGroups))
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

                        <div id="repeatDesignDetails" class="product-option-stack product-repeat-order-details" hidden>
                            <label for="previousOrderNumber">
                                前回ご注文の管理番号等がもしおわかりでしたら、ご入力ください。
                            </label>
                            <input id="previousOrderNumber" type="text" name="previous_order_number[legacy]"
                                autocomplete="off" disabled>
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
                                    <img src="{{ asset('assets/images/product/Rectangle 174.png') }}" alt="">
                                </span>
                                <span class="product-option-image-label">横型</span>
                            </label>

                            <label class="product-option-image-card">
                                <input type="radio" name="shape" value="vertical" required>
                                <span class="product-option-image-box">
                                    <img src="{{ asset('assets/images/product/Rectangle 175.png') }}" alt="">
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
                            <input id="artworkFile" type="file" name="artwork[legacy]"
                                accept=".ai,.pdf,.eps,.psd,.png,.jpg,.jpeg">
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

                    </fieldset>



                    <fieldset class="product-option-group" data-required-group="ring_type">
                        <legend class="product-option-heading">
                            <span class="product-option-number">4</span>
                            <span>金具 Combination Metal Type</span>
                        </legend>

                        <div class="product-option-image-grid product-option-image-grid-three">
                            @foreach (['丸型1', '丸型2', '丸型3', '丸型4', '丸型5', '丸型6'] as $ringIndex => $ringName)
                                <label class="product-option-image-card product-ring-card">
                                    <input type="radio" name="ring_type" value="ring-{{ $ringIndex + 1 }}"
                                        data-price="{{ $ringIndex < 2 ? 0 : 300 }}" required>
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
                            <span class="product-option-number">5</span>
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
                @endif
                <fieldset class="product-option-group">
                    <legend class="product-option-heading">
                        <span class="product-option-number">
                            {{ empty($optionGroups) ? 6 : $displayOptionNumber + 1 }}
                        </span>
                        <span>数量</span>
                    </legend>

                    <label class="product-quantity-field">
                        <input id="productQuantity" type="number" name="quantity" step="1"
                            value="{{ $editingCartItem['quantity'] ?? 100 }}" required
                            data-unit-price="{{ $product['unit_price'] }}">
                        <span>個</span>
                    </label>
                    <p class="product-option-note" data-quantity-rule-note hidden></p>
                </fieldset>

            </form>
        </section>

        <div id="orderSummaryFlyout" class="product-order-flyout">
            <button id="orderSummaryButton" type="button" class="product-order-tab" aria-expanded="false"
                aria-controls="orderSummary">
                <span>注文内容</span>
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M7 3h10v18H7zM9.5 8h5M9.5 12h5M9.5 16h3" />
                </svg>
            </button>

            <aside id="orderSummary" class="product-order-summary" aria-labelledby="orderSummaryTitle">
                <h2 id="orderSummaryTitle">注文内容</h2>
                <dl>
                    <div>
                        <dt>商品名</dt>
                        <dd>{{ $product['short_name'] }}</dd>
                    </div>
                    @foreach ($optionGroups as $optionGroup)
                        <div data-summary-option-group="{{ $optionGroup['id'] }}">
                            <dt>{{ $optionGroup['name'] }}</dt>
                            <dd data-summary-option-value>未選択</dd>
                        </div>
                    @endforeach
                    <div>
                        <dt>数量</dt>
                        <dd><span data-summary-quantity>100</span></dd>
                    </div>
                </dl>
            </aside>
        </div>
    </div>

    @if (filled($product['specification_image'] ?? null))
        <section class="product-detail-banner container" aria-label="タイシルクカードケース">
            <div class="product-detail-banner-inner">
                <img src="{{ asset($product['specification_image']) }}" alt="雅 Miyabi Thai Silk Card Box"
                    class="product-detail-banner-image">
            </div>
        </section>
    @endif

    @foreach ($product['accordion_content'] ?? [] as $accordionIndex => $accordion)
        @php
            $accordionTitle = data_get($accordion, 'title');
            $accordionContent = data_get($accordion, 'content');
            $accordionTitleId = 'productFeatureTitle-' . $accordionIndex;
            $accordionContentId = 'productFeatureContent-' . $accordionIndex;
        @endphp

        @if (filled($accordionTitle) || filled($accordionContent))
            <section class="product-feature-accordion container is-open" aria-labelledby="{{ $accordionTitleId }}">
                <button type="button" class="product-feature-toggle" aria-expanded="true"
                    aria-controls="{{ $accordionContentId }}">
                    <span class="product-feature-chevron" aria-hidden="true"></span>
                    <span id="{{ $accordionTitleId }}">{{ $accordionTitle }}</span>
                </button>

                <div id="{{ $accordionContentId }}" class="product-feature-content">
                    <div class="product-feature-content-inner">
                        {!! $accordionContent !!}
                    </div>
                </div>
            </section>
        @endif
    @endforeach

    <section class="container" aria-labelledby="productItemDescriptionTitle">
        <h2 id="productItemDescriptionTitle" class="product-item-description-title">
            アイテム説明
        </h2>

        <div class="product-item-description-grid">
            {{-- คำอธิบายด้านซ้าย --}}
            <div class="product-item-description-copy product-long-description">
                {!! $product['long_description'] ?? '' !!}
            </div>

            {{-- Legacy hardcoded item description (replaced by product_details.long_description)

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
        --}}

            {{-- รายละเอียดสินค้าด้านขวา --}}
            <div class="product-specification-card">
                <dl class="product-specification-list">
                    @foreach ($product['specification_content'] ?? [] as $specification)
                        @php
                            $specificationTitle = data_get($specification, 'title');
                            $specificationDescription = data_get($specification, 'desc');
                        @endphp

                        @if (filled($specificationTitle) || filled($specificationDescription))
                            <div>
                                <dt>{{ $specificationTitle }}</dt>
                                <dd>{!! nl2br(e($specificationDescription ?? '')) !!}</dd>
                            </div>
                        @endif
                    @endforeach

                    {{-- Legacy hardcoded specifications
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
                --}}
                </dl>
            </div>
        </div>
    </section>

    <div class="product-cart-bar">
        <div class="product-cart-bar-inner container">
            <div class="product-cart-price">
                <strong id="productTotalPrice">{{ number_format($product['unit_price'] * 100) }}</strong>
                <span>円（税抜）</span>
            </div>

            <button type="submit" form="productCustomizeForm" class="product-add-cart-button">
                {{ $editingCartItem ? 'カートの商品を更新' : 'カートに入れる' }}
            </button>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('productCustomizeForm');
            const optionDependencies = @json($optionDependencies);
            const optionQuantityRules = @json($optionQuantityRules);
            const editingFontEntries = @json($editingFontEntries);
            const editingArtworkNames = @json($editingArtworkNames);
            const editingCustomFields = @json($editingCartItem['custom_fields'] ?? []);
            const mainImage = document.getElementById('productMainImage');
            const thumbnails = Array.from(document.querySelectorAll('.product-gallery-thumbnail'));
            const previousButton = document.querySelector('.product-gallery-arrow-prev');
            const nextButton = document.querySelector('.product-gallery-arrow-next');
            const quantityInput = document.getElementById('productQuantity');
            const quantityRuleNote = form.querySelector('[data-quantity-rule-note]');
            const totalPrice = document.getElementById('productTotalPrice');
            const totalPriceInput = document.getElementById('productTotalInput');
            const fileInputs = form.querySelectorAll('input[data-artwork-file], #artworkFile');
            const orderSummaryFlyout = document.getElementById('orderSummaryFlyout');
            const orderSummaryButton = document.getElementById('orderSummaryButton');
            const featureToggles = document.querySelectorAll('.product-feature-toggle');
            let activeImageIndex = 0;
            let isSliding = false;

            const selectGalleryImage = function(index, direction) {
                if (!thumbnails.length || isSliding) return;

                const previousIndex = activeImageIndex;
                const targetIndex = (index + thumbnails.length) % thumbnails.length;

                if (previousIndex === targetIndex && mainImage.src) return;

                activeImageIndex = targetIndex;
                const activeThumbnail = thumbnails[activeImageIndex];
                const newSrc = activeThumbnail.dataset.image;

                if (!direction) {
                    if (previousIndex === thumbnails.length - 1 && activeImageIndex === 0) {
                        direction = 'next';
                    } else if (previousIndex === 0 && activeImageIndex === thumbnails.length - 1) {
                        direction = 'prev';
                    } else {
                        direction = activeImageIndex > previousIndex ? 'next' : 'prev';
                    }
                }

                isSliding = true;

                const clone = document.createElement('img');
                clone.src = newSrc;
                clone.alt = mainImage.alt || '';
                clone.className = 'product-gallery-slide-clone';

                const startX = direction === 'next' ? '100%' : '-100%';
                const exitX = direction === 'next' ? '-100%' : '100%';

                clone.style.transition = 'none';
                clone.style.transform = 'translate(calc(-50% + ' + startX + '), -50%)';
                clone.style.opacity = '0';

                mainImage.parentNode.appendChild(clone);
                clone.offsetHeight; // Force reflow

                const duration = 320;
                const easing = 'cubic-bezier(0.25, 1, 0.5, 1)';

                mainImage.style.transition = 'transform ' + duration + 'ms ' + easing + ', opacity ' + duration + 'ms ease';
                clone.style.transition = 'transform ' + duration + 'ms ' + easing + ', opacity ' + duration + 'ms ease';

                mainImage.style.transform = 'translateX(' + exitX + ')';
                mainImage.style.opacity = '0';

                clone.style.transform = 'translate(-50%, -50%)';
                clone.style.opacity = '1';

                setTimeout(function() {
                    mainImage.src = newSrc;
                    mainImage.style.transition = 'none';
                    mainImage.style.transform = '';
                    mainImage.style.opacity = '1';

                    if (clone.parentNode) {
                        clone.parentNode.removeChild(clone);
                    }

                    isSliding = false;
                }, duration);

                thumbnails.forEach(function(thumbnail, thumbnailIndex) {
                    const isActive = thumbnailIndex === activeImageIndex;
                    thumbnail.classList.toggle('is-active', isActive);
                    thumbnail.setAttribute('aria-current', String(isActive));
                });

                updateThumbnailIndicator();
            };

            const updateThumbnailIndicator = function() {
                const activeThumbnail = thumbnails[activeImageIndex];
                const indicator = document.querySelector('.product-gallery-thumbnail-indicator');
                const container = document.querySelector('.product-gallery-thumbnails');

                if (!activeThumbnail || !indicator || !container) return;

                const thumbnailRect = activeThumbnail.getBoundingClientRect();
                const containerRect = container.getBoundingClientRect();

                const left = thumbnailRect.left - containerRect.left;
                const width = thumbnailRect.width;

                indicator.style.width = width + 'px';
                indicator.style.transform = 'translateX(' + left + 'px)';
                indicator.style.opacity = '1';
            };

            const updateOrderSummary = function() {
                form.querySelectorAll('[data-option-group]').forEach(function(group) {
                    const summaryValue = document.querySelector(
                        '[data-summary-option-group="' + group.dataset.optionGroup + '"] ' +
                        '[data-summary-option-value]'
                    );

                    if (!summaryValue) {
                        return;
                    }

                    summaryValue.closest('div').hidden = group.hidden;

                    const selectedOption = group.querySelector(
                        'input[data-option-name]:checked:not(:disabled)'
                    );
                    summaryValue.textContent = selectedOption ?
                        selectedOption.dataset.optionName :
                        '未選択';
                });
            };

            const getDependencyStates = function(selectedOptionIds) {
                const groupStates = new Map();
                const optionStates = new Map();

                optionDependencies.forEach(function(rule) {
                    const targetId = rule.target_type === 'group' ?
                        Number(rule.target_group_id) :
                        Number(rule.target_option_id);

                    if (!targetId) {
                        return;
                    }

                    const states = rule.target_type === 'group' ? groupStates : optionStates;
                    const state = states.get(targetId) || {
                        hasShowRule: false,
                        showMatched: false,
                        hideMatched: false,
                        disableMatched: false
                    };
                    const isMatched = selectedOptionIds.has(Number(rule.parent_option_id));

                    if (rule.action_type === 'show') {
                        state.hasShowRule = true;
                        state.showMatched = state.showMatched || isMatched;
                    } else if (rule.action_type === 'hide') {
                        state.hideMatched = state.hideMatched || isMatched;
                    } else if (rule.action_type === 'disable') {
                        state.disableMatched = state.disableMatched || isMatched;
                    }

                    states.set(targetId, state);
                });

                return {
                    groupStates: groupStates,
                    optionStates: optionStates
                };
            };

            const stateIsHidden = function(state) {
                return (state.hasShowRule && !state.showMatched) || state.hideMatched;
            };

            const applyOptionDependencies = function() {
                let previousSignature = '';
                const maxPasses = Math.max(optionDependencies.length + 1, 2);

                for (let pass = 0; pass < maxPasses; pass += 1) {
                    const selectedOptionIds = new Set(
                        Array.from(
                            form.querySelectorAll('input[data-option-id]:checked:not(:disabled)')
                        ).map(function(input) {
                            return Number(input.dataset.optionId);
                        })
                    );
                    const states = getDependencyStates(selectedOptionIds);
                    const signature = JSON.stringify({
                        groups: Array.from(states.groupStates.entries()),
                        options: Array.from(states.optionStates.entries())
                    });

                    form.querySelectorAll('[data-dependency-disabled]').forEach(function(control) {
                        control.disabled = false;
                        control.removeAttribute('data-dependency-disabled');
                    });

                    form.querySelectorAll('[data-option-group]').forEach(function(group) {
                        group.hidden = false;
                        group.removeAttribute('data-dependency-hidden');
                        group.removeAttribute('data-dependency-disabled');
                    });

                    form.querySelectorAll('input[data-option-id]').forEach(function(input) {
                        const optionLabel = input.closest('label');
                        optionLabel.hidden = false;
                        optionLabel.removeAttribute('data-dependency-hidden');
                        optionLabel.removeAttribute('data-dependency-disabled');
                    });

                    states.groupStates.forEach(function(state, groupId) {
                        const group = form.querySelector(
                            '[data-option-group="' + groupId + '"]'
                        );

                        if (!group) {
                            return;
                        }

                        const shouldHide = stateIsHidden(state);
                        const shouldDisable = state.disableMatched;
                        group.hidden = shouldHide;
                        group.toggleAttribute('data-dependency-hidden', shouldHide);
                        group.toggleAttribute('data-dependency-disabled', shouldDisable);

                        if (shouldHide || shouldDisable) {
                            group.querySelectorAll('input, select, textarea, button').forEach(function(
                                control) {
                                control.disabled = true;
                                control.setAttribute('data-dependency-disabled', 'true');
                            });
                        }
                    });

                    states.optionStates.forEach(function(state, optionId) {
                        const input = form.querySelector(
                            'input[data-option-id="' + optionId + '"]'
                        );

                        if (!input) {
                            return;
                        }

                        const optionLabel = input.closest('label');
                        const shouldHide = stateIsHidden(state);
                        const shouldDisable = state.disableMatched;
                        optionLabel.hidden = shouldHide;
                        optionLabel.toggleAttribute('data-dependency-hidden', shouldHide);
                        optionLabel.toggleAttribute('data-dependency-disabled', shouldDisable);

                        if (shouldHide || shouldDisable) {
                            input.disabled = true;
                            input.setAttribute('data-dependency-disabled', 'true');
                        }
                    });

                    if (signature === previousSignature) {
                        break;
                    }

                    previousSignature = signature;
                }
            };

            const getSelectedQuantityLimits = function() {
                let minimum = null;
                let maximum = null;
                let hasRule = false;

                form.querySelectorAll('input[data-option-id]:checked:not(:disabled)').forEach(function(input) {
                    const rule = optionQuantityRules[input.dataset.optionId];

                    if (!rule || !rule.type) {
                        return;
                    }

                    let ruleMinimum = null;
                    let ruleMaximum = null;

                    if (rule.type === 'min' || rule.type === 'range') {
                        ruleMinimum = Number(rule.min);
                    }

                    if (rule.type === 'max' || rule.type === 'range') {
                        ruleMaximum = Number(rule.max);
                    }

                    if (rule.type === 'exact') {
                        ruleMinimum = Number(rule.exact);
                        ruleMaximum = Number(rule.exact);
                    }

                    if (Number.isFinite(ruleMinimum) && ruleMinimum > 0) {
                        hasRule = true;
                        minimum = minimum === null ?
                            ruleMinimum :
                            Math.max(minimum, ruleMinimum);
                    }

                    if (Number.isFinite(ruleMaximum) && ruleMaximum > 0) {
                        hasRule = true;
                        maximum = maximum === null ?
                            ruleMaximum :
                            Math.min(maximum, ruleMaximum);
                    }
                });

                return {
                    hasRule: hasRule,
                    minimum: minimum,
                    maximum: maximum,
                    hasConflict: minimum !== null && maximum !== null && minimum > maximum
                };
            };

            const updateQuantityLimits = function(adjustValue) {
                const limits = getSelectedQuantityLimits();
                quantityInput.setCustomValidity('');

                if (!limits.hasRule) {
                    quantityInput.removeAttribute('min');
                    quantityInput.removeAttribute('max');
                    quantityRuleNote.hidden = true;
                    quantityRuleNote.textContent = '';
                    return true;
                }

                if (limits.hasConflict) {
                    quantityInput.removeAttribute('min');
                    quantityInput.removeAttribute('max');
                    quantityInput.setCustomValidity('選択したオプションの数量条件が一致しません。');
                    quantityRuleNote.textContent = '選択したオプションの数量条件が一致しません。';
                    quantityRuleNote.hidden = false;
                    return false;
                }

                if (limits.minimum === null) {
                    quantityInput.removeAttribute('min');
                } else {
                    quantityInput.min = limits.minimum;
                }

                if (limits.maximum === null) {
                    quantityInput.removeAttribute('max');
                } else {
                    quantityInput.max = limits.maximum;
                }

                if (adjustValue) {
                    let quantity = Number(quantityInput.value);

                    if (!Number.isFinite(quantity)) {
                        quantity = limits.minimum ?? limits.maximum ?? 100;
                    }

                    if (limits.minimum !== null) {
                        quantity = Math.max(quantity, limits.minimum);
                    }

                    if (limits.maximum !== null) {
                        quantity = Math.min(quantity, limits.maximum);
                    }

                    quantityInput.value = quantity;
                }

                if (limits.minimum !== null && limits.minimum === limits.maximum) {
                    quantityRuleNote.textContent = '数量は' + limits.minimum + '個に指定されています。';
                } else if (limits.minimum !== null && limits.maximum !== null) {
                    quantityRuleNote.textContent = 'ご注文数量は' + limits.minimum +
                        '〜' + limits.maximum + '個です。';
                } else if (limits.minimum !== null) {
                    quantityRuleNote.textContent = 'ご注文数量は' + limits.minimum + '個以上です。';
                } else {
                    quantityRuleNote.textContent = 'ご注文数量は' + limits.maximum + '個以下です。';
                }

                quantityRuleNote.hidden = false;
                return true;
            };

            const updatePrice = function() {
                let quantity = Number(quantityInput.value);

                if (!Number.isFinite(quantity)) {
                    quantity = 0;
                }

                const basePrice = quantity * Number(quantityInput.dataset.unitPrice);
                let optionPrice = 0;
                const selectedOptions = form.querySelectorAll(
                    'input[data-option-price]:checked:not(:disabled)'
                );

                selectedOptions.forEach(function(selectedOption) {
                    const additionalPrice = Number(selectedOption.dataset.optionPrice || 0);
                    const priceType = selectedOption.dataset.priceType || 'per_item';
                    const freeFromQuantity = Number(selectedOption.dataset.freeFromQty || 0);

                    if (freeFromQuantity > 0 && quantity >= freeFromQuantity) {
                        return;
                    }

                    optionPrice += priceType === 'per_order' ?
                        additionalPrice :
                        additionalPrice * quantity;
                });

                if (!selectedOptions.length) {
                    form.querySelectorAll('input[data-price]:checked').forEach(function(selectedOption) {
                        optionPrice += Number(selectedOption.dataset.price || 0);
                    });
                }

                const price = basePrice + optionPrice;

                totalPrice.textContent = new Intl.NumberFormat('ja-JP').format(price);
                totalPriceInput.value = price;
                document.querySelector('[data-summary-quantity]').textContent = quantity;
                updateOrderSummary();
            };

            const validateRequiredGroup = function(name) {
                const group = form.querySelector('[data-required-group="' + name + '"]');

                if (!group) {
                    return true;
                }

                if (group.hidden || group.hasAttribute('data-dependency-disabled')) {
                    group.classList.remove('has-error');
                    return true;
                }

                const checked = group.querySelector('input[type="radio"]:checked');
                group.classList.toggle('has-error', !checked);
                return Boolean(checked);
            };

            const updateRepeatDesignDetails = function() {
                form.querySelectorAll('[data-previous-order-details]').forEach(function(details) {
                    const group = details.closest('[data-option-group]');
                    const selectedOption = group.querySelector(
                        'input[data-show-previous-order-detail]:checked:not(:disabled)'
                    );
                    const shouldShow = selectedOption &&
                        !group.hidden &&
                        selectedOption.dataset.showPreviousOrderDetail === 'true';
                    const orderNumberInput = details.querySelector('input');

                    details.hidden = !shouldShow;
                    orderNumberInput.disabled = !shouldShow;
                });

                const legacyDetails = document.getElementById('repeatDesignDetails');

                if (!legacyDetails) {
                    return;
                }

                const repeatDesign = form.querySelector('input[name="repeat_design"]:checked');
                const shouldShow = repeatDesign && repeatDesign.value === 'yes';
                const orderNumberInput = legacyDetails.querySelector('input');

                legacyDetails.hidden = !shouldShow;
                orderNumberInput.disabled = !shouldShow;
            };

            const reindexFontEntries = function(details) {
                const groupId = details.dataset.fontGroupId;
                const entries = Array.from(details.querySelectorAll('[data-font-entry]'));

                entries.forEach(function(entry, index) {
                    entry.querySelector('[data-font-entry-number]').textContent = index + 1;
                    entry.querySelectorAll('[data-font-field]').forEach(function(field) {
                        field.name = 'font_entries[' + groupId + '][' + index + '][' +
                            field.dataset.fontField + ']';
                    });

                    const removeButton = entry.querySelector('[data-remove-font-entry]');
                    removeButton.hidden = entries.length === 1;
                });
            };

            const updateFontOptionDetails = function() {
                form.querySelectorAll('[data-font-option-details]').forEach(function(details) {
                    const group = details.closest('[data-option-group]');
                    const selectedOption = group.querySelector(
                        'input[data-show-font-details]:checked:not(:disabled)'
                    );
                    const shouldShow = selectedOption &&
                        !group.hidden &&
                        selectedOption.dataset.showFontDetails === 'true';

                    details.hidden = !shouldShow;
                    details.querySelectorAll('input, button').forEach(function(control) {
                        control.disabled = !shouldShow;
                    });
                    reindexFontEntries(details);
                });
            };

            thumbnails.forEach(function(thumbnail, index) {
                thumbnail.addEventListener('click', function() {
                    selectGalleryImage(index);
                });
            });

            if (previousButton) {
                previousButton.addEventListener('click', function() {
                    selectGalleryImage(activeImageIndex - 1, 'prev');
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', function() {
                    selectGalleryImage(activeImageIndex + 1, 'next');
                });
            }

            const galleryMain = document.querySelector('.product-gallery-main');
            let touchStartX = 0;
            let touchEndX = 0;

            if (galleryMain) {
                galleryMain.addEventListener('touchstart', function(e) {
                    touchStartX = e.changedTouches[0].screenX;
                }, { passive: true });

                galleryMain.addEventListener('touchend', function(e) {
                    touchEndX = e.changedTouches[0].screenX;
                    const diff = touchEndX - touchStartX;
                    if (Math.abs(diff) > 40) {
                        if (diff < 0) {
                            selectGalleryImage(activeImageIndex + 1, 'next');
                        } else {
                            selectGalleryImage(activeImageIndex - 1, 'prev');
                        }
                    }
                }, { passive: true });
            }

            form.querySelectorAll('input[type="radio"]').forEach(function(radio) {
                radio.addEventListener('change', function() {
                    applyOptionDependencies();
                    updateRepeatDesignDetails();
                    updateFontOptionDetails();
                    updateQuantityLimits(true);

                    const requiredGroup = radio.closest('[data-required-group]');

                    if (requiredGroup) {
                        validateRequiredGroup(requiredGroup.dataset.requiredGroup);
                    }

                    updatePrice();
                });
            });

            form.addEventListener('click', function(event) {
                const addButton = event.target.closest('[data-add-font-entry]');

                if (addButton) {
                    const details = addButton.closest('[data-font-option-details]');
                    const entries = details.querySelector('[data-font-entries]');
                    const template = details.querySelector('[data-font-entry-template]');
                    const newEntry = template.content.firstElementChild.cloneNode(true);

                    entries.appendChild(newEntry);
                    reindexFontEntries(details);
                    newEntry.querySelector('[data-font-field="text"]').focus();
                    return;
                }

                const removeButton = event.target.closest('[data-remove-font-entry]');

                if (removeButton) {
                    const details = removeButton.closest('[data-font-option-details]');
                    const entries = details.querySelectorAll('[data-font-entry]');

                    if (entries.length > 1) {
                        removeButton.closest('[data-font-entry]').remove();
                        reindexFontEntries(details);
                    }
                    return;
                }

                const sizeButton = event.target.closest('[data-font-size-action]');

                if (!sizeButton) {
                    return;
                }

                const sizeInput = sizeButton.closest('.product-font-size-control')
                    .querySelector('[data-font-field="size"]');
                const minimum = Number(sizeInput.min || 1);
                const maximum = Number(sizeInput.max || 200);
                const currentValue = Number(sizeInput.value || 12);
                const adjustment = sizeButton.dataset.fontSizeAction === 'increase' ? 1 : -1;
                sizeInput.value = Math.max(minimum, Math.min(maximum, currentValue + adjustment));
                sizeInput.dispatchEvent(new Event('input', {
                    bubbles: true
                }));
            });

            quantityInput.addEventListener('input', updatePrice);
            quantityInput.addEventListener('change', function() {
                updateQuantityLimits(true);
                updatePrice();
            });

            fileInputs.forEach(function(fileInput) {
                const uploadField = fileInput.closest('.product-upload-field');
                const fileName = uploadField.querySelector(
                    '[data-artwork-file-name], #artworkFileName'
                );
                const groupMatch = fileInput.name.match(/^artwork\[([^\]]+)\]$/);
                const existingFileName = groupMatch ?
                    editingArtworkNames[groupMatch[1]] :
                    null;
                const emptyFileText = existingFileName ?
                    '現在のファイル：' + existingFileName + '（新しいファイルを選択すると差し替えます）' :
                    'ここにファイルをドラッグ＆ドロップ、またはクリックしてファイルを選択';

                fileName.textContent = emptyFileText;

                fileInput.addEventListener('change', function() {
                    fileName.textContent = fileInput.files.length ?
                        fileInput.files[0].name :
                        emptyFileText;
                });
            });

            orderSummaryButton.addEventListener('click', function() {
                const isOpen = orderSummaryFlyout.classList.toggle('is-open');
                orderSummaryButton.setAttribute('aria-expanded', String(isOpen));
            });

            orderSummaryFlyout.addEventListener('mouseenter', function() {
                orderSummaryButton.setAttribute('aria-expanded', 'true');
            });

            orderSummaryFlyout.addEventListener('mouseleave', function() {
                if (!orderSummaryFlyout.classList.contains('is-open')) {
                    orderSummaryButton.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('click', function(event) {
                if (orderSummaryFlyout.contains(event.target)) {
                    return;
                }

                orderSummaryFlyout.classList.remove('is-open');
                orderSummaryButton.setAttribute('aria-expanded', 'false');
            });

            document.addEventListener('keydown', function(event) {
                if (event.key !== 'Escape') {
                    return;
                }

                orderSummaryFlyout.classList.remove('is-open');
                orderSummaryButton.setAttribute('aria-expanded', 'false');
                orderSummaryButton.focus();
            });

            form.addEventListener('submit', function(event) {
                let formIsValid = updateQuantityLimits(false) && quantityInput.checkValidity();
                quantityInput.closest('.product-option-group')
                    .classList.toggle('has-error', !formIsValid);

                form.querySelectorAll('[data-required-group]').forEach(function(group) {
                    if (!validateRequiredGroup(group.dataset.requiredGroup)) {
                        formIsValid = false;
                    }
                });

                if (!formIsValid) {
                    event.preventDefault();
                    const firstError = form.querySelector('.product-option-group.has-error');
                    firstError.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });

            featureToggles.forEach(function(featureToggle) {
                const featureContent = document.getElementById(featureToggle.getAttribute('aria-controls'));

                if (!featureContent) {
                    return;
                }

                featureToggle.addEventListener('click', function() {
                    const accordion = featureToggle.closest('.product-feature-accordion');
                    const isOpen = !accordion.classList.contains('is-open');

                    featureContent.style.height = featureContent.getBoundingClientRect().height +
                        'px';
                    featureContent.offsetHeight;

                    accordion.classList.toggle('is-open', isOpen);
                    featureContent.style.height = isOpen ? featureContent.scrollHeight + 'px' :
                        '0px';
                    featureToggle.setAttribute('aria-expanded', String(isOpen));

                    featureContent.addEventListener('transitionend', function handleTransition(
                        event) {
                        if (event.propertyName !== 'height') {
                            return;
                        }

                        featureContent.removeEventListener('transitionend',
                            handleTransition);

                        if (accordion.classList.contains('is-open')) {
                            featureContent.style.height = 'auto';
                        }
                    });
                });
            });

            Object.entries(editingCustomFields).forEach(function([fieldName, savedValue]) {
                if (savedValue === null || typeof savedValue === 'object') {
                    return;
                }

                form.querySelectorAll('[name="' + CSS.escape(fieldName) + '"]').forEach(function(field) {
                    if (field.type === 'radio' || field.type === 'checkbox') {
                        field.checked = String(field.value) === String(savedValue);
                    } else {
                        field.value = savedValue;
                    }
                });
            });

            Object.entries(editingFontEntries).forEach(function([groupId, savedEntries]) {
                if (!Array.isArray(savedEntries) || !savedEntries.length) {
                    return;
                }

                const details = form.querySelector(
                    '[data-font-option-details][data-font-group-id="' + groupId + '"]'
                );

                if (!details) {
                    return;
                }

                const entriesContainer = details.querySelector('[data-font-entries]');
                const template = details.querySelector('[data-font-entry-template]');

                while (entriesContainer.querySelectorAll('[data-font-entry]').length < savedEntries
                    .length) {
                    entriesContainer.appendChild(template.content.firstElementChild.cloneNode(true));
                }

                entriesContainer.querySelectorAll('[data-font-entry]').forEach(function(entry, index) {
                    const savedEntry = savedEntries[index] || {};
                    entry.querySelector('[data-font-field="text"]').value = savedEntry.text || '';
                    entry.querySelector('[data-font-field="font"]').value = savedEntry.font || '';
                    entry.querySelector('[data-font-field="size"]').value = savedEntry.size || 12;
                });
                reindexFontEntries(details);
            });

            applyOptionDependencies();
            updateRepeatDesignDetails();
            updateFontOptionDetails();
            updateQuantityLimits(true);
            updatePrice();
            updateThumbnailIndicator();
            window.addEventListener('resize', updateThumbnailIndicator);
        });
    </script>
@endsection
