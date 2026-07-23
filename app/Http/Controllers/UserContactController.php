<?php

namespace App\Http\Controllers;

use App\Models\UserContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserContactController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $contacts = $user->contacts()->orderByDesc('is_main')->latest()->get();

        return view('frontend.account.contacts.index', compact('user', 'contacts'));
    }

    public function create(): View|RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->contacts()->count() >= 5) {
            return redirect()->route('account.contacts.index')
                ->with('error', 'You have reached the maximum of 5 contacts.');
        }

        return view('frontend.account.contacts.create', compact('user'));
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->contacts()->count() >= 5) {
            return redirect()->route('account.contacts.index')
                ->with('error', 'You have reached the maximum of 5 contacts.');
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $isMain = $request->boolean('is_main') || $user->contacts()->count() === 0;

        if ($isMain) {
            $user->contacts()->update(['is_main' => false]);
        }

        $user->contacts()->create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'receive_email' => $request->boolean('receive_email'),
            'is_main' => $isMain,
            'is_active' => true,
        ]);

        return redirect()->route('account.contacts.index')
            ->with('success', 'Contact created successfully.');
    }

    public function edit(UserContact $contact): View
    {
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        return view('frontend.account.contacts.edit', compact('user', 'contact'));
    }

    public function update(Request $request, UserContact $contact): RedirectResponse
    {
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        $isMain = $request->boolean('is_main');

        if ($isMain) {
            Auth::user()->contacts()->where('user_contact_id', '!=', $contact->user_contact_id)->update(['is_main' => false]);
        }

        $contact->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'receive_email' => $request->boolean('receive_email'),
            'is_main' => $isMain || $contact->is_main,
        ]);

        return redirect()->route('account.contacts.index')
            ->with('success', 'Contact updated successfully.');
    }

    public function destroy(UserContact $contact): RedirectResponse
    {
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        $wasMain = $contact->is_main;
        $contact->delete();

        if ($wasMain) {
            $nextContact = Auth::user()->contacts()->first();
            if ($nextContact) {
                $nextContact->update(['is_main' => true]);
            }
        }

        return redirect()->route('account.contacts.index')
            ->with('success', 'Contact deleted successfully.');
    }

    public function setMain(UserContact $contact): RedirectResponse
    {
        if ($contact->user_id !== Auth::id()) {
            abort(403);
        }

        Auth::user()->contacts()->update(['is_main' => false]);
        $contact->update(['is_main' => true]);

        return redirect()->route('account.contacts.index')
            ->with('success', 'Default contact updated successfully.');
    }
}
