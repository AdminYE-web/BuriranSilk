<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FaqPageController extends Controller
{
    private const PER_PAGE = 10;

    public function index(): View
    {
        if (Schema::hasTable('faqs')) {
            $faqs = Faq::query()
                ->where('status', 'show')
                ->where('show_main', 1)
                ->orderBy('sort_order')
                ->orderBy('faq_id')
                ->paginate(self::PER_PAGE);
        } else {
            $faqs = $this->fallbackFaqs();
        }

        if ($faqs->isEmpty() && $faqs->currentPage() === 1) {
            $faqs = $this->fallbackFaqs();
        }

        return view('frontend.faq.index', compact('faqs'));
    }

    private function fallbackFaqs(): LengthAwarePaginator
    {
        $items = new Collection([
            ['question' => '梱包について', 'answer' => '商品や数量に合わせて、破損のないよう丁寧に梱包してお届けします。'],
            ['question' => 'デザインの修正について', 'answer' => '製作開始前のデザイン修正については、お問い合わせフォームよりご相談ください。'],
            ['question' => '注文後の変更について', 'answer' => '製作状況により変更できない場合がございます。注文番号を添えてお早めにご連絡ください。'],
            ['question' => '配送について', 'answer' => '発送後にお送りするご案内メールから配送状況をご確認いただけます。'],
            ['question' => '納期について', 'answer' => 'ご注文内容と数量により納期が異なります。詳しい予定日はご注文確定後にご案内します。'],
            ['question' => 'お支払いについて', 'answer' => 'ご利用いただけるお支払い方法は、ご注文手続きの画面でご確認いただけます。'],
            ['question' => 'キャンセルについて', 'answer' => '製作開始後のキャンセルは承れない場合がございます。お早めにお問い合わせください。'],
            ['question' => '返品・交換について', 'answer' => '商品に不備があった場合は、到着後すみやかに商品の状態が分かる写真を添えてご連絡ください。'],
            ['question' => '領収書について', 'answer' => '領収書をご希望の場合は、ご注文時またはお問い合わせフォームよりお申し付けください。'],
            ['question' => '大量注文について', 'answer' => '数量の多いご注文や法人のお客様向けのお見積もりも承っております。お気軽にご相談ください。'],
        ]);

        return new LengthAwarePaginator(
            $items,
            $items->count(),
            self::PER_PAGE,
            1,
            ['path' => route('faq.index')],
        );
    }
}
