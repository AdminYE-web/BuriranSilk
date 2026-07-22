<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(Request $request): View
    {
        return $this->stepView($request, 1);
    }

    public function step1(Request $request): View
    {
        return $this->stepView($request, 1);
    }

    public function storeStep1(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->accountRules(), $this->messages());

        $request->session()->forget([
            'registration.customer',
            'registration.google',
        ]);
        $request->session()->put('registration.account', [
            'email' => $validated['email'],
            'password' => $validated['password'],
            'term_policy' => true,
        ]);

        return redirect()->route('register.step2');
    }

    public function step2(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('registration.account')) {
            return redirect()->route('register');
        }

        return $this->stepView($request, 2);
    }

    public function storeStep2(Request $request): RedirectResponse
    {
        if (! $request->session()->has('registration.account')) {
            return redirect()->route('register');
        }

        $postalCode = preg_replace('/\D/', '', (string) $request->input('postal_code', ''));

        if (strlen($postalCode) !== 7) {
            $postalCode = (string) $request->input('postal_code_front', '')
                .(string) $request->input('postal_code_back', '');
        }

        $request->merge([
            'postal_code_front' => substr($postalCode, 0, 3),
            'postal_code_back' => substr($postalCode, 3, 4),
            'postal_code' => strlen($postalCode) === 7
                ? substr($postalCode, 0, 3).'-'.substr($postalCode, 3, 4)
                : $request->input('postal_code'),
        ]);

        $rules = $this->customerRules();

        if ($request->input('customer_type') === 'corporate') {
            $rules['company_name'] = ['required', 'string', 'max:150'];
            $rules['company_name_kana'] = ['required', 'string', 'max:150', 'regex:/^[ァ-ヶー\s]+$/u'];
        }

        $validated = $request->validate($rules, $this->messages());

        $request->session()->put('registration.customer', [
            'customer_type' => $validated['customer_type'],
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'last_name_kana' => $validated['last_name_kana'],
            'first_name_kana' => $validated['first_name_kana'],
            'company_name' => $validated['company_name'] ?? null,
            'company_name_kana' => $validated['company_name_kana'] ?? null,
            'phone' => $validated['phone'],
            'postal_code' => $validated['postal_code'],
            'prefecture' => $validated['prefecture'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'receive_email' => (bool) ($validated['receive_email'] ?? false),
        ]);

        return redirect()->route('register.step3');
    }

    public function step3(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('registration.account')) {
            return redirect()->route('register');
        }

        if (! $request->session()->has('registration.customer')) {
            return redirect()->route('register.step2');
        }

        return $this->stepView($request, 3);
    }

    public function lookupPostalCode(Request $request): JsonResponse
    {
        $zipcode = preg_replace('/\D/', '', (string) $request->input('zipcode', ''));

        if (strlen($zipcode) !== 7) {
            return response()->json([
                'mainArea' => '',
                'subArea' => '',
                'message' => '郵便番号は7桁で入力してください。',
            ], 422);
        }

        $token = config('services.google.geocode_key');

        if (blank($token)) {
            return response()->json([
                'mainArea' => '',
                'subArea' => '',
                'message' => '住所検索を現在利用できません。',
            ], 503);
        }

        try {
            $response = Http::acceptJson()
                ->timeout(10)
                ->get('https://maps.googleapis.com/maps/api/geocode/json', [
                    'key' => $token,
                    'address' => $zipcode,
                    'language' => 'ja',
                    'sensor' => 'false',
                ]);

            $result = $response->json();
            $data = [
                'mainArea' => '',
                'subArea' => '',
            ];

            if (data_get($result, 'status') === 'OK' && filled(data_get($result, 'results.0.address_components'))) {
                $fallbackSubArea = '';

                collect(data_get($result, 'results.0.address_components'))
                    ->each(function (array $component) use (&$data, &$fallbackSubArea): void {
                        $types = $component['types'] ?? [];
                        $name = $component['long_name'] ?? '';

                        if (in_array('administrative_area_level_1', $types, true)) {
                            $data['mainArea'] = $name;
                        }

                        // locality is the municipality/ward (e.g. 新宿区) and must
                        // take precedence over smaller address components such as 8.
                        if (in_array('locality', $types, true)) {
                            $data['subArea'] = $name;
                        }

                        if (
                            blank($fallbackSubArea)
                            && (
                                in_array('sublocality', $types, true)
                                || in_array('sublocality_level_1', $types, true)
                            )
                        ) {
                            $fallbackSubArea = $name;
                        }
                    });

                $data['subArea'] = $data['subArea'] ?: $fallbackSubArea;
            }

            return response()->json($data);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'mainArea' => '',
                'subArea' => '',
                'message' => '住所検索に失敗しました。',
            ], 502);
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $registration = $request->session()->get('registration');

        // Keep the old endpoint compatible with direct full-form submissions.
        if (! is_array($registration) || ! isset($registration['account'], $registration['customer'])) {
            $validated = $request->validate(array_merge($this->accountRules(), [
                'last_name' => ['required', 'string', 'max:100'],
                'first_name' => ['required', 'string', 'max:100'],
                'phone' => ['required', 'string', 'max:50'],
                'receive_email' => ['nullable', 'boolean'],
            ]), $this->messages());

            $registration = [
                'account' => [
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'term_policy' => true,
                ],
                'customer' => [
                    'customer_type' => 'individual',
                    'last_name' => $validated['last_name'],
                    'first_name' => $validated['first_name'],
                    'last_name_kana' => '',
                    'first_name_kana' => '',
                    'company_name' => null,
                    'company_name_kana' => null,
                    'phone' => $validated['phone'],
                    'postal_code' => '',
                    'prefecture' => '',
                    'city' => '',
                    'address' => '',
                    'receive_email' => (bool) ($validated['receive_email'] ?? false),
                ],
            ];
        }

        $user = $this->createUser($registration);
        $isGoogleRegistration = filled(data_get($registration, 'google.identify_id'));

        if ($isGoogleRegistration) {
            Auth::login($user, true);
            $request->session()->forget('registration');
            $request->session()->regenerate();

            return redirect()
                ->route('home')
                ->with('login_success', 'Googleアカウントで会員登録が完了しました。');
        }

        $user->sendEmailVerificationNotification();

        $request->session()->forget('registration');
        $request->session()->put('registration.complete_email', $user->email);
        $request->session()->regenerate();

        return redirect()->route('register.complete');
    }

    public function complete(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('registration.complete_email');

        if (blank($email)) {
            return redirect()->route('register');
        }

        return view('frontend.auth.register-complete', compact('email'));
    }

    public function verifyEmail(Request $request, int $id, string $hash): RedirectResponse
    {
        $user = User::findOrFail($id);

        abort_unless(hash_equals(sha1($user->email), $hash), 403);

        if (! $user->hasVerifiedEmail()) {
            $user->forceFill([
                'email_verified_at' => now(),
                'status' => '1',
            ])->save();
        }

        return redirect()
            ->route('home', ['login' => 1])
            ->with('login_success', 'メールアドレスの確認が完了しました。ログインしてください。');
    }

    private function stepView(Request $request, int $step): View
    {
        return view('frontend.auth.register', [
            'step' => $step,
            'registration' => $request->session()->get('registration', []),
        ]);
    }

    private function createUser(array $registration): User
    {
        $account = $registration['account'];
        $customer = $registration['customer'];
        $google = $registration['google'] ?? null;
        $isGoogleRegistration = is_array($google) && filled($google['identify_id'] ?? null);

        return DB::transaction(function () use ($account, $customer, $google, $isGoogleRegistration): User {
            $user = null;

            if ($isGoogleRegistration) {
                $user = filled($google['existing_user_id'] ?? null)
                    ? User::query()->lockForUpdate()->find($google['existing_user_id'])
                    : User::query()->where('email', $account['email'])->lockForUpdate()->first();

                if ($user) {
                    if (strcasecmp((string) $user->email, (string) $account['email']) !== 0) {
                        throw ValidationException::withMessages([
                            'email' => 'Googleアカウントのメールアドレスが一致しません。',
                        ]);
                    }

                    if (
                        filled($user->identify_id)
                        && ((int) $user->social_type !== User::SOCIAL_GOOGLE
                            || ! hash_equals((string) $user->identify_id, (string) $google['identify_id']))
                    ) {
                        throw ValidationException::withMessages([
                            'email' => 'このメールアドレスは別のSNSアカウントに連携されています。',
                        ]);
                    }
                }
            } else {
                $pendingUser = User::query()
                    ->where('email', $account['email'])
                    ->lockForUpdate()
                    ->first();

                if ($pendingUser) {
                    if ((string) $pendingUser->status !== '2') {
                        throw ValidationException::withMessages([
                            'email' => 'このメールアドレスはすでに登録されています。',
                        ]);
                    }

                    $pendingUser->delete();
                }
            }

            $attributes = [
                'first_name' => $customer['first_name'],
                'last_name' => $customer['last_name'],
                'customer_type' => $customer['customer_type'] ?? 'individual',
                'last_name_kana' => $customer['last_name_kana'] ?? null,
                'first_name_kana' => $customer['first_name_kana'] ?? null,
                'company_name' => $customer['company_name'] ?? null,
                'company_name_kana' => $customer['company_name_kana'] ?? null,
                'name' => $customer['last_name'].' '.$customer['first_name'],
                'email' => $account['email'],
                'phone' => $customer['phone'],
                'status' => $isGoogleRegistration ? '1' : '2',
                'term_policy' => true,
                'receive_email' => (bool) ($customer['receive_email'] ?? false),
            ];

            if ($isGoogleRegistration) {
                $attributes = array_merge($attributes, [
                    'identify_id' => $google['identify_id'],
                    'social_type' => User::SOCIAL_GOOGLE,
                    'avatar' => $google['avatar'] ?? null,
                    'email_verified_at' => $user?->email_verified_at ?: now(),
                ]);
            }

            if ($user) {
                $user->forceFill($attributes)->save();
            } else {
                $user = User::create(array_merge($attributes, [
                    'password' => $account['password'],
                ]));
            }

            if (Schema::hasTable('user_contacts')) {
                $user->contacts()->updateOrCreate([
                    'is_main' => true,
                ], [
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'phone' => $customer['phone'],
                    'email' => $account['email'],
                    'receive_email' => (bool) ($customer['receive_email'] ?? false),
                    'is_active' => true,
                ]);
            }

            if (Schema::hasTable('user_addresses') && filled($customer['address'])) {
                $user->addresses()->updateOrCreate([
                    'address_type' => 'shipping',
                    'is_main' => true,
                ], [
                    'label' => 'main',
                    'first_name' => $customer['first_name'],
                    'last_name' => $customer['last_name'],
                    'phone' => $customer['phone'],
                    'company_name' => $customer['company_name'] ?? null,
                    'address' => $customer['address'],
                    'country' => 'JP',
                    'city' => $customer['city'],
                    'state' => $customer['prefecture'],
                    'zip_code' => $customer['postal_code'],
                    'is_active' => true,
                ]);
            }

            return $user;
        });
    }

    private function accountRules(): array
    {
        return [
            'email' => [
                'required',
                'email:rfc',
                'max:255',
                Rule::unique('users', 'email')->where(
                    fn ($query) => $query->where('status', '!=', '2')
                ),
            ],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
            'term_policy' => ['accepted'],
        ];
    }

    private function customerRules(): array
    {
        return [
            'customer_type' => ['required', 'in:individual,corporate'],
            'last_name' => ['required', 'string', 'max:100'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name_kana' => ['required', 'string', 'max:100', 'regex:/^[ァ-ヶー\s]+$/u'],
            'first_name_kana' => ['required', 'string', 'max:100', 'regex:/^[ァ-ヶー\s]+$/u'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'company_name_kana' => ['nullable', 'string', 'max:150', 'regex:/^[ァ-ヶー\s]+$/u'],
            'phone' => ['required', 'string', 'max:50'],
            'postal_code_front' => ['required', 'digits:3'],
            'postal_code_back' => ['required', 'digits:4'],
            'postal_code' => ['required', 'regex:/^\d{3}-?\d{4}$/'],
            'prefecture' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string', 'max:255'],
            'receive_email' => ['nullable', 'boolean'],
        ];
    }

    private function messages(): array
    {
        return [
            'email.required' => 'メールアドレスを入力してください。',
            'email.email' => '正しいメールアドレスを入力してください。',
            'email.unique' => 'このメールアドレスはすでに登録されています。',
            'password.required' => 'パスワードを入力してください。',
            'password.confirmed' => '確認用パスワードが一致しません。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'term_policy.accepted' => '利用規約およびプライバシーポリシーへの同意が必要です。',
            'customer_type.required' => '区分を選択してください。',
            'last_name.required' => '姓を入力してください。',
            'first_name.required' => '名を入力してください。',
            'last_name_kana.required' => '姓（カタカナ）を入力してください。',
            'first_name_kana.required' => '名（カタカナ）を入力してください。',
            'last_name_kana.regex' => 'カタカナで入力してください。',
            'first_name_kana.regex' => 'カタカナで入力してください。',
            'company_name.required' => '会社名を入力してください。',
            'company_name_kana.required' => '会社名（フリガナ）を入力してください。',
            'company_name_kana.regex' => '会社名（フリガナ）はカタカナで入力してください。',
            'phone.required' => '電話番号を入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code_front.required' => '郵便番号の前半3桁を入力してください。',
            'postal_code_front.digits' => '郵便番号の前半は3桁で入力してください。',
            'postal_code_back.required' => '郵便番号の後半4桁を入力してください。',
            'postal_code_back.digits' => '郵便番号の後半は4桁で入力してください。',
            'postal_code.regex' => '郵便番号は正しい形式で入力してください。',
            'prefecture.required' => '都道府県を選択してください。',
            'city.required' => '市区町村を入力してください。',
            'address.required' => '町名・番地を入力してください。',
        ];
    }
}
