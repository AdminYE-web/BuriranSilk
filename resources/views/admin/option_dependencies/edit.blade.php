@extends('admin.layouts.app')

@section('title', 'Edit Option Dependency | Indigo Admin')

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container {
            width: 100% !important;
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 8px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--fg);
            line-height: 42px;
            padding-left: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }

        .form-card {
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

        .form-group input[type="number"],
        .form-group select {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
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
            align-items: center;
            gap: 12px;
            margin-top: 28px;
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
            line-height: 1;
        }

        .btn-outline {
            background: #fff;
            border: 1px solid var(--border);
            color: var(--fg);
        }

        .btn-primary {
            background: var(--accent);
            border: 1px solid var(--accent);
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
                <h1>
                    {{ request()->cookie('dev') === '1' ? 'Edit Option Dependency' : 'オプション依存関係を編集' }}
                </h1>

                <p>
                    {{ request()->cookie('dev') === '1'
                        ? 'Update conditional display rule between options and groups.'
                        : 'オプションとグループ間の条件付き表示ルールを更新します。' }}
                </p>
            </div>

            <a href="{{ route('admin.option-dependencies.index') }}" class="btn-outline">
                {{ request()->cookie('dev') === '1' ? 'Back' : '戻る' }}
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

        <form action="{{ route('admin.option-dependencies.update', $dependency->dependency_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="section-title">{{ request()->cookie('dev') === '1' ? 'Dependency Setting' : '依存関係設定' }}
            </div>

            <div class="form-grid">
                <div class="form-group full">
                    <label>{{ request()->cookie('dev') === '1' ? 'Trigger Option' : 'トリガーオプション' }}</label>
                    <select name="parent_option_id" id="parent_option_id" class="searchable-select" required>
                        <option value="">
                            {{ request()->cookie('dev') === '1' ? '-- Select Trigger Option --' : '-- トリガーオプションを選択 --' }}
                        </option>
                        <optgroup label="Hotstrap (Type 1)">
                            @foreach ($options->filter(fn($o) => optional($o->group)->product_type == 1) as $option)
                                <option value="{{ $option->option_id }}"
                                    {{ old('parent_option_id', $dependency->parent_option_id) == $option->option_id ? 'selected' : '' }}>
                                    {{ $option->group->group_name ?? '-' }} ({{ $option->group->group_code ?? '-' }}) /
                                    {{ $option->option_name }}
                                    @if ($option->option_code)
                                        ({{ $option->option_code }})
                                    @endif
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Hotmobily (Type 2)">
                            @foreach ($options->filter(fn($o) => optional($o->group)->product_type == 2) as $option)
                                <option value="{{ $option->option_id }}"
                                    {{ old('parent_option_id', $dependency->parent_option_id) == $option->option_id ? 'selected' : '' }}>
                                    {{ $option->group->group_name ?? '-' }} ({{ $option->group->group_code ?? '-' }}) /
                                    {{ $option->option_name }}
                                    @if ($option->option_code)
                                        ({{ $option->option_code }})
                                    @endif
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Target Type' : 'ターゲットタイプ' }}</label>
                    <select name="target_type" id="target_type" required>
                        <option value="option"
                            {{ old('target_type', $dependency->target_type) == 'option' ? 'selected' : '' }}>
                            {{ request()->cookie('dev') === '1' ? 'Option - Show only option' : 'オプション - オプションのみ表示' }}
                        </option>

                        <option value="group"
                            {{ old('target_type', $dependency->target_type) == 'group' ? 'selected' : '' }}>
                            {{ request()->cookie('dev') === '1' ? 'group - Show Entire Group' : 'group - グループ全体を表示' }}
                        </option>
                    </select>
                </div>

                <div class="form-group" style="display: none">
                    <label>{{ request()->cookie('dev') === '1' ? 'Sort Order' : '表示順序' }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', $dependency->sort_order) }}">
                </div>

                <div class="form-group full" id="target_option_box">
                    <label>{{ request()->cookie('dev') === '1' ? 'Target Option' : 'ターゲットオプション' }}</label>
                    <select name="target_option_id" id="target_option_id" class="searchable-select">
                        <option value="">
                            {{ request()->cookie('dev') === '1' ? '-- Select Target Option --' : '-- ターゲットオプションを選択 --' }}
                        </option>
                        <optgroup label="Hotstrap (Type 1)">
                            @foreach ($options->filter(fn($o) => optional($o->group)->product_type == 1) as $option)
                                <option value="{{ $option->option_id }}"
                                    {{ old('target_option_id', $dependency->target_option_id) == $option->option_id ? 'selected' : '' }}>
                                    {{ $option->group->group_name ?? '-' }} ({{ $option->group->group_code ?? '-' }}) /
                                    {{ $option->option_name }}
                                    @if ($option->option_code)
                                        ({{ $option->option_code }})
                                    @endif
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Hotmobily (Type 2)">
                            @foreach ($options->filter(fn($o) => optional($o->group)->product_type == 2) as $option)
                                <option value="{{ $option->option_id }}"
                                    {{ old('target_option_id', $dependency->target_option_id) == $option->option_id ? 'selected' : '' }}>
                                    {{ $option->group->group_name ?? '-' }} ({{ $option->group->group_code ?? '-' }}) /
                                    {{ $option->option_name }}
                                    @if ($option->option_code)
                                        ({{ $option->option_code }})
                                    @endif
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Action Type' : 'アクションタイプ' }}</label>
                    <select name="action_type" required>
                        <option value="show"
                            {{ old('action_type', $dependency->action_type ?? 'show') == 'show' ? 'selected' : '' }}>
                            {{ request()->cookie('dev') === '1'
                                ? 'show - Display target when the Trigger Option is selected'
                                : 'show - トリガーオプションを選択したときに対象を表示' }}
                        </option>

                        <option value="hide"
                            {{ old('action_type', $dependency->action_type ?? '') == 'hide' ? 'selected' : '' }}>
                            {{ request()->cookie('dev') === '1'
                                ? 'hide - Hide target when the Trigger Option is selected'
                                : 'hide - トリガーオプションを選択したときにターゲットを非表示' }}
                        </option>

                        <option value="disable"
                            {{ old('action_type', $dependency->action_type ?? '') == 'disable' ? 'selected' : '' }}>
                            {{ request()->cookie('dev') === '1'
                                ? 'disable - Disable target selection when the Trigger Option is selected'
                                : 'disable - トリガーオプションを選択したときにターゲットの選択を無効化' }}
                        </option>
                    </select>
                </div>

                <div class="form-group full" id="target_group_box" style="display:none;">
                    <label>{{ request()->cookie('dev') === '1' ? 'Target Group' : '対象グループ' }}</label>
                    <select name="target_group_id" id="target_group_id" class="searchable-select">
                        <option value="">
                            {{ request()->cookie('dev') === '1' ? '-- Select Target Group --' : '-- 対象グループを選択 --' }}
                        </option>
                        <optgroup label="Hotstrap (Type 1)">
                            @foreach ($groups->where('product_type', 1) as $group)
                                <option value="{{ $group->option_group_id }}"
                                    {{ old('target_group_id', $dependency->target_group_id) == $group->option_group_id ? 'selected' : '' }}>
                                    {{ $group->group_name }} ({{ $group->group_code }})
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Hotmobily (Type 2)">
                            @foreach ($groups->where('product_type', 2) as $group)
                                <option value="{{ $group->option_group_id }}"
                                    {{ old('target_group_id', $dependency->target_group_id) == $group->option_group_id ? 'selected' : '' }}>
                                    {{ $group->group_name }} ({{ $group->group_code }})
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>
            </div>

            <div class="section-title">
                {{ request()->cookie('dev') === '1' ? 'Status' : 'ステータス' }}
            </div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $dependency->is_active) ? 'checked' : '' }}>
                    {{ request()->cookie('dev') === '1' ? 'Active' : '有効' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.option-dependencies.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') === '1' ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') === '1' ? 'Update Dependency' : '依存関係を更新' }}
                </button>
            </div>
        </form>
    </div>

@endsection

@section('js')
    {{-- ถ้า admin.layouts.app มี jQuery อยู่แล้ว ให้ลบบรรทัดนี้ออก --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        function toggleTargetType() {
            const type = document.getElementById('target_type').value;
            const optionBox = document.getElementById('target_option_box');
            const groupBox = document.getElementById('target_group_box');

            if (type === 'group') {
                optionBox.style.display = 'none';
                groupBox.style.display = 'block';
            } else {
                optionBox.style.display = 'block';
                groupBox.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            $('#parent_option_id').select2({
                placeholder: '-- Select Trigger Option --',
                allowClear: true,
                width: '100%'
            });

            $('#target_option_id').select2({
                placeholder: '-- Select Target Option --',
                allowClear: true,
                width: '100%'
            });

            $('#target_group_id').select2({
                placeholder: '-- Select Target Group --',
                allowClear: true,
                width: '100%'
            });

            document.getElementById('target_type').addEventListener('change', toggleTargetType);
            toggleTargetType();
        });
    </script>
@endsection
