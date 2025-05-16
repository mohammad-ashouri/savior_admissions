<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckImpersonatePermission
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->hasRole('Super Admin')) {
            return $next($request);
        }
        if ($request->is('impersonate/leave')) {
            return $next($request);
        }

        $targetUserId = $request->route('id');
        $targetUser = User::find($targetUserId);

        if ($targetUser && ($targetUser->hasRole('Student') || $targetUser->hasRole('Parent'))) {
            return $next($request);
        }
        abort('403', 'Access denied');
    }
}
