<?php

namespace App\Providers;

use App\Services\Auth\Hashing\AuthMeHasher;
use App\Services\Store\Purchasing\Payers\Payer;
use App\Services\Store\Purchasing\Payers\Pool as PayersPool;
use App\Services\Store\Purchasing\Payers\UnitpayPayer;
use App\Services\Store\Purchasing\Payments\Unitpay\Checkout as UnitpayCheckout;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $pathPublic = $this->app['config']->get('site.public_path', 'public');
        $this->app->instance('path.public', base_path($pathPublic));

        $this->app->singleton(UnitpayCheckout::class, function() {
            return new UnitpayCheckout(
                config('site.store.payment.unitpay.id'),
                config('site.store.payment.unitpay.secret')
            );
        });

        $this->registerPayers();
        $this->registerPayerPool();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //создаем новый драйвер проверки пароля
        $this->app['hash']->extend('authme', function () {
            return new AuthMeHasher();
        });

        Validator::extend('my_password', function ($attribute, $value, $parameters, $validator) {
            if (preg_match('/[A-zА-я]+/', $value) && preg_match('/[0-9]+/', $value)) {
                $passwordsFile = storage_path('app/unsafepwds.txt');
                $passwords = file($passwordsFile, FILE_IGNORE_NEW_LINES);

                return !in_array($value, $passwords);
            }

            return false;
        });
    }

    private function registerPayers(): void
    {
        $this->app->singleton(UnitpayPayer::class, function() {
            return new UnitpayPayer($this->app->make(UnitpayCheckout::class));
        });
    }

    private function registerPayerPool(): void
    {
        $this->app->singleton(PayersPool::class, function() {
            return new PayersPool(array_map(function($payer) {
                $instance = $this->app->make($payer);
                if($instance instanceof Payer) {
                    return $instance;
                }

                throw new \UnexpectedValueException("Payer {$payer} must be implements interface App\Services\Purchasing\Payers\Payer");
            }, config('site.store.payers', [])));
        });
    }
}
