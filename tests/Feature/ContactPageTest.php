<?php

namespace Tests\Feature;

use App\Mail\ContactConfirmationMail;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ContactPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('faqs', function (Blueprint $table) {
            $table->id('faq_id');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('language', 20)->default('ja');
            $table->string('question');
            $table->longText('answer')->nullable();
            $table->tinyInteger('show_main')->default(0);
            $table->tinyInteger('show_product')->default(0);
            $table->string('status')->default('show');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->string('contact_method', 20);
            $table->string('subject', 100);
            $table->string('order_number', 100)->nullable();
            $table->string('name');
            $table->string('email');
            $table->string('line_id')->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('message');
            $table->string('status_reply', 20)->default('pending');
            $table->string('attachment_path')->nullable();
            $table->string('attachment_original_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('contact_submissions');
        Schema::dropIfExists('faqs');

        parent::tearDown();
    }

    public function test_contact_page_shows_faq_and_form(): void
    {
        $response = $this->get(route('contact.index'));

        $response->assertOk();
        $response->assertSee('お問い合わせ');
        $response->assertSee('よくある質問');
        $response->assertSee('梱包について');
        $response->assertSee('お問い合わせフォーム');
        $response->assertSee('name="order_number"', false);
        $response->assertSee('お見積もりについて');
        $response->assertSee('納期・配送について');
        $response->assertSee('不良・返品について');
        $response->assertSee('一般のお問い合わせ');
    }

    public function test_contact_form_is_saved_and_confirmation_email_is_sent(): void
    {
        Mail::fake();

        $response = $this->post(route('contact.store'), [
            'last_name' => '山田',
            'first_name' => '太郎',
            'email' => 'customer@example.com',
            'inquiry_type' => 'delivery',
            'order_number' => 'ODR-10001',
            'message' => '配送予定日を教えてください。',
            'privacy' => '1',
        ]);

        $response->assertRedirect(route('contact.complete'));
        $response->assertSessionHas('contact_completed', true);
        $this->assertDatabaseHas('contact_submissions', [
            'name' => '山田 太郎',
            'email' => 'customer@example.com',
            'subject' => '配送について',
            'order_number' => 'ODR-10001',
            'status_reply' => 'pending',
        ]);
        Mail::assertSent(ContactConfirmationMail::class, function (ContactConfirmationMail $mail) {
            return $mail->hasTo('customer@example.com');
        });

        $complete = $this->get(route('contact.complete'));
        $complete->assertOk();
        $complete->assertSee('お問い合わせありがとうございました');
        $complete->assertSee('商品一覧へ');
        $complete->assertSee('トップページへ');
    }

    public function test_contact_complete_page_cannot_be_opened_directly(): void
    {
        $this->get(route('contact.complete'))
            ->assertRedirect(route('contact.index'));
    }

    public function test_contact_form_requires_mandatory_fields_and_privacy_consent(): void
    {
        $response = $this->from(route('contact.index'))->post(route('contact.store'), []);

        $response->assertRedirect(route('contact.index'));
        $response->assertSessionHasErrors([
            'last_name',
            'first_name',
            'email',
            'inquiry_type',
            'message',
            'privacy',
        ]);
        $this->assertDatabaseCount('contact_submissions', 0);
    }
}
