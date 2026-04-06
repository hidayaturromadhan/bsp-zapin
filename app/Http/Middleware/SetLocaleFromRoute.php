<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleFromRoute
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');

        if (in_array($locale, ['id', 'en'])) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('id');
        }

        return $next($request);
    }
}