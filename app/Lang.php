<?php


namespace App;


class Lang
{
    /**
     * Язык по-умолчанию.
     */
    public const DEFAULT = 'en';

    /**
     * Доступные языки.
     */
    public const ALLOWED_LANGS = [
        'ru', 'en'
    ];

    /**
     * Установленный язык.
     *
     * @var string|null
     */
    protected static $lang = null;

    /**
     * Lang constructor.
     */
    private function __construct()
    {
    }

    /**
     * Установить язык.
     *
     * @param string $lang
     */
    public static function set(string $lang): void
    {
        self::$lang = $lang;
    }

    /**
     * Проверить, установлен ли язык.
     *
     * @return bool
     */
    public static function hasLocale(): bool
    {
        return !empty(self::$lang);
    }

    /**
     * Получить язык.
     *
     * @return string
     */
    public static function locale(): string
    {
        return self::$lang;
    }
}
