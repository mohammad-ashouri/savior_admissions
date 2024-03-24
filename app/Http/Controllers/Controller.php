<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function logActivity($activity, $ip_address, $user_agent, $user_id = null)
    {
        // Detect device type based on user agent header
        $deviceType = null;
        if (strpos($user_agent, 'Mobile') !== false) {
            $deviceType = 'Mobile';
        } elseif (strpos($user_agent, 'Tablet') !== false) {
            $deviceType = 'Tablet';
        } else {
            $deviceType = 'Desktop';
        }

        // Detect browser type and version
        $browser = '';
        $browser_version = '';
        if (preg_match('/(MSIE|Edge|Firefox|Chrome|Safari)\/([^\s]+)/i', $user_agent, $matches)) {
            $browser = $matches[1];
            $browser_version = $matches[2];
        }

        // Detect platform type and version
        $platform = '';
        $platform_version = '';
        if (preg_match('/(Windows NT|Windows|Macintosh|Android|iOS) ([^\s]+)/i', $user_agent, $matches)) {
            $platform = $matches[1];
            $platform_version = $matches[2];
        }

        // Create activity log record
        ActivityLog::create([
            'user_id' => $user_id,
            'activity' => $activity,
            'ip_address' => $ip_address,
            'device' => $user_agent,
            'platform' => $platform,
            'platform_version' => $platform_version,
            'browser' => $browser,
            'browser_version' => $browser_version,
            'device_type' => $deviceType,
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
