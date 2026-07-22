<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleLoginController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')
            ->scopes([
                'openid',
                'profile',
                'email',
            ])
            ->with([
                'prompt' => 'select_account',
            ])
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            $identifyId = trim((string) $googleUser->getId());
            $email = Str::lower(trim((string) $googleUser->getEmail()));

            if ($identifyId === '' || $email === '') {
                return $this->loginError('Googleアカウントからユーザー情報を取得できませんでした。');
            }

            $userBySocialId = User::query()
                ->where('social_type', User::SOCIAL_GOOGLE)
                ->where('identify_id', $identifyId)
                ->first();
            $userByEmail = User::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();

            if ($userBySocialId && $userByEmail && ! $userBySocialId->is($userByEmail)) {
                return $this->loginError('このGoogleアカウントは別の会員情報に登録されています。');
            }

            $user = $userBySocialId ?: $userByEmail;

            if ($user) {
                if (
                    filled($user->identify_id)
                    && ((int) $user->social_type !== User::SOCIAL_GOOGLE
                        || ! hash_equals((string) $user->identify_id, $identifyId))
                ) {
                    return $this->loginError('このメールアドレスは別のSNSアカウントに連携されています。');
                }

                if (! in_array((string) $user->status, ['1', '2'], true)) {
                    return $this->loginError('このアカウントは現在ご利用いただけません。');
                }

                $user->forceFill([
                    'identify_id' => $identifyId,
                    'social_type' => User::SOCIAL_GOOGLE,
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => $user->email_verified_at ?: now(),
                    'status' => '1',
                ])->save();

                if ($this->hasCompleteCustomerProfile($user)) {
                    return $this->login($request, $user);
                }
            }

            [$firstName, $lastName] = $this->googleNames($googleUser, $email);
            $address = $user && Schema::hasTable('user_addresses')
                ? $user->mainShippingAddress()->first()
                : null;

            $request->session()->put('registration', [
                'account' => [
                    'email' => $email,
                    'password' => Str::random(64),
                    'term_policy' => true,
                ],
                'customer' => [
                    'customer_type' => $user?->customer_type
                        ?: (filled($user?->company_name ?: $address?->company_name) ? 'corporate' : 'individual'),
                    'last_name' => $user?->last_name ?: $lastName,
                    'first_name' => $user?->first_name ?: $firstName,
                    'last_name_kana' => $user?->last_name_kana ?: '',
                    'first_name_kana' => $user?->first_name_kana ?: '',
                    'company_name' => $user?->company_name ?: $address?->company_name,
                    'company_name_kana' => $user?->company_name_kana,
                    'phone' => $user?->phone ?: $address?->phone,
                    'postal_code' => $address?->zip_code ?: '',
                    'prefecture' => $address?->state ?: '',
                    'city' => $address?->city ?: '',
                    'address' => trim(implode(' ', array_filter([$address?->address, $address?->apartment]))),
                    'receive_email' => (bool) ($user?->receive_email ?? false),
                ],
                'google' => [
                    'existing_user_id' => $user?->user_id,
                    'identify_id' => $identifyId,
                    'social_type' => User::SOCIAL_GOOGLE,
                    'avatar' => $googleUser->getAvatar(),
                ],
            ]);
            $request->session()->regenerate();

            return redirect()
                ->route('register.step2')
                ->with('register_success', 'Googleアカウントを確認しました。お客様情報を入力してください。');
        } catch (Throwable $exception) {
            report($exception);

            return $this->loginError('Googleログインに失敗しました。もう一度お試しください。');
        }
    }

    private function hasCompleteCustomerProfile(User $user): bool
    {
        if (! Schema::hasTable('user_addresses')) {
            return false;
        }

        $address = $user->mainShippingAddress()->first();
        $required = [
            $user->first_name,
            $user->last_name,
            $user->first_name_kana,
            $user->last_name_kana,
            $user->phone,
            $address?->zip_code,
            $address?->state,
            $address?->city,
            $address?->address,
        ];

        if (collect($required)->contains(fn ($value) => blank($value))) {
            return false;
        }

        return $user->customer_type !== 'corporate'
            || (filled($user->company_name) && filled($user->company_name_kana));
    }

    private function googleNames($googleUser, string $email): array
    {
        $rawUser = $googleUser->user ?? [];
        $firstName = trim((string) (data_get($rawUser, 'given_name') ?: ''));
        $lastName = trim((string) (data_get($rawUser, 'family_name') ?: ''));
        $fullName = trim((string) $googleUser->getName());

        if ($fullName === '') {
            $fullName = Str::before($email, '@');
        }

        if ($firstName === '' && $lastName === '') {
            $nameParts = preg_split('/\s+/u', $fullName, 2, PREG_SPLIT_NO_EMPTY);
            $firstName = $nameParts[0] ?? $fullName;
            $lastName = $nameParts[1] ?? '';
        }

        return [$firstName, $lastName];
    }

    private function login(Request $request, User $user): RedirectResponse
    {
        Auth::login($user, true);
        $request->session()->regenerate();

        if (Schema::hasTable('user_login_logs')) {
            $user->recordLogin($request);
        }

        return redirect()
            ->intended(route('home'))
            ->with('login_success', 'Googleでサインインしました。');
    }

    private function loginError(string $message): RedirectResponse
    {
        return redirect()
            ->route('home')
            ->withErrors(['email' => $message], 'login')
            ->with('open_login_modal', true);
    }
}
