<?php


namespace App\Services\Player\SkinCloak\Cloak;


use App\Services\Player\SkinCloak\Resolution as BaseResolution;
use Intervention\Image\Image;

class Resolution extends BaseResolution
{
    /**
     * Разрешенные соотношения сторон плаща.
     */
    public const ALLOWED_RESOLUTIONS = [
        [22, 17]
    ];

    /**
     * @inheritDoc
     */
    public function check(Image $image): bool
    {
        return $this->checkAllowed($image, self::ALLOWED_RESOLUTIONS);
    }
}
