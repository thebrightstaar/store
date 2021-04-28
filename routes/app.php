<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| APP Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/redirect/{service}', 'SocialController@redirect');

Route::get('/callback/{service}', 'SocialController@callback');


Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function () {


    Route::group(['namespace' => 'app'/*, 'middleware' => 'guest'*/], function () {
        //guest  user
Route::get('fat','PaymentController@fatoorah');
        route::get('/', 'HomeController@home')->name('home')->middleware('VerifiedUser');
        route::get('category/{slug}', 'CategoryController@productsBySlug')->name('category');
        route::get('product/{slug}', 'ProductController@productsBySlug')->name('product.details');

        /**
         *  Cart routes
         */
        Route::group(['prefix' => 'cart'], function () {
            Route::get('/', 'CartController@getIndex')->name('app.cart.index');
            Route::post('/cart/add/{slug?}', 'CartController@postAdd')->name('app.cart.add');
            Route::post('/update/{slug}', 'CartController@postUpdate')->name('app.cart.update');
            Route::post('/update-all', 'CartController@postUpdateAll')->name('app.cart.update-all');
        });
    });


    Route::group(['namespace' => 'app', 'middleware' => ['auth', 'VerifiedUser']], function () {
        // must be authenticated user and verified
        Route::get('profile', function () {
            return 'You Are Authenticated ';
        });
    });

    Route::group(['namespace' => 'app', 'middleware' => 'auth'], function () {
        // must be authenticated user
        Route::post('verify-user/', 'VerificationCodeController@verify')->name('verify-user');
        Route::get('verify', 'VerificationCodeController@getVerifyPage')->name('get.verification.form');
        Route::get('products/{productId}/reviews', 'ProductReviewController@index')->name('products.reviews.index');
        Route::post('products/{productId}/reviews', 'ProductReviewController@store')->name('products.reviews.store');
        Route::get('payment/{amount}', 'PaymentController@getPayments') -> name('payment');
        Route::post('payment', 'PaymentController@processPayment') -> name('payment.process');

    });

});

Route::group(['namespace' => 'app', 'middleware' => 'auth'], function () {
    Route::post('wishlist', 'WishlistController@store')->name('wishlist.store');
    Route::delete('wishlist', 'WishlistController@destroy')->name('wishlist.destroy');
    Route::get('wishlist/products', 'WishlistController@index')->name('wishlist.products.index');
});



