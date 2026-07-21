<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    public function create(Request $request, string $token): View
    {
        return view('frontend.auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)->letters()->numbers()],
        ], [
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.confirmed' => '確認用パスワードが一致しません。',
        ]);

        $status = Password::reset($validated, function ($user, string $password): void {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
        });

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors([
                'email' => 'この再設定リンクは無効、または有効期限が切れています。もう一度お試しください。',
            ])->withInput($request->only('email'));
        }

        return redirect()->route('home', ['login' => 1])
            ->with('login_success', 'パスワードを再設定しました。新しいパスワードでログインしてください。');
    }
}