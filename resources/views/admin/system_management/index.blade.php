@extends('admin.layouts.app')

@section('title', 'System Management | Indigo Admin')

@section('css')
    <style>
        .setting-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .setting-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 24px;
        }

        .setting-header h1 {
            margin: 0 0 6px;
            font-size: 24px;
            color: var(--fg-dark);
        }

        .setting-header p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
        }

        .section-title {
            margin: 28px 0 16px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .setting-options {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            max-width: 760px;
        }

        .setting-option {
            position: relative;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            cursor: pointer;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .setting-option:hover {
            border-color: var(--accent);
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
        }

        .setting-option input {
            margin-top: 3px;
            width: 18px;
            height: 18px;
            accent-color: var(--accent);
            flex-shrink: 0;
        }

        .setting-option-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--fg-dark);
            margin-bottom: 5px;
        }

        .setting-option-desc {
            font-size: 13px;
            line-height: 1.45;
            color: var(--muted);
        }

        .setting-actions {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 12px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 38px;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #fff;
            cursor: pointer;
            font-family: inherit;
        }

        .alert-success {
            margin-bottom: 18px;
            padding: 12px 16px;
            border-radius: 8px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .setting-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')

    <div class="setting-card">
        <div class="setting-header">
            <div>
                <h1>System Management</h1>
                <p>Manage website system settings.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('admin.system-management.update') }}" method="POST">
            @csrf

            <div class="section-title">Price Tax Setting</div>

            <div class="setting-options">
                <label class="setting-option">
                    <input type="radio" name="price_tax_mode" value="exclude_tax" {{ old('price_tax_mode', $priceTaxMode) === 'exclude_tax' ? 'checked' : '' }}>

                    <div>
                        <div class="setting-option-title">
                            Exclude Tax
                        </div>
                        <div class="setting-option-desc">
                            ใช้ราคาก่อนรวมภาษี เช่น Additional Price / Unit Price
                        </div>
                    </div>
                </label>

                <label class="setting-option">
                    <input type="radio" name="price_tax_mode" value="include_tax" {{ old('price_tax_mode', $priceTaxMode) === 'include_tax' ? 'checked' : '' }}>

                    <div>
                        <div class="setting-option-title">
                            Include Tax
                        </div>
                        <div class="setting-option-desc">
                            ใช้ราคารวมภาษี เช่น Additional Price With Tax / Unit Price With Tax
                        </div>
                    </div>
                </label>
            </div>

            @error('price_tax_mode')
                <div style="margin-top:8px; color:#b91c1c; font-size:13px;">
                    {{ $message }}
                </div>
            @enderror

            <div class="setting-actions">
                <button type="submit" class="btn-primary">
                    Save Settings
                </button>
            </div>
        </form>
    </div>

@endsection