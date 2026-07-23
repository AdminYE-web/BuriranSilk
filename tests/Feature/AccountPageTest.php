<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccountPageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id('user_id');
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->string('password');
                $table->string('avatar')->nullable();
                $table->string('status')->default('1');
                $table->timestamp('email_verified_at')->nullable();
                $table->timestamp('last_login_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('users');
        parent::tearDown();
    }

    public function test_unauthenticated_user_is_redirected_from_account(): void
    {
        $response = $this->get('/account');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_account_page(): void
    {
        $user = User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'status' => '1',
        ]);

        $response = $this->actingAs($user)->get('/account');

        $response->assertOk();
        $response->assertSee('マイアカウント');
        $response->assertSee('John Doe');
    }

    public function test_header_renders_account_link_when_authenticated(): void
    {
        $user = User::create([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => bcrypt('password123'),
            'status' => '1',
        ]);

        $response = $this->actingAs($user)->get('/cart');

        $response->assertOk();
        $response->assertSee(route('account.index'), false);
    }
}
