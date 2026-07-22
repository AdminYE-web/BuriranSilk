<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\VerifyEmailCustom;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class FrontendRegistrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('customer_type', 20)->nullable();
            $table->string('last_name_kana')->nullable();
            $table->string('first_name_kana')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_name_kana')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('password');
            $table->string('status')->default('active');
            $table->boolean('term_policy')->default(false);
            $table->boolean('receive_email')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('user_contacts', function (Blueprint $table) {
            $table->id('user_contact_id');
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone', 50);
            $table->string('email');
            $table->boolean('is_main')->default(false);
            $table->boolean('receive_email')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id('user_address_id');
            $table->unsignedBigInteger('user_id');
            $table->string('address_type');
            $table->string('label')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('company_name')->nullable();
            $table->string('address');
            $table->string('apartment')->nullable();
            $table->string('country', 2)->default('JP');
            $table->string('city');
            $table->string('state');
            $table->string('zip_code', 20);
            $table->boolean('is_main')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('user_addresses');
        Schema::dropIfExists('user_contacts');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_registration_page_contains_four_steps(): void
    {
        $response = $this->get('/register');

        $response->assertOk();
        $response->assertSee('アカウント情報');
        $response->assertSee('お客様情報');
        $response->assertSee('入力内容確認');
        $response->assertSee('登録完了');
    }

    public function test_logged_in_customer_with_a_complete_profile_sees_the_customer_card(): void
    {
        $user = User::create([
            'first_name' => '太郎',
            'last_name' => '山田',
            'customer_type' => 'individual',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'タロウ',
            'name' => '山田 太郎',
            'email' => 'member@example.com',
            'phone' => '0123456789',
            'password' => 'Secret123',
            'status' => '1',
        ]);

        $user->addresses()->create([
            'address_type' => 'shipping',
            'label' => 'main',
            'first_name' => '太郎',
            'last_name' => '山田',
            'phone' => '0123456789',
            'address' => '六本木1-2-3',
            'country' => 'JP',
            'city' => '港区',
            'state' => '東京都',
            'zip_code' => '106-0032',
            'is_main' => true,
            'is_active' => true,
        ]);

        $response = $this->actingAs($user)->get(route('checkout.information'));

        $response->assertOk();
        $response->assertViewHas('showRegisteredCustomerCard', true);
        $response->assertSee('<div class="registered-customer-card">', false);
        $response->assertSee('member@example.com');
        $response->assertSee('ヤマダ タロウ');
        $response->assertSee('106-0032');
        $response->assertSee('六本木1-2-3');
        $response->assertSee('name="name"', false);
        $response->assertSee('value="山田 太郎"', false);
    }

    public function test_logged_in_customer_always_sees_the_read_only_customer_card(): void
    {
        $user = User::create([
            'first_name' => '太郎',
            'last_name' => '山田',
            'name' => '山田 太郎',
            'email' => 'incomplete@example.com',
            'phone' => '0123456789',
            'password' => 'Secret123',
            'status' => '1',
        ]);

        $response = $this->actingAs($user)->get(route('checkout.information'));

        $response->assertOk();
        $response->assertViewHas('showRegisteredCustomerCard', true);
        $response->assertSee('<div class="registered-customer-card">', false);
        $response->assertSee('incomplete@example.com');
        $response->assertSee('<dd>-</dd>', false);
        $response->assertSee('type="hidden" name="name"', false);
    }

    public function test_customer_can_register_and_is_logged_in(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'email' => 'new-customer@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
            'last_name' => '山田',
            'first_name' => '太郎',
            'phone' => '09012345678',
            'term_policy' => '1',
            'receive_email' => '1',
        ]);

        $response->assertRedirect(route('register.complete'));
        $this->assertGuest();
        $this->assertDatabaseHas('users', [
            'email' => 'new-customer@example.com',
            'first_name' => '太郎',
            'last_name' => '山田',
            'term_policy' => true,
            'status' => '2',
        ]);
        $this->assertDatabaseHas('user_contacts', [
            'email' => 'new-customer@example.com',
            'phone' => '09012345678',
            'is_main' => true,
        ]);

        Notification::assertSentTo(
            User::where('email', 'new-customer@example.com')->firstOrFail(),
            VerifyEmailCustom::class
        );
    }

    public function test_email_verification_activates_a_pending_customer(): void
    {
        $user = User::create([
            'first_name' => '山田',
            'last_name' => '太郎',
            'name' => '山田 太郎',
            'email' => 'pending-customer@example.com',
            'password' => 'Secret123',
            'status' => '2',
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(5),
            ['id' => $user->user_id, 'hash' => sha1($user->email)]
        );

        $response = $this->get($url);

        $response->assertRedirect(route('home', ['login' => 1]));
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'status' => '1',
        ]);
        $this->assertNotNull($user->fresh()->email_verified_at);
    }

    public function test_a_pending_email_address_can_be_registered_again(): void
    {
        Notification::fake();

        $pendingUser = User::create([
            'first_name' => '旧',
            'last_name' => '登録',
            'name' => '旧 登録',
            'email' => 'pending-again@example.com',
            'password' => 'Secret123',
            'status' => '2',
        ]);

        $registration = [
            'account' => [
                'email' => 'pending-again@example.com',
                'password' => 'Secret123',
                'term_policy' => true,
            ],
            'customer' => [
                'customer_type' => 'individual',
                'last_name' => '新規',
                'first_name' => '太郎',
                'last_name_kana' => 'シンキ',
                'first_name_kana' => 'タロウ',
                'company_name' => null,
                'company_name_kana' => null,
                'phone' => '09012345678',
                'postal_code' => '530-0001',
                'prefecture' => '大阪府',
                'city' => '大阪市北区',
                'address' => '梅田1丁目6-8',
                'receive_email' => false,
            ],
        ];

        $response = $this->withSession(['registration' => $registration])->post('/register');

        $response->assertRedirect(route('register.complete'));
        $this->assertDatabaseMissing('users', ['user_id' => $pendingUser->user_id]);
        $this->assertDatabaseHas('users', [
            'email' => 'pending-again@example.com',
            'first_name' => '太郎',
            'status' => '2',
        ]);
    }

    public function test_expired_email_verification_link_does_not_activate_a_customer(): void
    {
        $user = User::create([
            'first_name' => '山田',
            'last_name' => '太郎',
            'name' => '山田 太郎',
            'email' => 'expired-customer@example.com',
            'password' => 'Secret123',
            'status' => '2',
        ]);

        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->subMinute(),
            ['id' => $user->user_id, 'hash' => sha1($user->email)]
        );

        $this->get($url)->assertForbidden();
        $this->assertDatabaseHas('users', [
            'user_id' => $user->user_id,
            'status' => '2',
        ]);
    }

    public function test_registration_steps_persist_data_in_session(): void
    {
        $stepOne = $this->post('/register/step1', [
            'email' => 'session-customer@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Secret123',
            'term_policy' => '1',
        ]);

        $stepOne->assertRedirect(route('register.step2'));
        $stepOne->assertSessionHas('registration.account.email', 'session-customer@example.com');

        $stepTwoPage = $this->get('/register/step2');
        $stepTwoPage->assertOk();
        $stepTwoPage->assertSee('session-customer@example.com');
        $stepTwoPage->assertSee('お客様区分');

        $stepTwo = $this->post('/register/step2', [
            'customer_type' => 'individual',
            'last_name' => '山田',
            'first_name' => '花子',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'ハナコ',
            'phone' => '09012345678',
            'postal_code_front' => '530',
            'postal_code_back' => '0001',
            'prefecture' => '大阪府',
            'city' => '大阪市北区',
            'address' => '梅田1丁目6-8',
        ]);

        $stepTwo->assertRedirect(route('register.step3'));
        $stepTwo->assertSessionHas('registration.customer.city', '大阪市北区');
        $stepTwo->assertSessionHas('registration.customer.postal_code', '530-0001');

        $stepThree = $this->get('/register/step3');
        $stepThree->assertOk();
        $stepThree->assertSee('梅田1丁目6-8');
    }

    public function test_registration_requires_matching_password_and_policy_acceptance(): void
    {
        $response = $this->from('/register')->post('/register', [
            'email' => 'new-customer@example.com',
            'password' => 'Secret123',
            'password_confirmation' => 'Different123',
            'last_name' => '山田',
            'first_name' => '太郎',
            'phone' => '09012345678',
        ]);

        $response->assertRedirect('/register');
        $response->assertSessionHasErrors(['password', 'term_policy']);
        $this->assertGuest();
    }

    public function test_corporate_customer_requires_and_persists_company_details(): void
    {
        $session = [
            'registration' => [
                'account' => [
                    'email' => 'corporate@example.com',
                    'password' => 'Secret123',
                    'term_policy' => true,
                ],
            ],
        ];

        $invalid = $this->withSession($session)->post('/register/step2', [
            'customer_type' => 'corporate',
            'last_name' => '山田',
            'first_name' => '花子',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'ハナコ',
            'phone' => '09012345678',
            'postal_code' => '530-0001',
            'prefecture' => '大阪府',
            'city' => '大阪市北区',
            'address' => '梅田1丁目6-8',
        ]);

        $invalid->assertSessionHasErrors(['company_name', 'company_name_kana']);

        $valid = $this->withSession($session)->post('/register/step2', [
            'customer_type' => 'corporate',
            'last_name' => '山田',
            'first_name' => '花子',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'ハナコ',
            'company_name' => '株式会社タイシルク',
            'company_name_kana' => 'カブシキガイシャタイシルク',
            'phone' => '09012345678',
            'postal_code' => '530-0001',
            'prefecture' => '大阪府',
            'city' => '大阪市北区',
            'address' => '梅田1丁目6-8',
        ]);

        $valid->assertRedirect(route('register.step3'));
        $valid->assertSessionHas('registration.customer.company_name', '株式会社タイシルク');
    }

    public function test_postal_lookup_rejects_an_invalid_zipcode(): void
    {
        $response = $this->postJson('/register/postal-code', [
            'zipcode' => '123',
        ]);

        $response->assertUnprocessable();
        $response->assertJsonPath('mainArea', '');
        $response->assertJsonPath('subArea', '');
    }

    public function test_postal_lookup_uses_the_municipality_instead_of_a_small_address_component(): void
    {
        config(['services.google.geocode_key' => 'test-key']);

        Http::fake([
            'https://maps.googleapis.com/maps/api/geocode/json*' => Http::response([
                'status' => 'OK',
                'results' => [[
                    'address_components' => [
                        ['long_name' => '8', 'types' => ['sublocality_level_3', 'political']],
                        ['long_name' => '西新宿', 'types' => ['sublocality_level_1', 'sublocality', 'political']],
                        ['long_name' => '新宿区', 'types' => ['locality', 'political']],
                        ['long_name' => '東京都', 'types' => ['administrative_area_level_1', 'political']],
                    ],
                ]],
            ]),
        ]);

        $response = $this->postJson('/register/postal-code', [
            'zipcode' => '1638001',
        ]);

        $response->assertOk();
        $response->assertJsonPath('mainArea', '東京都');
        $response->assertJsonPath('subArea', '新宿区');
    }
}
