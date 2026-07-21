<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('passwordReset', [
            'email' => ['required', 'email'],
        ]);

        $user = User::query()
            ->where('email', $validated['email'])
            ->where('status', '1')
            ->first();

        if (! $user) {
            return back()
                ->withErrors(['email' => '登録済みかつメール認証済みのメールアドレスを入力してください。'], 'passwordReset')
                ->withInput($request->only('email'))
                ->with('open_password_reset_modal', true);
        }

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status !== Password::RESET_LINK_SENT) {
            return back()
                ->withErrors(['email' => '再設定メールを送信できませんでした。しばらくしてからお試しください。'], 'passwordReset')
                ->withInput($request->only('email'))
                ->with('open_password_reset_modal', true);
        }

        return back()->with('password_reset_sent', true);
    }
}