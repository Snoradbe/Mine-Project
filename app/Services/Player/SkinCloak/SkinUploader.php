<?php


namespace App\Services\Player\SkinCloak;


use App\Exceptions\Player\SkinCloak\InvalidResolutionException;
use App\Models\User;
use App\Services\Player\SkinCloak\Skin\Image as SkinImage;
use App\Services\Player\SkinCloak\Skin\Resolution;
use Illuminate\Http\UploadedFile;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;

class SkinUploader
{
    /**
     * Менеджер изображений.
     *
     * @var ImageManager
     */
    protected $imageManager;

    /**
     * Разрешение изображения.
     *
     * @var Resolution
     */
    protected $resolution;

    /**
     * SkinUploader constructor.
     *
     * @param ImageManager $imageManager
     * @param Resolution $resolution
     */
    public function __construct(ImageManager $imageManager, Resolution $resolution)
    {
        $this->imageManager = $imageManager;
        $this->resolution = $resolution;
    }

    /**
     * Сохранить изображение на сайте.
     *
     * @param User $user
     * @param Image $image
     */
    protected function save(User $user, Image $image): void
    {
        $image->save(SkinImage::getAbsolutePath($user->playername));
    }

    /**
     * Загрузить файл скина.
     *
     * @param User $user
     * @param UploadedFile $file
     * @throws InvalidResolutionException
     */
    public function upload(User $user, UploadedFile $file): void
    {
        $image = $this->imageManager->make($file);

        if (!$this->resolution->check($image)) {
            throw new InvalidResolutionException($image->width(), $image->height());
        }

        $this->save($user, $image);
    }
}
