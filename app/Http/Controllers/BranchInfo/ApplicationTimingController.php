<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationTiming;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;

class ApplicationTimingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:application-timing-list', ['only' => ['index']]);
        $this->middleware('permission:application-timing-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:application-timing-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:application-timing-delete', ['only' => ['destroy']]);
        $this->middleware('permission:application-timing-search', ['only' => ['searchApplicationTiming']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        $applicationTimings = [];
        if ($me->hasRole('Super Admin')) {
            $applicationTimings = ApplicationTiming::with('academicYearInfo')->orderBy('id', 'desc')->paginate(20);
            if ($applicationTimings->isEmpty()) {
                $applicationTimings = [];
            }
        } elseif (!$me->hasRole('Super Admin')) {
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            if ($myAllAccesses != null) {
                $principalAccess = explode("|", $myAllAccesses->principal);
                $admissionsOfficerAccess = explode("|", $myAllAccesses->admissions_officer);
                $filteredArray = array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
                $applicationTimings = ApplicationTiming::join('academic_years', 'application_timings.academic_year', '=', 'academic_years.id')
                    ->whereIn('academic_years.school_id', $filteredArray)
                    ->paginate(20);
                if ($applicationTimings->isEmpty()) {
                    $applicationTimings = [];
                }
            }
        }
        return view('BranchInfo.ApplicationTimings.index', compact('applicationTimings'));
    }
}
