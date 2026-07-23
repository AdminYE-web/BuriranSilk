<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\UserAddressController;
use App\Http\Controllers\UserContactController;
use App\Http\Controllers\UserOrderController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ContactSubmissionController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\GalleryBannerController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\HomeBannerController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\MaterialHomeController;
use App\Http\Controllers\Admin\MenuProductController;
use App\Http\Controllers\Admin\OptionDependencyController;
use App\Http\Controllers\Admin\OptionGroupController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\ProductArtworkTemplateController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductDetailController;
use App\Http\Controllers\Admin\ProductListBannerController;
use App\Http\Controllers\Admin\ProductOptionAssignmentController;
use App\Http\Controllers\Admin\ProductOptionController;
use App\Http\Controllers\Admin\ProductOptionPriceRuleController;
use App\Http\Controllers\Admin\ProductOptionVariantController;
use App\Http\Controllers\Admin\ProductPriceRuleController;
use App\Http\Controllers\Admin\ProductPriceTierController;
use App\Http\Controllers\Admin\ProductTemplateController;
use App\Http\Controllers\Admin\QuotationController;
use App\Http\Controllers\Admin\SystemManagementController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\GoogleLoginController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartQuotationController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductListController;
use App\Models\HomeBanner;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    try {
        $holidayCalendar = Cache::remember(
            'frontend.holiday-calendar.v2',
            now()->addMinutes(30),
            function () {
                $holidays = Http::acceptJson()
                    ->timeout(10)
                    ->retry(2, 200)
                    ->get('https://hotmobily.jp/api/get_holidays.php')
                    ->throw()
                    ->json();

                return collect($holidays)
                    ->filter(function ($holiday) {
                        $type = (int) data_get($holiday, 'extendedProps.type');

                        return filled(data_get($holiday, 'start'))
                            && in_array($type, [2, 3], true);
                    })
                    ->mapWithKeys(function ($holiday) {
                        return [
                            data_get($holiday, 'start') => (int) data_get($holiday, 'extendedProps.type'),
                        ];
                    })
                    ->all();
            }
        );
    } catch (Throwable $exception) {
        report($exception);
        $holidayCalendar = [];
    }

    $heroBanner = HomeBanner::query()
        ->where('is_active', 1)
        ->whereNotNull('image_pc')
        ->orderBy('sort_order')
        ->orderBy('home_banner_id')
        ->first();

    return view('frontend.home.index', compact('holidayCalendar', 'heroBanner'));
})->name('home');

Route::get('/contact', [ContactController::class, 'index'])
    ->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
Route::get('/contact/complete', [ContactController::class, 'complete'])
    ->name('contact.complete');

Route::get('/products', [ProductListController::class, 'index'])
    ->name('products.index');
Route::get('/products/{slug}', [ProductListController::class, 'show'])
    ->name('products.show');

Route::get('/cart', [CartController::class, 'index'])
    ->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])
    ->name('cart.store');
Route::patch('/cart/items/{item}', [CartController::class, 'update'])
    ->name('cart.items.update');
Route::delete('/cart/items/{item}', [CartController::class, 'destroy'])
    ->name('cart.items.destroy');
Route::get('/checkout', [CartController::class, 'checkout'])
    ->name('checkout.index');
Route::get('/checkout/information', [CartController::class, 'information'])
    ->name('checkout.information');
Route::match(['get', 'post'], '/checkout/confirmation', [CartController::class, 'confirmation'])
    ->name('checkout.confirmation');
Route::post('/checkout/orders', [CartController::class, 'placeOrder'])
    ->middleware('throttle:10,1')
    ->name('checkout.orders.store');
Route::get('/checkout/complete', [CartController::class, 'complete'])
    ->name('checkout.complete');
Route::post(
    '/cart/quotation/postal-code',
    [CartQuotationController::class, 'postalCode']
)
    ->middleware('throttle:20,1')
    ->name('cart.quotation.postal-code');

Route::post(
    '/cart/quotation/pdf',
    [CartQuotationController::class, 'download']
)
    ->middleware('throttle:10,1')
    ->name('cart.quotation.download');

Route::get('/login', function () {
    return redirect()->route('home');
})->middleware('guest');

Route::post('/login', [LoginController::class, 'login'])
    ->middleware('guest')
    ->name('login');
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware(['guest', 'throttle:6,1'])
    ->name('password.email');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware(['guest', 'throttle:6,1'])
    ->name('password.update');

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::put('/name', [AccountController::class, 'updateName'])->name('name.update');
    Route::put('/password', [AccountController::class, 'updatePassword'])->name('password.update');
    Route::post('/avatar', [AccountController::class, 'updateAvatar'])->name('avatar.update');
    Route::get('/contacts', [UserContactController::class, 'index'])->name('contacts.index');
    Route::get('/contacts/create', [UserContactController::class, 'create'])->name('contacts.create');
    Route::post('/contacts', [UserContactController::class, 'store'])->name('contacts.store');
    Route::get('/contacts/{contact}/edit', [UserContactController::class, 'edit'])->name('contacts.edit');
    Route::put('/contacts/{contact}', [UserContactController::class, 'update'])->name('contacts.update');
    Route::delete('/contacts/{contact}', [UserContactController::class, 'destroy'])->name('contacts.destroy');
    Route::put('/contacts/{contact}/set-main', [UserContactController::class, 'setMain'])->name('contacts.setMain');
    Route::get('/addresses/{type?}', [UserAddressController::class, 'index'])->name('addresses.index');
    Route::get('/addresses/{type}/create', [UserAddressController::class, 'create'])->name('addresses.create');
    Route::post('/addresses/{type}', [UserAddressController::class, 'store'])->name('addresses.store');
    Route::get('/addresses/{address}/edit', [UserAddressController::class, 'edit'])->name('addresses.edit');
    Route::put('/addresses/{address}', [UserAddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [UserAddressController::class, 'destroy'])->name('addresses.destroy');
    Route::put('/addresses/{address}/set-main', [UserAddressController::class, 'setMain'])->name('addresses.setMain');
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
});

// Both guests registering and authenticated customers adding an address use this lookup.
Route::post('/register/postal-code', [RegisterController::class, 'lookupPostalCode'])
    ->middleware('throttle:20,1')
    ->name('register.postal-code');

Route::middleware('guest')->group(function () {

    /*
      |--------------------------------------------------------------------------
      | Google Login
      |--------------------------------------------------------------------------
      */

    Route::get('/auth/google', [GoogleLoginController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleLoginController::class, 'callback'])
        ->name('google.callback');

    /*
    |--------------------------------------------------------------------------
    | Register
    |--------------------------------------------------------------------------
    */
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');
    Route::get('/register/step1', [RegisterController::class, 'step1'])
        ->name('register.step1');
    Route::post('/register/step1', [RegisterController::class, 'storeStep1'])
        ->name('register.step1.store');
    Route::get('/register/step2', [RegisterController::class, 'step2'])
        ->name('register.step2');
    Route::post('/register/step2', [RegisterController::class, 'storeStep2'])
        ->name('register.step2.store');
    Route::get('/register/step3', [RegisterController::class, 'step3'])
        ->name('register.step3');
    Route::post('/register', [RegisterController::class, 'store'])
        ->name('register.store');
});
Route::get('/register/complete', [RegisterController::class, 'complete'])
    ->middleware('guest')
    ->name('register.complete');
Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::prefix('admin-panel')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [AdminAuthController::class, 'login'])
        ->name('login.submit');

    Route::post('/logout', [AdminAuthController::class, 'logout'])
        ->name('logout');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::post('products/{product}/duplicate', [ProductController::class, 'duplicate'])
            ->name('products.duplicate');

        Route::resource('products', ProductController::class);
        Route::get('products/{product}/options', [ProductOptionAssignmentController::class, 'edit'])
            ->name('products.options.edit');

        Route::put('products/{product}/options', [ProductOptionAssignmentController::class, 'update'])
            ->name('products.options.update');

        Route::resource('product-price-tiers', ProductPriceTierController::class);

        Route::resource('option-groups', OptionGroupController::class);

        Route::resource('product-options', ProductOptionController::class);

        Route::resource('option-dependencies', OptionDependencyController::class);

        Route::resource('product-details', ProductDetailController::class);

        Route::resource('categories', CategoryController::class);
        Route::resource('materials', MaterialController::class);
        Route::resource('product-list-banners', ProductListBannerController::class);

        Route::get('product-options/{option}/variants', [ProductOptionVariantController::class, 'index'])
            ->name('product-options.variants.index');

        Route::get('product-options/{option}/variants/create', [ProductOptionVariantController::class, 'create'])
            ->name('product-options.variants.create');

        Route::post('product-options/{option}/variants', [ProductOptionVariantController::class, 'store'])
            ->name('product-options.variants.store');

        Route::get('product-option-variants/{variant}/edit', [ProductOptionVariantController::class, 'edit'])
            ->name('product-option-variants.edit');

        Route::put('product-option-variants/{variant}', [ProductOptionVariantController::class, 'update'])
            ->name('product-option-variants.update');

        Route::delete('product-option-variants/{variant}', [ProductOptionVariantController::class, 'destroy'])
            ->name('product-option-variants.destroy');

        Route::get('product-price-rules/{productPriceRule}/duplicate', [ProductPriceRuleController::class, 'duplicate'])
            ->name('product-price-rules.duplicate');
        Route::resource('product-price-rules', ProductPriceRuleController::class);
        Route::resource('product-artwork-templates', ProductArtworkTemplateController::class);
        Route::resource('material-homes', MaterialHomeController::class);
        Route::resource('home-banners', HomeBannerController::class);

        // Route::resource('users', UserAdminController::class)->only(['index', 'show']);
        Route::resource('contact-submissions', ContactSubmissionController::class)->only(['index', 'show']);
        Route::get('orders', [OrderAdminController::class, 'index'])
            ->name('orders.index');

        Route::get('orders/{order}/quotation', [OrderAdminController::class, 'downloadQuotation'])
            ->name('orders.quotation');

        Route::get('orders/{order}/invoice', [OrderAdminController::class, 'downloadInvoice'])
            ->name('orders.invoice');

        Route::get('orders/{order}', [OrderAdminController::class, 'show'])
            ->name('orders.show');

        Route::put('orders/{order}/status', [OrderAdminController::class, 'updateStatus'])
            ->name('orders.updateStatus');
        Route::resource('galleries', GalleryController::class);
        Route::resource('gallery-banners', GalleryBannerController::class);
        Route::get('product-price-rules/product-options/{product}', [ProductPriceRuleController::class, 'getProductOptions'])
            ->name('product-price-rules.product-options');
        Route::get('/language/{language}', function ($language) {
            if (! in_array($language, ['pt', 'ja', 'en'])) {
                abort(404);
            }

            session(['admin_product_language' => $language]);

            return back();
        })->name('product-language.switch');
        Route::resource('articles', ArticleController::class);

        Route::post('articles/upload-editor-image', [ArticleController::class, 'uploadEditorImage'])
            ->name('articles.uploadEditorImage');
        Route::resource('product-templates', ProductTemplateController::class);
        Route::post('faqs/update-sort', [FaqController::class, 'updateSort'])
            ->name('faqs.updateSort');
        Route::resource('faqs', FaqController::class);
        Route::patch('quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])
            ->name('quotations.updateStatus');
        Route::resource('quotations', QuotationController::class);

        Route::get('quotations/product-options/{product}', [QuotationController::class, 'productOptions'])
            ->name('quotations.productOptions');

        Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'downloadPdf'])
            ->name('quotations.pdf');
        Route::post('products/{product}/option-groups/update-sort', [ProductOptionAssignmentController::class, 'updateGroupSort'])
            ->name('products.option-groups.updateSort');
        Route::post('/products/{product}/duplicate-translation', [ProductController::class, 'duplicateTranslation'])
            ->name('products.duplicate-translation');
        Route::post('option-groups/{optionGroup}/duplicate-translation', [OptionGroupController::class, 'duplicateTranslation'])
            ->name('option-groups.duplicate-translation');
        Route::post('categories/{category}/duplicate-translation', [CategoryController::class, 'duplicateTranslation'])
            ->name('categories.duplicate-translation');
        Route::post('materials/{material}/duplicate-translation', [MaterialController::class, 'duplicateTranslation'])
            ->name('materials.duplicate-translation');
        Route::post('product-options/{productOption}/duplicate-translation', [ProductOptionController::class, 'duplicateTranslation'])
            ->name('product-options.duplicate-translation');
        Route::post('option-dependencies/{optionDependency}/duplicate-translation', [OptionDependencyController::class, 'duplicateTranslation'])
            ->name('option-dependencies.duplicate-translation');
        Route::post('material-homes/{materialHome}/duplicate-translation', [MaterialHomeController::class, 'duplicateTranslation'])
            ->name('material-homes.duplicate-translation');
        Route::post('galleries/{gallery}/duplicate-translation', [GalleryController::class, 'duplicateTranslation'])
            ->name('galleries.duplicate-translation');
        Route::post('articles/{article}/duplicate-translation', [ArticleController::class, 'duplicateTranslation'])
            ->name('articles.duplicate-translation');

        Route::get('contact-submissions/{submission}/reply', [ContactSubmissionController::class, 'reply'])
            ->name('contact-submissions.reply');

        Route::post('contact-submissions/{submission}/reply', [ContactSubmissionController::class, 'sendReply'])
            ->name('contact-submissions.send-reply');

        // Route::get('system-management', [SystemManagementController::class, 'index'])
        //     ->name('system-management.index');

        // Route::post('system-management', [SystemManagementController::class, 'update'])
        //     ->name('system-management.update');
        // Route::post('users/{user}/email-change', [UserAdminController::class, 'sendEmailChangeVerification'])
        //     ->name('users.email-change.send');

        Route::get('/products/{product}/preview', [ProductController::class, 'preview'])
            ->name('products.preview');
        Route::get('products/{product}/preview-order', [ProductListController::class, 'previewOrder'])
            ->name('products.preview-order');
        // Route::get('/articles/{article}/preview', [BlogController::class, 'preview'])
        //     ->name('articles.preview');
        Route::post('/categories/sort', [CategoryController::class, 'updateSort'])
            ->name('categories.sort');
        Route::get('menu-products', [MenuProductController::class, 'index'])
            ->name('menu-products.index');

        Route::post('menu-products/add', [MenuProductController::class, 'add'])
            ->name('menu-products.add');

        Route::post('menu-products/{product}/remove', [MenuProductController::class, 'remove'])
            ->name('menu-products.remove');

        Route::resource('option-price-rules', ProductOptionPriceRuleController::class)
            ->parameters([
                'option-price-rules' => 'optionPriceRule',
            ]);

        Route::get(
            '/option-price-rules/{optionPriceRule}/duplicate',
            [ProductOptionPriceRuleController::class, 'duplicate']
        )->name('option-price-rules.duplicate');

        Route::middleware('super.admin')->group(function () {
            Route::resource('users', UserAdminController::class)->only(['index', 'show']);

            Route::post('users/{user}/email-change', [UserAdminController::class, 'sendEmailChangeVerification'])
                ->name('users.email-change.send');

            Route::get('system-management', [SystemManagementController::class, 'index'])
                ->name('system-management.index');

            Route::post('system-management', [SystemManagementController::class, 'update'])
                ->name('system-management.update');
        });
    });
});
