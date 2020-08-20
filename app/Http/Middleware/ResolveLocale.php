<?php


namespace App\Http\Middleware;


use App\Lang;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ResolveLocale
{
    /**
     * Определить язык по uri.
     *
     * @return string|null
     */
    public static function resolveFromURI(): ?string
    {
        $uri = request()->path();
        $segments = explode('/', $uri, 2);

        if (!empty($lang = trim($segments[0] ?? '')) && in_array($lang, Lang::ALLOWED_LANGS)) {
            Lang::set($lang);

            return $lang;
        }

        return null;
    }

    /**
     * Определить и установить язык перед маршрутом.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $userLang = $request->cookie('lang', Lang::DEFAULT);

        if (!Lang::hasLocale()) {
            return redirect('/' . $userLang . '/' . ltrim($request->getRequestUri(), '/'));
        }

        App::setLocale(Lang::locale());

        return $next($request);
    }
}
