<div class="template-form-card">
    <div class="template-form-grid">

        <div class="template-form-group">
            <label>{{ request()->cookie('dev') === '1' ? 'Product' : '製品' }} <span>*</span></label>
            <select name="product_id" required>
                <option value="">{{ request()->cookie('dev') === '1' ? '-- Select Product --' : '-- 製品を選択 --' }}
                </option>

                @foreach ($products as $product)
                    <option value="{{ $product->product_id }}"
                        {{ old('product_id', $template->product_id ?? '') == $product->product_id ? 'selected' : '' }}>
                        {{ $product->product_name }}
                    </option>
                @endforeach
            </select>

            @error('product_id')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="template-form-group">
            <label>{{ request()->cookie('dev') === '1' ? 'Template Size' : 'テンプレートサイズ' }}</label>
            <input type="text" name="template_size" value="{{ old('template_size', $template->template_size ?? '') }}"
                placeholder="Optional: Ex: 10mm, 15mm, A4, 70x70mm">

            <small style="display:block;margin-top:6px;color:var(--muted);font-size:12px;">
                {{ request()->cookie('dev') === '1' ? 'Leave blank if this product does not have size variations.' : '製品にサイズバリエーションがない場合は空欄にしてください。' }}
            </small>

            @error('template_size')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="template-form-group template-form-full">
            <label>{{ request()->cookie('dev') === '1' ? 'Template File' : 'テンプレートファイル' }}
                {{ empty($template) ? '*' : '' }}</label>

            <div class="template-upload-box">
                <input type="file" name="template_file" id="template_file" class="template-upload-input"
                    accept=".pdf,.ai,application/pdf,application/postscript,application/octet-stream"
                    {{ empty($template) ? 'required' : '' }}>

                <label for="template_file" class="template-upload-btn">
                    {{ request()->cookie('dev') === '1' ? 'Upload PDF / AI File' : 'PDF / AIファイルのアップロード' }}
                </label>

                <div class="template-upload-file-name" id="templateFileName">
                    @if (!empty($template->original_name))
                        {{ request()->cookie('dev') === '1' ? 'Current file: ' : '現在のファイル: ' }}
                        {{ $template->original_name }}
                    @else
                        No file selected
                    @endif
                </div>

                @if (!empty($template->file_path))
                    <div style="margin-top:10px;">
                        <a href="{{ asset('storage/' . $template->file_path) }}" target="_blank"
                            class="template-current-file">
                            {{ request()->cookie('dev') === '1' ? 'View current file' : '現在のファイルを表示' }}
                        </a>
                    </div>
                @endif
            </div>

            @error('template_file')
                <div class="error-text">{{ $message }}</div>
            @enderror
        </div>

        <div class="template-form-group">
            <label class="template-active-box">
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', $template->is_active ?? 1) ? 'checked' : '' }}>
                {{ request()->cookie('dev') === '1' ? 'Active' : 'アクティブ' }}
            </label>
        </div>

    </div>
</div>
