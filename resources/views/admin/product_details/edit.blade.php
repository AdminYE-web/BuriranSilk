@extends('admin.layouts.app')

@section('title', 'Edit Product Detail | Indigo Admin')
@section('css')
    <style>
        .dynamic-list {
            display: grid;
            gap: 16px;
            margin-bottom: 16px;
        }

        .dynamic-item {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px;
            background: #fff;
        }

        .dynamic-item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .dynamic-item-header h3 {
            margin: 0;
            font-size: 16px;
            color: var(--fg-dark);
        }

        .btn-danger-light {
            border: 1px solid #fecaca;
            background: #fef2f2;
            color: #b91c1c;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-danger-light:hover {
            background: #fee2e2;
        }

        .image-box.small {
            width: 80px;
        }

        .image-box.small img {
            height: 60px;
        }

        <style>.form-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 24px;
        }

        .form-header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            color: var(--fg-dark);
        }

        .form-header p,
        .muted-text {
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

        .form-group label,
        .image-panel>label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .form-group input[type="text"],
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

        .form-group small {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        .section-title {
            margin: 28px 0 16px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .image-panel {
            margin-bottom: 18px;
        }

        .image-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .image-box {
            width: 120px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px;
            background: var(--bg);
        }

        .image-box img {
            width: 100%;
            height: 90px;
            object-fit: contain;
            display: block;
            border-radius: 8px;
            background: #fff;
            margin-bottom: 8px;
        }

        .radio-label {
            font-size: 12px;
            color: var(--fg);
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
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

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 28px;
        }

        .btn-primary {
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #fff;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
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
                <h1>Edit Product Detail</h1>
                <p>Manage detail content, specification content, accordion content and images.</p>
            </div>

            <a href="{{ route('admin.product-details.index') }}" class="btn-outline">
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

        <form action="{{ route('admin.product-details.update', $detail->product_detail_id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="section-title">Product Detail Setting</div>

            <div class="form-grid">
                <div class="form-group full">
                    <label>Product</label>
                    <select name="product_id">
                        <option value="">-- Select Product --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->product_id }}"
                                {{ old('product_id', $detail->product_id) == $product->product_id ? 'selected' : '' }}>
                                {{ $product->product_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Specification Image</label>

                    @if ($detail->specification_image)
                        <div class="image-box">
                            <img src="{{ asset('storage/' . $detail->specification_image) }}" alt="Specification Image">
                        </div>
                    @else
                        <p class="muted-text">No image</p>
                    @endif

                    <br>

                    <label>Change Specification Image</label>
                    <input type="file" name="specification_image" accept="image/*">
                    <small>recommend the size 440x440.</small>
                </div>

                <div class="form-group">
                    <label>Sample Image</label>

                    @if ($detail->sample_image)
                        <div class="image-box">
                            <img src="{{ asset('storage/' . $detail->sample_image) }}" alt="Sample Image">
                        </div>
                    @else
                        <p class="muted-text">No image</p>
                    @endif

                    <br>

                    <label>Change Sample Image</label>
                    <input type="file" name="sample_image" accept="image/*">
                    <small>recommend the size 1296x330.</small>
                </div>
            </div>

            <div class="section-title">Detail Content</div>

            @php
                $oldDetails = old(
                    'detail_content',
                    $detail->detail_content ?? [
                        [
                            'headline' => '',
                            'desc' => '',
                            'icon_image' => '',
                        ],
                    ],
                );

                if (empty($oldDetails)) {
                    $oldDetails = [
                        [
                            'headline' => '',
                            'desc' => '',
                            'icon_image' => '',
                        ],
                    ];
                }
            @endphp

            <div id="detail-content-wrapper" class="dynamic-list">
                @foreach ($oldDetails as $index => $item)
                    <div class="dynamic-item detail-item">
                        <div class="dynamic-item-header">
                            <h3>Detail Set <span class="detail-number">{{ $index + 1 }}</span></h3>

                            <button type="button" class="btn-danger-light remove-detail-item">
                                Remove
                            </button>
                        </div>

                        <div class="form-grid">
                            <div class="form-group full">
                                <label>Headline</label>
                                <input type="text" name="detail_content[{{ $index }}][headline]"
                                    value="{{ $item['headline'] ?? '' }}">
                            </div>

                            <div class="form-group full">
                                <label>Description</label>
                                <textarea name="detail_content[{{ $index }}][desc]" rows="4">{{ $item['desc'] ?? '' }}</textarea>
                            </div>

                            <div class="form-group product-detail-image-field">
                                <label>Icon Image</label>

                                @if (!empty($item['icon_image']))
                                    <div class="image-box small">
                                        <img src="{{ asset('storage/' . $item['icon_image']) }}" alt="Icon">
                                    </div>
                                @else
                                    <p class="muted-text">No icon image</p>
                                @endif

                                <input type="hidden" name="detail_content[{{ $index }}][icon_image]"
                                    value="{{ $item['icon_image'] ?? '' }}">

                                <br>

                                <label>Change Icon Image</label>
                                <input type="file" name="detail_icon_images[{{ $index }}]" accept="image/*">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-detail-item" class="btn-outline">
                + Add Detail Set
            </button>

            <div class="section-title">Specification Content</div>

            @php
                $oldSpecs = old(
                    'specification_content',
                    $detail->specification_content ?? [
                        [
                            'title' => '',
                            'desc' => '',
                            'icon_image' => '',
                        ],
                    ],
                );

                if (empty($oldSpecs)) {
                    $oldSpecs = [
                        [
                            'title' => '',
                            'desc' => '',
                            'icon_image' => '',
                        ],
                    ];
                }
            @endphp

            <div id="spec-content-wrapper" class="dynamic-list">
                @foreach ($oldSpecs as $index => $item)
                    <div class="dynamic-item spec-item">
                        <div class="dynamic-item-header">
                            <h3>Spec Set <span class="spec-number">{{ $index + 1 }}</span></h3>

                            <button type="button" class="btn-danger-light remove-spec-item">
                                Remove Spec
                            </button>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="specification_content[{{ $index }}][title]"
                                    value="{{ $item['title'] ?? '' }}" placeholder="เช่น Width Options:">
                            </div>

                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="specification_content[{{ $index }}][desc]" rows="3"
                                    placeholder="เช่น 10mm, 15mm, 20mm, and more">{{ $item['desc'] ?? '' }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Link Text</label>
                                <input type="text" name="specification_content[{{ $index }}][link_text]"
                                    value="{{ $item['link_text'] ?? '' }}" placeholder="เช่น View more colors">
                            </div>

                            <div class="form-group">
                                <label>Link URL</label>
                                <input type="text" name="specification_content[{{ $index }}][link_url]"
                                    value="{{ $item['link_url'] ?? '' }}"
                                    placeholder="เช่น /colors หรือ https://example.com">
                            </div>

                          <div class="form-group product-detail-image-field">
    <label>Spec Icon</label>

    @if (!empty($item['icon_image']))
        <div class="image-box small">
            <img src="{{ asset('storage/' . $item['icon_image']) }}" alt="Spec Icon">
        </div>
    @else
        <p class="muted-text">No icon image</p>
    @endif

    <input type="hidden"
        name="specification_content[{{ $index }}][icon_image]"
        value="{{ $item['icon_image'] ?? '' }}">

    <br>

    <label>Change Spec Icon</label>
    <input type="file"
        name="spec_icon_images[{{ $index }}]"
        accept="image/*">
</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-spec-item" class="btn-outline">
                + Add Spec Set
            </button>

            <div class="section-title">Accordion Content</div>

            @php
                $oldAccordions = old(
                    'accordion_content',
                    $detail->accordion_content ?? [
                        [
                            'title' => '',
                            'content' => '',
                        ],
                    ],
                );

                if (empty($oldAccordions)) {
                    $oldAccordions = [
                        [
                            'title' => '',
                            'content' => '',
                        ],
                    ];
                }
            @endphp

            <div id="accordion-content-wrapper" class="dynamic-list">
                @foreach ($oldAccordions as $index => $item)
                    <div class="dynamic-item accordion-item-admin">
                        <div class="dynamic-item-header">
                            <h3>Accordion Set <span class="accordion-number">{{ $index + 1 }}</span></h3>

                            <button type="button" class="btn-danger-light remove-accordion-item">
                                Remove Accordion
                            </button>
                        </div>

                        <div class="form-grid">
                            <div class="form-group full">
                                <label>Title</label>
                                <input type="text" name="accordion_content[{{ $index }}][title]"
                                    value="{{ $item['title'] ?? '' }}" placeholder="เช่น Production fee">
                            </div>

                            <div class="form-group full">
                                <label>Content</label>
                                <textarea name="accordion_content[{{ $index }}][content]" class="accordion-ckeditor" rows="8">{{ $item['content'] ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-accordion-item" class="btn-outline">
                + Add Accordion Set
            </button>

            <div class="section-title">Status</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $detail->is_active) ? 'checked' : '' }}>
                    Active
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-outline">
                    Cancel
                </a>

                <button type="submit" class="btn-primary">
                    Update
                </button>
            </div>
        </form>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <script>
        let detailIndex = document.querySelectorAll('.detail-item').length;
        let specIndex = document.querySelectorAll('.spec-item').length;
        let accordionIndex = document.querySelectorAll('.accordion-item-admin').length;
        const accordionEditors = {};

        document.getElementById('add-detail-item').addEventListener('click', function() {
            const wrapper = document.getElementById('detail-content-wrapper');

            const html = `
            <div class="dynamic-item detail-item">
                <div class="dynamic-item-header">
                    <h3>Detail Set <span class="detail-number">${detailIndex + 1}</span></h3>
                    <button type="button" class="btn-danger-light remove-detail-item">Remove</button>
                </div>

                <div class="form-grid">
                    <div class="form-group full">
                        <label>Headline</label>
                        <input type="text" name="detail_content[${detailIndex}][headline]" value="">
                    </div>

                    <div class="form-group full">
                        <label>Description</label>
                        <textarea name="detail_content[${detailIndex}][desc]" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Icon Image</label>
                        <input type="hidden" name="detail_content[${detailIndex}][icon_image]" value="">
                        <input type="file" name="detail_icon_images[${detailIndex}]" accept="image/*">
                    </div>
                </div>
            </div>
        `;

            wrapper.insertAdjacentHTML('beforeend', html);
            detailIndex++;
            updateDetailNumbers();
        });

        document.getElementById('add-spec-item').addEventListener('click', function() {
            const wrapper = document.getElementById('spec-content-wrapper');

            const html = `
            <div class="dynamic-item spec-item">
                <div class="dynamic-item-header">
                    <h3>Spec Set <span class="spec-number">${specIndex + 1}</span></h3>
                    <button type="button" class="btn-danger-light remove-spec-item">Remove Spec</button>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="specification_content[${specIndex}][title]" value="" placeholder="เช่น Width Options:">
                    </div>

                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="specification_content[${specIndex}][desc]" rows="3" placeholder="เช่น 10mm, 15mm, 20mm, and more"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Link Text</label>
                        <input type="text" name="specification_content[${specIndex}][link_text]" value="" placeholder="เช่น View more colors">
                    </div>

                    <div class="form-group">
                        <label>Link URL</label>
                        <input type="text" name="specification_content[${specIndex}][link_url]" value="" placeholder="เช่น /colors หรือ https://example.com">
                    </div>

                    <div class="form-group">
                        <label>Spec Icon</label>
                        <input type="hidden" name="specification_content[${specIndex}][icon_image]" value="">
                        <input type="file" name="spec_icon_images[${specIndex}]" accept="image/*">
                    </div>
                </div>
            </div>
        `;

            wrapper.insertAdjacentHTML('beforeend', html);
            specIndex++;
            updateSpecNumbers();
        });

        function initAccordionEditor(textarea) {
            if (!textarea || textarea.dataset.editorReady === '1') return;

            const editorKey = textarea.getAttribute('name');

            ClassicEditor
                .create(textarea)
                .then(function(editor) {
                    accordionEditors[editorKey] = editor;
                    textarea.dataset.editorReady = '1';
                })
                .catch(function(error) {
                    console.error(error);
                });
        }

        document.querySelectorAll('.accordion-ckeditor').forEach(initAccordionEditor);

        document.getElementById('add-accordion-item').addEventListener('click', function() {
            const wrapper = document.getElementById('accordion-content-wrapper');

            const html = `
            <div class="dynamic-item accordion-item-admin">
                <div class="dynamic-item-header">
                    <h3>Accordion Set <span class="accordion-number">${accordionIndex + 1}</span></h3>
                    <button type="button" class="btn-danger-light remove-accordion-item">Remove Accordion</button>
                </div>

                <div class="form-grid">
                    <div class="form-group full">
                        <label>Title</label>
                        <input type="text" name="accordion_content[${accordionIndex}][title]" value="" placeholder="เช่น Production fee">
                    </div>

                    <div class="form-group full">
                        <label>Content</label>
                        <textarea name="accordion_content[${accordionIndex}][content]" class="accordion-ckeditor" rows="8"></textarea>
                    </div>
                </div>
            </div>
        `;

            wrapper.insertAdjacentHTML('beforeend', html);

            const newTextarea = wrapper.querySelector(
                `textarea[name="accordion_content[${accordionIndex}][content]"]`
            );

            initAccordionEditor(newTextarea);

            accordionIndex++;
            updateAccordionNumbers();
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-detail-item')) {
                const items = document.querySelectorAll('.detail-item');

                if (items.length <= 1) {
                    alert('ต้องมี Detail อย่างน้อย 1 ชุด');
                    return;
                }

                e.target.closest('.detail-item').remove();
                updateDetailNumbers();
            }

            if (e.target.classList.contains('remove-spec-item')) {
                const items = document.querySelectorAll('.spec-item');

                if (items.length <= 1) {
                    alert('ต้องมี Spec อย่างน้อย 1 ชุด');
                    return;
                }

                e.target.closest('.spec-item').remove();
                updateSpecNumbers();
            }

            if (e.target.classList.contains('remove-accordion-item')) {
                const items = document.querySelectorAll('.accordion-item-admin');

                if (items.length <= 1) {
                    alert('ต้องมี Accordion อย่างน้อย 1 ชุด');
                    return;
                }

                const item = e.target.closest('.accordion-item-admin');
                const textarea = item.querySelector('.accordion-ckeditor');

                if (textarea) {
                    const editorKey = textarea.getAttribute('name');

                    if (accordionEditors[editorKey]) {
                        accordionEditors[editorKey].destroy();
                        delete accordionEditors[editorKey];
                    }
                }

                item.remove();
                updateAccordionNumbers();
            }
        });

        function updateDetailNumbers() {
            document.querySelectorAll('.detail-item').forEach(function(item, index) {
                item.querySelector('.detail-number').innerText = index + 1;
            });
        }

        function updateSpecNumbers() {
            document.querySelectorAll('.spec-item').forEach(function(item, index) {
                item.querySelector('.spec-number').innerText = index + 1;
            });
        }

        function updateAccordionNumbers() {
            document.querySelectorAll('.accordion-item-admin').forEach(function(item, index) {
                item.querySelector('.accordion-number').innerText = index + 1;
            });
        }
    </script>

@endsection
