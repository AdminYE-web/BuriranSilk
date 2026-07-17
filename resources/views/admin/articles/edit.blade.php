@extends('admin.layouts.app')

@section('title', 'Edit Article | Indigo Admin')

@section('css')
    <style>
        .article-page-card {
            max-width: 1180px;
            margin: 0 auto;
            padding: 24px;
        }

        .article-form-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 24px;
            margin-top: 18px;
        }

        .article-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .article-form-group {
            margin-bottom: 20px;
        }

        .article-form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--fg-dark);
            font-size: 14px;
            font-weight: 700;
        }

        .article-form-group label span {
            color: #dc2626;
        }

        .article-form-group input[type="text"],
        .article-form-group input[type="date"],
        .article-form-group input[type="file"],
        .article-form-group textarea,
        .article-form-group select {
            width: 100%;
            min-height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 10px 12px;
            background: #fff;
            color: var(--fg-dark);
            font-size: 14px;
            font-family: inherit;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease;
        }

        .article-form-group textarea {
            min-height: 260px;
            resize: vertical;
        }

        .article-form-group input:focus,
        .article-form-group textarea:focus,
        .article-form-group select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        .article-cover-preview {
            margin-top: 12px;
            width: 180px;
            height: 115px;
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            background: var(--bg);
        }

        .article-cover-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .article-active-box {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            font-size: 14px;
            color: var(--fg);
            font-weight: 600;
        }

        .article-active-box input {
            width: 16px;
            height: 16px;
            accent-color: var(--accent);
        }

        .article-editor-wrap {
            margin-top: 8px;
        }

        .ck-editor__editable {
            min-height: 420px;
            font-size: 15px;
            line-height: 1.7;
        }

        .ck.ck-editor {
            border-radius: 12px;
            overflow: hidden;
        }

        .ck.ck-toolbar {
            border-color: var(--border) !important;
            background: #f8fafc !important;
        }

        .ck.ck-editor__main>.ck-editor__editable {
            border-color: var(--border) !important;
        }

        .article-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .error-text {
            margin-top: 6px;
            color: #dc2626;
            font-size: 12px;
        }

        .alert-success {
            margin: 16px 0;
            padding: 12px 16px;
            background: #ecfdf5;
            color: #047857;
            border: 1px solid #a7f3d0;
            border-radius: 10px;
            font-size: 14px;
        }

        .article-filter-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 18px 0;
            padding: 14px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
        }

        .article-filter-bar input,
        .article-filter-bar select {
            height: 40px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 12px;
            font-size: 14px;
            outline: none;
            background: #fff;
        }

        .article-filter-bar input {
            min-width: 280px;
        }

        .article-thumb {
            width: 76px;
            height: 54px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
            background: var(--bg);
        }

        .article-status {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .article-status.is-active {
            background: #ecfdf5;
            color: #047857;
        }

        .article-status.is-inactive {
            background: #fef2f2;
            color: #b91c1c;
        }

        .article-action-link {
            color: var(--accent);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            margin-right: 8px;
        }

        .article-action-link:hover {
            text-decoration: underline;
        }

        .article-delete-btn {
            border: 0;
            background: transparent;
            color: #dc2626;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            padding: 0;
        }

        .article-delete-btn:hover {
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .article-page-card {
                padding: 16px;
            }

            .article-form-card {
                padding: 18px;
            }

            .article-form-grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .article-filter-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .article-filter-bar input,
            .article-filter-bar select {
                width: 100%;
                min-width: 0;
            }

            .article-actions {
                flex-direction: column;
                align-items: stretch;
            }

            .article-actions .btn-primary,
            .article-actions .btn-outline {
                width: 100%;
            }
        }

        .article-upload-box {
            border: 1px dashed var(--border);
            border-radius: 12px;
            padding: 16px;
            background: #f8fafc;
        }

        .article-upload-input {
            display: none;
        }

        .article-upload-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 140px;
            height: 42px;
            border-radius: 10px;
            border: 1px solid var(--accent);
            background: #fff;
            color: var(--accent);
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .article-upload-btn:hover {
            background: var(--accent);
            color: #fff;
            transform: translateY(-1px);
        }

        .article-upload-file-name {
            margin-top: 10px;
            color: var(--muted);
            font-size: 13px;
        }

        .article-update-btn {
            min-width: 140px;
            height: 42px;
            border: 1px solid var(--accent);
            border-radius: 10px;
            background: var(--accent);
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            padding: 0 22px;
            transition: .2s ease;
        }

        .article-update-btn:hover {
            opacity: .92;
            transform: translateY(-1px);
        }

        .article-cancel-btn {
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
            padding: 0 18px;
            transition: .2s ease;
        }

        .article-cancel-btn:hover {
            border-color: var(--accent);
            color: var(--accent);
            background: #f8fafc;
        }

        .article-form-group-full {
            grid-column: 1 / -1;
        }
    </style>
@endsection
@section('content')

    <form action="{{ route('admin.articles.update', $article->article_id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="table-card">
            <div class="table-header">
                <div>
                    <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Edit Article' : '記事の編集' }}</div>
                    <div class="showing-text">{{ $article->title }}</div>
                </div>

                <a href="{{ route('admin.articles.index') }}"
                    class="btn-outline">{{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}</a>
            </div>

            @include('admin.articles._form', ['article' => $article])

            <div class="article-actions">
                <button type="submit" class="article-update-btn">
                    {{ request()->cookie('dev') == '1' ? 'Update Article' : '記事の更新' }}
                </button>

                <a href="{{ route('admin.articles.index') }}" class="article-cancel-btn">
                    {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
                </a>
            </div>
        </div>
    </form>

@endsection

@section('js')
    @include('admin.articles._ckeditor')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const coverInput = document.getElementById('cover_image');
            const fileNameBox = document.getElementById('coverImageFileName');

            if (coverInput && fileNameBox) {
                coverInput.addEventListener('change', function() {
                    fileNameBox.textContent = this.files.length > 0 ?
                        this.files[0].name :
                        'No file selected';
                });
            }
        });
    </script>
@endsection
