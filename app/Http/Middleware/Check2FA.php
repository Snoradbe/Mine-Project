<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class Check2FA
{
    /**
     * Исключение маршрутов, где не действует проверка.
     *
     * @var array
     */
    protected $except = [
        '2fa',
        '2fa.auth',
        'home',
        'logout'
    ];

    /**
     * Проверить, должен ли проверяться маршрут.
     *
     * @param string|null $route
     * @return bool
     */
    protected function checkExcept(?string $route): bool
    {
        return !is_null($route) && in_array($route, $this->except);
    }

    /**
     * Проверить, прошел ли пользователь двухфакторную авторизацию.
     * Если у пользователя включена двухфакторная авторизация.
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (is_null($user)) {
            return $next($request);
        }

        if ($this->checkExcept(optional($request->route())->getName())) {
            return $next($request);
        }

        $checked2fa = $request->session()->get('2fa', false);
        if ($user->has2fa() && !$checked2fa) {
            return redirect()->route('2fa');
        }

        return $next($request);
    }
}
