<?php


namespace App\Services\Player\SkinCloak;


use App\Exceptions\Player\SkinCloak\NotUploadedException;
use App\Models\User;
use App\Services\Player\SkinCloak\Cloak\Image;
use Illuminate\Filesystem\Filesystem;

class CloakDelete
{
    /**
     * Система файлов.
     *
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * SkinDelete constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Удалить изображение плаща.
     *
     * @param User $user
     * @throws NotUploadedException
     */
    public function delete(User $user)
    {
        if (!Image::exists($user->playername)) {
            throw new NotUploadedException();
        }

        $this->filesystem->delete(Image::absolutePath($user->playername));
    }
}
