<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class CheckEmailAssigment
{
    /**
     * Проверить, если у авторизованного пользователя не указана почта.
     * Если почта не указана, то пользователь будет перенаправлен на страницу
     * с формой привязки почты.
     *
     * @param Request $request
     * @param Closure $next
     * @param bool $need
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next, bool $need = true)
    {
        $user = $request->user();
        if (!is_null($user)) {
            //если нужно проверять наличие почты у игрока
            if ($need) {
                if (!$user->hasEmail()) {
                    return redirect()->route('account.email.assigment');
                }
            } else {
                if ($user->hasEmail()) {
                    return redirect()->route('account.home');
                }
            }
        } else {
            return  redirect()->route('login');
        }

        return $next($request);
    }
}
