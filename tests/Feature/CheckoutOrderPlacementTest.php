<?php

namespace Tests\Feature;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CheckoutOrderPlacementTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('order_no')->unique();
            $table->uuid('checkout_token')->nullable()->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->integer('total_items')->default(0);
            $table->integer('total_quantity')->default(0);
            $table->integer('qty');
            $table->decimal('base_unit_price', 10, 2);
            $table->decimal('option_total', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('vat_amount', 10, 2)->default(0);
            $table->decimal('grand_total', 10, 2)->default(0);
            $table->string('currency')->default('JPY');
            $table->string('status')->default('pending');
            $table->string('order_status')->default('pending');
            $table->string('payment_status')->default('pending');
            $table->string('info_method')->nullable();
            $table->text('signature_text')->nullable();
            $table->string('delivery_option')->nullable();
            $table->boolean('publish_website')->nullable();
            $table->boolean('newsletter')->default(false);
            $table->text('notes')->nullable();
            $table->json('checkout_data')->nullable();
            $table->timestamp('shipping_date')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('confirmation_email_sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_customers', function (Blueprint $table) {
            $table->id('order_customer_id');
            $table->unsignedBigInteger('order_id');
            foreach ([
                'customer_type', 'personal_name', 'personal_name_kana', 'company_name',
                'company_name_kana', 'personal_first_name', 'personal_last_name',
                'personal_phone', 'personal_email', 'personal_postcode', 'personal_province',
                'personal_city', 'personal_area', 'shipping_name', 'shipping_name_kana',
                'shipping_postcode', 'shipping_province', 'shipping_city', 'shipping_area',
                'shipping_district', 'shipping_subdistrict', 'shipping_building_room',
                'billing_address_type', 'billing_name', 'billing_name_kana',
                'billing_first_name', 'billing_last_name', 'billing_phone', 'billing_email',
                'billing_postcode', 'billing_province', 'billing_city', 'billing_area',
                'billing_district', 'billing_subdistrict', 'billing_building_room',
            ] as $column) {
                $table->string($column)->nullable();
            }
            $table->boolean('same_as_customer')->default(true);
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name')->nullable();
            $table->string('product_name_snapshot')->nullable();
            $table->string('product_image')->nullable();
            $table->integer('qty');
            $table->integer('quantity');
            $table->unsignedBigInteger('price_rule_id')->nullable();
            $table->string('price_rule_name')->nullable();
            $table->decimal('base_unit_price', 10, 2);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('base_total', 10, 2);
            $table->decimal('product_total', 10, 2);
            $table->decimal('option_total', 10, 2);
            $table->decimal('item_total', 10, 2);
            $table->json('options')->nullable();
            $table->json('custom_colors')->nullable();
            $table->json('configuration')->nullable();
            $table->timestamps();
        });

        Schema::create('order_item_options', function (Blueprint $table) {
            $table->id('order_item_option_id');
            $table->unsignedBigInteger('order_item_id');
            $table->unsignedBigInteger('option_group_id')->nullable();
            $table->unsignedBigInteger('option_id')->nullable();
            $table->string('group_name_snapshot');
            $table->string('option_name_snapshot')->nullable();
            $table->decimal('additional_price', 10, 2)->default(0);
            $table->string('price_type')->default('per_item');
            $table->text('custom_value')->nullable();
            $table->decimal('total_price', 10, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('order_payments', function (Blueprint $table) {
            $table->id('order_payment_id');
            $table->unsignedBigInteger('order_id');
            $table->string('transaction_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('currency')->default('JPY');
            $table->timestamp('paid_at')->nullable();
            $table->json('payment_response')->nullable();
            $table->timestamps();
        });

        Schema::create('order_artworks', function (Blueprint $table) {
            $table->id('order_artwork_id');
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('cart_item_id')->nullable();
            $table->string('file_path')->nullable();
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->boolean('no_artwork')->default(false);
            $table->text('print_text')->nullable();
            $table->string('font_option')->nullable();
            $table->string('font_other')->nullable();
            $table->unsignedBigInteger('template_id')->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        foreach (['order_artworks', 'order_payments', 'order_item_options', 'order_items', 'order_customers', 'orders'] as $table) {
            Schema::dropIfExists($table);
        }

        parent::tearDown();
    }

    public function test_confirmed_checkout_is_saved_and_confirmation_email_is_sent(): void
    {
        Mail::fake();
        $token = 'b95a5774-34c7-45a8-b447-c120d4038627';

        $response = $this->withSession([
            'checkout.token' => $token,
            'checkout.customer' => $this->customer(),
            'cart.items' => ['cart-one' => $this->cartItem()],
        ])->post(route('checkout.orders.store'), [
            'checkout_token' => $token,
        ]);

        $response->assertRedirect(route('checkout.complete'));
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'subtotal' => 240,
            'shipping_fee' => 800,
            'vat_amount' => 104,
            'grand_total' => 1144,
            'order_status' => 'order_pending',
        ]);
        $this->assertDatabaseHas('order_customers', [
            'personal_name' => 'テスト太郎',
            'shipping_postcode' => '100-0001',
            'billing_address_type' => 'same_as_customer',
        ]);
        $this->assertDatabaseHas('order_items', [
            'product_name_snapshot' => 'Test Product',
            'quantity' => 2,
            'item_total' => 240,
        ]);
        $this->assertDatabaseHas('order_item_options', [
            'group_name_snapshot' => '形状',
            'option_name_snapshot' => '縦型',
            'total_price' => 40,
        ]);
        $this->assertDatabaseHas('order_payments', [
            'payment_method' => 'bank_transfer',
            'payment_status' => 'pending',
            'amount' => 1144,
        ]);
        $response->assertSessionMissing('cart.items');

        Mail::assertSent(OrderConfirmationMail::class, fn ($mail) => $mail->hasTo('customer@example.com'));

        $order = Order::query()
            ->with(['customer', 'items.optionDetails', 'payment', 'artworks'])
            ->firstOrFail();

        $adminHtml = view('admin.orders.show', compact('order'))->render();
        $this->assertStringContainsString('お客様情報', $adminHtml);
        $this->assertStringContainsString('請求先情報', $adminHtml);
        $this->assertStringContainsString('選択オプション', $adminHtml);
        $this->assertStringContainsString('その他情報', $adminHtml);
        $this->assertStringContainsString('/storage/products/test.png', $adminHtml);
        $this->assertStringNotContainsString('/storage/storage/', $adminHtml);
        $this->assertStringContainsString('印刷文字・書体', $adminHtml);
        $this->assertStringContainsString('test1', $adminHtml);
        $this->assertStringContainsString('font1', $adminHtml);
        $this->assertStringNotContainsString('&quot;font_entries&quot;', $adminHtml);

        $emailHtml = (new OrderConfirmationMail($order))->render();
        $this->assertStringContainsString('Test Product', $emailHtml);
        $this->assertStringContainsString('1,144', $emailHtml);

        $complete = $this->get(route('checkout.complete'));
        $complete->assertOk()->assertSee('ご注文ありがとうございます');
    }

    public function test_information_page_does_not_advance_when_required_fields_are_missing(): void
    {
        $customer = $this->customer();
        unset($customer['address']);

        $response = $this->withSession([
            'cart.items' => ['cart-one' => $this->cartItem()],
        ])->post(route('checkout.confirmation'), $customer);

        $response
            ->assertRedirect(route('checkout.information'))
            ->assertSessionHasErrors(['address'])
            ->assertSessionHasInput('name', 'テスト太郎');
        $this->assertNull(session('checkout.token'));
    }

    private function customer(): array
    {
        return [
            'customer_type' => 'individual',
            'name' => 'テスト太郎',
            'name_kana' => 'テストタロウ',
            'email' => 'customer@example.com',
            'phone' => '09012345678',
            'info_method' => '詳細情報を全て入力する',
            'postal_code_front' => '100',
            'postal_code_back' => '0001',
            'prefecture' => '東京都',
            'city' => '千代田区',
            'address' => '千代田1-1',
            'same_as_customer' => '1',
            'billing_address_type' => 'same_as_customer',
            'payment_method' => 'bank_transfer',
            'publish_website' => 'yes',
            'notes' => 'テスト注文です。',
        ];
    }

    private function cartItem(): array
    {
        return [
            'id' => 'cart-one',
            'product_id' => 1,
            'product_name' => 'Test Product',
            'image' => 'storage/products/test.png',
            'quantity' => 2,
            'base_unit_price' => 100,
            'base_subtotal' => 200,
            'option_subtotal' => 40,
            'line_subtotal' => 240,
            'selected_options' => [[
                'group_id' => 5,
                'option_id' => 10,
                'group_name' => '形状',
                'option_name' => '縦型',
                'option_detail' => null,
                'additional_price' => 20,
                'price_type' => 'per_item',
                'line_price' => 40,
            ]],
            'previous_order_numbers' => [],
            'font_entries' => [
                '6' => [[
                    'text' => 'test1',
                    'font' => 'font1',
                    'size' => 12,
                ]],
            ],
            'artworks' => [],
            'custom_fields' => [],
        ];
    }
}
