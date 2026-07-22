<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CartPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('status')->default('1');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('product_option_assignments', function (Blueprint $table) {
            $table->id('assignment_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('option_id');
            $table->boolean('is_active')->default(true);
            $table->string('qty_rule_type')->nullable();
            $table->integer('min_qty')->nullable();
            $table->integer('max_qty')->nullable();
            $table->integer('exact_qty')->nullable();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('product_option_assignments');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_cart_page_can_be_opened_with_an_empty_session(): void
    {
        $response = $this->get('/cart');

        $response->assertOk();
        $response->assertSee('ショッピングカート');
        $response->assertSee('カートに商品は入っていません。');
    }

    public function test_checkout_button_skips_account_choice_for_a_logged_in_customer(): void
    {
        $user = User::create([
            'name' => 'Test Customer',
            'email' => 'member@example.com',
            'password' => 'Secret123',
            'status' => '1',
        ]);

        $response = $this->actingAs($user)->withSession([
            'cart.items' => [
                'test-item' => $this->cartItem(),
            ],
        ])->get('/cart');

        $response->assertOk();
        $response->assertSee('href="'.route('checkout.information').'"', false);
    }

    public function test_checkout_button_keeps_account_choice_for_a_guest(): void
    {
        $response = $this->withSession([
            'cart.items' => [
                'test-item' => $this->cartItem(),
            ],
        ])->get('/cart');

        $response->assertOk();
        $response->assertSee('href="'.route('checkout.index').'"', false);
    }

    public function test_cart_quantity_can_be_updated_and_price_is_recalculated(): void
    {
        $response = $this->withSession([
            'cart.items' => [
                'test-item' => $this->cartItem(),
            ],
        ])->patch('/cart/items/test-item', [
            'quantity' => 3,
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('cart.items.test-item.quantity', 3);
        $response->assertSessionHas('cart.items.test-item.option_subtotal', 60);
        $response->assertSessionHas('cart.items.test-item.line_subtotal', 360);
    }

    public function test_cart_item_can_be_removed(): void
    {
        $response = $this->withSession([
            'cart.items' => [
                'test-item' => $this->cartItem(),
            ],
        ])->delete('/cart/items/test-item');

        $response->assertRedirect('/cart');
        $response->assertSessionMissing('cart.items.test-item');
    }

    public function test_cart_edit_link_returns_to_the_same_product_with_item_id(): void
    {
        $response = $this->withSession([
            'cart.items' => [
                'test-item' => $this->cartItem(),
            ],
        ])->get('/cart');

        $response->assertOk();
        $response->assertSee('/products/test-product?edit_cart=test-item', false);
    }

    public function test_cart_quantity_ajax_response_contains_updated_totals(): void
    {
        $response = $this->withSession([
            'cart.items' => [
                'test-item' => $this->cartItem(),
            ],
        ])->patchJson('/cart/items/test-item', [
            'quantity' => 3,
        ]);

        $response->assertOk();
        $response->assertJsonPath('item.quantity', 3);
        $response->assertJsonPath('item.line_subtotal', 360);
        $response->assertJsonPath('summary.subtotal', 360);
        $response->assertJsonPath('summary.shipping', 800);
        $response->assertJsonPath('summary.vat', 116);
        $response->assertJsonPath('summary.total', 1276);
    }

    public function test_cart_uses_current_quantity_range_from_product_option_assignments(): void
    {
        DB::table('product_option_assignments')->insert([
            'product_id' => 1,
            'option_id' => 10,
            'is_active' => 1,
            'qty_rule_type' => 'range',
            'min_qty' => 2,
            'max_qty' => 4,
            'exact_qty' => null,
        ]);

        $page = $this->withSession([
            'cart.items' => [
                'test-item' => $this->cartItem(),
            ],
        ])->get('/cart');

        $page->assertOk();
        $page->assertSee('min="2"', false);
        $page->assertSee('max="4"', false);
        $page->assertSee('ご注文数量は2〜4個です。');

        $response = $this->patchJson('/cart/items/test-item', [
            'quantity' => 5,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('quantity');
    }

    public function test_cart_uses_exact_quantity_from_product_option_assignments(): void
    {
        DB::table('product_option_assignments')->insert([
            'product_id' => 1,
            'option_id' => 10,
            'is_active' => 1,
            'qty_rule_type' => 'exact',
            'min_qty' => null,
            'max_qty' => null,
            'exact_qty' => 8,
        ]);

        $page = $this->withSession([
            'cart.items' => [
                'test-item' => $this->cartItem(),
            ],
        ])->get('/cart');

        $page->assertOk();
        $page->assertSee('min="8"', false);
        $page->assertSee('max="8"', false);
        $page->assertSee('数量は8個に指定されています。');

        $response = $this->patchJson('/cart/items/test-item', [
            'quantity' => 7,
        ]);

        $response->assertUnprocessable();
        $response->assertJsonValidationErrors('quantity');
    }

    private function cartItem(): array
    {
        return [
            'id' => 'test-item',
            'product_id' => 1,
            'product_slug' => 'test-product',
            'product_name' => 'Test product',
            'image' => null,
            'quantity' => 1,
            'base_unit_price' => 100,
            'base_subtotal' => 100,
            'selected_options' => [
                [
                    'option_id' => 10,
                    'group_name' => 'Shape',
                    'option_name' => 'Vertical',
                    'option_detail' => null,
                    'additional_price' => 20,
                    'price_type' => 'per_item',
                    'free_from_qty' => null,
                    'line_price' => 20,
                    'quantity_rule' => [
                        'type' => null,
                        'min' => null,
                        'max' => null,
                        'exact' => null,
                    ],
                ],
            ],
            'option_subtotal' => 20,
            'line_subtotal' => 120,
            'previous_order_numbers' => [],
            'font_entries' => [],
            'artworks' => [],
            'custom_fields' => [],
        ];
    }
}
