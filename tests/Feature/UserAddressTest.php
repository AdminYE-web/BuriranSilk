<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserAddressTest extends TestCase
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

        if (! Schema::hasTable('user_addresses')) {
            Schema::create('user_addresses', function (Blueprint $table) {
                $table->id('user_address_id');
                $table->unsignedBigInteger('user_id');
                $table->string('address_type')->default('shipping');
                $table->string('label');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('phone');
                $table->string('company_name')->nullable();
                $table->string('address');
                $table->string('apartment')->nullable();
                $table->string('country');
                $table->string('city');
                $table->string('state');
                $table->string('zip_code');
                $table->boolean('is_main')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('users');
        parent::tearDown();
    }

    public function test_authenticated_user_can_view_shipping_addresses(): void
    {
        $user = User::create([
            'email' => 'address_test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/account/addresses/shipping');

        $response->assertOk();
        $response->assertSee('お届け先住所一覧');
    }

    public function test_user_can_create_shipping_address(): void
    {
        $user = User::create([
            'email' => 'create_address@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->post('/account/addresses/shipping', [
            'label' => 'Home',
            'first_name' => 'Taro',
            'last_name' => 'Yamada',
            'phone' => '09012345678',
            'address' => '1-2-3 Roppongi',
            'country' => 'Japan',
            'city' => 'Minato-ku',
            'state' => 'Tokyo',
            'zip_code' => '1060032',
            'is_main' => '1',
        ]);

        $response->assertRedirect('/account/addresses/shipping');
        $this->assertDatabaseHas('user_addresses', [
            'user_id' => $user->user_id,
            'address_type' => 'shipping',
            'label' => 'Home',
            'city' => 'Minato-ku',
            'is_main' => 1,
        ]);
    }

    public function test_user_can_set_main_address(): void
    {
        $user = User::create([
            'email' => 'main_address@example.com',
            'password' => bcrypt('password'),
        ]);

        $addr1 = UserAddress::create([
            'user_id' => $user->user_id,
            'address_type' => 'billing',
            'label' => 'Office',
            'first_name' => 'Office',
            'last_name' => 'User',
            'phone' => '09011111111',
            'address' => 'Addr 1',
            'country' => 'Japan',
            'city' => 'Tokyo',
            'state' => 'Tokyo',
            'zip_code' => '1000001',
            'is_main' => true,
        ]);

        $addr2 = UserAddress::create([
            'user_id' => $user->user_id,
            'address_type' => 'billing',
            'label' => 'Home',
            'first_name' => 'Home',
            'last_name' => 'User',
            'phone' => '09022222222',
            'address' => 'Addr 2',
            'country' => 'Japan',
            'city' => 'Tokyo',
            'state' => 'Tokyo',
            'zip_code' => '1000002',
            'is_main' => false,
        ]);

        $response = $this->actingAs($user)->put('/account/addresses/' . $addr2->user_address_id . '/set-main');

        $response->assertRedirect('/account/addresses/billing');
        $this->assertTrue($addr2->fresh()->is_main);
        $this->assertFalse($addr1->fresh()->is_main);
    }
}
