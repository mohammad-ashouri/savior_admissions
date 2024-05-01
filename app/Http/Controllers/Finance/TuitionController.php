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

        return view('Finance.Tuition.Pay.index', compact('studentApplianceStatus', 'tuition', 'applicationInfo', 'paymentMethod', 'discountPercentages', 'familyDiscount'));
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
