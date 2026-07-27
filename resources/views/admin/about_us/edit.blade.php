@extends('admin.layouts.app')

@section('title', 'About Us CMS | Admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('admin/css/about-us.css') }}">
@endsection

@section('content')
    @php($isDev = request()->cookie('dev') == '1')

    <div class="about-us-admin-card">
        <div class="about-us-admin-header">
            <div>
                <h1>{{ $isDev ? 'About Us CMS' : '私たちについて CMS' }}</h1>
                <p>{{ $isDev ? 'Manage banner, intro and rich page details.' : 'バナー、イントロ、ページ詳細を管理します。' }}</p>
            </div>
            <a class="about-us-admin-preview-link" href="{{ route('about-us') }}" target="_blank" rel="noopener">
                {{ $isDev ? 'Preview page' : 'ページを確認' }}
            </a>
        </div>

        @if (session('success'))
            <div class="about-us-admin-alert success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="about-us-admin-alert error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.about-us.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <section class="about-us-admin-section">
                <h2 class="about-us-admin-section-title">{{ $isDev ? 'Banner Images' : 'バナー画像' }}</h2>
                <p class="about-us-admin-section-note">{{ $isDev ? 'Upload separate images for desktop and mobile.' : 'デスクトップ用とモバイル用の画像をそれぞれアップロードしてください。' }}</p>

                <div class="about-us-admin-grid">
                    <div class="about-us-admin-field">
                        <label for="bannerDesktop">{{ $isDev ? 'Desktop Banner' : 'デスクトップバナー' }}</label>
                        <input id="bannerDesktop" type="file" name="banner_desktop" accept="image/jpeg,image/png,image/webp">
                        <small>{{ $isDev ? 'Recommended: 1920 × 760 px, up to 8 MB.' : '推奨：1920 × 760 px、最大8MB。' }}</small>
                        @if ($aboutUsPage->banner_desktop)
                            <div class="about-us-admin-image-preview">
                                <img src="{{ asset('storage/'.$aboutUsPage->banner_desktop) }}" alt="">
                                <label class="about-us-admin-remove">
                                    <input type="checkbox" name="remove_banner_desktop" value="1">
                                    {{ $isDev ? 'Remove image' : '画像を削除' }}
                                </label>
                            </div>
                        @endif
                    </div>

                    <div class="about-us-admin-field">
                        <label for="bannerMobile">{{ $isDev ? 'Mobile Banner' : 'モバイルバナー' }}</label>
                        <input id="bannerMobile" type="file" name="banner_mobile" accept="image/jpeg,image/png,image/webp">
                        <small>{{ $isDev ? 'Recommended: 750 × 900 px, up to 8 MB.' : '推奨：750 × 900 px、最大8MB。' }}</small>
                        @if ($aboutUsPage->banner_mobile)
                            <div class="about-us-admin-image-preview mobile">
                                <img src="{{ asset('storage/'.$aboutUsPage->banner_mobile) }}" alt="">
                                <label class="about-us-admin-remove">
                                    <input type="checkbox" name="remove_banner_mobile" value="1">
                                    {{ $isDev ? 'Remove image' : '画像を削除' }}
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
            </section>

            <section class="about-us-admin-section">
                <h2 class="about-us-admin-section-title">{{ $isDev ? 'Intro Section' : 'イントロセクション' }}</h2>
                <p class="about-us-admin-section-note">{{ $isDev ? 'Upload the intro image and edit its text with CKEditor.' : 'イントロ画像をアップロードし、CKEditorで文章を編集できます。' }}</p>

                <div class="about-us-admin-field">
                    <label for="introImage">{{ $isDev ? 'Intro Image' : 'イントロ画像' }}</label>
                    <input id="introImage" type="file" name="intro_image" accept="image/jpeg,image/png,image/webp">
                    <small>{{ $isDev ? 'Recommended: 1200 × 900 px, up to 8 MB.' : '推奨：1200 × 900 px、最大8MB。' }}</small>
                    @if ($aboutUsPage->intro_image)
                        <div class="about-us-admin-image-preview">
                            <img src="{{ asset('storage/'.$aboutUsPage->intro_image) }}" alt="">
                            <label class="about-us-admin-remove">
                                <input type="checkbox" name="remove_intro_image" value="1">
                                {{ $isDev ? 'Remove image' : '画像を削除' }}
                            </label>
                        </div>
                    @endif
                </div>

                <div class="about-us-admin-field about-us-admin-editor" style="margin-top:18px">
                    <label for="introContent">{{ $isDev ? 'Intro Content' : 'イントロ本文' }}</label>
                    <textarea id="introContent" name="intro_content">{{ old('intro_content', $aboutUsPage->intro_content) }}</textarea>
                </div>
            </section>

            <section class="about-us-admin-section">
                <h2 class="about-us-admin-section-title">{{ $isDev ? 'Detail Section' : '詳細セクション' }}</h2>
                <p class="about-us-admin-section-note">{{ $isDev ? 'Add formatted text, upload images, or paste a YouTube URL using Media Embed.' : 'テキスト、画像アップロード、Media EmbedからYouTube URLを追加できます。' }}</p>

                <div class="about-us-admin-field about-us-admin-editor detail">
                    <label for="detailContent">{{ $isDev ? 'Detail Content' : '詳細本文' }}</label>
                    <textarea id="detailContent" name="detail_content">{{ old('detail_content', $aboutUsPage->detail_content) }}</textarea>
                </div>
            </section>

            <div class="about-us-admin-actions">
                <button type="submit" class="about-us-admin-save">{{ $isDev ? 'Save About Us' : '保存する' }}</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <script>
        class AboutUsUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }

            upload() {
                return this.loader.file.then((file) => new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('upload', file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');

                    fetch('{{ route('admin.about-us.upload-editor-image') }}', {
                        method: 'POST',
                        body: formData,
                        headers: { 'Accept': 'application/json' },
                        credentials: 'same-origin'
                    })
                    .then(async (response) => {
                        const result = await response.json();
                        if (!response.ok || !result.url) throw new Error(result.message || 'Upload failed');
                        resolve({ default: result.url });
                    })
                    .catch(reject);
                }));
            }

            abort() {}
        }

        function AboutUsUploadPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = (loader) => new AboutUsUploadAdapter(loader);
        }

        function AboutUsAlignmentPlugin(editor) {
            const alignments = ['left', 'center', 'right', 'justify'];

            editor.model.schema.extend('$block', { allowAttributes: 'textAlignment' });
            editor.conversion.for('downcast').attributeToAttribute({
                model: { key: 'textAlignment', values: alignments },
                view: {
                    left: { key: 'style', value: 'text-align:left' },
                    center: { key: 'style', value: 'text-align:center' },
                    right: { key: 'style', value: 'text-align:right' },
                    justify: { key: 'style', value: 'text-align:justify' }
                }
            });
            editor.conversion.for('upcast').attributeToAttribute({
                view: {
                    styles: {
                        'text-align': /^(left|center|right|justify)$/
                    }
                },
                model: {
                    key: 'textAlignment',
                    value: (viewElement) => viewElement.getStyle('text-align')
                }
            });
        }

        const alignmentIcons = {
            left: 'M3 4h14v1.5H3zm0 4h9v1.5H3zm0 4h14v1.5H3zm0 4h9v1.5H3z',
            center: 'M3 4h14v1.5H3zm2.5 4h9v1.5h-9zM3 12h14v1.5H3zm2.5 4h9v1.5h-9z',
            right: 'M3 4h14v1.5H3zm5 4h9v1.5H8zm-5 4h14v1.5H3zm5 4h9v1.5H8z',
            justify: 'M3 4h14v1.5H3zm0 4h14v1.5H3zm0 4h14v1.5H3zm0 4h14v1.5H3z'
        };

        function addAlignmentButtons(editor) {
            const toolbar = editor.ui.view.toolbar.element.querySelector('.ck-toolbar__items');
            const buttons = new Map();

            ['left', 'center', 'right', 'justify'].forEach((alignment) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.className = 'ck ck-button ck-off';
                button.title = `Align ${alignment}`;
                button.setAttribute('aria-label', `Align ${alignment}`);
                button.setAttribute('aria-pressed', 'false');
                button.innerHTML = `<svg class="ck ck-icon ck-reset_all-excluded ck-icon_inherit-color" viewBox="0 0 20 20"><path d="${alignmentIcons[alignment]}"></path></svg>`;

                button.addEventListener('mousedown', (event) => {
                    event.preventDefault();
                    editor.model.change((writer) => {
                        Array.from(editor.model.document.selection.getSelectedBlocks()).forEach((block) => {
                            writer.setAttribute('textAlignment', alignment, block);
                        });
                    });
                    editor.editing.view.focus();
                    updateButtons();
                });

                toolbar.appendChild(button);
                buttons.set(alignment, button);
            });

            const updateButtons = () => {
                const firstBlock = Array.from(editor.model.document.selection.getSelectedBlocks())[0];
                const activeAlignment = firstBlock?.getAttribute('textAlignment') || 'left';
                buttons.forEach((button, alignment) => {
                    const isActive = alignment === activeAlignment;
                    button.classList.toggle('ck-on', isActive);
                    button.classList.toggle('ck-off', !isActive);
                    button.setAttribute('aria-pressed', String(isActive));
                });
            };

            editor.model.document.selection.on('change:range', updateButtons);
            editor.model.document.selection.on('change:attribute', updateButtons);
            editor.model.document.on('change:data', updateButtons);
            updateButtons();
        }

        const editorConfig = {
            extraPlugins: [AboutUsUploadPlugin, AboutUsAlignmentPlugin],
            mediaEmbed: { previewsInData: true },
            toolbar: [
                'heading', '|', 'bold', 'italic', 'link',
                'bulletedList', 'numberedList', '|',
                'blockQuote', 'insertTable', 'imageUpload', 'mediaEmbed', '|',
                'undo', 'redo'
            ]
        };

        document.querySelectorAll('#introContent, #detailContent').forEach((element) => {
            ClassicEditor.create(element, editorConfig)
                .then(addAlignmentButtons)
                .catch((error) => console.error(error));
        });
    </script>
@endsection
