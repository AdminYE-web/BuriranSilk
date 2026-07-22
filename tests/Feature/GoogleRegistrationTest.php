<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleRegistrationTest extends TestCase
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
            $table->string('avatar')->nullable();
            $table->string('status')->default('1');
            $table->string('identify_id')->nullable();
            $table->unsignedTinyInteger('social_type')->nullable();
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

    public function test_new_google_customer_continues_to_step_two_and_completes_registration(): void
    {
        Notification::fake();
        $this->fakeGoogleUser();

        $callback = $this->get(route('google.callback'));

        $callback->assertRedirect(route('register.step2'));
        $callback->assertSessionHas('registration.account.email', 'google-customer@example.com');
        $callback->assertSessionHas('registration.google.identify_id', 'google-id-123');
        $callback->assertSessionHas('registration.customer.first_name', '太郎');
        $callback->assertSessionHas('registration.customer.last_name', '山田');
        $this->assertGuest();
        $this->assertDatabaseCount('users', 0);

        $stepTwoPage = $this->get(route('register.step2'));
        $stepTwoPage->assertOk();
        $stepTwoPage->assertSee('google-customer@example.com');
        $stepTwoPage->assertSee('value="山田"', false);
        $stepTwoPage->assertSee('value="太郎"', false);

        $stepTwo = $this->post(route('register.step2.store'), [
            'customer_type' => 'individual',
            'last_name' => '山田',
            'first_name' => '太郎',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'タロウ',
            'phone' => '09012345678',
            'postal_code_front' => '106',
            'postal_code_back' => '0032',
            'postal_code' => '106-0032',
            'prefecture' => '東京都',
            'city' => '港区',
            'address' => '六本木1-2-3',
        ]);

        $stepTwo->assertRedirect(route('register.step3'));
        $this->get(route('register.step3'))->assertOk();

        $complete = $this->post(route('register.store'));

        $complete->assertRedirect(route('home'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'google-customer@example.com',
            'identify_id' => 'google-id-123',
            'social_type' => User::SOCIAL_GOOGLE,
            'last_name_kana' => 'ヤマダ',
            'status' => '1',
        ]);
        $this->assertNotNull(User::where('email', 'google-customer@example.com')->firstOrFail()->email_verified_at);
        $this->assertDatabaseHas('user_addresses', [
            'zip_code' => '106-0032',
            'city' => '港区',
            'is_main' => true,
        ]);
        Notification::assertNothingSent();
    }

    public function test_existing_google_customer_with_a_complete_profile_logs_in_normally(): void
    {
        $user = User::create([
            'first_name' => '太郎',
            'last_name' => '山田',
            'customer_type' => 'individual',
            'last_name_kana' => 'ヤマダ',
            'first_name_kana' => 'タロウ',
            'name' => '山田 太郎',
            'email' => 'google-customer@example.com',
            'phone' => '09012345678',
            'password' => 'Secret123',
            'identify_id' => 'google-id-123',
            'social_type' => User::SOCIAL_GOOGLE,
            'status' => '1',
            'email_verified_at' => now(),
        ]);
        $user->addresses()->create([
            'address_type' => 'shipping',
            'label' => 'main',
            'first_name' => '太郎',
            'last_name' => '山田',
            'phone' => '09012345678',
            'address' => '六本木1-2-3',
            'country' => 'JP',
            'city' => '港区',
            'state' => '東京都',
            'zip_code' => '106-0032',
            'is_main' => true,
            'is_active' => true,
        ]);
        $this->fakeGoogleUser();

        $response = $this->get(route('google.callback'));

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        $response->assertSessionMissing('registration');
    }

    private function fakeGoogleUser(): void
    {
        $googleUser = new class
        {
            public array $user = [
                'given_name' => '太郎',
                'family_name' => '山田',
            ];

            public function getId(): string
            {
                return 'google-id-123';
            }

            public function getEmail(): string
            {
                return 'google-customer@example.com';
            }

            public function getName(): string
            {
                return '山田 太郎';
            }

            public function getAvatar(): string
            {
                return 'https://example.com/avatar.png';
            }
        };

        $provider = Mockery::mock();
        $provider->shouldReceive('user')->once()->andReturn($googleUser);
        Socialite::shouldReceive('driver')->once()->with('google')->andReturn($provider);
    }
}
