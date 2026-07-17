<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

<script>
class LaravelUploadAdapter {
    constructor(loader) {
        this.loader = loader;
    }

    upload() {
        return this.loader.file.then(file => {
            return new Promise((resolve, reject) => {
                const data = new FormData();

                data.append('upload', file);
                data.append('_token', '{{ csrf_token() }}');

                fetch('{{ route('admin.articles.uploadEditorImage') }}', {
                    method: 'POST',
                    body: data
                })
                .then(response => response.json())
                .then(result => {
                    if (result.url) {
                        resolve({
                            default: result.url
                        });
                    } else {
                        reject(result.message || 'Upload failed');
                    }
                })
                .catch(error => {
                    reject(error);
                });
            });
        });
    }

    abort() {}
}

function LaravelUploadAdapterPlugin(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
        return new LaravelUploadAdapter(loader);
    };
}

ClassicEditor
    .create(document.querySelector('#article_detail'), {
        extraPlugins: [LaravelUploadAdapterPlugin],
        toolbar: [
            'heading',
            '|',
            'bold',
            'italic',
            'link',
            'bulletedList',
            'numberedList',
            '|',
            'blockQuote',
            'insertTable',
            'imageUpload',
            'undo',
            'redo'
        ],
    })
    .then(editor => {
        editor.ui.view.editable.element.style.minHeight = '420px';
    })
    .catch(error => {
        console.error(error);
    });
</script>