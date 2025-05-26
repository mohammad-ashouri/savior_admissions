<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckIfProfileRegistered
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->generalInformationInfo->status=='0'){
            return redirect()->route('profile');
        }
//        if (auth()->user()->generalInformationInfo->fida_code == null and auth()->user()->hasRole('Parent')) {
//            return redirect()->route('re-insertion-data.fida-code');
//        }

        return $next($request);
    }
}
