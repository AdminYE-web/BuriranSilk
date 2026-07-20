<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FrontendLoginModalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('status')->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_header_contains_the_login_modal(): void
    {
        $response = $this->get('/cart');

        $response->assertOk();
        $response->assertSee('data-login-modal-open', false);
        $response->assertSee('id="accountLoginModal"', false);
        $response->assertSee('サインイン');
    }

    public function test_user_can_login_from_the_modal(): void
    {
        $user = User::create([
            'email' => 'customer@example.com',
            'password' => 'secret-password',
        ]);

        $response = $this->from('/cart')->post('/login', [
            'email' => 'customer@example.com',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect('/cart');
        $this->assertAuthenticatedAs($user);
    }

    public function test_failed_login_reopens_the_modal_with_an_error(): void
    {
        $response = $this->from('/cart')->post('/login', [
            'email' => 'nobody@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/cart');
        $response->assertSessionHas('open_login_modal', true);
        $response->assertSessionHasErrors('email', null, 'login');
    }
}
