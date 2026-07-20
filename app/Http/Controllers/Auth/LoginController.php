<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validateWithBag('login', [
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors([
                    'email' => 'メールアドレスまたはパスワードが正しくありません。',
                ], 'login')
                ->withInput($request->only('email'))
                ->with('open_login_modal', true);
        }

        if ((string) $request->user()->status !== '1') {
            Auth::logout();

            return back()
                ->withErrors([
                    'email' => 'メールアドレスの確認が完了していません。登録メールをご確認ください。',
                ], 'login')
                ->withInput($request->only('email'))
                ->with('open_login_modal', true);
        }

        $request->session()->regenerate();

        if (Schema::hasTable('user_login_logs')) {
            $request->user()?->recordLogin($request);
        }

        return back()->with('login_success', 'サインインしました。');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
