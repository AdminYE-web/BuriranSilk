<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Order;
use App\Models\OrderArtwork;
use App\Models\Product;
use App\Models\ProductOptionAssignment;
use App\Models\User;
use App\Support\CartPricing;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart.items', []);

        foreach ($cart as $itemId => $item) {
            $cart[$itemId] = $this->syncQuantityRules($item);
        }

        $request->session()->put('cart.items', $cart);
        $items = array_values($cart);

        return view('frontend.cart.index', [
            'items' => $items,
            'summary' => CartPricing::summary($items),
        ]);
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        if (empty($request->session()->get('cart.items', []))) {
            return redirect()
                ->route('cart.index')
                ->with('cart_success', 'カートに商品を追加してからご注文手続きへお進みください。');
        }

        return view('frontend.checkout.index');
    }

    public function information(Request $request): View
    {
        $registeredCustomer = $request->user()
            ? $this->registeredCustomerProfile($request->user())
            : null;
        $showRegisteredCustomerCard = $registeredCustomer !== null;

        return view('frontend.checkout.information', compact(
            'registeredCustomer',
            'showRegisteredCustomerCard'
        ));
    }

    public function confirmation(Request $request): View|RedirectResponse
    {
        $items = array_values($request->session()->get('cart.items', []));

        if (empty($items)) {
            return redirect()->route('cart.index');
        }

        if ($request->isMethod('post')) {
            $customer = $request->except('_token');
            $validator = $this->checkoutValidator($customer);

            if ($validator->fails()) {
                return redirect()
                    ->route('checkout.information')
                    ->withErrors($validator)
                    ->withInput($customer);
            }

            $request->session()->put('checkout.customer', $customer);
            $request->session()->put('checkout.token', (string) Str::uuid());
        }

        $customer = $request->session()->get('checkout.customer');
        $checkoutToken = $request->session()->get('checkout.token');

        if (empty($customer) || empty($checkoutToken)) {
            return redirect()->route('checkout.information');
        }

        return view('frontend.checkout.confirmation', [
            'items' => $items,
            'summary' => CartPricing::summary($items),
            'customer' => $customer,
            'checkoutToken' => $checkoutToken,
        ]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $cart = $request->session()->get('cart.items', []);
        $customer = $request->session()->get('checkout.customer', []);
        $sessionToken = (string) $request->session()->get('checkout.token', '');
        $submittedToken = (string) $request->input('checkout_token', '');

        if (empty($cart) || empty($customer)) {
            return redirect()->route('cart.index');
        }

        abort_unless(
            $sessionToken !== '' && hash_equals($sessionToken, $submittedToken),
            419
        );

        if ($existingOrder = Order::query()->where('checkout_token', $submittedToken)->first()) {
            return $this->finishCheckout($request, $existingOrder, true);
        }

        $validator = $this->checkoutValidator($customer);

        if ($validator->fails()) {
            return redirect()
                ->route('checkout.information')
                ->withErrors($validator)
                ->withInput($customer);
        }

        $items = array_values($cart);
        $summary = CartPricing::summary($items);

        $order = DB::transaction(function () use ($request, $customer, $items, $summary, $submittedToken) {
            $totalQuantity = (int) collect($items)->sum('quantity');
            $optionTotal = (int) collect($items)->sum('option_subtotal');

            $order = Order::query()->create([
                'order_no' => $this->generateOrderNumber(),
                'checkout_token' => $submittedToken,
                'user_id' => $request->user()?->user_id,
                'total_items' => count($items),
                'total_quantity' => $totalQuantity,
                'qty' => $totalQuantity,
                'base_unit_price' => (float) ($items[0]['base_unit_price'] ?? 0),
                'option_total' => $optionTotal,
                'subtotal' => $summary['subtotal'],
                'shipping_fee' => $summary['shipping'],
                'tax_amount' => $summary['vat'],
                'vat_amount' => $summary['vat'],
                'grand_total' => $summary['total'],
                'currency' => 'JPY',
                'status' => 'pending',
                'order_status' => 'order_pending',
                'payment_status' => 'pending',
                'info_method' => $customer['info_method'] ?? null,
                'signature_text' => $customer['signature_text'] ?? null,
                'delivery_option' => $customer['delivery_option'] ?? null,
                'publish_website' => ($customer['publish_website'] ?? null) === 'yes',
                'newsletter' => ($customer['newsletter'] ?? null) === '1',
                'notes' => $customer['notes'] ?? null,
                'checkout_data' => $customer,
            ]);

            $this->createOrderCustomer($order, $customer);

            foreach ($items as $item) {
                $orderItem = $order->items()->create([
                    'product_id' => (int) $item['product_id'],
                    'product_name' => $item['product_name'] ?? null,
                    'product_name_snapshot' => $item['product_name'] ?? null,
                    'product_image' => $item['image'] ?? null,
                    'qty' => (int) $item['quantity'],
                    'quantity' => (int) $item['quantity'],
                    'base_unit_price' => (float) $item['base_unit_price'],
                    'unit_price' => (float) $item['base_unit_price'],
                    'base_total' => (float) ($item['base_subtotal'] ?? 0),
                    'product_total' => (float) ($item['base_subtotal'] ?? 0),
                    'option_total' => (float) ($item['option_subtotal'] ?? 0),
                    'item_total' => (float) ($item['line_subtotal'] ?? 0),
                    'options' => $item['selected_options'] ?? [],
                    'custom_colors' => data_get($item, 'custom_fields.custom_colors', []),
                    'configuration' => [
                        'previous_order_numbers' => $item['previous_order_numbers'] ?? [],
                        'font_entries' => $item['font_entries'] ?? [],
                        'custom_fields' => $item['custom_fields'] ?? [],
                    ],
                ]);

                foreach ($item['selected_options'] ?? [] as $option) {
                    $priceType = in_array($option['price_type'] ?? null, ['per_item', 'per_order', 'text'], true)
                        ? $option['price_type']
                        : 'per_item';

                    $orderItem->optionDetails()->create([
                        'option_group_id' => $option['group_id'] ?? null,
                        'option_id' => $option['option_id'] ?? null,
                        'group_name_snapshot' => $option['group_name'] ?: 'オプション',
                        'option_name_snapshot' => $option['option_name'] ?? null,
                        'additional_price' => (float) ($option['additional_price'] ?? 0),
                        'price_type' => $priceType,
                        'custom_value' => $option['option_detail'] ?? null,
                        'total_price' => (float) ($option['line_price'] ?? 0),
                    ]);
                }

                foreach ($item['artworks'] ?? [] as $artwork) {
                    OrderArtwork::query()->create([
                        'order_id' => $order->order_id,
                        'order_item_id' => $orderItem->order_item_id,
                        'product_id' => $item['product_id'],
                        'cart_item_id' => $item['id'] ?? null,
                        'file_path' => $artwork['path'] ?? null,
                        'original_name' => $artwork['original_name'] ?? null,
                        'mime_type' => $artwork['mime_type'] ?? null,
                        'file_size' => $artwork['size'] ?? null,
                        'status' => 'pending',
                    ]);
                }
            }

            $order->payment()->create([
                'payment_method' => $customer['payment_method'] ?? 'bank_transfer',
                'payment_status' => 'pending',
                'amount' => $summary['total'],
                'currency' => 'JPY',
            ]);

            return $order;
        });

        $order->load(['customer', 'items.optionDetails', 'payment', 'artworks']);
        $emailSent = false;

        try {
            Mail::to($order->customer->personal_email)
                ->send(new OrderConfirmationMail($order));

            $order->forceFill(['confirmation_email_sent_at' => now()])->save();
            $emailSent = true;
        } catch (\Throwable $exception) {
            Log::error('Order confirmation email could not be sent.', [
                'order_id' => $order->order_id,
                'order_no' => $order->order_no,
                'message' => $exception->getMessage(),
            ]);
        }

        return $this->finishCheckout($request, $order, $emailSent);
    }

    public function complete(Request $request): View|RedirectResponse
    {
        $orderNo = $request->session()->get('checkout.completed_order_no');

        if (empty($orderNo)) {
            return redirect()->route('home');
        }

        return view('frontend.checkout.complete', [
            'orderNo' => $orderNo,
            'emailSent' => (bool) $request->session()->get('checkout.email_sent', false),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product' => ['required', 'string', 'max:255'],
            'cart_item_id' => ['nullable', 'string', 'max:100'],
            'quantity' => ['required', 'integer', 'min:1'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'integer'],
            'previous_order_number' => ['nullable'],
            'font_entries' => ['nullable', 'array'],
            'font_entries.*' => ['nullable', 'array'],
            'font_entries.*.*.text' => ['nullable', 'string', 'max:1000'],
            'font_entries.*.*.font' => ['nullable', 'string', 'max:255'],
            'font_entries.*.*.size' => ['nullable', 'integer', 'between:1,200'],
            'artwork' => ['nullable', 'array'],
            'artwork.*' => ['nullable', 'file', 'max:51200'],
        ]);

        $product = $this->findProduct($validated['product']);
        $quantity = (int) $validated['quantity'];
        $assignments = $this->selectedAssignments($product, $validated['options'] ?? []);
        $cart = $request->session()->get('cart.items', []);
        $editingItemId = $validated['cart_item_id'] ?? null;
        $existingItem = null;

        if (filled($editingItemId)) {
            $existingItem = $cart[$editingItemId] ?? null;

            if (! $existingItem || (int) $existingItem['product_id'] !== (int) $product->product_id) {
                throw ValidationException::withMessages([
                    'cart_item_id' => '編集するカート商品が見つかりません。カートからもう一度お試しください。',
                ]);
            }
        }

        $this->validateQuantity($quantity, $assignments);
        $this->validateArtworkFiles($request);

        $itemId = $editingItemId ?: (string) Str::uuid();
        $selectedOptions = $assignments->map(function (ProductOptionAssignment $assignment) use ($quantity) {
            $option = $assignment->option;
            $additionalPrice = (float) $option->additional_price;
            $isFree = (int) $option->free_from_qty > 0
                && $quantity >= (int) $option->free_from_qty;
            $priceType = $option->price_type ?: 'per_item';
            $linePrice = $isFree
                ? 0
                : $additionalPrice * ($priceType === 'per_order' ? 1 : $quantity);

            return [
                'group_id' => (int) $option->option_group_id,
                'group_name' => $option->group?->group_name ?: '',
                'display_type' => $option->group?->display_type ?: 'button',
                'option_id' => (int) $option->option_id,
                'option_name' => $option->option_name,
                'option_detail' => $option->option_detail,
                'additional_price' => $additionalPrice,
                'price_type' => $priceType,
                'free_from_qty' => $option->free_from_qty ? (int) $option->free_from_qty : null,
                'line_price' => (int) round($linePrice),
                'quantity_rule' => [
                    'type' => $assignment->qty_rule_type,
                    'min' => $assignment->min_qty ? (int) $assignment->min_qty : null,
                    'max' => $assignment->max_qty ? (int) $assignment->max_qty : null,
                    'exact' => $assignment->exact_qty ? (int) $assignment->exact_qty : null,
                ],
            ];
        })->values()->all();

        $unitPrice = $this->getProductPrice($product);
        $newArtworks = $this->storeArtworkFiles($request, $itemId);
        $item = [
            'id' => $itemId,
            'product_id' => (int) $product->product_id,
            'product_slug' => filled($product->product_code)
                ? $product->product_code
                : (string) $product->product_id,
            'product_name' => $product->product_name,
            'image' => $this->imageAssetPath($product->mainImage?->image_path),
            'quantity' => $quantity,
            'base_unit_price' => $unitPrice,
            'selected_options' => $selectedOptions,
            'previous_order_numbers' => $this->normalizePreviousOrderNumbers(
                $validated['previous_order_number'] ?? []
            ),
            'font_entries' => $this->normalizeFontEntries($validated['font_entries'] ?? []),
            'artworks' => $this->mergeArtworkFiles(
                $existingItem['artworks'] ?? [],
                $newArtworks
            ),
            'custom_fields' => $this->normalizeCustomFields($request->except([
                '_token',
                'product',
                'cart_item_id',
                'quantity',
                'total_price',
                'options',
                'previous_order_number',
                'font_entries',
                'artwork',
            ])),
            'added_at' => $existingItem['added_at'] ?? now()->toIso8601String(),
            'updated_at' => now()->toIso8601String(),
        ];
        $item['quantity_limits'] = $this->quantityLimits($selectedOptions);
        $item = $this->recalculateItem($item, $quantity);

        $cart[$itemId] = $item;
        $request->session()->put('cart.items', $cart);

        return redirect()->route('cart.index')->with(
            'cart_success',
            $existingItem ? 'カートの商品を更新しました。' : '商品をカートに追加しました。'
        );
    }

    public function update(Request $request, string $item): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);
        $cart = $request->session()->get('cart.items', []);

        abort_unless(isset($cart[$item]), 404);

        $quantity = (int) $validated['quantity'];
        $cart[$item] = $this->syncQuantityRules($cart[$item]);
        $this->validateStoredQuantity($quantity, $cart[$item]['selected_options'] ?? []);
        $cart[$item] = $this->recalculateItem($cart[$item], $quantity);
        $request->session()->put('cart.items', $cart);

        if ($request->expectsJson()) {
            return response()->json([
                'item' => [
                    'quantity' => $cart[$item]['quantity'],
                    'line_subtotal' => $cart[$item]['line_subtotal'],
                    'quantity_limits' => $cart[$item]['quantity_limits'],
                ],
                'summary' => CartPricing::summary(array_values($cart)),
            ]);
        }

        return redirect()->route('cart.index')->with('cart_success', '数量を更新しました。');
    }

    public function destroy(Request $request, string $item): RedirectResponse
    {
        $cart = $request->session()->get('cart.items', []);
        abort_unless(isset($cart[$item]), 404);

        foreach ($cart[$item]['artworks'] ?? [] as $artwork) {
            if (! empty($artwork['path'])) {
                Storage::disk('public')->delete($artwork['path']);
            }
        }

        unset($cart[$item]);
        $request->session()->put('cart.items', $cart);

        return redirect()->route('cart.index')->with('cart_success', '商品をカートから削除しました。');
    }

    private function registeredCustomerProfile(User $user): array
    {
        $user->loadMissing(['mainContact', 'mainShippingAddress']);

        $previousCustomer = null;

        if (Schema::hasTable('orders') && Schema::hasTable('order_customers')) {
            $previousCustomer = $user->orders()
                ->with('customer')
                ->latest('order_id')
                ->first()?->customer;
        }

        $contact = $user->mainContact;
        $address = $user->mainShippingAddress;
        $name = filled($user->name)
            ? trim((string) $user->name)
            : trim(implode(' ', array_filter([$user->last_name, $user->first_name])));
        $nameKana = trim(implode(' ', array_filter([
            $user->last_name_kana,
            $user->first_name_kana,
        ])));
        $companyName = $user->company_name
            ?: $address?->company_name
            ?: $previousCustomer?->company_name;
        $customerType = $user->customer_type
            ?: $previousCustomer?->customer_type
            ?: (filled($companyName) ? 'corporate' : 'individual');
        $postalCode = preg_replace(
            '/\D/',
            '',
            (string) ($address?->zip_code ?: $previousCustomer?->personal_postcode)
        );

        return [
            'customer_type' => $customerType,
            'name' => $name ?: $previousCustomer?->personal_name,
            'name_kana' => $nameKana ?: $previousCustomer?->personal_name_kana,
            'company_name' => $companyName,
            'company_name_kana' => $user->company_name_kana ?: $previousCustomer?->company_name_kana,
            'email' => $user->email ?: $contact?->email ?: $previousCustomer?->personal_email,
            'phone' => $user->phone ?: $contact?->phone ?: $address?->phone ?: $previousCustomer?->personal_phone,
            'postal_code_front' => strlen($postalCode) === 7 ? substr($postalCode, 0, 3) : '',
            'postal_code_back' => strlen($postalCode) === 7 ? substr($postalCode, 3, 4) : '',
            'prefecture' => $address?->state ?: $previousCustomer?->personal_province,
            'city' => $address?->city ?: $previousCustomer?->personal_city,
            'address' => trim(implode(' ', array_filter([
                $address?->address ?: $previousCustomer?->personal_area,
                $address?->apartment,
            ]))),
        ];
    }

    private function checkoutValidator(array $customer)
    {
        $detailsRequired = ($customer['info_method'] ?? '') === '詳細情報を全て入力する';
        $addressesRequired = ($customer['info_method'] ?? '') !== '入力省略(営業より連絡します)';
        $shippingRequired = $addressesRequired && ($customer['same_as_customer'] ?? null) !== '1';
        $billingRequired = $addressesRequired && ($customer['billing_address_type'] ?? '') === 'different';

        return Validator::make($customer, [
            'customer_type' => ['required', 'in:corporate,individual'],
            'name' => ['required', 'string', 'max:255'],
            'name_kana' => ['required', 'string', 'max:255'],
            'company_name' => [($customer['customer_type'] ?? '') === 'corporate' && $detailsRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'company_name_kana' => [($customer['customer_type'] ?? '') === 'corporate' && $detailsRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'info_method' => ['required', 'in:詳細情報を全て入力する,入力省略(営業より連絡します),メールの署名等をコピーする'],
            'signature_text' => [($customer['info_method'] ?? '') === 'メールの署名等をコピーする' ? 'required' : 'nullable', 'string', 'max:5000'],
            'postal_code_front' => [$detailsRequired ? 'required' : 'nullable', 'digits:3'],
            'postal_code_back' => [$detailsRequired ? 'required' : 'nullable', 'digits:4'],
            'prefecture' => [$detailsRequired ? 'required' : 'nullable', 'string', 'max:100'],
            'city' => [$detailsRequired ? 'required' : 'nullable', 'string', 'max:150'],
            'address' => [$detailsRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'shipping_name' => [$shippingRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'shipping_name_kana' => [$shippingRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'shipping_postal_code_front' => [$shippingRequired ? 'required' : 'nullable', 'digits:3'],
            'shipping_postal_code_back' => [$shippingRequired ? 'required' : 'nullable', 'digits:4'],
            'shipping_prefecture' => [$shippingRequired ? 'required' : 'nullable', 'string', 'max:100'],
            'shipping_city' => [$shippingRequired ? 'required' : 'nullable', 'string', 'max:150'],
            'shipping_address' => [$shippingRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'billing_address_type' => ['nullable', 'in:same_as_customer,same_as_shipping,different'],
            'billing_name' => [$billingRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'billing_name_kana' => [$billingRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'billing_postal_code_front' => [$billingRequired ? 'required' : 'nullable', 'digits:3'],
            'billing_postal_code_back' => [$billingRequired ? 'required' : 'nullable', 'digits:4'],
            'billing_prefecture' => [$billingRequired ? 'required' : 'nullable', 'string', 'max:100'],
            'billing_city' => [$billingRequired ? 'required' : 'nullable', 'string', 'max:150'],
            'billing_address' => [$billingRequired ? 'required' : 'nullable', 'string', 'max:255'],
            'payment_method' => ['required', 'in:bank_transfer'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ], [
            'required' => ':attributeを入力してください。',
            'email' => ':attributeの形式が正しくありません。',
            'digits' => ':attributeは:digits桁で入力してください。',
            'in' => ':attributeの選択内容が正しくありません。',
            'max.string' => ':attributeは:max文字以内で入力してください。',
        ], [
            'customer_type' => 'お客様区分',
            'name' => 'お名前',
            'name_kana' => 'フリガナ',
            'company_name' => '会社名',
            'company_name_kana' => '会社名（フリガナ）',
            'email' => 'メールアドレス',
            'phone' => '電話番号',
            'info_method' => '情報入力方法',
            'signature_text' => 'メールの署名等',
            'postal_code_front' => '郵便番号（前3桁）',
            'postal_code_back' => '郵便番号（後4桁）',
            'prefecture' => '都道府県',
            'city' => '市区町村',
            'address' => '町名・番地',
            'shipping_name' => 'お届け先のお名前',
            'shipping_name_kana' => 'お届け先のフリガナ',
            'shipping_postal_code_front' => 'お届け先の郵便番号（前3桁）',
            'shipping_postal_code_back' => 'お届け先の郵便番号（後4桁）',
            'shipping_prefecture' => 'お届け先の都道府県',
            'shipping_city' => 'お届け先の市区町村',
            'shipping_address' => 'お届け先の町名・番地',
            'billing_address_type' => '請求先情報',
            'billing_name' => '請求先のお名前',
            'billing_name_kana' => '請求先のフリガナ',
            'billing_postal_code_front' => '請求先の郵便番号（前3桁）',
            'billing_postal_code_back' => '請求先の郵便番号（後4桁）',
            'billing_prefecture' => '請求先の都道府県',
            'billing_city' => '請求先の市区町村',
            'billing_address' => '請求先の町名・番地',
            'payment_method' => 'お支払い方法',
            'notes' => 'ご連絡事項',
        ]);
    }

    private function createOrderCustomer(Order $order, array $customer): void
    {
        $personalAddress = [
            'name' => $customer['name'] ?? null,
            'name_kana' => $customer['name_kana'] ?? null,
            'postcode' => $this->postcode($customer, 'postal_code'),
            'province' => $customer['prefecture'] ?? null,
            'city' => $customer['city'] ?? null,
            'area' => $customer['address'] ?? null,
        ];

        $sameAsCustomer = ($customer['same_as_customer'] ?? null) === '1';
        $shippingAddress = $sameAsCustomer ? $personalAddress : [
            'name' => $customer['shipping_name'] ?? null,
            'name_kana' => $customer['shipping_name_kana'] ?? null,
            'postcode' => $this->postcode($customer, 'shipping_postal_code'),
            'province' => $customer['shipping_prefecture'] ?? null,
            'city' => $customer['shipping_city'] ?? null,
            'area' => $customer['shipping_address'] ?? null,
        ];

        $billingType = $customer['billing_address_type'] ?? 'same_as_customer';
        $billingAddress = match ($billingType) {
            'same_as_shipping' => $shippingAddress,
            'different' => [
                'name' => $customer['billing_name'] ?? null,
                'name_kana' => $customer['billing_name_kana'] ?? null,
                'postcode' => $this->postcode($customer, 'billing_postal_code'),
                'province' => $customer['billing_prefecture'] ?? null,
                'city' => $customer['billing_city'] ?? null,
                'area' => $customer['billing_address'] ?? null,
            ],
            default => $personalAddress,
        };

        $order->customer()->create([
            'customer_type' => $customer['customer_type'] ?? null,
            'personal_name' => $personalAddress['name'],
            'personal_name_kana' => $personalAddress['name_kana'],
            'company_name' => $customer['company_name'] ?? null,
            'company_name_kana' => $customer['company_name_kana'] ?? null,
            'personal_first_name' => $personalAddress['name'],
            'personal_last_name' => null,
            'personal_phone' => $customer['phone'] ?? null,
            'personal_email' => $customer['email'] ?? null,
            'personal_postcode' => $personalAddress['postcode'],
            'personal_province' => $personalAddress['province'],
            'personal_city' => $personalAddress['city'],
            'personal_area' => $personalAddress['area'],
            'same_as_customer' => $sameAsCustomer,
            'shipping_name' => $shippingAddress['name'],
            'shipping_name_kana' => $shippingAddress['name_kana'],
            'shipping_postcode' => $shippingAddress['postcode'],
            'shipping_province' => $shippingAddress['province'],
            'shipping_city' => $shippingAddress['city'],
            'shipping_area' => $shippingAddress['area'],
            'shipping_district' => $shippingAddress['city'],
            'shipping_subdistrict' => $shippingAddress['area'],
            'shipping_address' => trim(implode('', array_filter([$shippingAddress['province'], $shippingAddress['city'], $shippingAddress['area']]))),
            'billing_address_type' => $billingType,
            'billing_name' => $billingAddress['name'],
            'billing_name_kana' => $billingAddress['name_kana'],
            'billing_first_name' => $billingAddress['name'],
            'billing_last_name' => null,
            'billing_phone' => $customer['phone'] ?? null,
            'billing_email' => $customer['email'] ?? null,
            'billing_postcode' => $billingAddress['postcode'],
            'billing_province' => $billingAddress['province'],
            'billing_city' => $billingAddress['city'],
            'billing_area' => $billingAddress['area'],
            'billing_district' => $billingAddress['city'],
            'billing_subdistrict' => $billingAddress['area'],
            'billing_address' => trim(implode('', array_filter([$billingAddress['province'], $billingAddress['city'], $billingAddress['area']]))),
        ]);
    }

    private function postcode(array $data, string $prefix): ?string
    {
        $front = trim((string) ($data[$prefix.'_front'] ?? ''));
        $back = trim((string) ($data[$prefix.'_back'] ?? ''));

        return $front !== '' && $back !== '' ? $front.'-'.$back : null;
    }

    private function generateOrderNumber(): string
    {
        do {
            $orderNo = 'ODR_TS_'.now()->format('YmdHisv').random_int(10, 99);
        } while (Order::query()->where('order_no', $orderNo)->exists());

        return $orderNo;
    }

    private function finishCheckout(Request $request, Order $order, bool $emailSent): RedirectResponse
    {
        $request->session()->put([
            'checkout.completed_order_no' => $order->order_no,
            'checkout.email_sent' => $emailSent,
        ]);
        $request->session()->forget([
            'cart.items',
            'checkout.customer',
            'checkout.token',
        ]);

        return redirect()->route('checkout.complete');
    }

    private function findProduct(string $slug): Product
    {
        $language = request()->cookie('dev') === '1' ? 'en' : 'ja';
        $languages = $language === 'pt' ? ['pt'] : [$language, 'pt'];

        return Product::query()
            ->with([
                'mainImage',
                'displayPriceTier',
                'priceTiers',
                'optionAssignments' => fn ($query) => $query
                    ->where('is_active', 1)
                    ->orderBy('sort_order'),
                'optionAssignments.option' => fn ($query) => $query
                    ->where('is_active', 1)
                    ->with('group'),
            ])
            ->where('is_active', 1)
            ->whereIn('language', $languages)
            ->where(function (Builder $query) use ($slug) {
                $query->where('product_code', $slug);

                if (ctype_digit($slug)) {
                    $query->orWhere('product_id', (int) $slug);
                }
            })
            ->orderByRaw('CASE WHEN language = ? THEN 0 ELSE 1 END', [$language])
            ->firstOrFail();
    }

    private function selectedAssignments(Product $product, array $submittedOptions)
    {
        $submittedOptions = collect($submittedOptions)
            ->filter(fn ($optionId) => filled($optionId))
            ->mapWithKeys(fn ($optionId, $groupId) => [(int) $groupId => (int) $optionId]);
        $assignments = $product->optionAssignments
            ->filter(fn ($assignment) => $assignment->option?->group?->is_active)
            ->keyBy(fn ($assignment) => (int) $assignment->option_id);

        return $submittedOptions->map(function ($optionId, $groupId) use ($assignments) {
            $assignment = $assignments->get($optionId);

            if (! $assignment || (int) $assignment->option->option_group_id !== $groupId) {
                throw ValidationException::withMessages([
                    'options' => '選択されたオプションが無効です。もう一度選択してください。',
                ]);
            }

            return $assignment;
        })->values();
    }

    private function validateQuantity(int $quantity, $assignments): void
    {
        $this->validateStoredQuantity($quantity, $assignments->map(fn ($assignment) => [
            'quantity_rule' => [
                'type' => $assignment->qty_rule_type,
                'min' => $assignment->min_qty,
                'max' => $assignment->max_qty,
                'exact' => $assignment->exact_qty,
            ],
        ])->all());
    }

    private function validateStoredQuantity(int $quantity, array $selectedOptions): void
    {
        $limits = $this->quantityLimits($selectedOptions);

        if ($limits['conflict']
            || ($limits['min'] !== null && $quantity < $limits['min'])
            || ($limits['max'] !== null && $quantity > $limits['max'])) {
            throw ValidationException::withMessages([
                'quantity' => '選択したオプションで指定された数量条件を確認してください。',
            ]);
        }
    }

    private function syncQuantityRules(array $item): array
    {
        $optionIds = collect($item['selected_options'] ?? [])
            ->pluck('option_id')
            ->filter()
            ->map(fn ($optionId) => (int) $optionId)
            ->values();

        if ($optionIds->isEmpty()) {
            $item['quantity_limits'] = $this->quantityLimits($item['selected_options'] ?? []);

            return $item;
        }

        $assignments = ProductOptionAssignment::query()
            ->where('product_id', (int) $item['product_id'])
            ->where('is_active', 1)
            ->whereIn('option_id', $optionIds)
            ->get()
            ->keyBy(fn ($assignment) => (int) $assignment->option_id);

        foreach ($item['selected_options'] as &$option) {
            $assignment = $assignments->get((int) ($option['option_id'] ?? 0));
            $option['quantity_rule'] = [
                'type' => $assignment?->qty_rule_type,
                'min' => $assignment?->min_qty ? (int) $assignment->min_qty : null,
                'max' => $assignment?->max_qty ? (int) $assignment->max_qty : null,
                'exact' => $assignment?->exact_qty ? (int) $assignment->exact_qty : null,
            ];
        }
        unset($option);

        $item['quantity_limits'] = $this->quantityLimits($item['selected_options']);

        return $item;
    }

    private function quantityLimits(array $selectedOptions): array
    {
        $minimums = [];
        $maximums = [];
        $hasRule = false;

        foreach ($selectedOptions as $option) {
            $rule = $option['quantity_rule'] ?? [];
            $type = $rule['type'] ?? null;

            if ($type === 'exact' && (int) ($rule['exact'] ?? 0) > 0) {
                $minimums[] = (int) $rule['exact'];
                $maximums[] = (int) $rule['exact'];
                $hasRule = true;
            }

            if (in_array($type, ['min', 'range'], true) && (int) ($rule['min'] ?? 0) > 0) {
                $minimums[] = (int) $rule['min'];
                $hasRule = true;
            }

            if (in_array($type, ['max', 'range'], true) && (int) ($rule['max'] ?? 0) > 0) {
                $maximums[] = (int) $rule['max'];
                $hasRule = true;
            }
        }

        $minimum = $minimums ? max($minimums) : null;
        $maximum = $maximums ? min($maximums) : null;
        $conflict = $minimum !== null && $maximum !== null && $minimum > $maximum;

        if ($conflict) {
            $note = '選択したオプションの数量条件が一致しません。';
        } elseif ($minimum !== null && $maximum !== null && $minimum === $maximum) {
            $note = '数量は'.$minimum.'個に指定されています。';
        } elseif ($minimum !== null && $maximum !== null) {
            $note = 'ご注文数量は'.$minimum.'〜'.$maximum.'個です。';
        } elseif ($minimum !== null) {
            $note = 'ご注文数量は'.$minimum.'個以上です。';
        } elseif ($maximum !== null) {
            $note = 'ご注文数量は'.$maximum.'個以下です。';
        } else {
            $note = null;
        }

        return [
            'has_rule' => $hasRule,
            'min' => $minimum,
            'max' => $maximum,
            'conflict' => $conflict,
            'note' => $note,
        ];
    }

    private function validateArtworkFiles(Request $request): void
    {
        $allowedExtensions = ['ai', 'pdf', 'eps', 'psd', 'png', 'jpg', 'jpeg'];

        foreach ((array) $request->file('artwork', []) as $file) {
            if ($file && ! in_array(strtolower($file->getClientOriginalExtension()), $allowedExtensions, true)) {
                throw ValidationException::withMessages([
                    'artwork' => '入稿データのファイル形式が正しくありません。',
                ]);
            }
        }
    }

    private function storeArtworkFiles(Request $request, string $itemId): array
    {
        $artworks = [];

        foreach ((array) $request->file('artwork', []) as $groupId => $file) {
            if (! $file) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            $filename = Str::uuid().($extension ? '.'.$extension : '');
            $path = $file->storeAs('cart-artwork/'.$itemId, $filename, 'public');
            $artworks[] = [
                'group_id' => (int) $groupId,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'size' => $file->getSize(),
            ];
        }

        return $artworks;
    }

    private function mergeArtworkFiles(array $existingArtworks, array $newArtworks): array
    {
        if ($newArtworks === []) {
            return $existingArtworks;
        }

        $replacedGroupIds = collect($newArtworks)
            ->pluck('group_id')
            ->map(fn ($groupId) => (int) $groupId);
        $remainingArtworks = collect($existingArtworks)
            ->reject(function ($artwork) use ($replacedGroupIds) {
                $isReplaced = $replacedGroupIds->contains((int) ($artwork['group_id'] ?? 0));

                if ($isReplaced && ! empty($artwork['path'])) {
                    Storage::disk('public')->delete($artwork['path']);
                }

                return $isReplaced;
            })
            ->values()
            ->all();

        return [...$remainingArtworks, ...$newArtworks];
    }

    private function normalizePreviousOrderNumbers(mixed $values): array
    {
        if (! is_array($values)) {
            $values = ['legacy' => $values];
        }

        return collect($values)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->mapWithKeys(fn ($value, $groupId) => [(string) $groupId => $value])
            ->all();
    }

    private function normalizeFontEntries(array $groups): array
    {
        return collect($groups)->map(function ($entries) {
            return collect((array) $entries)->map(fn ($entry) => [
                'text' => trim((string) ($entry['text'] ?? '')),
                'font' => trim((string) ($entry['font'] ?? '')),
                'size' => max(1, min(200, (int) ($entry['size'] ?? 12))),
            ])->values()->all();
        })->all();
    }

    private function normalizeCustomFields(array $fields): array
    {
        return collect($fields)->map(function ($value) {
            if (is_array($value)) {
                return $this->normalizeCustomFields($value);
            }

            return is_scalar($value) ? trim((string) $value) : null;
        })->filter(fn ($value) => $value !== null && $value !== '')->all();
    }

    private function recalculateItem(array $item, int $quantity): array
    {
        $item['quantity'] = $quantity;
        $item['base_subtotal'] = (int) round((float) $item['base_unit_price'] * $quantity);
        $optionSubtotal = 0;

        foreach ($item['selected_options'] as &$option) {
            $isFree = (int) ($option['free_from_qty'] ?? 0) > 0
                && $quantity >= (int) $option['free_from_qty'];
            $option['line_price'] = $isFree
                ? 0
                : (int) round((float) $option['additional_price'] * (
                    ($option['price_type'] ?? 'per_item') === 'per_order' ? 1 : $quantity
                ));
            $optionSubtotal += $option['line_price'];
        }
        unset($option);

        $item['option_subtotal'] = $optionSubtotal;
        $item['line_subtotal'] = $item['base_subtotal'] + $optionSubtotal;

        return $item;
    }

    private function getProductPrice(Product $product): float
    {
        if ($product->displayPriceTier) {
            return (float) ($product->displayPriceTier->unit_price ?? 0);
        }

        return (float) ($product->priceTiers->first()?->unit_price ?? 0);
    }

    private function imageAssetPath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        $normalizedPath = ltrim(str_replace('\\', '/', $path), '/');

        if (Str::startsWith($normalizedPath, ['http://', 'https://', 'storage/', 'assets/'])) {
            return $normalizedPath;
        }

        return 'storage/'.ltrim(Str::after($normalizedPath, 'public/'), '/');
    }
}
