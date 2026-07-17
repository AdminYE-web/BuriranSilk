@extends('admin.layouts.app')

@section('title', 'Contact Detail | Indigo Admin')

@section('css')
    <style>
        .contact-detail-card {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
        }

        .section-title {
            margin: 34px 0 16px;
            padding-top: 22px;
            border-top: 1px solid var(--border);
            font-size: 18px;
            font-weight: 700;
            color: var(--fg-dark);
        }

        .btn-outline {
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
            border: 1px solid var(--border);
            background: #fff;
            color: var(--fg);
            font-family: inherit;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .info-table th,
        .info-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
            font-size: 14px;
        }

        .info-table th {
            width: 220px;
            background: var(--bg);
            color: var(--fg-dark);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            font-size: 12px;
        }

        .info-table td {
            background: #fff;
        }

        .info-table tr:last-child th,
        .info-table tr:last-child td {
            border-bottom: 0;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 5px 10px;
            border-radius: 999px;
            background: var(--bg);
            border: 1px solid var(--border);
            font-size: 12px;
            font-weight: 600;
            color: var(--fg);
            text-transform: capitalize;
            white-space: nowrap;
        }

        .message-box {
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            line-height: 1.7;
            white-space: pre-wrap;
        }

        @media (max-width: 900px) {
            .contact-detail-card {
                padding: 18px;
            }

            .info-table th,
            .info-table td {
                display: block;
                width: 100%;
            }
        }

        .reply-status-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .reply-done {
            background: #ecfdf5;
            border-color: #a7f3d0;
            color: #047857;
        }

        .reply-pending {
            background: #fff7ed;
            border-color: #fed7aa;
            color: #c2410c;
        }

        .btn-reply-small {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 30px;
            padding: 6px 12px;
            border-radius: 999px;
            background: var(--accent);
            border: 1px solid var(--accent);
            color: #fff;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-reply-small:hover {
            color: #fff;
            filter: brightness(0.95);
        }
    </style>
@endsection

@section('content')

    <div class="table-card contact-detail-card">
        <div class="table-header">
            <div>
                <div class="table-title">{{ request()->cookie('dev') == '1' ? 'Contact Detail' : '連絡先詳細' }}</div>
                <div class="showing-text">
                    {{ $contactSubmission->name }} &middot; {{ $contactSubmission->email }}
                </div>
            </div>

            <a href="{{ route('admin.contact-submissions.index') }}" class="btn-outline">
                {{ request()->cookie('dev') == '1' ? 'Back' : '戻る' }}
            </a>
        </div>

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Contact Information' : '連絡先情報' }}</div>

        <table class="info-table">
            <tr>
                <th>ID</th>
                <td>{{ $contactSubmission->id }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Reply Status' : '返信状況' }}</th>
                <td>
                    <div class="reply-status-actions">
                        @if (($contactSubmission->status_reply ?? 'pending') === 'reply')
                            <span class="status-badge reply-done">Reply</span>
                        @else
                            <span class="status-badge reply-pending">Pending</span>

                            <a href="{{ route('admin.contact-submissions.reply', $contactSubmission) }}"
                                class="btn-reply-small">
                                {{ request()->cookie('dev') == '1' ? 'Reply' : '返信' }}
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Name' : '名前' }}</th>
                <td>{{ $contactSubmission->name }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Email' : 'メール' }}</th>
                <td>{{ $contactSubmission->email }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Phone' : '電話' }}</th>
                <td>{{ trim(($contactSubmission->country_code ?? '') . ' ' . ($contactSubmission->phone ?? '')) ?: '-' }}
                </td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'LINE ID' : 'LINE ID' }}</th>
                <td>{{ $contactSubmission->line_id ?: '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Preferred Method' : '希望する連絡方法' }}</th>
                <td><span class="status-pill">{{ $contactSubmission->contact_method }}</span></td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Subject' : '件名' }}</th>
                <td><span class="status-pill">{{ $contactSubmission->subject }}</span></td>
            </tr>
        </table>

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Message' : 'メッセージ' }}</div>

        <div class="message-box">{{ $contactSubmission->message }}</div>

        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Submission Metadata' : '送信メタデータ' }}</div>

        <table class="info-table">
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Attachment' : '添付ファイル' }}</th>
                <td>
                    @if($contactSubmission->attachment_path)
                        <a href="{{ asset('storage/' . $contactSubmission->attachment_path) }}" target="_blank" rel="noopener">
                            {{ $contactSubmission->attachment_original_name ?: $contactSubmission->attachment_path }}
                        </a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'IP Address' : 'IPアドレス' }}</th>
                <td>{{ $contactSubmission->ip_address ?: '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Submitted At' : '送信日時' }}</th>
                <td>{{ $contactSubmission->created_at ? $contactSubmission->created_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
            <tr>
                <th>{{ request()->cookie('dev') == '1' ? 'Last Updated' : '最終更新日時' }}</th>
                <td>{{ $contactSubmission->updated_at ? $contactSubmission->updated_at->format('d/m/Y H:i') : '-' }}</td>
            </tr>
        </table>
        <div class="section-title">{{ request()->cookie('dev') == '1' ? 'Reply Logs' : '返信ログ' }}</div>

        <table>
            <thead>
                <tr>
                    <th>{{ request()->cookie('dev') == '1' ? 'Sent By' : '送信者' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Subject' : '件名' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Message' : 'メッセージ' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Attachment' : '添付ファイル' }}</th>
                    <th>{{ request()->cookie('dev') == '1' ? 'Sent At' : '送信日時' }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contactSubmission->replies as $reply)
                    <tr>
                        <td>
                            <strong>{{ $reply->admin_name ?? '-' }}</strong><br>
                            <small>{{ $reply->admin_email ?? '-' }}</small>
                        </td>
                        <td>{{ $reply->reply_subject ?? '-' }}</td>
                        <td>{{ \Illuminate\Support\Str::limit($reply->reply_message, 120) }}</td>
                        <td>
                            @if($reply->attachment_path)
                                <a href="{{ asset('storage/' . $reply->attachment_path) }}" target="_blank">
                                    {{ $reply->attachment_original_name ?? 'View file' }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            {{ $reply->sent_at ? $reply->sent_at->format('d/m/Y H:i') : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:24px;">
                            No reply logs.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


@endsection