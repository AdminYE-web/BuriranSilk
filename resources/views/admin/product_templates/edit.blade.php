@extends('admin.layouts.app')

@section('title', 'Edit Product Template | Indigo Admin')

@section('css')
    <style>
        .template-page-card {
            max-width: 980px;
            margin: 0 auto;
            padding: 24px;
        }

        .template-form-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
            margin-top: 18px;
        }

        .template-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .template-form-full {
            grid-column: 1 / -1;
        }

        .template-form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--fg-dark);
            font-size: 14px;
            font-weight: 700;
        }

        .template-form-group label span {
            color: #dc2626;
        }

        .template-form-group input[type="text"],
        .template-form-group select {
            width: 100%;
            min-height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff;
            color: var(--fg-dark);
            font-size: 14px;
            outline: none;
        }

        .template-upload-box {
            border: 1px dashed var(--border);
            border-radius: 12px;
            padding: 18px;
            background: #f8fafc;
        }

        .template-upload-input {
            display: none;
        }

        .template-upload-btn {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 180px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid var(--accent);
            background: #fff;
            color: var(--accent) !important;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
            margin-bottom: 0 !important;
        }

        .template-upload-btn:hover {
            background: var(--accent);
            color: #fff !important;
            transform: translateY(-1px);
        }

        .template-upload-file-name {
            margin-top: 10px;
            color: var(--muted);
            font-size: 13px;
        }

        .template-current-file {
            color: var(--accent);
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
        }

        .template-active-box {
            display: inline-flex !important;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            font-size: 14px;
            color: var(--fg);
            font-weight: 600;
        }

        .template-active-box input {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
        }

        .template-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .template-save-btn {
            min-width: 150px;
            height: 42px;
            border: 1px solid var(--accent);
            border-radius: 10px;
            background: var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            padding: 0 22px;
        }

        .template-cancel-btn {
            min-width: 110px;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            color: var(--fg);
            font-size: 14px;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .error-text {
            margin-top: 6px;
            color: #dc2626;
            font-size: 12px;
        }

        @media (max-width: 768px) {
            .template-form-grid {
                grid-template-columns: 1fr;
            }

            .template-form-full {
                grid-column: auto;
            }

            .template-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .template-save-btn,
            .template-cancel-btn {
                width: 100%;
            }
        }
    </style>
@endsection

@section('content')

    <form action="{{ route('admin.product-templates.update', $template->template_id) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="table-card template-page-card">
            <div class="table-header">
                <div>
                    <div class="table-title">{{ request()->cookie('dev') === '1' ? 'Edit Product Template' : '製品テンプレートの編集' }}
                    </div>
                    <div class="showing-text">{{ $template->product->product_name ?? '-' }}</div>
                </div>

                <a href="{{ route('admin.product-templates.index') }}"
                    class="btn-outline">{{ request()->cookie('dev') === '1' ? 'Back' : '戻る' }}</a>
            </div>

            @include('admin.product_templates._form', ['template' => $template])

            <div class="template-actions">
                <button type="submit" class="template-save-btn">
                    {{ request()->cookie('dev') === '1' ? 'Update Template' : 'テンプレートを保存' }}
                </button>

                <a href="{{ route('admin.product-templates.index') }}" class="template-cancel-btn">
                    {{ request()->cookie('dev') === '1' ? 'Cancel' : 'キャンセル' }}
                </a>
            </div>
        </div>
    </form>

@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('template_file');
            const fileName = document.getElementById('templateFileName');

            if (input && fileName) {
                input.addEventListener('change', function() {
                    fileName.textContent = this.files.length ?
                        this.files[0].name :
                        'No file selected';
                });
            }
        });
    </script>
@endsection
