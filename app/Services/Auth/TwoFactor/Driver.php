<?php


namespace App\Services\Auth\TwoFactor;


use App\Models\User;

interface Driver
{
    /**
     * Сгенерировать секретный ключ.
     *
     * @return string
     */
    public function generateSecret(): string;

    /**
     * Сгенерировать QR код.
     *
     * @param string $secret
     * @param string $username
     * @param int $size
     * @return string
     */
    public function generateQR(string $username, string $secret, int $size): string;

    /**
     * Проверить код.
     *
     * @param string $secret
     * @param string $code
     * @return bool
     */
    public function checkCode(string $secret, string $code): bool;

    /**
     * Отправить код пользователю.
     *
     * @param User $user
     */
    public function sendCode(User $user): void;
}
