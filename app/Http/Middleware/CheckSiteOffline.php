<?php


namespace App\Http\Middleware;


use Illuminate\Http\Request;

class CheckSiteOffline
{
    public function handle(Request $request, \Closure $next)
    {
        $enabled = config('site.enabled', true);
        $routeLogin = in_array($request->route()->getName(), ['login', 'logout']);
        //dd($routeLogin);
        if ($enabled || $routeLogin) {
            return $next($request);
        }
        //dd($request->route()->getName());

        $user = $request->user();
        if ((is_null($user) && !$routeLogin) || (!is_null($user) && !$user->isAdmin())) {
            return response(view('offline'));
        }

        return $next($request);
    }
}
