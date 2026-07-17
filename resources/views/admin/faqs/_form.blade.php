<div class="faq-form-card">
    <div class="faq-form-grid">

        <div class="faq-form-group faq-form-full">
            <label>{{ request()->cookie('dev') == '1' ? 'Product' : '製品' }}</label>
            <select name="product_id">
                <option value="">{{ request()->cookie('dev') == '1' ? 'Select a product' : '製品を選択' }}</option>

                @foreach($products as $product)
                    <option value="{{ $product->product_id }}"
                        {{ old('product_id', $faq->product_id ?? '') == $product->product_id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>

            @error('product_id')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="faq-form-group faq-form-full">
            <label>{{ request()->cookie('dev') == '1' ? 'Question' : '質問' }} <span>*</span></label>
            <input
                type="text"
                name="question"
                value="{{ old('question', $faq->question ?? '') }}"
                required
            >

            @error('question')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="faq-form-group faq-form-full">
            <label>{{ request()->cookie('dev') == '1' ? 'Answer' : '回答' }}</label>
            <textarea name="answer" rows="8">{{ old('answer', $faq->answer ?? '') }}</textarea>

            @error('answer')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="faq-form-group">
            <label>{{ request()->cookie('dev') == '1' ? 'Sort Order' : 'ソート順' }} <span>*</span></label>
            <input
                type="number"
                name="sort_order"
                value="{{ old('sort_order', $faq->sort_order ?? 0) }}"
                min="0"
            >

            @error('sort_order')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="faq-form-group">
            <label>{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }} <span>*</span></label>
            <select name="status" required>
                <option value="show" {{ old('status', $faq->status ?? 'show') === 'show' ? 'selected' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Show' : '表示' }}
                </option>
                <option value="hide" {{ old('status', $faq->status ?? 'show') === 'hide' ? 'selected' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Hide' : '非表示' }}
                </option>
            </select>

            @error('status')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="faq-form-group faq-form-full">
            <label>{{ request()->cookie('dev') == '1' ? 'Display Settings' : '表示設定' }}</label>

            <label class="faq-check-box">
                <input type="checkbox" name="show_main" value="1"
                    {{ old('show_main', $faq->show_main ?? 0) ? 'checked' : '' }}>
                {{ request()->cookie('dev') == '1' ? 'Show on Main FAQ page' : 'メインFAQページに表示' }}
            </label>

            <label class="faq-check-box">
                <input type="checkbox" name="show_product" value="1"
                    {{ old('show_product', $faq->show_product ?? 0) ? 'checked' : '' }}>
                {{ request()->cookie('dev') == '1' ? 'Show on Product Detail page' : '製品詳細ページに表示' }}
            </label>
        </div>

    </div>
</div>