<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\Discount;
use App\Models\Finance\DiscountDetail;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:discounts-list', ['only' => ['index']]);
        $this->middleware('permission:discounts-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:discounts-show', ['only' => ['show']]);
        $this->middleware('permission:discounts-change-status', ['only' => ['changeTuitionStatus']]);
    }

    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $discounts = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $discounts = Discount::with('academicYearInfo')->orderBy('academic_year', 'desc')->get();
        } elseif (auth()->user()->hasRole(['Financial Manager','Super Admin'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $discounts = Discount::with('academicYearInfo')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->get();
        }

        if ($discounts->isEmpty()) {
            $discounts = [];
        }

        return view('Finance.Discounts.index', compact('discounts'));
    }

    public function edit($id): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $discounts = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $discounts = Discount::with('academicYearInfo')->with('allDiscounts')->orderBy('academic_year', 'desc')->find($id);
        } elseif (auth()->user()->hasRole(['Financial Manager','Super Admin'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $discounts = Discount::with('academicYearInfo')->with('allDiscounts')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->find($id);
        }

        if (empty($discounts)) {
            $discounts = [];
        }

        return view('Finance.Discounts.edit', compact('discounts'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $discounts = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $discounts = Discount::with('academicYearInfo')->with('allDiscounts')->orderBy('academic_year', 'desc')->find($request->discount_id);
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $discounts = Discount::with('academicYearInfo')->with('allDiscounts')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->find($request->discount_id);
        }

        if (empty($discounts)) {
            abort(403);
        }

        if (count($request->name) != count($request->percentage)) {
            abort(503);
        }
        //        dd(request()->all());

        foreach ($request->name as $key => $name) {
            $discount_details = DiscountDetail::where('discount_id', $request->discount_id)->whereName($name)->first();
            if (empty($discount_details)) {
                $discount_details = new DiscountDetail;
                $discount_details->discount_id = $request->discount_id;
                $discount_details->name = $name;
                $discount_details->percentage = $request->percentage[$key];
                $discount_details->interviewer_permission = $request->display_in_form[$key];
            } else {
                $discount_details->name = $name;
                $discount_details->percentage = $request->percentage[$key];
                $discount_details->interviewer_permission = $request->display_in_form[$key];
                $discount_details->status = $request->status[$key];
            }
            $discount_details->save();
        }

        return redirect()->route('Discounts.index')
            ->with('success', 'Discount settings saved successfully!');

    }

    public function getDiscountPercentage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_id' => 'required|exists:discount_details,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator], 422);
        }

        return DiscountDetail::whereId($request->discount_id)->value('percentage');
    }
}
