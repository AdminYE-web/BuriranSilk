<h1>Product Details</h1>

@if(session('success'))
    <div style="color: green; margin-bottom: 15px;">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('admin.product-details.create') }}">
    Add Product Detail
</a>

<br><br>

<table border="1" cellpadding="8" cellspacing="0">
    <thead>
        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Sample Image</th>
            <th>Status</th>
            <th>Manage</th>
        </tr>
    </thead>

    <tbody>
        @forelse($details as $detail)
            <tr>
                <td>{{ $detail->product_detail_id }}</td>

                <td>{{ $detail->product->product_name ?? '-' }}</td>

                <td>
                    @if($detail->sample_image)
                        <img 
                            src="{{ asset('storage/' . $detail->sample_image) }}" 
                            width="100"
                        >
                    @else
                        -
                    @endif
                </td>

                <td>{{ $detail->is_active ? 'Active' : 'Inactive' }}</td>

                <td>
                    <a href="{{ route('admin.product-details.edit', $detail->product_detail_id) }}">
                        Edit
                    </a>

                    <form 
                        action="{{ route('admin.product-details.destroy', $detail->product_detail_id) }}" 
                        method="POST" 
                        style="display:inline;"
                    >
                        @csrf
                        @method('DELETE')

                        <button type="submit" onclick="return confirm('Delete this detail?')">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" align="center">
                    No product details found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<br>

{{ $details->links() }}