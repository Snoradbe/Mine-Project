<?php


namespace App\Services\Player\SkinCloak\Skin;


use App\Services\Player\SkinCloak\Resolution as BaseResolution;
use Intervention\Image\Image;

class Resolution extends BaseResolution
{
    /**
     * Разрешенные соотношения сторон скина.
     */
    public const ALLOWED_RESOLUTIONS = [
        [64, 32],
        [64, 64]
    ];

    /**
     * @inheritDoc
     */
    public function check(Image $image): bool
    {
        return $this->checkAllowed($image, self::ALLOWED_RESOLUTIONS);
    }
}
