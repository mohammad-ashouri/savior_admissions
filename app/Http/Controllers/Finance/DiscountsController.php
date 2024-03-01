<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\Discount;
use App\Models\Finance\DiscountDetail;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;

class DiscountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:discounts-list', ['only' => ['index']]);
        $this->middleware('permission:discounts-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:discounts-show', ['only' => ['show']]);
        $this->middleware('permission:discounts-change-status', ['only' => ['changeTuitionStatus']]);
    }

    public function index()
    {
        $me = User::find(session('id'));
        $discounts = [];
        if ($me->hasRole('Super Admin')) {
            $discounts = Discount::with('academicYearInfo')->orderBy('academic_year', 'desc')->paginate(10);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $financialManagerAccess = explode('|', $myAllAccesses->financial_manager);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $discounts = Discount::with('academicYearInfo')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->paginate(10);
        }

        if ($discounts->isEmpty()) {
            $discounts = [];
        }

        return view('Finance.Discounts.index', compact('discounts'));
    }

    public function edit($id)
    {
        $me = User::find(session('id'));
        $discounts = [];
        if ($me->hasRole('Super Admin')) {
            $discounts = Discount::with('academicYearInfo')->with('allDiscounts')->orderBy('academic_year', 'desc')->find($id);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $financialManagerAccess = explode('|', $myAllAccesses->financial_manager);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $discounts = Discount::with('academicYearInfo')->with('allDiscounts')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->find($id);
        }

        if (empty($discounts)) {
            $discounts = [];
        }

        return view('Finance.Discounts.edit', compact('discounts'));
    }

    public function store(Request $request)
    {
        $me = User::find(session('id'));
        $discounts = [];
        if ($me->hasRole('Super Admin')) {
            $discounts = Discount::with('academicYearInfo')->with('allDiscounts')->orderBy('academic_year', 'desc')->find($request->discount_id);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $principalAccess = explode('|', $myAllAccesses->principal);
            $financialManagerAccess = explode('|', $myAllAccesses->financial_manager);
            $filteredArray = array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $discounts = Discount::with('academicYearInfo')->with('allDiscounts')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->find($request->discount_id);
        }

        if (empty($discounts)) {
            abort(403);
        }

        if (count($request->id) != count($request->percentage)) {
            abort(503);
        }
        foreach ($request->id as $key => $id) {
            $discount_details = DiscountDetail::where('discount_id', $request->discount_id)->where('id', $id)->first();
            if (empty($discount_details)) {
                $discount_details = new DiscountDetail();
                $discount_details->discount_id = $request->discount_id;
                $discount_details->name = $request->name[$key];
                $discount_details->percentage = $request->percentage[$key];
                $discount_details->interviewer_permission = $request->display_in_form[$key];
            } else {
                $discount_details->name = $request->name[$key];
                $discount_details->percentage = $request->percentage[$key];
                $discount_details->interviewer_permission = $request->display_in_form[$key];
                $discount_details->status = $request->status[$key];
            }
            $discount_details->save();
        }

        return redirect()->route('Discounts.index')
            ->with('success', 'Discount settings saved successfully!');

    }
}
