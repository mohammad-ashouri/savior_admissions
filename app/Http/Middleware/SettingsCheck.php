<?php

namespace App\Http\Middleware;

use App\Models\SystemSettings;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $me=User::find(auth()->user()->id);
        //Check maintenance mode
        $systemSettingsMaintenanceMode=SystemSettings::whereName('maintenance_mode')->first();
        if ($systemSettingsMaintenanceMode->value==1 and !$me->hasRole('Super Admin')){
            return response()->view('GeneralPages.errors.maintenance_mode', [], 503);
        }

        return $next($request);
    }
}
