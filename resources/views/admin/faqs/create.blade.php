@extends('admin.layouts.app')

@section('title', 'Create FAQ | Indigo Admin')

@include('admin.faqs._style')

@section('content')

<form action="{{ route('admin.faqs.store') }}" method="POST">
    @csrf

    <div class="table-card faq-page-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Create FAQ' : 'FAQを作成' }}</div>
                <div class="showing-text">{{ request()->cookie('dev') == '1' ? 'Current language:' : '現在の言語:' }} {{ strtoupper($language) }}</div>
            </div>

            <a href="{{ route('admin.faqs.index') }}" class="btn-outline">{{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}</a>
        </div>

        @include('admin.faqs._form')

        <div class="faq-actions-bottom">
            <button type="submit" class="faq-save-btn">
                {{ request()->cookie('dev') == '1' ? 'Save FAQ' : 'FAQを保存' }}
            </button>

            <a href="{{ route('admin.faqs.index') }}" class="faq-cancel-btn">
                {{ request()->cookie('dev') == '1' ? 'Cancel' : 'キャンセル' }}
            </a>
        </div>
    </div>
</form>

@endsection