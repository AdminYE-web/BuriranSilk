<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(): View
    {
        return view('frontend.account.index', [
            'user' => Auth::user(),
        ]);
    }

    public function updateName(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->first_name = $validated['first_name'];
        $user->last_name = $validated['last_name'];
        $user->name = trim($validated['first_name'] . ' ' . $validated['last_name']);
        $user->save();

        return redirect()->route('account.index')->with('success', 'Name updated successfully.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('account.index')->with('success', 'Password updated successfully.');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,png', 'max:1024'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();

        return redirect()->route('account.index')->with('success', 'Avatar updated successfully.');
    }

    public function contacts(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $contacts = $user->contacts()->get();

        return view('frontend.account.contacts.index', [
            'user' => $user,
            'contacts' => $contacts,
        ]);
    }

    public function addresses(?string $type = 'shipping'): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $addresses = $user->addresses()
            ->when($type, fn ($query) => $query->where('address_type', $type))
            ->get();

        return view('frontend.account.addresses.index', [
            'user' => $user,
            'addresses' => $addresses,
            'type' => $type,
        ]);
    }

    public function orders(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $orders = $user->orders()->latest()->get();

        return view('frontend.account.orders.index', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }
}
