<?php


namespace App\Services;


class Avatar
{
    public const PATH = 'uploads/avatars';

    private static function getDefault(): string
    {
        return self::PATH . '/default.png';
    }

    private static function getPath(string $playername): string
    {
        return self::PATH . '/' . $playername . '.png';
    }

    public static function get(string $playername): string
    {
        $file = self::getPath($playername);

        return is_file(public_path($file)) ? $file : self::getDefault();
    }
}
