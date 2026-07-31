<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderCustomer;
use App\Models\OrderItem;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class OrderTrackingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->string('order_no')->unique();
            $table->integer('total_quantity')->default(0);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('vat_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->string('status')->default('pending');
            $table->string('order_status')->default('order_pending');
            $table->string('payment_status')->default('pending');
            $table->timestamp('shipping_date')->nullable();
            $table->json('checkout_data')->nullable();
            $table->timestamps();
        });

        Schema::create('order_customers', function (Blueprint $table) {
            $table->id('order_customer_id');
            $table->unsignedBigInteger('order_id');
            $table->string('personal_name')->nullable();
            $table->string('personal_email')->nullable();
            $table->string('personal_phone')->nullable();
            $table->string('personal_postcode')->nullable();
            $table->string('personal_province')->nullable();
            $table->string('personal_city')->nullable();
            $table->string('personal_area')->nullable();
            $table->boolean('same_as_customer')->default(true);
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id('order_item_id');
            $table->unsignedBigInteger('order_id');
            $table->string('product_name')->nullable();
            $table->string('product_name_snapshot')->nullable();
            $table->string('product_image')->nullable();
            $table->integer('qty')->default(1);
            $table->decimal('item_total', 12, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('order_item_options', function (Blueprint $table) {
            $table->id('order_item_option_id');
            $table->unsignedBigInteger('order_item_id');
            $table->string('group_name_snapshot')->nullable();
            $table->string('option_name_snapshot')->nullable();
            $table->text('custom_value')->nullable();
            $table->timestamps();
        });

        Schema::create('order_payments', function (Blueprint $table) {
            $table->id('order_payment_id');
            $table->unsignedBigInteger('order_id');
            $table->string('payment_status')->default('pending');
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('order_payments');
        Schema::dropIfExists('order_item_options');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('order_customers');
        Schema::dropIfExists('orders');
        parent::tearDown();
    }

    public function test_tracking_form_is_public(): void
    {
        $this->get('/track-order')->assertOk()->assertSee('ご注文の追跡');
    }

    public function test_customer_can_track_an_order_with_matching_email(): void
    {
        $order = $this->createOrder();

        $this->post('/track-order', [
            'order_no' => $order->order_no,
            'email' => 'CUSTOMER@example.com',
        ])->assertOk()
            ->assertSee('注文状況')
            ->assertSee('ORD-TRACK-1001')
            ->assertSee('シルクネックストラップ')
            ->assertSee('生産')
            ->assertSee('--progress-width: 40%', false);
    }

    public function test_tracking_does_not_reveal_an_order_for_a_wrong_email(): void
    {
        $this->createOrder();

        $this->followingRedirects()->post('/track-order', [
            'order_no' => 'ORD-TRACK-1001',
            'email' => 'wrong@example.com',
        ])->assertOk()
            ->assertSee('注文番号またはメールアドレスが正しくありません')
            ->assertDontSee('シルクネックストラップ');
    }

    private function createOrder(): Order
    {
        $order = Order::create([
            'order_no' => 'ORD-TRACK-1001',
            'total_quantity' => 100,
            'subtotal' => 12000,
            'shipping_fee' => 500,
            'tax_amount' => 1250,
            'grand_total' => 13750,
            'status' => 'pending',
            'order_status' => 'production',
            'payment_status' => 'paid',
        ]);

        OrderCustomer::create([
            'order_id' => $order->order_id,
            'personal_name' => '山田 太郎',
            'personal_email' => 'customer@example.com',
            'personal_phone' => '090-1234-5678',
        ]);

        OrderItem::create([
            'order_id' => $order->order_id,
            'product_name' => 'シルクネックストラップ',
            'qty' => 100,
            'item_total' => 12000,
        ]);

        return $order;
    }
}
