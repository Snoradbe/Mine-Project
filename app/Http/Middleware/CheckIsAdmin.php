<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class CheckIsAdmin
{
    /**
     * Проверить, является ли пользователь админом.
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if (is_null($user) || !$user->isAdmin()) {
            return redirect(home_route('home'));
        }

        return $next($request);
    }
}
