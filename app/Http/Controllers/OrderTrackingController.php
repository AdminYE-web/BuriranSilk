<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    public function index(): View
    {
        return view('frontend.orders.track', ['order' => null]);
    }

    public function search(Request $request): View|RedirectResponse
    {
        $validated = $request->validate([
            'order_no' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
        ], [], [
            'order_no' => '注文番号',
            'email' => 'メールアドレス',
        ]);

        $order = Order::query()
            ->with(['customer', 'payment', 'items.optionDetails'])
            ->where('order_no', trim($validated['order_no']))
            ->whereHas('customer', function ($query) use ($validated) {
                $query->whereRaw('LOWER(personal_email) = ?', [Str::lower(trim($validated['email']))]);
            })
            ->first();

        if (! $order) {
            return to_route('orders.track')
                ->withErrors([
                    'lookup' => '注文番号またはメールアドレスが正しくありません。入力内容をご確認ください。',
                ])
                ->withInput();
        }

        return view('frontend.orders.track', compact('order'));
    }
}
