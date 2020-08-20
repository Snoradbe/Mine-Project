<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class CheckInGroup
{
    /**
     * Проверить, состоит ли пользователь в указанных группах.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$groups
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$groups)
    {
        $user = $request->user();
        if (is_null($user) || !$user->inGroup($groups)) {
            return redirect(home_route('home'));
        }

        return $next($request);
    }
}
