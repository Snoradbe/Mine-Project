<?php


namespace App\Services\Player\SkinCloak;


use Intervention\Image\Image;

abstract class Resolution
{
    /**
     * Проверить указанные пропарции изображения.
     *
     * @param Image $image
     * @param array $allowedResolutions
     * @return bool
     */
    protected function checkAllowed(Image $image, array $allowedResolutions): bool
    {
        foreach ($allowedResolutions as $resolution)
        {
            [$width, $height] = $resolution;

            if ($width === $image->width() && $height === $image->height()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Проверить пропорции изображения.
     *
     * @param Image $image
     * @return bool
     */
    abstract public function check(Image $image): bool;
}
