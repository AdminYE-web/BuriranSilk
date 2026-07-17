@extends('admin.layouts.app')

@section('title', 'Add Home Banner | Indigo Admin')

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

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
            color: var(--fg-dark);
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group input[type="file"] {
            width: 100%;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 14px;
            font-family: inherit;
            background: #fff;
            color: var(--fg);
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
    @php
        $isDev = request()->cookie('dev') == '1';
    @endphp
    <div class="form-card">
        <div class="form-header">
            <div>
                <h1>{{ $isDev ? 'Add Home Banner' : 'ホームバナーを追加' }}</h1>

                <p>
                    {{ $isDev
                        ? 'Create homepage banner for PC and mobile, link, sorting and status.'
                        : 'PC・モバイル用のホームページバナー、リンク、表示順、ステータスを設定します。' }}
                </p>
            </div>

            <a href="{{ route('admin.home-banners.index') }}" class="btn-outline">
                {{ $isDev ? 'Back' : '戻る' }}
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

        <form action="{{ route('admin.home-banners.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="section-title">{{ $isDev ? 'Banner Information' : 'バナー情報' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ $isDev ? 'Title' : 'タイトル' }}</label>
                    <input type="text" name="title" value="{{ old('title') }}">
                </div>

                <div class="form-group">
                    <label>{{ $isDev ? 'Link URL' : 'リンクURL' }}</label>
                    <input type="text" name="link_url" value="{{ old('link_url') }}"
                        placeholder="https://example.com หรือ /products">
                </div>

                <div class="form-group">
                    <label>{{ $isDev ? 'Sort Order' : '表示順' }}</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                </div>
            </div>

            <div class="section-title">{{ $isDev ? 'Banner Images' : 'バナー画像' }}</div>

            <div class="form-grid">
                <div class="form-group">
                    <label>{{ $isDev ? 'PC Image' : 'PC画像' }}</label>
                    <input type="file" name="image_pc" accept="image/*">
                    <small>
                        {{ $isDev ? 'Recommended PC size: 1920x600 or 1440x480' : 'おすすめPCサイズ： 1920x600 または 1440x480' }}
                    </small>
                </div>

                <div class="form-group">
                    <label>{{ $isDev ? 'Mobile Image' : 'モバイル画像' }}</label>
                    <input type="file" name="image_mobile" accept="image/*">
                    <small>
                        {{ $isDev ? 'Recommended Mobile size: 750x900 or 750x1000' : 'おすすめMobileサイズ： 750x900 または 750x1000' }}
                    </small>
                </div>
            </div>

            <div class="section-title">{{ $isDev ? 'Status' : 'ステータス' }}</div>

            <div class="checkbox-grid">
                <label>
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    {{ $isDev ? 'Active' : '有効' }}
                </label>
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.home-banners.index') }}" class="btn-outline">
                    {{ $isDev ? 'Cancel' : 'キャンセル' }}
                </a>

                <button type="submit" class="btn-primary">
                    {{ $isDev ? 'Save Home Banner' : '保存' }}
                </button>
            </div>
        </form>
    </div>

@endsection
