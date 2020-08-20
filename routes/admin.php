<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'IndexController@index')->name('home');
Route::post('/cache/config/clear', 'IndexController@clearConfigCache')->name('cache.config.clear');

Route::prefix('/settings')->as('settings.')->group(function () {
    Route::get('/admins', 'SettingsController@admins')->name('admins');
    Route::post('/admins/add', 'SettingsController@adminsAdd')->name('admins.add');
    Route::post('/admins/remove/{admin}', 'SettingsController@adminsRemove')->name('admins.remove');
    Route::get('/base', 'SettingsController@base')->name('base');
});

Route::namespace('Users')->prefix('/users')->as('users.')->group(function () {
    Route::get('/', 'UsersController@index')->name('index');
    Route::prefix('/{user}')->group(function () {
        Route::get('/', 'UsersController@show')->name('show');
        Route::post('/remove/email', 'UsersController@removeEmail')->name('remove.email');
        Route::post('/remove/2fa', 'UsersController@remove2fa')->name('remove.2fa');
        Route::post('/set/group', 'UsersController@setGroup')->name('set.group');
        Route::post('/set/coins', 'UsersController@setCoins')->name('set.coins');
    });
});

Route::namespace('Store')->prefix('/store')->as('store.')->group(function () {
    Route::resource('categories', 'CategoryController');
    Route::resource('products', 'ProductsController');
    Route::resource('discounts', 'DiscountsController');
    Route::post('/set-discounts/{discount}', 'DiscountsController@setDiscounts')->name('discounts.set-discount');
    Route::post('/remove-discounts', 'DiscountsController@removeDiscounts')->name('discounts.remove-discounts');
    Route::resource('promo', 'PromoController');
    Route::post('/promo-activation/{promoUser}/delete', 'PromoController@deleteActivation')->name('promo.user-activation.delete');

    Route::prefix('/purchases')->as('purchases.')->group(function () {
        Route::get('/', 'PurchasesController@index')->name('index');
    });
});

Route::namespace('Status')->prefix('/status')->as('status.')->group(function () {
    Route::get('/reports', 'ReportsController@index')->name('reports.index');
});

Route::prefix('/servers-sorting')->as('servers-sorting.')->group(function () {
    Route::get('/', 'ServersSortingController@index')->name('index');
    Route::post('/add', 'ServersSortingController@add')->name('add');
    Route::post('/save', 'ServersSortingController@save')->name('save');
});

Route::resource('news', 'NewsController');
