<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\UserContact;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserContactTest extends TestCase
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

        if (! Schema::hasTable('user_contacts')) {
            Schema::create('user_contacts', function (Blueprint $table) {
                $table->id('user_contact_id');
                $table->unsignedBigInteger('user_id');
                $table->string('first_name');
                $table->string('last_name');
                $table->string('phone');
                $table->string('email');
                $table->boolean('is_main')->default(false);
                $table->boolean('receive_email')->default(false);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('user_contacts');
        Schema::dropIfExists('users');
        parent::tearDown();
    }

    public function test_authenticated_user_can_view_contacts_page(): void
    {
        $user = User::create([
            'email' => 'contact_test@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->get('/account/contacts');

        $response->assertOk();
        $response->assertSee('連絡先情報');
    }

    public function test_user_can_create_contact(): void
    {
        $user = User::create([
            'email' => 'create_contact@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->actingAs($user)->post('/account/contacts', [
            'first_name' => 'Alice',
            'last_name' => 'Smith',
            'phone' => '0812345678',
            'email' => 'alice@example.com',
            'is_main' => '1',
        ]);

        $response->assertRedirect('/account/contacts');
        $this->assertDatabaseHas('user_contacts', [
            'user_id' => $user->user_id,
            'first_name' => 'Alice',
            'email' => 'alice@example.com',
            'is_main' => 1,
        ]);
    }

    public function test_user_can_set_main_contact(): void
    {
        $user = User::create([
            'email' => 'main_contact@example.com',
            'password' => bcrypt('password'),
        ]);

        $contact1 = UserContact::create([
            'user_id' => $user->user_id,
            'first_name' => 'Contact 1',
            'last_name' => 'User',
            'phone' => '0811111111',
            'email' => 'c1@example.com',
            'is_main' => true,
        ]);

        $contact2 = UserContact::create([
            'user_id' => $user->user_id,
            'first_name' => 'Contact 2',
            'last_name' => 'User',
            'phone' => '0822222222',
            'email' => 'c2@example.com',
            'is_main' => false,
        ]);

        $response = $this->actingAs($user)->put('/account/contacts/' . $contact2->user_contact_id . '/set-main');

        $response->assertRedirect('/account/contacts');
        $this->assertTrue($contact2->fresh()->is_main);
        $this->assertFalse($contact1->fresh()->is_main);
    }
}
