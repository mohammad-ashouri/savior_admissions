<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionDetail;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;

class TuitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:tuition-list', ['only' => ['index']]);
        $this->middleware('permission:tuition-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:tuition-show', ['only' => ['show']]);
        $this->middleware('permission:tuition-change-price', ['only' => ['changeTuitionPrice']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(session('id'));
        $tuitions = [];
        if ($me->hasRole('Super Admin')) {
            $tuitions = Tuition::with('academicYearInfo')->orderBy('academic_year', 'desc')->paginate(10);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $tuitions = Tuition::with('academicYearInfo')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->paginate(10);
        }

        if ($tuitions->isEmpty()) {
            $tuitions = [];
        }
        $this->logActivity(json_encode(['activity' => 'Getting Tuitions']), request()->ip(), request()->userAgent());

        return view('Finance.Tuition.index', compact('tuitions'));
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(session('id'));
        $tuitions = [];
        if ($me->hasRole('Super Admin')) {
            $tuitions = Tuition::with('academicYearInfo')->with('allTuitions')->orderBy('academic_year', 'desc')->find($id);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $tuitions = Tuition::with('academicYearInfo')->with('allTuitions')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->find($id);
        }

        if (empty($tuitions)) {
            $tuitions = [];
        }
        $this->logActivity(json_encode(['activity' => 'Getting Academic Year Tuitions Information For Edit', 'tuition_id' => $id]), request()->ip(), request()->userAgent());

        return view('Finance.Tuition.edit', compact('tuitions'));

    }

    public function changeTuitionPrice(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'tuition_id' => 'required|exists:tuition_details,id',
            'price' => 'required|integer',
        ]);

        $tuition = TuitionDetail::find($request->tuition_id);
        $tuition->price = $request->price;
        $tuition->save();
        $this->logActivity(json_encode(['activity' => 'Tuition Fee Changed', 'tuition_id' => $request->tuition_id, 'price' => $request->price]), request()->ip(), request()->userAgent());

        return response()->json(['message' => 'Tuition fee changed successfully!'], 200);
    }
}
