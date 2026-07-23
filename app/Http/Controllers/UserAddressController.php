<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserAddressController extends Controller
{
    public function index(?string $type = 'shipping'): View
    {
        $type = in_array($type, ['shipping', 'billing'], true) ? $type : 'shipping';

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $addresses = $user->addresses()
            ->where('address_type', $type)
            ->orderByDesc('is_main')
            ->latest()
            ->get();

        return view('frontend.account.addresses.index', compact('user', 'addresses', 'type'));
    }

    public function create(string $type = 'shipping'): View|RedirectResponse
    {
        $type = in_array($type, ['shipping', 'billing'], true) ? $type : 'shipping';

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->addresses()->where('address_type', $type)->count() >= 5) {
            return redirect()->route('account.addresses.index', $type)
                ->with('error', "You have reached the maximum of 5 {$type} addresses.");
        }

        return view('frontend.account.addresses.create', compact('user', 'type'));
    }

    public function store(Request $request, string $type = 'shipping'): RedirectResponse
    {
        $type = in_array($type, ['shipping', 'billing'], true) ? $type : 'shipping';

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->addresses()->where('address_type', $type)->count() >= 5) {
            return redirect()->route('account.addresses.index', $type)
                ->with('error', "You have reached the maximum of 5 {$type} addresses.");
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'apartment' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:255'],
        ]);

        $isMain = $request->boolean('is_main') || $user->addresses()->where('address_type', $type)->count() === 0;

        if ($isMain) {
            $user->addresses()->where('address_type', $type)->update(['is_main' => false]);
        }

        $user->addresses()->create(array_merge($validated, [
            'address_type' => $type,
            'is_main' => $isMain,
            'is_active' => true,
        ]));

        return redirect()->route('account.addresses.index', $type)
            ->with('success', 'Address created successfully.');
    }

    public function edit(UserAddress $address): View
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $type = $address->address_type;

        return view('frontend.account.addresses.edit', compact('user', 'address', 'type'));
    }

    public function update(Request $request, UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'label' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'apartment' => ['nullable', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'zip_code' => ['required', 'string', 'max:255'],
        ]);

        $isMain = $request->boolean('is_main');

        if ($isMain) {
            Auth::user()->addresses()
                ->where('address_type', $address->address_type)
                ->where('user_address_id', '!=', $address->user_address_id)
                ->update(['is_main' => false]);
        }

        $address->update(array_merge($validated, [
            'is_main' => $isMain || $address->is_main,
        ]));

        return redirect()->route('account.addresses.index', $address->address_type)
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $type = $address->address_type;
        $wasMain = $address->is_main;
        $address->delete();

        if ($wasMain) {
            $nextAddress = Auth::user()->addresses()->where('address_type', $type)->first();
            if ($nextAddress) {
                $nextAddress->update(['is_main' => true]);
            }
        }

        return redirect()->route('account.addresses.index', $type)
            ->with('success', 'Address deleted successfully.');
    }

    public function setMain(UserAddress $address): RedirectResponse
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->addresses()
            ->where('address_type', $address->address_type)
            ->update(['is_main' => false]);

        $address->update(['is_main' => true]);

        return redirect()->route('account.addresses.index', $address->address_type)
            ->with('success', 'Default address updated successfully.');
    }
}
