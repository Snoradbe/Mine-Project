<?php


namespace App\Services\Player\SkinCloak\Skin;


class Image
{
    /**
     * Image constructor.
     */
    private function __construct()
    {
    }

    /**
     * Получить абсолютный путь к изображению скина по указанному нику.
     * Если ник будет null, то вернется путь к папке со скинами.
     * Если скин не будет найден, то вернется путь к скину по-умолчанию.
     *
     * @var string|null $username
     * @return string
     */
    public static function absolutePath(?string $username = null): string
    {
        if ($username === null) {
            return public_path('uploads/skins');
        }

        $path = self::filename(self::absolutePath(), $username);

        return file_exists($path) && is_readable($path)
            ? $path : public_path("uploads/default.png");
    }

    /**
     * Получить абсолютный путь к изображению скина по указанному нику.
     *
     * @var string $username
     * @return string
     */
    public static function getAbsolutePath(string $username): string
    {
        return self::filename(self::absolutePath(), $username);
    }

    /**
     * Получить ссылку на изображение скина по указанному нику.
     * Если ник будет null, то вернется ссылка на директорию со скинами.
     * Если скин не будет найден, то вернется ссылка на скин по-умолчанию.
     *
     * @param string|null $username
     * @return string
     */
    public static function assetPath(?string $username = null): string
    {
        if ($username === null) {
            return asset("uploads/skins");
        }

        $assetPath = self::filename(self::assetPath(), $username);
        $absolutePath = self::filename(self::absolutePath(), $username);

        return file_exists($absolutePath) && is_readable($absolutePath)
            ? $assetPath : asset("uploads/default.png");
    }

    /**
     * Получить ссылку на изображение скина по указанному нику.
     *
     * @var string $username
     * @return string
     */
    public static function getAssetPath(string $username): string
    {
        return self::filename(self::assetPath(), $username);
    }

    /**
     * Проверить, установлен ли скин у указанного игрока.
     *
     * @param string $username
     * @return bool
     */
    public static function isDefault(string $username): bool
    {
        $path = self::filename(self::absolutePath(), $username);

        return !(file_exists($path) && is_readable($path));
    }

    /**
     * Получить полный путь к скину игрока.
     *
     * @param string $path
     * @param string $username
     * @return string
     */
    private static function filename(string $path, string $username): string
    {
        return $path . "/{$username}.png";
    }
}
