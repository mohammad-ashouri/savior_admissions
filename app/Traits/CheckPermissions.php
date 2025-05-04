<?php

namespace App\Traits;

trait CheckPermissions
{
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

    //Getting principal and financial manager accesses
    public function getFilteredAccessesI($userAccessInfo): array
    {
        $interviewerAccess = [];

        if (! empty($userAccessInfo->interviewer)) {
            $interviewerAccess = explode('|', $userAccessInfo->interviewer);
        }

        return array_filter(array_unique(array_merge($interviewerAccess, $interviewerAccess)));
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

    //Getting principal and admissions officer and financial manager accesses
    public function getFilteredAccessesPAF($userAccessInfo): array
    {
        $principalAccess = [];
        $admissionsOfficerAccess = [];

        if (! empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (! empty($userAccessInfo->admissions_officer)) {
            $admissionsOfficerAccess = explode('|', $userAccessInfo->admissions_officer);
        }

        $financialManagerAccess = [];

        if (! empty($userAccessInfo->financial_manager)) {
            $financialManagerAccess = explode('|', $userAccessInfo->financial_manager);
        }

        return array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess, $financialManagerAccess)));
    }

    //Getting financial manager accesses
    public function getFilteredAccessesF($userAccessInfo): array
    {
        $financialManagerAccess = [];

        if (! empty($userAccessInfo->financial_manager)) {
            $financialManagerAccess = explode('|', $userAccessInfo->financial_manager);
        }

        return array_filter(array_unique(array_merge($financialManagerAccess)));
    }

    //Getting principal accesses
    public function getFilteredAccessesP($userAccessInfo): array
    {
        $principalAccess = [];

        if (! empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        return array_filter(array_unique(array_merge($principalAccess)));
    }
}
