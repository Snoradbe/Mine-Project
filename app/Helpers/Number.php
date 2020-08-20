<?php


namespace App\Helpers;


class Number
{
    /**
     * Метки чисел.
     *
     * @var array ['suffix' => 'threshold']
     */
    private const THRESHOLDS = [
        '' => 900,
        'K' => 900000,
        'M' => 900000000,
        'B' => 900000000000,
        'T' => 90000000000000,
    ];

    /**
     * Выводимое число по-умолчанию.
     * Если число больше меток.
     *
     * @var string
     */
    private const DEFAULT = '900T+';

    /**
     * Получить сокращенную строку числа.
     *
     * @param float $value
     * @param int $precision
     * @return string
     */
    public static function readable(float $value, int $precision = 1): string
    {
        foreach (self::THRESHOLDS as $suffix => $threshold) {
            if ($value < $threshold) {
                return self::format($value, $precision, $threshold, $suffix);
            }
        }

        return self::DEFAULT;
    }

    /**
     * Форматировать число в строку.
     *
     * @param float $value
     * @param int $precision
     * @param int $threshold
     * @param string $suffix
     * @return string
     */
    private static function format(float $value, int $precision, int $threshold, string $suffix): string
    {
        $formattedNumber = number_format($value / ($threshold / self::THRESHOLDS['']), $precision);
        $cleanedNumber = (strpos($formattedNumber, '.') === false)
            ? $formattedNumber
            : rtrim(rtrim($formattedNumber, '0'), '.');

        return $cleanedNumber . $suffix;
    }
}
