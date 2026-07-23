<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserOrderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id('user_id');
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('status')->default('1');
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id('order_id');
                $table->string('order_no')->unique();
                $table->unsignedBigInteger('user_id');
                $table->decimal('grand_total', 12, 2)->default(0);
                $table->string('status')->default('pending');
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id('order_item_id');
                $table->unsignedBigInteger('order_id');
                $table->string('product_name');
                $table->string('product_name_snapshot')->nullable();
                $table->string('product_image')->nullable();
                $table->integer('qty')->default(1);
                $table->decimal('item_total', 12, 2)->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('order_item_options')) {
            Schema::create('order_item_options', function (Blueprint $table) {
                $table->id('order_item_option_id');
                $table->unsignedBigInteger('order_item_id');
                $table->string('group_name_snapshot')->nullable();
                $table->string('option_name_snapshot')->nullable();
                $table->text('custom_value')->nullable();
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('order_item_options');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('users');
        parent::tearDown();
    }

    public function test_authenticated_user_can_view_orders_page(): void
    {
        $user = User::create([
            'email' => 'order_test@example.com',
            'password' => bcrypt('password'),
        ]);

        $order = Order::create([
            'order_no' => 'ORD-10001',
            'user_id' => $user->user_id,
            'grand_total' => 5000,
            'status' => 'pending',
        ]);

        OrderItem::create([
            'order_id' => $order->order_id,
            'product_name' => 'Silk Lanyard 01',
            'qty' => 100,
            'item_total' => 5000,
        ]);

        $response = $this->actingAs($user)->get('/account/orders');

        $response->assertOk();
        $response->assertSee('注文履歴');
        $response->assertSee('ORD-10001');
        $response->assertSee('Silk Lanyard 01');
    }

    public function test_user_can_filter_orders_by_status(): void
    {
        $user = User::create([
            'email' => 'order_filter@example.com',
            'password' => bcrypt('password'),
        ]);

        $order1 = Order::create([
            'order_no' => 'ORD-PENDING',
            'user_id' => $user->user_id,
            'grand_total' => 2000,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order1->order_id,
            'product_name' => 'Pending Item',
            'qty' => 1,
            'item_total' => 2000,
        ]);

        $order2 = Order::create([
            'order_no' => 'ORD-SHIPPED',
            'user_id' => $user->user_id,
            'grand_total' => 3000,
            'status' => 'shipped',
        ]);
        OrderItem::create([
            'order_id' => $order2->order_id,
            'product_name' => 'Shipped Item',
            'qty' => 1,
            'item_total' => 3000,
        ]);

        $response = $this->actingAs($user)->get('/account/orders?status=shipped');

        $response->assertOk();
        $response->assertSee('ORD-SHIPPED');
        $response->assertDontSee('ORD-PENDING');
    }
}
