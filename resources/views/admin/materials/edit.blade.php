@extends('admin.layouts.app')

@section('title', 'Edit Material | Indigo Admin')

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

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .form-group input[type="text"],
        .form-group select {
            width: 100%;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
            outline: none;
        }

        .form-group input[type="text"]:focus,
        .form-group select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
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
                <h1>{{ request()->cookie('dev') == '1' ? 'Edit Material' : 'マテリアルを編集' }}</h1>
                <p>{{ request()->cookie('dev') == '1' ? 'Update material code, name and active status.' : '製品マテリアル、コード、アクティブステータスを更新します。' }}
                </p>
            </div>

            <a href="{{ route('admin.materials.index') }}" class="btn-outline">
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

        <form action="{{ route('admin.materials.update', $material->material_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Material Information' : 'マテリアル情報' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Material Code' : 'マテリアルコード' }}</label>
                    <input type="text" name="material_code" value="{{ old('material_code', $material->material_code) }}">
                </div>
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Material Name' : 'マテリアル名' }}</label>
                    <input type="text" name="material_name"
                        value="{{ old('material_name', $material->material_name) }}">
                </div>
                <div class="form-group">
                    <label>{{ request()->cookie('dev') == '1' ? 'Product Type' : '製品タイプ' }}</label>
                    <select name="product_type" required>
                        <option value="1"
                            {{ old('product_type', $material->product_type ?? 1) == 1 ? 'selected' : '' }}>
                            Hotstrap
                        </option>
                        <option value="2"
                            {{ old('product_type', $material->product_type ?? 1) == 2 ? 'selected' : '' }}>
                            Hotmobily
                        </option>
                    </select>
                </div>
                <div class="form-group" style="display: none">
                    <label>{{ request()->cookie('dev') == '1' ? 'Translation Key' : '翻訳キー' }}</label>
                    <input type="text" name="translation_key"
                        value="{{ old('translation_key', $material->translation_key ?? '') }}"
                        placeholder="เช่น mat_xxxxxxxx">
                    <small>{{ request()->cookie('dev') == '1' ? 'Used to bind the same material across languages' : 'ใช้สำหรับผูก material เดียวกันข้ามภาษา' }}</small>
                </div>


            </div>

            <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Status' : 'ステータス' }}</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $material->is_active) ? 'checked' : '' }}>
                    {{ request()->cookie('dev') == '1' ? 'Active' : 'アクティブ' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.materials.index') }}" class="btn-outline">
                    {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ request()->cookie('dev') == '1' ? 'Update Material' : '更新' }}
                </button>
            </div>
        </form>
    </div>

@endsection
