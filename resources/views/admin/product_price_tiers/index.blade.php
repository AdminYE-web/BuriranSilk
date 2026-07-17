<h1>Product Price Tiers</h1>

@if(session('success'))
<div style="color: green; margin-bottom: 15px;">
    {{ session('success') }}
</div>
@endif

<a href="{{ route('admin.product-price-tiers.create') }}">
    Add Price Tier
</a>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Min Qty</th>
            <th>Max Qty</th>
            <th>Unit Price</th>
            <th>Status</th>
            <th>Manage</th>
        </tr>
    </thead>

    <tbody>
        @forelse($tiers as $tier)
        <tr>
            <td>{{ $tier->tier_id }}</td>

            <td>
                {{ $tier->product->product_name ?? '-' }}
            </td>

            <td>{{ $tier->min_qty }}</td>

            <td>
                {{ $tier->max_qty ?? 'ขึ้นไป' }}
            </td>

            <td>
                {{ number_format($tier->unit_price, 2) }}
            </td>

            <td>
                {{ $tier->is_active ? 'Active' : 'Inactive' }}
            </td>

            <td>
                <a href="{{ route('admin.product-price-tiers.edit', $tier->tier_id) }}">
                    Edit
                </a>

                <form
                    action="{{ route('admin.product-price-tiers.destroy', $tier->tier_id) }}"
                    method="POST"
                    style="display:inline;">
                    @csrf
                    @method('DELETE')

                    <button type="submit" onclick="return confirm('Delete this price tier?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" align="center">
                No price tiers found.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

<br>

{{ $tiers->links() }}