<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Detect locale from URL prefix: /en/... → 'en', else 'id'
        $segment = $request->segment(1);

        if ($segment === 'en') {
            app()->setLocale('en');
        } else {
            app()->setLocale('id');
        }

        return $next($request);
    }
}
