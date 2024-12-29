<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class UserLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set user language
        $languages = explode(",", $request->server('HTTP_ACCEPT_LANGUAGE'));

        if ($languages != null && env("MULTIPLE_LANGUAGES") == 1) {
            App::setLocale($languages[0]);
        } else {
            App::setLocale(str_replace("_", "-", env("APP_FAKER_LOCALE")));
        }
        return $next($request);
    }
}
