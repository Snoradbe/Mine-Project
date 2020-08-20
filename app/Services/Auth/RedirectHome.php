<?php


namespace App\Services\Auth;


trait RedirectHome
{
    /**
     * Перенаправить на домашнюю страницу.
     *
     * @return string
     */
    public function redirectTo()
    {
        return home_route('home');
    }
}
