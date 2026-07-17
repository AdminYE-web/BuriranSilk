@extends('admin.layouts.app')

@section('title', 'Add Gallery Banner | Indigo Admin')

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

        .form-group input {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
        }

        .checkbox-box {
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
            display: inline-flex;
            gap: 8px;
            align-items: center;
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

        @media (max-width: 900px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('content')

    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ request()->cookie('dev') === '1' ? 'Add Gallery Banner' : 'ギャラリーバナーを追加' }}</h1>
                <p>{{ request()->cookie('dev') === '1' ? 'Create banner for gallery page with PC and mobile images.' : 'ギャラリーページ用のバナーを作成します（PC用とモバイル用の画像が必要です）。' }}
                </p>
            </div>

            <a href="{{ route('admin.gallery-banners.index') }}" class="btn-outline">
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

        <form action="{{ route('admin.gallery-banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="form-group full">
                    <label>{{ request()->cookie('dev') === '1' ? 'Title' : 'タイトル' }}</label>
                    <input type="text" name="title" value="{{ old('title') }}">
                </div>

                <div class="form-group full">
                    <label>{{ request()->cookie('dev') === '1' ? 'Link URL' : 'リンクURL' }}</label>
                    <input type="text" name="link_url" value="{{ old('link_url') }}" placeholder="https://example.com">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'PC Image' : 'PC画像' }}</label>
                    <input type="file" name="image_pc" accept="image/*">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Mobile Image' : 'モバイル画像' }}</label>
                    <input type="file" name="image_mobile" accept="image/*">
                </div>

                <div class="form-group">
                    <label>{{ request()->cookie('dev') === '1' ? 'Sort Order' : '表示順' }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}">
                </div>


            </div>

            <label class="checkbox-box">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                {{ request()->cookie('dev') === '1' ? 'Active' : '有効' }}
            </label>


            <div class="form-actions">
                <a href="{{ route('admin.gallery-banners.index') }}"
                    class="btn-outline">{{ request()->cookie('dev') === '1' ? 'Cancel' : 'キャンセル' }}</a>
                <button type="submit" class="btn-primary">Save Banner</button>
            </div>
        </form>
    </div>

@endsection
