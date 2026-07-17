@section('css')
<style>
    .faq-page-card {
        max-width: 980px;
        margin: 0 auto;
        padding: 24px;
    }

    .faq-form-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 24px;
        margin-top: 18px;
    }

    .faq-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .faq-form-full {
        grid-column: 1 / -1;
    }

    .faq-form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--fg-dark);
        font-size: 14px;
        font-weight: 700;
    }

    .faq-form-group label span {
        color: #dc2626;
    }

    .faq-form-group input[type="text"],
    .faq-form-group input[type="number"],
    .faq-form-group select,
    .faq-form-group textarea {
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

    .faq-form-group textarea {
        resize: vertical;
        line-height: 1.6;
    }

    .faq-check-box {
        display: flex !important;
        align-items: center;
        gap: 8px;
        margin-bottom: 10px !important;
        font-weight: 600 !important;
        color: var(--fg);
    }

    .faq-check-box input {
        width: 16px;
        height: 16px;
        accent-color: var(--accent);
    }

    .faq-actions-bottom {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .faq-save-btn {
        min-width: 140px;
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

    .faq-cancel-btn {
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
        .faq-form-grid {
            grid-template-columns: 1fr;
        }

        .faq-form-full {
            grid-column: auto;
        }

        .faq-actions-bottom {
            flex-direction: column;
            align-items: stretch;
        }

        .faq-save-btn,
        .faq-cancel-btn {
            width: 100%;
        }
    }
</style>
@endsection