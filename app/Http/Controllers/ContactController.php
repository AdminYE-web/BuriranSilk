<?php

namespace App\Http\Controllers;

use App\Mail\ContactConfirmationMail;
use App\Models\ContactSubmission;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContactController extends Controller
{
    private const INQUIRY_TYPES = [
        'delivery' => '配送について',
        'estimate' => 'お見積もりについて',
        'design' => 'デザインについて',
        'order' => 'ご注文について',
        'delivery_schedule' => '納期・配送について',
        'defective_return' => '不良・返品について',
        'general' => '一般のお問い合わせ',
        'other' => 'その他',
    ];

    public function index(): View
    {
        $faqs = Schema::hasTable('faqs')
            ? Faq::query()
                ->where('status', 'show')
                ->where('show_main', 1)
                ->orderBy('sort_order')
                ->orderBy('faq_id')
                ->limit(4)
                ->get()
            : collect();

        if ($faqs->isEmpty()) {
            $faqs = collect([
                ['question' => '梱包について', 'answer' => '商品や数量に合わせて、安全にお届けできるよう丁寧に梱包いたします。'],
                ['question' => 'デザインの修正について', 'answer' => '製作開始前のデザイン修正については、お問い合わせフォームよりご相談ください。'],
                ['question' => '注文後の変更について', 'answer' => '製作状況により変更できない場合があります。注文番号を添えてお早めにご連絡ください。'],
                ['question' => '配送について', 'answer' => '発送時にお送りするご案内メールから配送状況をご確認いただけます。'],
            ]);
        }

        return view('frontend.contact.index', [
            'faqs' => $faqs,
            'inquiryTypes' => self::INQUIRY_TYPES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255'],
            'inquiry_type' => ['required', Rule::in(array_keys(self::INQUIRY_TYPES))],
            'order_number' => ['nullable', 'string', 'max:100'],
            'message' => ['required', 'string', 'max:5000'],
            'privacy' => ['accepted'],
            'website' => ['nullable', 'prohibited'],
        ], [
            'required' => ':attributeを入力してください。',
            'email' => 'メールアドレスの形式が正しくありません。',
            'in' => ':attributeの選択内容が正しくありません。',
            'accepted' => 'プライバシーポリシーへの同意が必要です。',
            'max.string' => ':attributeは:max文字以内で入力してください。',
        ], [
            'last_name' => '姓',
            'first_name' => '名',
            'email' => 'メールアドレス',
            'inquiry_type' => 'お問い合わせ種別',
            'order_number' => '注文番号',
            'message' => 'お問い合わせ内容',
        ]);

        $submission = ContactSubmission::query()->create([
            'contact_method' => 'email',
            'subject' => self::INQUIRY_TYPES[$validated['inquiry_type']],
            'order_number' => $validated['order_number'] ?? null,
            'name' => trim($validated['last_name'].' '.$validated['first_name']),
            'email' => $validated['email'],
            'message' => $validated['message'],
            'ip_address' => $request->ip(),
            'status_reply' => 'pending',
        ]);

        $emailSent = false;

        try {
            Mail::to($submission->email)->send(new ContactConfirmationMail($submission));
            $emailSent = true;
        } catch (\Throwable $exception) {
            Log::warning('Contact confirmation email could not be sent.', [
                'contact_submission_id' => $submission->id,
                'message' => $exception->getMessage(),
            ]);
        }

        return redirect()
            ->route('contact.complete')
            ->with('contact_completed', true)
            ->with('contact_email_sent', $emailSent);
    }

    public function complete(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('contact_completed')) {
            return redirect()->route('contact.index');
        }

        return view('frontend.contact.complete', [
            'emailSent' => (bool) $request->session()->get('contact_email_sent'),
        ]);
    }
}
