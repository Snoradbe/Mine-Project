<?php


namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class NeedRecaptcha
{
    /**
     * Вывести форму с каптчей.
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Http\Response|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->has('g-recaptcha-response')) {
            return response()->view('windows.captcha', [
                'request' => $request->all()
            ]);
        }

        return $next($request);
    }
}
