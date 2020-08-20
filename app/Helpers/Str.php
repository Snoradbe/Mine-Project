<?php


namespace App\Helpers;


use Illuminate\Support\Str as BaseStr;

class Str extends BaseStr
{
    /**
     * Получить склонение слова числа.
     *
     * @param int|double|float $number
     * @param string $form1 например: час
     * @param string $form2 например: часа
     * @param string|null $form3 например: часов
     * @return string
     */
    public static function declensionNumber($number, string $form1, string $form2, ?string $form3 = null): string
    {
        $number = abs($number) % 100;
        if ($number > 4 && $number < 21) return $form3 ? $form3 : $form2;

        $number = $number % 10;
        if ($number > 1 && $number < 5) return $form2;

        if ($number == 1) return $form1;

        return $form3 ? $form3 : $form2;
    }

    /**
     * Получить склонение слова прилагательного.
     *
     * @param int|double|float $number
     * @param string $form1 например: красный
     * @param string $form2 например: красных
     * @return string
     */
    public static function declensionAdjective($number, string $form1, string $form2): string
    {
        $number = abs($number) % 100;
        if ($number == 11) return $form2;

        $number %= 10;
        if ($number == 1) return $form1;

        return $form2;
    }

    /**
     * Разделить слово по символам.
     * Например: '11112222' разделит на '1111 2222'
     *
     * @param string $word
     * @param int $symbols
     * @param string $separator
     * @return string
     */
    public static function separateWord(string $word, int $symbols, string $separator = ' '): string
    {
        return implode($separator, str_split($word, $symbols));
    }
}
