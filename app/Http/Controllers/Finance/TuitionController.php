<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\PaymentMethod;
use App\Models\Finance\Discount;
use App\Models\Finance\DiscountDetail;
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionDetail;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

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

    public function changeTuitionPrice(Request $request)
    {
        $requestData = $request->all();

        foreach ($requestData as $key => $value) {
            $requestData[$key] = preg_replace('/[^0-9]/', '', $value);
        }

        $validator = Validator::make($requestData, [
            'tuition_details_id' => 'required|integer|exists:tuition_details,id',
            'full_payment_irr' => 'required|integer',
            'full_payment_usd' => 'required|integer',
            'two_installment_amount_irr' => 'required|integer',
            'two_installment_amount_usd' => 'required|integer',
            'two_installment_advance_irr' => 'required|integer',
            'two_installment_advance_usd' => 'required|integer',
            'two_installment_each_installment_irr' => 'required|integer',
            'two_installment_each_installment_usd' => 'required|integer',
            'date_of_installment1_two' => 'required|date',
            'date_of_installment2_two' => 'required|date',
            'four_installment_amount_irr' => 'required|integer',
            'four_installment_amount_usd' => 'required|integer',
            'four_installment_advance_irr' => 'required|integer',
            'four_installment_advance_usd' => 'required|integer',
            'four_installment_each_installment_irr' => 'required|integer',
            'four_installment_each_installment_usd' => 'required|integer',
            'date_of_installment1_four' => 'required|date',
            'date_of_installment2_four' => 'required|date',
            'date_of_installment3_four' => 'required|date',
            'date_of_installment4_four' => 'required|date',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Change Tuition Price Failed', 'values' => $request, 'errors' => json_encode($validator->errors())]), request()->ip(), request()->userAgent());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'errors' => $validator->errors(),
            ], 419);
        }

        $tuition = TuitionDetail::find($request->tuition_details_id);
        $tuition->full_payment = json_encode(['full_payment_irr' => $request->full_payment_irr, 'full_payment_usd' => $request->full_payment_usd], true);
        $tuition->two_installment_payment = json_encode([
            'two_installment_amount_irr' => $request->two_installment_amount_irr,
            'two_installment_amount_usd' => $request->two_installment_amount_usd,
            'two_installment_advance_irr' => $request->two_installment_advance_irr,
            'two_installment_advance_usd' => $request->two_installment_advance_usd,
            'two_installment_each_installment_irr' => $request->two_installment_each_installment_irr,
            'two_installment_each_installment_usd' => $request->two_installment_each_installment_usd,
            'date_of_installment1_two' => $request->date_of_installment1_two,
            'date_of_installment2_two' => $request->date_of_installment2_two,
        ], true);
        $tuition->four_installment_payment = json_encode([
            'four_installment_amount_irr' => $request->four_installment_amount_irr,
            'four_installment_amount_usd' => $request->four_installment_amount_usd,
            'four_installment_advance_irr' => $request->four_installment_advance_irr,
            'four_installment_advance_usd' => $request->four_installment_advance_usd,
            'four_installment_each_installment_irr' => $request->four_installment_each_installment_irr,
            'four_installment_each_installment_usd' => $request->four_installment_each_installment_usd,
            'date_of_installment1_four' => $request->date_of_installment1_four,
            'date_of_installment2_four' => $request->date_of_installment2_four,
            'date_of_installment3_four' => $request->date_of_installment3_four,
            'date_of_installment4_four' => $request->date_of_installment4_four,
        ], true);
        $tuition->save();
        $this->logActivity(json_encode(['activity' => 'Tuition Fee Changed', 'tuition_id' => $request->tuition_id, 'price' => $request->price]), request()->ip(), request()->userAgent());

        return response()->json(['message' => 'Tuition fee changed successfully!'], 200);
    }

    public function payTuition($student_id)
    {
        if (empty($this->getActiveAcademicYears())) {
            abort(403);
        }

        $studentApplianceStatus = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->where('student_id', $student_id)->where('tuition_payment_status', 'Pending')->whereIn('academic_year', $this->getActiveAcademicYears())->first();

        if (empty($studentApplianceStatus)) {
            abort(403);
        }

        $applicationInfo = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
            ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
            ->join('interviews', 'applications.id', '=', 'interviews.application_id')
            ->where('application_reservations.student_id', $student_id)
            ->where('applications.reserved', 1)
            ->where('application_reservations.payment_status', 1)
            ->where('applications.interviewed', 1)
            ->where('interviews.interview_type', 3)
            ->whereIn('application_timings.academic_year', $this->getActiveAcademicYears())
            ->orderByDesc('application_reservations.id')
            ->first();

        //Get tuition price
        $tuition = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
            ->where('tuitions.academic_year', $applicationInfo->academic_year)
            ->where('tuition_details.level', $applicationInfo->level)
            ->first();

        $paymentMethods = PaymentMethod::get();

        //Discount Percentages
        $interviewFormDiscounts = json_decode($applicationInfo->interview_form, true)['discount'];
        $discountPercentages = DiscountDetail::whereIn('id', $interviewFormDiscounts)->pluck('percentage')->sum();

        //Get all students with paid status in all active academic years
        $me=auth()->user()->id;

        $allStudentsWithMyGuardian=StudentInformation::where('guardian',$me)->pluck('student_id')->toArray();
        $allStudentsWithPaidStatusInActiveAcademicYear = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->whereIn('student_id', $allStudentsWithMyGuardian)
            ->where('tuition_payment_status', 'Paid')
            ->whereIn('academic_year', $this->getActiveAcademicYears())
            ->count();


        $familyDiscount = 0;
        if ($allStudentsWithPaidStatusInActiveAcademicYear == 2) {
            $familyDiscount = 25;
        }
        if ($allStudentsWithPaidStatusInActiveAcademicYear == 3) {
            $familyDiscount = 30;
        }
        if ($allStudentsWithPaidStatusInActiveAcademicYear > 4) {
            $familyDiscount = 40;
        }

        return view('Finance.Tuition.Pay.index', compact('studentApplianceStatus', 'tuition', 'applicationInfo', 'paymentMethods', 'discountPercentages', 'familyDiscount'));
    }

    public function tuitionPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|in:1,2',
            'appliance_id' => 'required|exists:student_appliance_statuses,id',
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Application Payment Failed', 'errors' => json_encode($validator)]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (empty($this->getActiveAcademicYears())) {
            abort(403);
        }
        $appliance_id = $request->appliance_id;

        $studentApplianceStatus = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->where('id', $appliance_id)
            ->where('tuition_payment_status', 'Pending')
            ->whereIn('academic_year', $this->getActiveAcademicYears())
            ->first();
        dd($studentApplianceStatus);
        $applicationInfo = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
            ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
            ->join('interviews', 'applications.id', '=', 'interviews.application_id')
            ->where('application_reservations.student_id', $student_id)
            ->where('applications.reserved', 1)
            ->where('application_reservations.payment_status', 1)
            ->where('applications.interviewed', 1)
            ->where('interviews.interview_type', 3)
            ->whereIn('application_timings.academic_year', $this->getActiveAcademicYears())
            ->orderByDesc('application_reservations.id')
            ->first();

        //Get tuition price
        $tuition = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
            ->where('tuitions.academic_year', $applicationInfo->academic_year)
            ->where('tuition_details.level', $applicationInfo->level)
            ->first();

        $paymentMethod = PaymentMethod::find(2);

        //Discount Percentages
        $interviewFormDiscounts = json_decode($applicationInfo->interview_form, true)['discount'];
        $discountPercentages = DiscountDetail::whereIn('id', $interviewFormDiscounts)->pluck('percentage')->sum();

        //Get all students with paid status in all active academic years
        $allStudentsWithPaidStatusInActiveAcademicYear = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->where('student_id', '!=', $student_id)
            ->where('tuition_payment_status', 'Paid')
            ->whereIn('academic_year', $this->getActiveAcademicYears())
            ->count();

        $familyDiscount = 0;
        if ($allStudentsWithPaidStatusInActiveAcademicYear == 1) {
            $familyDiscount = 30;
        }
        if ($allStudentsWithPaidStatusInActiveAcademicYear == 2) {
            $familyDiscount = 35;
        }
        if ($allStudentsWithPaidStatusInActiveAcademicYear == 3) {
            $familyDiscount = 40;
        }
        if ($allStudentsWithPaidStatusInActiveAcademicYear > 3) {
            $familyDiscount = 45;
        }

        if (empty($studentApplianceStatus)) {
            abort(403);
        }

        switch ($request->payment_method) {
            case 1:
                break;
            case 2:
                $amount = $applicationInformation->applicationInfo->applicationTimingInfo->fee;
                // Create new invoice.
                $invoice = (new Invoice)->amount($amount);

                return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyApplicationPayment')->purchase(
                    $invoice,
                    function ($driver, $transactionID) use ($amount, $applicationInformation) {
                        $dataInvoice = new \App\Models\Invoice();
                        $dataInvoice->user_id = auth()->user()->id;
                        $dataInvoice->type = 'Application Reservation';
                        $dataInvoice->amount = $amount;
                        $dataInvoice->description = json_encode(['amount' => $amount, 'reservation_id' => $applicationInformation->id], true);
                        $dataInvoice->transaction_id = $transactionID;
                        $dataInvoice->save();
                    }
                )->pay()->render();
        }

        return view('Finance.Tuition.Pay.index', compact('studentApplianceStatus', 'tuition', 'applicationInfo', 'paymentMethod', 'discountPercentages', 'familyDiscount'));
    }
}
