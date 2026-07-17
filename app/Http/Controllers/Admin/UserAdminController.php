<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\UserEmailChangeVerificationMail;
use App\Models\User;
use App\Models\UserEmailChangeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->withCount('orders')
            ->withMax('loginLogs', 'logged_in_at')
            ->orderBy('user_id', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($userQuery) use ($search) {
                $userQuery
                    ->where('email', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load([
            'mainContact',
            'mainShippingAddress',
            'mainBillingAddress',
            'socialAccounts',
        ]);

        $orders = $user->orders()
            ->with(['customer', 'payment'])
            ->orderBy('order_id', 'desc')
            ->paginate(10, ['*'], 'orders_page');

        $loginLogs = $user->loginLogs()
            ->orderBy('logged_in_at', 'desc')
            ->paginate(10, ['*'], 'logs_page');

        return view('admin.users.show', compact('user', 'orders', 'loginLogs'));
    }

    public function sendEmailChangeVerification(Request $request, User $user)
    {
        $request->validate([
            'new_email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->user_id, 'user_id'),
            ],
        ]);

        if ($request->new_email === $user->email) {
            return back()->with('error', 'New email must be different from current email.');
        }

        // ยกเลิก request เก่าที่ยังไม่ verify ของ user นี้
        UserEmailChangeRequest::where('user_id', $user->user_id)
            ->whereNull('verified_at')
            ->delete();

        $emailChangeRequest = UserEmailChangeRequest::create([
            'user_id' => $user->user_id,
            'admin_id' => auth()->id(),
            'old_email' => $user->email,
            'new_email' => $request->new_email,
            'token' => Str::random(64),
            'expires_at' => now()->addHours(24),
        ]);

        $verifyUrl = route('users.email-change.verify', [
            'token' => $emailChangeRequest->token,
        ]);

        Mail::to($emailChangeRequest->new_email)
            ->send(new UserEmailChangeVerificationMail($user, $emailChangeRequest, $verifyUrl));

        return back()->with('success', 'Verification email has been sent to the new email address.');
    }

    public function verifyEmailChange(string $token)
    {
        $emailChangeRequest = UserEmailChangeRequest::where('token', $token)
            ->whereNull('verified_at')
            ->firstOrFail();

        if ($emailChangeRequest->expires_at && now()->greaterThan($emailChangeRequest->expires_at)) {
            return redirect()
                ->route('login')
                ->with('error', 'This email change verification link has expired.');
        }

        $user = User::where('user_id', $emailChangeRequest->user_id)->firstOrFail();

        // กันกรณี email ใหม่นี้ถูกใช้ไปแล้วระหว่างรอ verify
        $emailExists = User::where('email', $emailChangeRequest->new_email)
            ->where('user_id', '!=', $user->user_id)
            ->exists();

        if ($emailExists) {
            return redirect()
                ->route('login')
                ->with('error', 'This email is already used by another account.');
        }

        $user->forceFill([
            'email' => $emailChangeRequest->new_email,
            'email_verified_at' => now(),
            'status' => 1,
        ])->save();

        $emailChangeRequest->update([
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('login')
            ->with('message', 'Your email address has been changed successfully. Please login again.');
    }
}
