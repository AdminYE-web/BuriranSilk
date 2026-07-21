<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            ->redirect();
    }

    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $identifyId = trim((string) $googleUser->getId());

            $email = Str::lower(
                trim((string) $googleUser->getEmail())
            );

            if ($identifyId === '' || $email === '') {
                return $this->loginError(
                    'Googleアカウントからユーザー情報を取得できませんでした。'
                );
            }

            $userBySocialId = User::query()
                ->where('social_type', User::SOCIAL_GOOGLE)
                ->where('identify_id', $identifyId)
                ->first();

            $userByEmail = User::query()
                ->whereRaw('LOWER(email) = ?', [$email])
                ->first();

            if (
                $userBySocialId
                && $userByEmail
                && ! $userBySocialId->is($userByEmail)
            ) {
                return $this->loginError(
                    'このGoogleアカウントは別の会員情報に登録されています。'
                );
            }

            $user = $userBySocialId ?: $userByEmail;

            if ($user) {
                if (
                    filled($user->identify_id)
                    && (
                        (int) $user->social_type !== User::SOCIAL_GOOGLE
                        || ! hash_equals(
                            (string) $user->identify_id,
                            $identifyId
                        )
                    )
                ) {
                    return $this->loginError(
                        'このメールアドレスは別のSNSアカウントに連携されています。'
                    );
                }

                if (! in_array((string) $user->status, ['1', '2'], true)) {
                    return $this->loginError(
                        'このアカウントは現在ご利用いただけません。'
                    );
                }

                $user->forceFill([
                    'identify_id' => $identifyId,
                    'social_type' => User::SOCIAL_GOOGLE,
                    'avatar' => $googleUser->getAvatar(),
                    'email_verified_at' => $user->email_verified_at ?: now(),
                    'status' => '1',
                ])->save();
            } else {
                $rawUser = $googleUser->user ?? [];

                $firstName = trim((string) (
                    data_get($rawUser, 'given_name') ?: ''
                ));

                $lastName = trim((string) (
                    data_get($rawUser, 'family_name') ?: ''
                ));

                $fullName = trim((string) $googleUser->getName());

                if ($fullName === '') {
                    $fullName = Str::before($email, '@');
                }

                if ($firstName === '' && $lastName === '') {
                    $nameParts = preg_split(
                        '/\s+/u',
                        $fullName,
                        2,
                        PREG_SPLIT_NO_EMPTY
                    );

                    $firstName = $nameParts[0] ?? $fullName;
                    $lastName = $nameParts[1] ?? '';
                }

                $user = User::query()->create([
                    'name' => $fullName,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => '',
                    'password' => Hash::make(Str::random(64)),
                    'identify_id' => $identifyId,
                    'social_type' => User::SOCIAL_GOOGLE,
                    'avatar' => $googleUser->getAvatar(),
                    'status' => '1',
                    'term_policy' => true,
                    'receive_email' => false,
                ]);

                $user->forceFill([
                    'email_verified_at' => now(),
                ])->save();
            }

            Auth::login($user, true);

            $request->session()->regenerate();

            if (Schema::hasTable('user_login_logs')) {
                $user->recordLogin($request);
            }

            return redirect()
                ->intended(route('home'))
                ->with('login_success', 'Googleでサインインしました。');
        } catch (Throwable $exception) {
            report($exception);

            return $this->loginError(
                'Googleログインに失敗しました。もう一度お試しください。'
            );
        }
    }

    private function loginError(string $message): RedirectResponse
    {
        return redirect()
            ->route('home')
            ->withErrors([
                'email' => $message,
            ], 'login')
            ->with('open_login_modal', true);
    }
}