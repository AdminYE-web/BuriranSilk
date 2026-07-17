@extends('admin.layouts.app')

@section('title', 'Manage Menu Bar & Recommended Products | Indigo Admin')

@section('css')
    <style>
        .manage-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 18px;
            padding: 0 24px 24px;
        }

        .manage-section {
            margin-top: 24px;
            padding: 0 24px;
        }

        .manage-section-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
            margin-bottom: 14px;
        }

        .manage-box {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
        }

        .manage-box-header {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            background: #f8fafc;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
        }

        .manage-box-title {
            font-weight: 700;
            color: var(--fg-dark);
        }

        .manage-count {
            font-size: 12px;
            color: var(--muted);
        }

        .manage-list {
            padding: 12px 16px;
        }

        .manage-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #eef2f7;
        }

        .manage-item:last-child {
            border-bottom: 0;
        }

        .manage-product-name {
            font-weight: 600;
            color: var(--fg-dark);
            font-size: 14px;
        }

        .manage-product-code {
            color: var(--muted);
            font-size: 12px;
            margin-top: 3px;
        }

        .manage-add-form {
            display: flex;
            gap: 10px;
            padding: 12px 16px;
            border-top: 1px solid var(--border);
            background: #f8fafc;
        }

        .manage-add-form select {
            flex: 1;
            height: 38px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 10px;
            background: #fff;
        }

        .btn-small {
            height: 38px;
            border-radius: 8px;
            padding: 0 14px;
            border: 1px solid var(--accent);
            background: var(--accent);
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap;
        }

        .btn-remove {
            border: 0;
            background: none;
            color: #dc2626;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
        }

        .alert-error {
            margin: 0 24px 16px;
            padding: 12px 16px;
            background: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
            border-radius: 8px;
            font-size: 14px;
        }

        .alert-success {
            margin: 0 24px 16px;
            padding: 12px 16px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 8px;
            font-size: 14px;
        }

        @media (max-width: 900px) {
            .manage-grid {
                grid-template-columns: 1fr;
            }

            .manage-add-form {
                flex-direction: column;
            }
        }

        .type-tabs {
            display: flex;
            gap: 10px;
            padding: 0 24px;
            margin-top: 24px;
            border-bottom: 1px solid var(--border);
        }

        .type-tab-btn {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg-dark);
            padding: 10px 18px;
            border-radius: 10px 10px 0 0;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            margin-bottom: -1px;
        }

        .type-tab-btn.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        .type-tab-content {
            display: none;
            padding-bottom: 24px;
        }

        .type-tab-content.active {
            display: block;
        }

        .manage-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 18px;
            padding: 0 24px 24px;
        }
    </style>
@endsection

@section('content')
    <div class="table-card">
        <div class="table-header">
            <div>
                <div class="table-title">
                    {{ request()->cookie('dev') === '1' ? 'Manage Menu Bar & Recommended Products' : 'メニューバーとおすすめ製品の管理' }}
                </div>
                <div class="showing-text">
                    {{ request()->cookie('dev') === '1' ? 'Manage products shown in menu bar and recommended product sections.' : 'メニューバーとおすすめ製品セクションに表示される製品を管理します。' }}
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="type-tabs">
            <button type="button" class="type-tab-btn active" data-tab="hotstrap">
                Hotstrap
            </button>

            <button type="button" class="type-tab-btn" data-tab="hotmobily">
                Hotmobily
            </button>
        </div>

        <div id="tab-hotstrap" class="type-tab-content active">

            <div class="manage-section">
                <div class="manage-section-title">{{ request()->cookie('dev') === '1' ? 'Menu Bar Products' : 'メニューバー製品' }}
                </div>
            </div>

            <div class="manage-grid">
                @include('admin.menu_products.partials.product_box', [
                    'title' => 'Hotstrap',
                    'items' => $menuHotstrap,
                    'products' => $products->where('product_type', 1)->where('product_recomend_menu', 0),
                    'target' => 'menu',
                    'limit' => 12,
                ])
            </div>

            <div class="manage-section">
                <div class="manage-section-title">{{ request()->cookie('dev') === '1' ? 'Recommended Products' : 'おすすめ製品' }}
                </div>
            </div>

            <div class="manage-grid">
                @include('admin.menu_products.partials.product_box', [
                    'title' => 'Hotstrap',
                    'items' => $recommendedHotstrap,
                    'products' => $products->where('product_type', 1)->where('product_recomend', 0),
                    'target' => 'recommended',
                    'limit' => 5,
                ])
            </div>

        </div>

        <div id="tab-hotmobily" class="type-tab-content">

            <div class="manage-section">
                <div class="manage-section-title">{{ request()->cookie('dev') === '1' ? 'Menu Bar Products' : 'メニューバー製品' }}
                </div>
            </div>

            <div class="manage-grid">
                @include('admin.menu_products.partials.product_box', [
                    'title' => 'Hotmobily',
                    'items' => $menuHotmobily,
                    'products' => $products->where('product_type', 2)->where('product_recomend_menu', 0),
                    'target' => 'menu',
                    'limit' => 12,
                ])
            </div>

            <div class="manage-section">
                <div class="manage-section-title">{{ request()->cookie('dev') === '1' ? 'Recommended Products' : 'おすすめ製品' }}
                </div>
            </div>

            <div class="manage-grid">
                @include('admin.menu_products.partials.product_box', [
                    'title' => 'Hotmobily',
                    'items' => $recommendedHotmobily,
                    'products' => $products->where('product_type', 2)->where('product_recomend', 0),
                    'target' => 'recommended',
                    'limit' => 5,
                ])
            </div>

        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.type-tab-btn');
            const tabContents = document.querySelectorAll('.type-tab-content');

            tabButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const tab = this.dataset.tab;

                    tabButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });

                    tabContents.forEach(function(content) {
                        content.classList.remove('active');
                    });

                    this.classList.add('active');

                    const targetContent = document.getElementById('tab-' + tab);
                    if (targetContent) {
                        targetContent.classList.add('active');
                    }
                });
            });
        });
    </script>
@endsection
