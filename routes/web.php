<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Voyager admin routes
Route::group(['prefix' => 'admin'], function () {
    $namespacePrefix = '\\'.config('voyager.controllers.namespace').'\\';
    Route::get('/status/activate', $namespacePrefix . 'StatusController@activate')->name('voyager.status.activate');
    Route::get('/status/deactivate', $namespacePrefix . 'StatusController@deactivate')->name('voyager.status.deactivate');

    // product attributes
    Route::get('/products/{product}/attributes/edit', $namespacePrefix . 'VoyagerProductController@attributesEdit')->name('voyager.products.attributes.edit');
    Route::post('/products/{product}/attributes', $namespacePrefix . 'VoyagerProductController@attributesUpdate')->name('voyager.products.attributes.update');

    // product variants
    Route::get('/products/{product}/variants', $namespacePrefix . 'VoyagerProductController@variants')->name('voyager.products.variants');
    Route::get('/products/{product}/variants/create', $namespacePrefix . 'VoyagerProductController@variantsCreate')->name('voyager.products.variants.create');
    Route::post('/products/{product}/variants/store', $namespacePrefix . 'VoyagerProductController@variantsStore')->name('voyager.products.variants.store');
    Route::get('/products/{product}/variants/{variant}/edit', $namespacePrefix . 'VoyagerProductController@variantsEdit')->name('voyager.products.variants.edit');
    Route::put('/products/{product}/variants/{variant}/update', $namespacePrefix . 'VoyagerProductController@variantsUpdate')->name('voyager.products.variants.update');
    Route::delete('/products/{product}/variants/{variant}', $namespacePrefix . 'VoyagerProductController@variantsDestroy')->name('voyager.products.variants.destroy');

    Voyager::routes();
});

// cache
Route::get('optimize-cache', function() {
    Artisan::call('optimize');
    return "Optimized!";
});
Route::get('optimize-clear', function() {
    Artisan::call('optimize:clear');
    return "Optimize clear!";
});

// telegram bot
Route::post('telegram-bot-nWq723bZP7x5cfF', "TelegramBotController@index")->name('telegram-bot');
// Route::get('telegram-bot/sethook-nWq723bZP7x5cfF', "TelegramBotController@sethook")->name('telegram-bot.sethook');
// Route::get('telegram-bot/deletehook-nWq723bZP7x5cfF', "TelegramBotController@deletehook")->name('telegram-bot.deletehook');

// Payment
Route::post('paycom-xBrGbjU2RyaNwBY', 'PaymentGatewayController@paycom')->name('payment-gateway.paycom');
Route::any('click-SVNfd45qbr5dW9b/prepare', 'PaymentGatewayController@click')->name('payment-gateway.click.prepare');
Route::any('click-SVNfd45qbr5dW9b/complete', 'PaymentGatewayController@click')->name('payment-gateway.click.complete');

Route::get('testing-29szTThmfP35dFx', 'TestingController@index')->name('testing.index');

// synchronization
Route::post('synchro/torgsoft-LYtkVn6MhH2TqdhK', 'SynchroController@torgsoft')->name('synchro.torgsoft');
// Route::get('synchro/torgsoft', 'SynchroController@torgsoft')->name('synchro.torgsoft.get');

// Localized site routes
Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ /*'localeSessionRedirect', */'localizationRedirect', 'localeViewPath', 'localize' ]
    ],  function() {

    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
    // Route::group(['middleware' => ['auth']], function() {

    // home page
    Route::get('/', "HomeController@index")->name('home');
    Route::get('/home/latest-products/{category}', "HomeController@latestProducts")->name('home.latest-products');


    // search
    Route::get('search', "SearchController@index")->name('search');
    Route::get('search/ajax', "SearchController@ajax")->name('search.ajax');

    // contacts
    Route::get('contacts', "ContactController@index")->name('contacts');
    Route::post('contacts/send', "ContactController@send")->name('contacts.send');

    // subscriber
    Route::post('subscriber/subscribe', "SubscriberController@subscribe")->name('subscriber.subscribe');
    Route::get('subscriber/unsubscribe', "SubscriberController@unsubscribe")->name('subscriber.unsubscribe');

    // category view
    Route::get('categories', "CategoryController@index")->name('categories');
    Route::get('category/{category}-{slug}', "CategoryController@view")->name('category');

    // brand view
    Route::get('brands', "BrandController@index")->name('brands');
    Route::get('brand/{brand}-{slug}', "BrandController@view")->name('brand');

    // product view
    Route::get('product/{product}-{slug}', "ProductController@view")->name('product');
    Route::get('sale', "ProductController@sale")->name('sale');
    Route::get('featured', "ProductController@featured")->name('featured');

    // products routes
    Route::get('products/create', "ProductController@create")->name('products.create');
    Route::post('products', "ProductController@store")->name('products.store');
    Route::get('products/{product}/edit', "ProductController@edit")->name('products.edit');
    Route::put('products/{product}/', "ProductController@update")->name('products.update');
    Route::delete('products/{product}/', "ProductController@destroy")->name('products.destroy');

    // product attributes
    Route::get('products/{product}/attributes/edit', "ProductController@attributesEdit")->name('products.attributes.edit');
    Route::post('products/{product}/attributes', 'ProductController@attributesUpdate')->name('products.attributes.update');

    // product variants
    Route::get('products/{product}/variants', 'ProductController@variants')->name('products.variants');
    Route::get('products/{product}/variants/create', 'ProductController@variantsCreate')->name('products.variants.create');
    Route::post('products/{product}/variants/store', 'ProductController@variantsStore')->name('products.variants.store');
    Route::get('products/{product}/variants/{variant}/edit', 'ProductController@variantsEdit')->name('products.variants.edit');
    Route::put('products/{product}/variants/{variant}/update', 'ProductController@variantsUpdate')->name('products.variants.update');
    Route::delete('products/{product}/variants/{variant}', 'ProductController@variantsDestroy')->name('products.variants.destroy');

    // reviews
    Route::post('reviews/store', "ReviewController@store")->name('reviews.store');

    // regular pages
    Route::get('page/{page}-{slug}', "PageController@index")->name('page');
    Route::get('page/{page}-{slug}/print', "PageController@print")->name('page.print');

    // publications pages
    Route::get('news', "PublicationController@news")->name('news');
    Route::get('events', "PublicationController@events")->name('events');
    Route::get('faq', "PublicationController@faq")->name('faq');
    Route::get('competitions', "PublicationController@competitions")->name('competitions');
    Route::get('projects', "PublicationController@projects")->name('projects');
    Route::get('ads', "PublicationController@ads")->name('ads');
    Route::get('mass-media', "PublicationController@massMedia")->name('mass-media');
    Route::get('useful-links', "PublicationController@usefulLinks")->name('useful-links');
    Route::get('publications/{publication}-{slug}', "PublicationController@show")->name('publications.show');
    Route::get('publications/{publication}/increment/views', "PublicationController@incrementViews")->name('publications.increment.views');
    Route::get('publications/{publication}-{slug}/print', "PublicationController@print")->name('publications.print');

    // banner statistics routes
    Route::get('banner/{banner}/increment/clicks', "BannerController@incrementClicks")->name('banner.increment.clicks');
    Route::get('banner/{banner}/increment/views', "BannerController@incrementViews")->name('banner.increment.views');
    // });

    // cart routes
    Route::get('cart','CartController@index')->name('cart.index');
    Route::get('cart/checkout','CartController@checkout')->name('cart.checkout');
    Route::post('cart','CartController@add')->name('cart.add');
    Route::post('cart/update','CartController@update')->name('cart.update');
    Route::delete('cart/{id}','CartController@delete')->name('cart.delete');
    Route::post('cart/conditions','CartController@addCondition')->name('cart.addCondition');
    Route::delete('cart/conditions','CartController@clearCartConditions')->name('cart.clearCartConditions');
    Route::get('cart/debug','CartController@debug')->name('cart.debug');

    // wishlist routes
    Route::get('wishlist','WishlistController@index')->name('wishlist.index');
    Route::post('wishlist','WishlistController@add')->name('wishlist.add');
    Route::delete('wishlist/{id}','WishlistController@delete')->name('wishlist.delete');

    // order routes
    Route::get('order/{order}-{check}','OrderController@show')->name('order.show');
    Route::post('order','OrderController@add')->name('order.add');

    // profile routes
    Route::get('profile', "ProfileController@index")->name('profile.show');
    Route::get('profile/edit', "ProfileController@edit")->name('profile.edit');
    Route::put('profile', "ProfileController@update")->name('profile.update');
    Route::post('profile/password', "ProfileController@password")->name('profile.password');
    Route::get('profile/orders', "ProfileController@orders")->name('profile.orders');
    Route::get('profile/products', "ProfileController@products")->name('profile.products');
    Route::get('profile/shop/edit', "ProfileController@shopEdit")->name('profile.shop.edit');
    Route::put('profile/shop', "ProfileController@shopUpdate")->name('profile.shop.update');
    Route::get('profile/request-seller-status', "ProfileController@requestSellerStatus")->name('profile.request-seller-status');

    // shop routes
    Route::get('shops', "ShopController@index")->name('shop.index');
    Route::get('shop/{shop}', "ShopController@show")->name('shop.show');

    // auth routes
    Auth::routes(['verify' => true]);

    // custom auth routes (phone registration)
    Route::get('register/verify', 'Auth\RegisterController@showRegistrationVerifyForm')->name('register.verify');
    Route::post('register/verify', 'Auth\RegisterController@registerVerify')->middleware('throttle:10,60');

    Route::get('password/phone', 'Auth\ForgotPasswordController@showLinkRequestPhoneForm')->name('password.phone');
    Route::post('password/phone', 'Auth\ForgotPasswordController@passwordPhone');
    Route::get('password/phone/verify', 'Auth\ForgotPasswordController@showPasswordPhoneVerifyForm')->name('password.phone.verify');
    Route::post('password/phone/verify', 'Auth\ForgotPasswordController@passwordPhoneVerify')->middleware('throttle:10,60');
});

// non localized routes
