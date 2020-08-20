<?php


namespace App\Services\Player\SkinCloak\Cloak;


class Image
{
    /**
     * Image constructor.
     */
    private function __construct()
    {
    }

    /**
     * Получить абсолютный путь к изображению плаща по указанному нику.
     * Если ник будет null, то вернется путь к папке со плащами.
     * Если плащ не будет найден, то вернется путь к плащу по-умолчанию.
     *
     * @var string|null $username
     * @return string|null
     */
    public static function absolutePath(?string $username = null): ?string
    {
        if ($username === null) {
            return public_path('uploads/cloaks');
        }

        $path = self::filename(self::absolutePath(), $username);

        return file_exists($path) && is_readable($path) ? $path : null;
    }

    /**
     * Получить абсолютный путь к изображению плаща по указанному нику.
     *
     * @var string $username
     * @return string
     */
    public static function getAbsolutePath(string $username): string
    {
        return self::filename(self::absolutePath(), $username);
    }

    /**
     * Получить ссылку на изображение плаща по указанному нику.
     * Если ник будет null, то вернется ссылка на директорию со плащами.
     * Если плащ не будет найден, то вернется ссылка на плащ по-умолчанию.
     *
     * @param string|null $username
     * @return string|null
     */
    public static function assetPath(?string $username = null): ?string
    {
        if ($username === null) {
            return asset("uploads/cloaks");
        }

        $assetPath = self::filename(self::assetPath(), $username);
        $absolutePath = self::filename(self::absolutePath(), $username);

        return file_exists($absolutePath) && is_readable($absolutePath)
            ? $assetPath : null;
    }

    /**
     * Получить ссылку на изображение плаща по указанному нику.
     *
     * @var string $username
     * @return string
     */
    public static function getAssetPath(string $username): string
    {
        return self::filename(self::assetPath(), $username);
    }

    /**
     * Проверить, установлен ли плащ у указанного игрока.
     *
     * @param string $username
     * @return bool
     */
    public static function exists(string $username): bool
    {
        $path = self::filename(self::absolutePath(), $username);

        return !(file_exists($path) && is_readable($path));
    }

    /**
     * Получить полный путь к плащу игрока.
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
