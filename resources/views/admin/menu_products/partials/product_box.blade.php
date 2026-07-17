<div class="manage-box">
    <div class="manage-box-header">
        <div class="manage-box-title">{{ $title }}</div>
        <div class="manage-count">
            {{ $items->count() }} / {{ $limit }}
        </div>
    </div>

    <div class="manage-list">
        @forelse ($items as $product)
            <div class="manage-item">
                <div>
                    <div class="manage-product-name">
                        {{ $product->product_name }}
                    </div>
                    <div class="manage-product-code">
                        ID: {{ $product->product_id }} | Code: {{ $product->product_code }}
                    </div>
                </div>

                <form action="{{ route('admin.menu-products.remove', $product->product_id) }}" method="POST">
                    @csrf

                    <input type="hidden" name="target" value="{{ $target }}">

                    <button type="submit" class="btn-remove" onclick="return confirm('Remove this product?')">
                        Remove
                    </button>
                </form>
            </div>
        @empty
            <div class="manage-item">
                <div class="manage-product-code">No products selected.</div>
            </div>
        @endforelse
    </div>

    <form action="{{ route('admin.menu-products.add') }}" method="POST" class="manage-add-form">
        @csrf

        <input type="hidden" name="target" value="{{ $target }}">

        <select name="product_id" required>
            <option value="">{{ request()->cookie('dev') == '1' ? '-- Select Product --' : '-- 商品を選択 --' }}
            </option>

            @foreach ($products as $product)
                <option value="{{ $product->product_id }}">
                    {{ $product->product_name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn-small">
            {{ request()->cookie('dev') == '1' ? '+ Add' : '+ 追加' }}
        </button>
    </form>
</div>
