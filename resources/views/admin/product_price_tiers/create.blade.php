<h1>Add Product Price Tiers</h1>

<a href="{{ route('admin.product-price-tiers.index') }}">
    Back
</a>

<br><br>

@if($errors->any())
<div style="color:red; margin-bottom: 15px;">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.product-price-tiers.store') }}" method="POST">
    @csrf

    <div>
        <label>Product</label><br>
        <select name="product_id">
            <option value="">-- Select Product --</option>

            @foreach($products as $product)
                <option
                    value="{{ $product->product_id }}"
                    {{ old('product_id') == $product->product_id ? 'selected' : '' }}
                >
                    {{ $product->product_name }}
                </option>
            @endforeach
        </select>
    </div>

    <br>

    <h3>Price Tiers</h3>

    <div id="price-tier-wrapper">
        @php
            $oldTiers = old('tiers', [
                [
                    'min_qty' => '',
                    'max_qty' => '',
                    'unit_price' => '',
                    'is_active' => 1,
                ]
            ]);
        @endphp

        @foreach($oldTiers as $index => $tier)
            <div class="tier-item" style="border:1px solid #ddd; padding:15px; margin-bottom:15px;">
                <h4>Tier <span class="tier-number">{{ $index + 1 }}</span></h4>

                <div>
                    <label>Min Qty</label><br>
                    <input 
                        type="number" 
                        name="tiers[{{ $index }}][min_qty]" 
                        value="{{ $tier['min_qty'] ?? '' }}"
                    >
                </div>

                <br>

                <div>
                    <label>Max Qty</label><br>
                    <input 
                        type="number" 
                        name="tiers[{{ $index }}][max_qty]" 
                        value="{{ $tier['max_qty'] ?? '' }}"
                    >
                    <small>ปล่อยว่าง = ตั้งแต่ Min Qty ขึ้นไป</small>
                </div>

                <br>

                <div>
                    <label>Unit Price</label><br>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="tiers[{{ $index }}][unit_price]" 
                        value="{{ $tier['unit_price'] ?? '' }}"
                    >
                </div>

                <br>

                <div>
                    <label>
                        <input 
                            type="checkbox" 
                            name="tiers[{{ $index }}][is_active]" 
                            value="1"
                            {{ !empty($tier['is_active']) ? 'checked' : '' }}
                        >
                        Active
                    </label>
                </div>

                <br>

                <button type="button" class="remove-tier">
                    Remove
                </button>
            </div>
        @endforeach
    </div>

    <button type="button" id="add-tier">
        + Add Tier
    </button>

    <br><br>

    <button type="submit">Save</button>
</form>

<script>
    let tierIndex = document.querySelectorAll('.tier-item').length;

    document.getElementById('add-tier').addEventListener('click', function () {
        const wrapper = document.getElementById('price-tier-wrapper');

        const html = `
            <div class="tier-item" style="border:1px solid #ddd; padding:15px; margin-bottom:15px;">
                <h4>Tier <span class="tier-number">${tierIndex + 1}</span></h4>

                <div>
                    <label>Min Qty</label><br>
                    <input 
                        type="number" 
                        name="tiers[${tierIndex}][min_qty]" 
                        value=""
                    >
                </div>

                <br>

                <div>
                    <label>Max Qty</label><br>
                    <input 
                        type="number" 
                        name="tiers[${tierIndex}][max_qty]" 
                        value=""
                    >
                    <small>ปล่อยว่าง = ตั้งแต่ Min Qty ขึ้นไป</small>
                </div>

                <br>

                <div>
                    <label>Unit Price</label><br>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="tiers[${tierIndex}][unit_price]" 
                        value=""
                    >
                </div>

                <br>

                <div>
                    <label>
                        <input 
                            type="checkbox" 
                            name="tiers[${tierIndex}][is_active]" 
                            value="1"
                            checked
                        >
                        Active
                    </label>
                </div>

                <br>

                <button type="button" class="remove-tier">
                    Remove
                </button>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        tierIndex++;
        updateTierNumbers();
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-tier')) {
            const items = document.querySelectorAll('.tier-item');

            if (items.length <= 1) {
                alert('ต้องมีอย่างน้อย 1 tier');
                return;
            }

            e.target.closest('.tier-item').remove();
            updateTierNumbers();
        }
    });

    function updateTierNumbers() {
        document.querySelectorAll('.tier-item').forEach(function (item, index) {
            item.querySelector('.tier-number').innerText = index + 1;
        });
    }
</script>