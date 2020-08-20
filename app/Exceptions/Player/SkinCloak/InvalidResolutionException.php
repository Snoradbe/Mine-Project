<?php


namespace App\Exceptions\Player\SkinCloak;


use App\Exceptions\BaseException;

class InvalidResolutionException extends BaseException
{
    /**
     * InvalidResolutionException constructor.
     *
     * @param int $width
     * @param int $height
     */
    public function __construct(int $width, int $height)
    {
        parent::__construct(sprintf(__('cabinet.response.skin_cloak.errors.invalid_resolution'), $width, $height));
    }
}
