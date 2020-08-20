<?php

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

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

$domain = config('site.domain');

//////////////// субдомен account ////////////////
Route::domain('account.' . $domain)->namespace('Account')->group(function () {

	Auth::routes(['register' => false, 'verify' => true, 'reset' => true]);
	Route::post('login', 'Auth\LoginController@login')->name('login');


    Route::get('email/verify/{id}/{hash}/{email}/{old}', 'Auth\VerificationController@verify')->name('verification.verify');

    Route::get('/email/assigment', 'EmailController@assigment')
        ->middleware('check.email:0')
        ->name('account.email.assigment');
    Route::post('/email/assigment', 'EmailController@makeAssigment')
        ->middleware('check.email:0')
        ->name('account.email.assigment.make');

    Route::get('/2fa/confirm', 'Auth\Guard2FAController@confirm')->name('2fa');
    Route::post('/2fa/confirm', 'Auth\Guard2FAController@auth')->name('2fa.auth');

    //доступ для пользователей с проверенной почтой
    Route::middleware(['auth', 'check.email'])->as('account.')->group(function () {
        Route::get('/', 'AccountController@index')->name('home');
        Route::get('/settings', 'AccountController@settings')->name('settings');
        Route::post('/settings/set-lang', 'AccountController@setLang')->name('settings.set-lang');

        Route::post('/email/change', 'EmailController@update')->name('email.update');
        Route::get('/email/confirm/{id}/{hash}/{token}/{new}', 'EmailController@confirmDetach')->name('email.confirm');

        Route::prefix('/2fa')->as('2fa.')->group(function () {
            Route::post('/setup', 'Guard2FAController@setup')->name('setup');
            Route::post('/setup/set', 'Guard2FAController@set')->name('set');
            Route::post('/disable', 'Guard2FAController@disable')->name('disable');
            Route::post('/keys', 'Guard2FAController@downloadKeys')->name('keys.download');
        });

		Route::post('/password/change', 'Auth\ChangePasswordController@change')->name('password.change.update');

        Route::get('/playtime/reset', 'AccountController@resetPlayTimeConfirmation')->name('playtime.reset.confirm');
    });

});


//////////////// субдомен store ////////////////
Route::domain('store.' . $domain)->namespace('Store')->as('store.')->group(function () {
    Route::get('/', 'IndexController@index')->name('home');
    Route::post('/', 'IndexController@login')->name('login');

    Route::post('/search', 'IndexController@loadProducts');

    Route::post('/buy/{product}', 'CartController@buy')->name('cart.buy'); //оплата за рубли с переходом на кассу

    Route::prefix('/cart')->middleware(['auth', 'check.email'])->group(function () {
        Route::get('/', 'CartController@index')->name('cart');
        Route::post('/put/{product}', 'CartController@put')->name('cart.put');
        Route::post('/update', 'CartController@update')->name('cart.update');
        Route::post('/delete/{cart}', 'CartController@delete')->name('cart.delete');
        Route::post('/pay', 'CartController@pay')->name('cart.pay'); //оплата корзины
        Route::post('/promo', 'CartController@checkPromo')->name('cart.promo');
    });

	Route::any('/payment/pay/{payerName}', 'PaymentController@pay');
});

//////////////// основной домен ////////////////
Route::get('/test/a', 'TestController@a');

Route::post('/status/report', 'Status\ReportController@send')->name('status.reports.send');
Route::get('/status', 'Status\IndexController@index')->name('status.index');
Route::get('/players-statistics', 'PlayersStatisticsController@index')->name('home.players-statistics');
Route::get('/', 'HomeController@index')->name('home');
