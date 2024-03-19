<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Jenssegers\Agent\Agent;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function logActivity($activity, $ip_address, $user_agent, $user_id = null): void
    {
        $agent = new Agent();
        ActivityLog::create([
            'user_id' => $user_id,
            'activity' => $activity,
            'ip_address' => $ip_address,
            'user_agent' => $user_agent,
            'device' => $agent->device(),
        ]);
    }

    public function alerts($state, $errorVariable, $errorText): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $state,
            'errors' => [
                $errorVariable => [$errorText]
            ]
        ]);
    }

    public function success($state, $messageVariable, $messageText): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => $state,
            'message' => [
                $messageVariable => [$messageText]
            ]
        ]);
    }

    //Getting principal and financial manager accesses
    public function getFilteredAccessesPF($userAccessInfo): array
    {
        $principalAccess = [];
        $financialManagerAccess = [];

        if (! empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (! empty($userAccessInfo->financial_manager)) {
            $financialManagerAccess = explode('|', $userAccessInfo->financial_manager);
        }

        return array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));
    }
    //Getting principal and admissions officer accesses
    public function getFilteredAccessesPA($userAccessInfo): array
    {
        $principalAccess = [];
        $admissionsOfficerAccess = [];

        if (! empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (! empty($userAccessInfo->admissions_officer)) {
            $admissionsOfficerAccess = explode('|', $userAccessInfo->admissions_officer);
        }

        return array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
    }
}
