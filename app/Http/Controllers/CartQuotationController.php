<?php

namespace App\Http\Controllers;

use App\Support\CartPricing;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class CartQuotationController extends Controller
{
    public function download(Request $request)
    {
        $isEnglish = $request->cookie('dev') === '1';

        $validated = $request->validateWithBag('quotation', [
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'postal_code' => ['required', 'regex:/^\d{3}-?\d{4}$/'],
            'prefecture' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'building' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'memo' => ['nullable', 'string', 'max:1000'],
        ], [
            'last_name.required' => $isEnglish
                ? 'Please enter your last name.'
                : 'お名前（姓）を入力してください。',

            'first_name.required' => $isEnglish
                ? 'Please enter your first name.'
                : 'お名前（名）を入力してください。',

            'postal_code.required' => $isEnglish
                ? 'Please enter your postal code.'
                : '郵便番号を入力してください。',

            'postal_code.regex' => $isEnglish
                ? 'Please enter a valid Japanese postal code.'
                : '郵便番号を正しい形式で入力してください。',

            'prefecture.required' => $isEnglish
                ? 'Please enter your prefecture.'
                : '都道府県を入力してください。',

            'address.required' => $isEnglish
                ? 'Please enter your address.'
                : '住所を入力してください。',

            'phone.required' => $isEnglish
                ? 'Please enter your phone number.'
                : '電話番号を入力してください。',
        ]);

        $items = array_values(
            $request->session()->get('cart.items', [])
        );

        if (empty($items)) {
            return back()
                ->withErrors([
                    'cart' => $isEnglish
                        ? 'Your cart is empty.'
                        : 'カートに商品がありません。',
                ], 'quotation')
                ->withInput();
        }

        $summary = CartPricing::summary($items);
        $issuedAt = now();
        $quotationNumber = 'TS-QT-'.$issuedAt->format('Ymd-His');

        $pdf = Pdf::loadView('frontend.cart.quotation-pdf', [
    'items' => $items,
    'summary' => $summary,
    'customer' => $validated,
    'quotationNumber' => $quotationNumber,
    'issuedAt' => $issuedAt,
    'validUntil' => $issuedAt->copy()->addDays(30),
    'isEnglish' => $isEnglish,
    'company' => config('quotation.company', []),
])
    ->setPaper('a4', 'portrait')
    ->setOption([
        'defaultFont' => 'NotoSansJP',
        'fontDir' => storage_path('fonts'),
        'fontCache' => storage_path('fonts'),
        'chroot' => base_path(),
        'isFontSubsettingEnabled' => true,
        'isRemoteEnabled' => false,
    ]);

        return $pdf->download(
            'quotation-'.$quotationNumber.'.pdf'
        );
    }

    public function postalCode(Request $request): JsonResponse
    {
        $postalCode = preg_replace(
            '/\D/',
            '',
            (string) $request->input('postal_code')
        );

        if (strlen($postalCode) !== 7) {
            return response()->json([
                'message' => '郵便番号は7桁で入力してください。',
            ], 422);
        }

        $apiKey = config('services.google.geocode_key');

        if (blank($apiKey)) {
            return response()->json([
                'message' => '住所検索を現在利用できません。',
            ], 503);
        }

        try {
            $response = Http::acceptJson()
                ->timeout(10)
                ->get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'key' => $apiKey,
                    'address' => $postalCode,
                    'language' => 'ja',
                    'region' => 'jp',
                ])
                ->throw()
                ->json();

            if (data_get($response, 'status') !== 'OK') {
                return response()->json([
                    'message' => '住所が見つかりませんでした。',
                ], 404);
            }

            $prefecture = '';
            $address = '';
            $fallbackAddress = '';

            foreach (
                data_get($response, 'results.0.address_components', [])
                as $component
            ) {
                $types = $component['types'] ?? [];
                $name = $component['long_name'] ?? '';

                if (in_array('administrative_area_level_1', $types, true)) {
                    $prefecture = $name;
                }

                if (in_array('locality', $types, true)) {
                    $address = $name;
                }

                if (
                    in_array('sublocality', $types, true)
                    || in_array('sublocality_level_1', $types, true)
                ) {
                    $fallbackAddress = $name;
                }
            }

            return response()->json([
                'prefecture' => $prefecture,
                'address' => $address ?: $fallbackAddress,
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => '住所検索に失敗しました。',
            ], 502);
        }
    }
}