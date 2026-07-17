@extends('admin.layouts.app')

@section('title', 'Edit FAQ | Indigo Admin')

@include('admin.faqs._style')

@section('content')

<form action="{{ route('admin.faqs.update', $faq->faq_id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="table-card faq-page-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Edit FAQ' : 'FAQを編集' }}</div>
                <div class="showing-text">{{ $faq->question }}</div>
            </div>

            <a href="{{ route('admin.faqs.index') }}" class="btn-outline">
                {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
            </a>
        </div>

        @include('admin.faqs._form', ['faq' => $faq])

        <div class="faq-actions-bottom">
            <button type="submit" class="faq-save-btn">
                {{ request()->cookie('dev') == '1' ? 'Update FAQ' : 'FAQを更新' }}
            </button>

            <a href="{{ route('admin.faqs.index') }}" class="faq-cancel-btn">
                {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
            </a>
        </div>
    </div>
</form>

@endsection