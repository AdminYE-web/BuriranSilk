@extends('admin.layouts.app')

@section('title', 'Add Material Home | Indigo Admin')

@section('css')
    <style>
        .form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
        }

        .form-header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            color: var(--fg-dark);
        }

        .form-header p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"],
        .form-group select,
        .form-group textarea {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
        }

        .form-group textarea {
            resize: vertical;
        }

        .section-title {
            margin: 28px 0 16px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .checkbox-grid label {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
            font-size: 14px;
            color: var(--fg);
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn-outline,
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            font-family: inherit;
            border: 1px solid transparent;
        }

        .btn-outline {
            background: #fff;
            border-color: var(--border);
            color: var(--fg);
        }

        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .alert-error {
            margin-bottom: 18px;
            padding: 12px 16px;
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-error ul {
            margin: 0;
            padding-left: 20px;
        }

        @media (max-width: 900px) {

            .form-grid,
            .checkbox-grid {
                grid-template-columns: 1fr;
            }

            .form-header {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>Add Material Home</h1>
                <p>Create homepage material section, image, description, sorting and status.</p>
            </div>

            <a href="{{ route('admin.material-homes.index') }}" class="btn-outline">
                Back
            </a>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.material-homes.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="section-title">Material Home Information</div>

            <div class="form-grid">
                <div class="form-group">
    <label>Product Type</label>
    <select name="product_type" id="product_type">
        <option value="1" {{ old('product_type', 1) == 1 ? 'selected' : '' }}>
            Hotstrap
        </option>
        <option value="2" {{ old('product_type') == 2 ? 'selected' : '' }}>
            Hotmobily
        </option>
    </select>
</div>

<div class="form-group">
    <label>Material</label>
    <select name="material_id" id="material_id">
        <option value="">-- Select Material --</option>

        @foreach($materials as $material)
            <option
                value="{{ $material->material_id }}"
                data-product-type="{{ $material->product_type }}"
                {{ old('material_id') == $material->material_id ? 'selected' : '' }}
            >
                {{ $material->material_name }}
            </option>
        @endforeach
    </select>
</div>
                <div class="form-group" style="display: none">
                    <label>Translation Key</label>
                    <input type="text" name="translation_key" value="{{ old('translation_key', $translationKey ?? '') }}"
                        placeholder="เช่น mh_xxxxxxxx">
                    <small>ใช้สำหรับผูก Material Home เดียวกันข้ามภาษา</small>
                </div>

                <div class="form-group">
                    <label>Title</label>
                    <input type="text" name="title" value="{{ old('title') }}">
                </div>

                <div class="form-group full">
                    <label>Description</label>
                    <textarea name="description" rows="5">{{ old('description') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                </div>

                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" accept="image/*">
                    <small>Recommended size:  316x224.</small>
                </div>
            </div>

            <div class="section-title">Status</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.material-homes.index') }}" class="btn-outline">
                    Cancel
                </a>

                <button type="submit" class="btn-primary">
                    Save Material Home
                </button>
            </div>
        </form>
    </div>
   <script>
    document.addEventListener('DOMContentLoaded', function () {
        const productTypeSelect = document.getElementById('product_type');
        const materialSelect = document.getElementById('material_id');

        function filterMaterials() {
            const selectedType = productTypeSelect.value;
            const currentMaterial = materialSelect.value;

            let hasSelectedVisible = false;

            Array.from(materialSelect.options).forEach(option => {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                const isSameType = option.dataset.productType === selectedType;
                option.hidden = !isSameType;

                if (option.value === currentMaterial && isSameType) {
                    hasSelectedVisible = true;
                }
            });

            if (!hasSelectedVisible) {
                materialSelect.value = '';
            }
        }

        productTypeSelect.addEventListener('change', filterMaterials);

        filterMaterials();
    });
</script>
@endsection
