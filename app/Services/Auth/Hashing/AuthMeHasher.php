<?php


namespace App\Services\Auth\Hashing;


use Illuminate\Contracts\Hashing\Hasher;

class AuthMeHasher implements Hasher
{
    /**
     * @inheritDoc
     */
    public function info($hashedValue)
    {
        return [];
    }

    /**
     * Создать SHA256 хеш.
     *
     * @param $value
     * @return string
     */
    protected function makeSHA256($value): string
    {
        return hash('sha256', $value);
    }

    /**
     * @inheritDoc
     */
    public function make($value, array $options = [])
    {
        return $this->makeSHA256($value);
    }

    /**
     * @inheritDoc
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return $this->makeSHA256($value) === $hashedValue;
    }

    /**
     * @inheritDoc
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
