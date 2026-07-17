@extends('admin.layouts.app')

@section('title', 'Manage Product Options | Indigo Admin')
@section('css')
    <style>
        .option-group-list {
            display: grid;
            gap: 18px;
        }

        .option-group-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        .option-group-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--border);
            background: var(--bg);
        }

        .option-group-header h3 {
            margin: 0;
            font-size: 17px;
            color: var(--fg-dark);
        }

        .option-group-header span {
            color: var(--muted);
            font-size: 13px;
        }

        .option-list {
            padding: 8px 18px;
        }

        .option-row {
            padding: 14px 0;
            border-bottom: 1px solid var(--border);
        }

        .option-row:last-child {
            border-bottom: none;
        }

        .option-main-check {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .option-main-check small {
            margin-left: 6px;
            color: var(--muted);
            font-weight: 500;
        }

        .option-setting {
            margin-top: 12px;
            margin-left: 28px;
            padding: 12px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 10px;
        }

        .option-setting-grid {
            display: grid;
            grid-template-columns: 180px 120px 120px;
            align-items: end;
            gap: 14px;
        }

        .option-setting-grid label {
            font-size: 13px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .option-setting-grid input[type="number"] {
            width: 100%;
            margin-top: 6px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 14px;
        }

        .mini-check {
            display: flex;
            align-items: center;
            gap: 8px;
            padding-bottom: 8px;
        }

        @media (max-width: 700px) {
            .option-setting-grid {
                grid-template-columns: 1fr;
            }

            .option-setting {
                margin-left: 0;
            }
        }

        .form-actions {
            position: sticky;
            bottom: 0;
            z-index: 100;

            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;

            margin: 28px -24px -24px;
            padding: 14px 24px;

            background: rgba(255, 255, 255, 0.96);
            /* backdrop-filter: blur(8px);
                                    border-top: 1px solid var(--border);
                                    box-shadow: 0 -8px 20px rgba(15, 23, 42, 0.08); */
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
            line-height: 1;
        }

        .btn-outline {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--fg);
        }

        .btn-outline:hover {
            background: var(--bg);
        }

        .btn-primary {
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #fff;
        }

        .btn-primary:hover {
            background: var(--accent-hover);
        }

        .option-group-title-wrap {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .group-drag-handle {
            width: 28px;
            height: 28px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: #64748b;
            font-size: 16px;
            cursor: grab;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            flex-shrink: 0;
        }

        .group-drag-handle:active {
            cursor: grabbing;
        }

        .option-group-card.sortable-ghost {
            opacity: .55;
            background: #eef4ff;
        }

        .option-group-card.sortable-chosen {
            background: #f8fafc;
        }

        .option-group-list.is-saving {
            opacity: .65;
            pointer-events: none;
        }

        .option-row-top {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .option-drag-handle {
            width: 28px;
            height: 28px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            color: #64748b;
            font-size: 16px;
            cursor: grab;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            user-select: none;
            flex-shrink: 0;
        }

        .option-drag-handle:active {
            cursor: grabbing;
        }

        .option-row.sortable-ghost {
            opacity: .55;
            background: #eef4ff;
        }

        .option-row.sortable-chosen {
            background: #f8fafc;
        }

        .option-setting-grid {
            grid-template-columns: 120px 120px;
        }

        .option-setting-grid-qty {
            grid-template-columns: 120px 120px 180px 120px 120px 120px;
        }

        .option-setting-grid select {
            width: 100%;
            margin-top: 6px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 10px;
            font-size: 14px;
            background: #fff;
        }

        @media (max-width: 900px) {
            .option-setting-grid-qty {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 600px) {
            .option-setting-grid-qty {
                grid-template-columns: 1fr;
            }
        }

        .options-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 180px;
            gap: 20px;
            align-items: start;
        }

        .option-scrollspy {
            position: sticky;
            top: 90px;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            overflow-x: hidden;

            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px;
        }

        .option-scrollspy a {
            display: block;
            padding: 10px 12px;
            border-radius: 8px;
            color: var(--fg);
            text-decoration: none;
            font-size: 14px;
        }

        .option-scrollspy a.active {
            background: var(--accent);
            color: #fff;
        }

        @media (max-width: 900px) {
            .options-layout {
                grid-template-columns: 1fr;
            }

            .option-scrollspy {
                display: none;
            }
        }

        .option-scrollspy {
            position: sticky;
            top: 90px;
            max-height: calc(100vh - 110px);
            overflow-y: hidden;
            overflow-x: hidden;
        }

        .option-scrollspy:hover {
            overflow-y: auto;
        }

        .option-scrollspy::-webkit-scrollbar {
            width: 6px;
        }

        .option-scrollspy::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 999px;
        }

        .option-scrollspy a {
            display: block;
            padding: 12px 12px;
            border-radius: 8px;
            color: var(--fg);
            text-decoration: none;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
        }

        .option-scrollspy a:last-child {
            border-bottom: none;
        }

        .option-scrollspy a.active {
            background: var(--accent);
            color: #fff;
            border-bottom-color: transparent;
        }

        .option-group-header {
            cursor: pointer;
        }

        .option-group-toggle {
            width: 30px;
            height: 30px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: #fff;
            cursor: pointer;
            font-size: 16px;
        }

        .option-group-card.is-collapsed .option-list {
            display: none;
        }

        .mar-4 {
            margin: 20px 0px;
        }

        .option-group-card.is-collapsed .option-list {
            display: none;
        }

        .option-group-card {
            cursor: pointer;
        }

        .option-list {
            cursor: default;
        }



        .option-scrollspy {
            position: sticky;
            top: 90px;
            max-height: calc(100vh - 110px);
            overflow-y: auto;
            overflow-x: hidden;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px;
        }

        .option-scrollspy-search {
            width: 100%;
            margin-bottom: 8px;
            padding: 9px 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 13px;
            outline: none;
            background: #fff;

            position: sticky;
            top: 0;
            z-index: 2;
        }

        .option-scrollspy-search:focus {
            border-color: var(--accent);
        }
    </style>
@endsection

@section('content')

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ request()->cookie('dev') == '1' ? 'Manage Options' : 'オプション設定' }}</h1>
                <p>{{ $product->product_name }}</p>
            </div>

            <a href="{{ route('admin.products.index') }}" class="btn-outline mar-4">
                {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
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

        <form id="product-options-form" action="{{ route('admin.products.options.update', $product->product_id) }}"
            method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="options_json" id="options_json">
            <div class="options-layout">
                <div class="option-group-list">
                    @foreach ($groups as $group)
                        <div class="option-group-card is-collapsed" id="option-group-{{ $group->option_group_id }}"
                            data-group-id="{{ $group->option_group_id }}">
                            <div class="option-group-header">
                                <div class="option-group-title-wrap">
                                    <span class="group-drag-handle" title="Drag group">☰</span>

                                    <div>
                                        <h3>{{ $group->group_name }} ({{ $group->group_code }})</h3>
                                        <span>{{ $group->options->count() }} options</span>
                                    </div>
                                </div>

                                <div class="option-group-actions" style="display: flex; gap: 8px;">
                                    <button type="button" class="option-group-toggle">+</button>

                                    <button type="button" class="btn-outline btn-move-after-active"
                                        style="min-height: 28px; padding: 4px 10px; font-size: 12px; border-radius: 6px;">
                                        {{ request()->cookie('dev') == '1' ? 'move to top' : 'トップへ移動' }}
                                    </button>
                                    <button type="button" class="btn-outline btn-select-all"
                                        style="min-height: 28px; padding: 4px 10px; font-size: 12px; border-radius: 6px;">
                                        {{ request()->cookie('dev') == '1' ? 'select all' : 'すべて選択' }}
                                    </button>
                                    <button type="button" class="btn-outline btn-deselect-all"
                                        style="min-height: 28px; padding: 4px 10px; font-size: 12px; border-radius: 6px;">
                                        {{ request()->cookie('dev') == '1' ? 'deselect all' : '選択解除' }}
                                    </button>
                                </div>
                            </div>

                            <div class="option-list option-sortable-list">
                                @forelse ($group->options as $index => $option)
                                    @php
                                        $isChecked = in_array((int) $option->option_id, $assignedOptionIds);
                                        $pivot = $assignedPivot[$option->option_id]->pivot ?? null;
                                        $isExtra = $index >= 4 && !$isChecked;
                                    @endphp

                                    <div class="option-row {{ $isExtra ? 'option-row-extra' : '' }}"
                                        data-option-id="{{ $option->option_id }}"
                                        style="{{ $isExtra ? 'display: none;' : '' }}">
                                        <div class="option-row-top">
                                            <span class="option-drag-handle" title="Drag option">☰</span>

                                            <label class="option-main-check">

                                                <input type="checkbox" class="option-checkbox"
                                                    data-option-id="{{ $option->option_id }}"
                                                    {{ $isChecked ? 'checked' : '' }}>

                                                <span>
                                                    {{ $option->option_name }}

                                                    @if ($option->additional_price > 0)
                                                        <small>
                                                            +{{ number_format($option->additional_price, 2) }}
                                                        </small>
                                                    @endif
                                                </span>
                                            </label>


                                        </div>

                                        <div class="option-setting option-setting-{{ $option->option_id }}"
                                            style="{{ $isChecked ? '' : 'display:none;' }}">
                                            <input type="hidden" name="options[{{ $option->option_id }}][option_id]"
                                                value="{{ $option->option_id }}" {{ $isChecked ? '' : 'disabled' }}>

                                            <div class="option-setting-grid option-setting-grid-qty">
                                                <input type="hidden" class="option-sort-input"
                                                    name="options[{{ $option->option_id }}][sort_order]"
                                                    value="{{ $pivot->sort_order ?? $loop->iteration }}"
                                                    {{ $isChecked ? '' : 'disabled' }}>

                                                <label class="mini-check">
                                                    <input type="checkbox"
                                                        name="options[{{ $option->option_id }}][is_default]" value="1"
                                                        {{ $pivot && $pivot->is_default ? 'checked' : '' }}
                                                        {{ $isChecked ? '' : 'disabled' }}>
                                                    {{ request()->cookie('dev') == '1' ? 'Default' : 'デフォルト' }}
                                                </label>

                                                <label class="mini-check">
                                                    <input type="checkbox"
                                                        name="options[{{ $option->option_id }}][is_active]" value="1"
                                                        {{ !$pivot || $pivot->is_active ? 'checked' : '' }}
                                                        {{ $isChecked ? '' : 'disabled' }}>
                                                    {{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}
                                                </label>

                                                <label>
                                                    {{ request()->cookie('dev') == '1' ? 'Quantity Rule' : '数量ルール' }}
                                                    <select name="options[{{ $option->option_id }}][qty_rule_type]"
                                                        class="qty-rule-select" {{ $isChecked ? '' : 'disabled' }}>
                                                        <option value=""
                                                            {{ empty($pivot?->qty_rule_type) ? 'selected' : '' }}>
                                                            {{ request()->cookie('dev') == '1' ? 'No limit' : '制限なし' }}
                                                        </option>
                                                        <option value="min"
                                                            {{ ($pivot?->qty_rule_type ?? '') === 'min' ? 'selected' : '' }}>
                                                            {{ request()->cookie('dev') == '1' ? 'Minimum only' : '最小のみ' }}
                                                        </option>
                                                        <option value="max"
                                                            {{ ($pivot?->qty_rule_type ?? '') === 'max' ? 'selected' : '' }}>
                                                            {{ request()->cookie('dev') == '1' ? 'Maximum only' : '最大のみ' }}
                                                        </option>
                                                        <option value="exact"
                                                            {{ ($pivot?->qty_rule_type ?? '') === 'exact' ? 'selected' : '' }}>
                                                            {{ request()->cookie('dev') == '1' ? 'Exact quantity only' : '数量指定のみ' }}
                                                        </option>
                                                        <option value="range"
                                                            {{ ($pivot?->qty_rule_type ?? '') === 'range' ? 'selected' : '' }}>
                                                            {{ request()->cookie('dev') == '1' ? 'Min - Max range' : '最小-最大範囲' }}
                                                        </option>
                                                    </select>
                                                </label>

                                                <label>
                                                    {{ request()->cookie('dev') == '1' ? 'Min Qty' : '最小数量' }}
                                                    <input type="number" name="options[{{ $option->option_id }}][min_qty]"
                                                        value="{{ $pivot->min_qty ?? '' }}" min="1"
                                                        {{ $isChecked ? '' : 'disabled' }}>
                                                </label>

                                                <label>
                                                    {{ request()->cookie('dev') == '1' ? 'Max Qty' : '最大数量' }}
                                                    <input type="number" name="options[{{ $option->option_id }}][max_qty]"
                                                        value="{{ $pivot->max_qty ?? '' }}" min="1"
                                                        {{ $isChecked ? '' : 'disabled' }}>
                                                </label>

                                                <label>
                                                    {{ request()->cookie('dev') == '1' ? 'Exact Qty' : '数量指定' }}
                                                    <input type="number"
                                                        name="options[{{ $option->option_id }}][exact_qty]"
                                                        value="{{ $pivot->exact_qty ?? '' }}" min="1"
                                                        {{ $isChecked ? '' : 'disabled' }}>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="muted-text">No options in this group.</p>
                                @endforelse

                                @php
                                    $extraCount = 0;
                                    foreach ($group->options as $index => $opt) {
                                        $isOptChecked = in_array((int) $opt->option_id, $assignedOptionIds);
                                        if ($index >= 4 && !$isOptChecked) {
                                            $extraCount++;
                                        }
                                    }
                                @endphp

                                @if ($extraCount > 0)
                                    <div class="show-more-wrapper"
                                        style="padding: 12px 0; text-align: center; border-top: 1px dashed var(--border);">
                                        <button type="button" class="btn-outline btn-toggle-more" data-expanded="false"
                                            data-total-count="{{ $group->options->count() }}"
                                            style="min-height: 32px; padding: 6px 14px; font-size: 13px; border-radius: 6px;">
                                            {{ __('messages.admin.show_all') }} ({{ $group->options->count() }})
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <nav class="option-scrollspy">
                    <input type="text" id="optionGroupSearch" class="option-scrollspy-search"
                        placeholder="Search option group...">

                    @foreach ($groups as $group)
                        <a href="#option-group-{{ $group->option_group_id }}"
                            data-search-text="{{ strtolower($group->group_name . ' ' . $group->group_code) }}">
                            {{ $group->group_name }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.products.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? 'Save Options' : 'オプションを保存' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.querySelectorAll('.option-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const optionId = this.dataset.optionId;
                const setting = document.querySelector('.option-setting-' + optionId);

                if (!setting) return;

                const inputs = setting.querySelectorAll('input, select, textarea');

                if (this.checked) {
                    setting.style.display = 'block';

                    inputs.forEach(function(input) {
                        input.disabled = false;
                    });
                } else {
                    setting.style.display = 'none';

                    inputs.forEach(function(input) {
                        input.disabled = true;
                    });
                }
            });
        });

        // Select All / Deselect All handlers
        document.querySelectorAll('.btn-select-all').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const card = this.closest('.option-group-card');
                const checkboxes = card.querySelectorAll('.option-checkbox');
                checkboxes.forEach(function(checkbox) {
                    if (!checkbox.checked) {
                        checkbox.checked = true;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });

                // Automatically expand the group to show all options
                const toggleMoreBtn = card.querySelector('.btn-toggle-more');
                if (toggleMoreBtn && toggleMoreBtn.getAttribute('data-expanded') !== 'true') {
                    toggleMoreBtn.click();
                }
            });
        });

        document.querySelectorAll('.btn-deselect-all').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const card = this.closest('.option-group-card');
                const checkboxes = card.querySelectorAll('.option-checkbox');
                checkboxes.forEach(function(checkbox) {
                    if (checkbox.checked) {
                        checkbox.checked = false;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            });
        });

        // Toggle More/Less handlers
        document.querySelectorAll('.btn-toggle-more').forEach(function(btn) {
            const totalCount = btn.getAttribute('data-total-count');
            btn.addEventListener('click', function() {
                const card = this.closest('.option-group-card');
                const extraRows = card.querySelectorAll('.option-row-extra');
                const isExpanded = this.getAttribute('data-expanded') === 'true';

                if (isExpanded) {
                    extraRows.forEach(function(row) {
                        row.style.display = 'none';
                    });
                    this.setAttribute('data-expanded', 'false');
                    this.textContent = "{{ __('messages.admin.show_all') }} (" + totalCount + ")";
                } else {
                    extraRows.forEach(function(row) {
                        row.style.display = 'block';
                    });
                    this.setAttribute('data-expanded', 'true');
                    this.textContent = "{{ __('messages.admin.show_less') }}";
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const groupList = document.querySelector('.option-group-list');

            if (groupList && typeof Sortable !== 'undefined') {
                new Sortable(groupList, {
                    animation: 160,
                    handle: '.group-drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',

                    // ทำให้ลากใกล้ขอบจอแล้ว scroll ตาม
                    scroll: true,
                    forceAutoScrollFallback: true,
                    scrollSensitivity: 120,
                    scrollSpeed: 18,
                    bubbleScroll: true,

                    onEnd: function() {
                        updateProductGroupSortOrder();
                        syncScrollspyOrder();
                    }
                });
            }

            /**
             * เช็คว่า group นี้มี option ที่เปิดใช้งานอยู่ไหม
             */
            function isGroupActive(card) {
                const checkedOptions = card.querySelectorAll('.option-checkbox:checked');

                return Array.from(checkedOptions).some(function(checkbox) {
                    const optionId = checkbox.dataset.optionId;
                    const setting = card.querySelector('.option-setting-' + optionId);

                    if (!setting) return false;

                    const activeCheckbox = setting.querySelector('[name$="[is_active]"]');

                    // ถ้าไม่มี checkbox Active ให้ถือว่า option ที่ checked คือ active
                    return !activeCheckbox || activeCheckbox.checked;
                });
            }

            /**
             * ย้าย group ที่กด ไปต่อจาก group ล่าสุดที่มี option active
             */
            function moveGroupAfterLastActive(card) {
                if (!groupList || !card) return;

                const cards = Array.from(
                    groupList.querySelectorAll('.option-group-card[data-group-id]')
                );

                const lastActiveCard = cards
                    .filter(function(item) {
                        return item !== card && isGroupActive(item);
                    })
                    .pop();

                if (lastActiveCard) {
                    lastActiveCard.insertAdjacentElement('afterend', card);
                } else {
                    groupList.prepend(card);
                }

                syncScrollspyOrder();
                updateProductGroupSortOrder();

                card.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            /**
             * จัดลำดับ sidebar ขวาให้ตรงกับ group ที่ถูกย้าย
             */
            function syncScrollspyOrder() {
                const sidebar = document.querySelector('.option-scrollspy');

                if (!sidebar) return;

                document.querySelectorAll('.option-group-card[data-group-id]').forEach(function(card) {
                    const link = sidebar.querySelector('a[href="#' + card.id + '"]');

                    if (link) {
                        sidebar.appendChild(link);
                    }
                });
            }

            /**
             * ปุ่มย้าย group ไปต่อจาก active group ล่าสุด
             */
            document.querySelectorAll('.btn-move-after-active').forEach(function(button) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    const card = this.closest('.option-group-card');

                    moveGroupAfterLastActive(card);
                });
            });

            function updateProductGroupSortOrder() {
                if (!groupList) return;

                const cards = document.querySelectorAll('.option-group-card[data-group-id]');

                const items = Array.from(cards).map(function(card, index) {
                    return {
                        option_group_id: card.dataset.groupId,
                        sort_order: index + 1
                    };
                });

                groupList.classList.add('is-saving');

                fetch('{{ route('admin.products.option-groups.updateSort', $product->product_id) }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            items: items
                        })
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            throw new Error('Unable to update group order.');
                        }

                        return response.json();
                    })
                    .catch(function(error) {
                        console.error(error);
                        alert('Unable to update group order.');
                    })
                    .finally(function() {
                        groupList.classList.remove('is-saving');
                    });
            }
        });
        document.querySelectorAll('.option-sortable-list').forEach(function(optionList) {
            new Sortable(optionList, {
                animation: 160,
                handle: '.option-drag-handle',
                ghostClass: 'sortable-ghost',
                chosenClass: 'sortable-chosen',

                scroll: true,
                forceAutoScrollFallback: true,
                scrollSensitivity: 100,
                scrollSpeed: 16,
                bubbleScroll: true,

                onEnd: function() {
                    refreshOptionSortInputs(optionList);
                }
            });
        });

        function refreshOptionSortInputs(optionList) {
            const rows = optionList.querySelectorAll('.option-row[data-option-id]');

            rows.forEach(function(row, index) {
                const sortInput = row.querySelector('.option-sort-input');

                if (sortInput) {
                    sortInput.value = index + 1;
                }
            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sections = document.querySelectorAll('.option-group-card[id]');
            const navLinks = document.querySelectorAll('.option-scrollspy a');
            const sidebar = document.querySelector('.option-scrollspy');

            function setActiveLink() {
                let currentId = '';

                sections.forEach(function(section) {
                    const rect = section.getBoundingClientRect();

                    if (rect.top <= 140) {
                        currentId = section.id;
                    }
                });

                navLinks.forEach(function(link) {
                    link.classList.remove('active');

                    if (link.getAttribute('href') === '#' + currentId) {
                        link.classList.add('active');

                        if (sidebar) {
                            const targetScroll =
                                link.offsetTop - (sidebar.clientHeight / 2) + (link.offsetHeight / 2);

                            sidebar.scrollTo({
                                top: targetScroll,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            }

            navLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();

                    const target = document.querySelector(this.getAttribute('href'));
                    if (!target) return;

                    const headerOffset = 100;
                    const targetPosition =
                        target.getBoundingClientRect().top + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                });
            });

            window.addEventListener('scroll', setActiveLink);
            setActiveLink();
        });

        document.querySelectorAll('.option-group-toggle').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.stopPropagation();

                const card = this.closest('.option-group-card');
                card.classList.toggle('is-collapsed');

                this.textContent = card.classList.contains('is-collapsed') ? '+' : '−';
            });
        });

        document.querySelectorAll('.option-group-card').forEach(function(card) {
            card.addEventListener('click', function(e) {
                if (
                    e.target.closest('input, select, textarea, button, a, label') ||
                    e.target.closest('.group-drag-handle, .option-drag-handle')
                ) {
                    return;
                }

                card.classList.toggle('is-collapsed');

                const toggleBtn = card.querySelector('.option-group-toggle');
                if (toggleBtn) {
                    toggleBtn.textContent = card.classList.contains('is-collapsed') ? '+' : '−';
                }
            });
        });
    </script>
    <script>
        document.getElementById('product-options-form').addEventListener('submit', function() {
            const options = [];

            document.querySelectorAll('.option-checkbox:checked').forEach(function(checkbox) {
                const optionId = checkbox.dataset.optionId;
                const setting = document.querySelector('.option-setting-' + optionId);

                if (!setting) return;

                options.push({
                    option_id: parseInt(optionId),
                    sort_order: parseInt(setting.querySelector('[name$="[sort_order]"]')?.value ||
                        0),
                    is_default: setting.querySelector('[name$="[is_default]"]')?.checked ? 1 : 0,
                    is_active: setting.querySelector('[name$="[is_active]"]')?.checked ? 1 : 0,
                    qty_rule_type: setting.querySelector('[name$="[qty_rule_type]"]')?.value ||
                        null,
                    min_qty: setting.querySelector('[name$="[min_qty]"]')?.value || null,
                    max_qty: setting.querySelector('[name$="[max_qty]"]')?.value || null,
                    exact_qty: setting.querySelector('[name$="[exact_qty]"]')?.value || null,
                });
            });

            document.getElementById('options_json').value = JSON.stringify(options);

            // กันไม่ให้ input options[...] เดิมถูกส่งไปด้วย
            document.querySelectorAll('[name^="options["]').forEach(function(input) {
                input.disabled = true;
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('optionGroupSearch');
            const navLinks = document.querySelectorAll('.option-scrollspy a');

            if (!searchInput) return;

            searchInput.addEventListener('input', function() {
                const keyword = this.value.toLowerCase().trim();

                navLinks.forEach(function(link) {
                    const text = link.dataset.searchText || link.textContent.toLowerCase();

                    if (text.includes(keyword)) {
                        link.style.display = 'block';
                    } else {
                        link.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
