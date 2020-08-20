<?php


namespace App\Services\Auth\TwoFactor;


use App\Models\User;
use Illuminate\Support\Manager;

/**
 * @method string generateSecret()
 * @method string generateQR(string $username, string $secret, int $size)
 * @method bool checkCode(string $secret, string $code)
 * @method void sendCode(User $user)
 */
class TwoFactorManager extends Manager
{
    /**
     * @inheritDoc
     */
    public function getDefaultDriver()
    {
        return $this->config->get('site.two_factor.driver');
    }

    /**
     * Создать драйвер Гугл аутентификатора.
     *
     * @return GoogleAuthDriver
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function createGoogleAuthDriver(): GoogleAuthDriver
    {
        return $this->container->make(GoogleAuthDriver::class);
    }
}
