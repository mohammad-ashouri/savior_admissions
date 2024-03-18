<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RememberMe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (! $request->hasCookie('remember_web')) {
            $rememberToken = Auth::guard('web')->viaRemember();

            if ($rememberToken) {
                $cookie = cookie('remember_web', $rememberToken, 60 * 24 * 30);

                return $next($request)->withCookie($cookie);
            }
        }

        return $next($request);
    }
}
