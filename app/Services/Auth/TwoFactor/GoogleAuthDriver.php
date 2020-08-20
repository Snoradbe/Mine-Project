<?php


namespace App\Services\Auth\TwoFactor;


use App\Models\User;
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

class GoogleAuthDriver implements Driver
{
    /**
     * Гугл аутентификатор.
     *
     * @var GoogleAuthenticator
     */
    protected $google;

    /**
     * GoogleAuthProvider constructor.
     *
     * @param GoogleAuthenticator $google
     */
    public function __construct(GoogleAuthenticator $google)
    {
        $this->google = $google;
    }

    /**
     * @inheritDoc
     */
    public function generateSecret(): string
    {
        return $this->google->generateSecret();
    }

    /**
     * @inheritDoc
     */
    public function generateQR(string $username, string $secret, int $size): string
    {
        return GoogleQrUrl::generate($username, $secret, config('app.name'), $size);
    }

    /**
     * @inheritDoc
     */
    public function checkCode(string $secret, string $code): bool
    {
        return $this->google->checkCode($secret, $code);
    }

    /**
     * @inheritDoc
     */
    public function sendCode(User $user): void
    {
        //do nothing
    }
}
