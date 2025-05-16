<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Evidence;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\PaymentMethod;
use App\Models\Document;
use App\Models\Finance\Discount;
use App\Models\Finance\DiscountDetail;
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionDetail;
use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
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
        $this->middleware('permission:all-tuitions-index', ['only' => ['allTuitions']]);
    }

    public function index(): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {

        $tuitions = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $tuitions = Tuition::with('academicYearInfo')->orderBy('academic_year', 'desc')->get();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $tuitions = Tuition::with('academicYearInfo')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->get();
        }

        if ($tuitions->isEmpty()) {
            $tuitions = [];
        }

        return view('Finance.Tuition.index', compact('tuitions'));
    }

    public function edit($id): View|Application|Factory|\Illuminate\Contracts\Foundation\Application
    {

        $tuitions = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $tuitions = Tuition::with('academicYearInfo')->with('allTuitions')->orderBy('academic_year', 'desc')->find($id);
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereStatus(1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $tuitions = Tuition::with('academicYearInfo')->with('allTuitions')->whereIn('academic_year', $academicYears)->orderBy('academic_year', 'desc')->find($id);
        }

        if (empty($tuitions)) {
            $tuitions = [];
        }

        $old_ids = [1, 2, 3];
        if (in_array($id, $old_ids)) {
            return view('Finance.Tuition.old.edit', compact('tuitions'));
        }

        return view('Finance.Tuition.new.edit', compact('tuitions'));
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
            'full_payment_irr_ministry' => 'required|integer',
            'three_installment_amount_irr' => 'required|integer',
            'three_installment_amount_irr_ministry' => 'required|integer',
            'three_installment_advance_irr' => 'required|integer',
            'three_installment_advance_irr_ministry' => 'required|integer',
            'three_installment_each_installment_irr' => 'required|integer',
            'three_installment_each_installment_irr_ministry' => 'required|integer',
            'date_of_installment1_three' => 'required|date',
            'date_of_installment2_three' => 'required|date',
            'date_of_installment3_three' => 'required|date',
            'seven_installment_amount_irr' => 'required|integer',
            'seven_installment_amount_irr_ministry' => 'required|integer',
            'seven_installment_advance_irr' => 'required|integer',
            'seven_installment_advance_irr_ministry' => 'required|integer',
            'seven_installment_each_installment_irr' => 'required|integer',
            'seven_installment_each_installment_irr_ministry' => 'required|integer',
            'date_of_installment1_seven' => 'required|date',
            'date_of_installment2_seven' => 'required|date',
            'date_of_installment3_seven' => 'required|date',
            'date_of_installment4_seven' => 'required|date',
            'date_of_installment5_seven' => 'required|date',
            'date_of_installment6_seven' => 'required|date',
            'date_of_installment7_seven' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'errors' => $validator->errors(),
            ], 419);
        }

        $tuition = TuitionDetail::find($request->tuition_details_id);
        $tuition->full_payment = json_encode(['full_payment_irr' => $request->full_payment_irr]);
        $tuition->full_payment_ministry = json_encode(['full_payment_irr_ministry' => $request->full_payment_irr_ministry]);
        $tuition->three_installment_payment = json_encode([
            'three_installment_amount_irr' => $request->three_installment_amount_irr,
            'three_installment_advance_irr' => $request->three_installment_advance_irr,
            'three_installment_each_installment_irr' => $request->three_installment_each_installment_irr,
            'date_of_installment1_three' => $request->date_of_installment1_three,
            'date_of_installment2_three' => $request->date_of_installment2_three,
            'date_of_installment3_three' => $request->date_of_installment3_three,
        ]);
        $tuition->three_installment_payment_ministry = json_encode([
            'three_installment_amount_irr_ministry' => $request->three_installment_amount_irr_ministry,
            'three_installment_advance_irr_ministry' => $request->three_installment_advance_irr_ministry,
            'three_installment_each_installment_irr_ministry' => $request->three_installment_each_installment_irr_ministry,
            'date_of_installment1_three_ministry' => $request->date_of_installment1_three,
            'date_of_installment2_three_ministry' => $request->date_of_installment2_three,
            'date_of_installment3_three_ministry' => $request->date_of_installment3_three,
        ]);
        $tuition->seven_installment_payment = json_encode([
            'seven_installment_amount_irr' => $request->seven_installment_amount_irr,
            'seven_installment_advance_irr' => $request->seven_installment_advance_irr,
            'seven_installment_each_installment_irr' => $request->seven_installment_each_installment_irr,
            'date_of_installment1_seven' => $request->date_of_installment1_seven,
            'date_of_installment2_seven' => $request->date_of_installment2_seven,
            'date_of_installment3_seven' => $request->date_of_installment3_seven,
            'date_of_installment4_seven' => $request->date_of_installment4_seven,
            'date_of_installment5_seven' => $request->date_of_installment5_seven,
            'date_of_installment6_seven' => $request->date_of_installment6_seven,
            'date_of_installment7_seven' => $request->date_of_installment7_seven,
        ]);
        $tuition->seven_installment_payment_ministry = json_encode([
            'seven_installment_amount_irr_ministry' => $request->seven_installment_amount_irr_ministry,
            'seven_installment_advance_irr_ministry' => $request->seven_installment_advance_irr_ministry,
            'seven_installment_each_installment_irr_ministry' => $request->seven_installment_each_installment_irr_ministry,
            'date_of_installment1_seven_ministry' => $request->date_of_installment1_seven,
            'date_of_installment2_seven_ministry' => $request->date_of_installment2_seven,
            'date_of_installment3_seven_ministry' => $request->date_of_installment3_seven,
            'date_of_installment4_seven_ministry' => $request->date_of_installment4_seven,
            'date_of_installment5_seven_ministry' => $request->date_of_installment5_seven,
            'date_of_installment6_seven_ministry' => $request->date_of_installment6_seven,
            'date_of_installment7_seven_ministry' => $request->date_of_installment7_seven,
        ]);
        $tuition->save();

        return response()->json(['message' => 'Tuition fee changed successfully!'], 200);
    }

    public function payTuition($student_id)
    {
        if (empty($this->getActiveAcademicYears())) {
//            abort(403);
        }

        $studentApplianceStatus = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->whereStudentId($student_id)
            ->whereTuitionPaymentStatus('Pending')
//            ->whereIn('academic_year', $this->getActiveAcademicYears())
            ->first();

        if (empty($studentApplianceStatus)) {
            abort(403);
        }

        $applicationInfo = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
            ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
            ->join('interviews', 'applications.id', '=', 'interviews.application_id')
            ->where('application_reservations.student_id', $student_id)
            ->where('applications.reserved', 1)
            ->where('application_reservations.payment_status', 1)
//            ->where('applications.interviewed', 1)
            ->where('interviews.interview_type', 3)
            ->where('application_timings.academic_year', $studentApplianceStatus->academic_year)
            ->orderByDesc('application_reservations.id')
            ->first();

        // Get tuition price
        $tuition = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
            ->where('tuitions.academic_year', $applicationInfo->academic_year)
            ->where('tuition_details.level', $applicationInfo->level)
            ->first();

        // Get payment methods
        $paymentMethods = PaymentMethod::get();

        // Get evidence info for foreign school in last year
        if (in_array($studentApplianceStatus->academic_year,[1,2,3])){
            $evidence=Evidence::where('appliance_id',$studentApplianceStatus->id)->first()->informations;
            if (json_decode($evidence, true)['foreign_school'] == 'Yes') {
                $foreignSchool = true;
            } else {
                $foreignSchool = false;
            }
        }else{
            if (json_decode($applicationInfo['interview_form'], true)['foreign_school'] == 'Yes') {
                $foreignSchool = true;
            } else {
                $foreignSchool = false;
            }
        }

        // Discount Percentages
        $discountPercentages = 0;
        if (isset(json_decode($applicationInfo->interview_form, true)['discount'])) {
            $interviewFormDiscounts = json_decode($applicationInfo->interview_form, true)['discount'];
            $discountPercentages = DiscountDetail::whereIn('id', $interviewFormDiscounts)->pluck('percentage')->sum();
        }

        // Get all students with paid status in all active academic years
//        auth()->user() = auth()->user()->id;

//        $allStudentsWithMyGuardian = StudentInformation::whereGuardian(auth()->user())->pluck('student_id')->toArray();
//        $allStudentsWithPaidStatusInActiveAcademicYear = StudentApplianceStatus::with('studentInfo')
//            ->with('academicYearInfo')
//            ->whereIn('student_id', $allStudentsWithMyGuardian)
//            ->whereTuitionPaymentStatus('Paid')
//            ->whereIn('academic_year', $this->getActiveAcademicYears())
//            ->count();

        $allDiscountPercentages = $this->getAllDiscounts($student_id,$studentApplianceStatus->academic_year);
//        $previousDiscountPrice = $this->getAllFamilyDiscountPrice(auth()->user());

        // Calculate discount for minimum level
//        $minimumLevel = $this->getMinimumApplianceLevelInfo(auth()->user());
//
//        if (! empty($minimumLevel['academic_year']) and ! $minimumLevel['level'] == null) {
//            $minimumLevelTuitionDetails = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
//                ->where('tuitions.academic_year', $minimumLevel['academic_year'])->where('tuition_details.level', $minimumLevel['level']->level)->first();
//            if ($minimumLevelTuitionDetails->level > $applicationInfo->level) {
//                $minimumLevelTuitionDetails = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
//                    ->where('tuitions.academic_year', $applicationInfo->academic_year)->where('tuition_details.level', $applicationInfo->level)->first();
//            }
//        }

//        if (empty($minimumLevelTuitionDetails)) {
//            $minimumLevelTuitionDetails = [];
//        }
//        $minimumSignedStudentNumber = $this->getMinimumSignedChildNumber(auth()->user());

        return view('Finance.Tuition.Pay.index', compact('studentApplianceStatus', 'tuition', 'applicationInfo', 'paymentMethods', 'discountPercentages', 'allDiscountPercentages', 'foreignSchool'));
    }

    public function tuitionPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_type' => 'required|in:1,2,3,5,6,4',
            'payment_method' => 'required|exists:payment_methods,id',
            'student_id' => 'required|exists:student_appliance_statuses,student_id',
            'appliance_id' => 'required|exists:student_appliance_statuses,id',
            'accept' => 'required|accepted',
            'description' => 'nullable|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if (empty($this->getActiveAcademicYears())) {
            abort(403);
        }
        $appliance_id = $request->appliance_id;
        $student_id = $request->student_id;
        $description = $request->description;
        $studentApplianceStatus = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->whereId($appliance_id)
            ->whereTuitionPaymentStatus('Pending')
//            ->whereIn('academic_year', $this->getActiveAcademicYears())
            ->first();

        if (empty($studentApplianceStatus)) {
            abort(403);
        }

        $applicationInfo = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
            ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
            ->join('interviews', 'applications.id', '=', 'interviews.application_id')
            ->where('application_reservations.student_id', $student_id)
            ->where('applications.reserved', 1)
            ->where('application_reservations.payment_status', 1)
//            ->where('applications.interviewed', 1)
            ->where('interviews.interview_type', 3)
//            ->whereIn('application_timings.academic_year', $this->getActiveAcademicYears())
            ->orderByDesc('application_reservations.id')
            ->first();

        if (empty($applicationInfo)) {
            abort(403);
        }

        // Get tuition price
        $tuition = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
            ->where('tuitions.academic_year', $applicationInfo->academic_year)
            ->where('tuition_details.level', $applicationInfo->level)
            ->first();

        if (in_array($studentApplianceStatus->academic_year,[1,2,3])){
            $evidence=Evidence::where('appliance_id',$studentApplianceStatus->id)->first()->informations;
            if (json_decode($evidence, true)['foreign_school'] == 'Yes') {
                $foreignSchool = true;
            } else {
                $foreignSchool = false;
            }
        }else{
            if (json_decode($applicationInfo['interview_form'], true)['foreign_school'] == 'Yes') {
                $foreignSchool = true;
            } else {
                $foreignSchool = false;
            }
        }

        $paymentMethod = $request->payment_method;
        $paymentType = $request->payment_type;

        $allDiscounts = $this->getAllDiscounts($student_id,$studentApplianceStatus->academic_year);

        $familyPercentagePriceThreeInstallment = $familyPercentagePriceSevenInstallment = $familyPercentagePriceFullPayment = 0;

        if (in_array($studentApplianceStatus->academic_year,[1,2,3])){
            // Amount information
            if ($foreignSchool) {
                $fullPayment = json_decode($tuition->full_payment_ministry, true);
                $fullPaymentAmount = str_replace(',', '', $fullPayment['full_payment_irr_ministry']);
                $totalDiscountsFull = (($fullPaymentAmount * $allDiscounts) / 100) + $familyPercentagePriceFullPayment;
                $tuitionDiscountFull = ($fullPaymentAmount * 40) / 100;
                if ($totalDiscountsFull > $tuitionDiscountFull) {
                    $totalDiscountsFull = $tuitionDiscountFull;
                }
                $fullPaymentAmountWithDiscounts = $fullPaymentAmount - $totalDiscountsFull;

                $twoInstallmentPayment = json_decode($tuition->two_installment_payment_ministry, true);
                $twoInstallmentAdvance = str_replace(',', '', $twoInstallmentPayment['two_installment_advance_irr_ministry']);

                $fourPayment = json_decode($tuition->four_installment_payment_ministry, true);
                $fourAdvance = str_replace(',', '', $fourPayment['four_installment_advance_irr_ministry']);
            } else {
                $fullPayment = json_decode($tuition->full_payment, true);
                $fullPaymentAmount = str_replace(',', '', $fullPayment['full_payment_irr']);
                $totalDiscountsFull = (($fullPaymentAmount * $allDiscounts) / 100) + $familyPercentagePriceFullPayment;
                $tuitionDiscountFull = ($fullPaymentAmount * 40) / 100;
                if ($totalDiscountsFull > $tuitionDiscountFull) {
                    $totalDiscountsFull = $tuitionDiscountFull;
                }
                $fullPaymentAmountWithDiscounts = $fullPaymentAmount - $totalDiscountsFull;

                $twoInstallmentPayment = json_decode($tuition->two_installment_payment, true);
                $twoInstallmentAdvance = str_replace(',', '', $twoInstallmentPayment['two_installment_advance_irr']);

                $fourPayment = json_decode($tuition->four_installment_payment, true);
                $fourAdvance = str_replace(',', '', $fourPayment['four_installment_advance_irr']);
            }
        }else{
            // Amount information
            if ($foreignSchool) {
                $fullPayment = json_decode($tuition->full_payment_ministry, true);
                $fullPaymentAmount = str_replace(',', '', $fullPayment['full_payment_irr_ministry']);
                $totalDiscountsFull = (($fullPaymentAmount * $allDiscounts) / 100) + $familyPercentagePriceFullPayment;
                $tuitionDiscountFull = ($fullPaymentAmount * 40) / 100;
                if ($totalDiscountsFull > $tuitionDiscountFull) {
                    $totalDiscountsFull = $tuitionDiscountFull;
                }
                $fullPaymentAmountWithDiscounts = $fullPaymentAmount - $totalDiscountsFull;

                $threeInstallmentPayment = json_decode($tuition->three_installment_payment_ministry, true);
                $threeInstallmentAdvance = str_replace(',', '', $threeInstallmentPayment['three_installment_advance_irr_ministry']);

                $sevenPayment = json_decode($tuition->seven_installment_payment_ministry, true);
                $sevenAdvance = str_replace(',', '', $sevenPayment['seven_installment_advance_irr_ministry']);
            } else {
                $fullPayment = json_decode($tuition->full_payment, true);
                $fullPaymentAmount = str_replace(',', '', $fullPayment['full_payment_irr']);
                $totalDiscountsFull = (($fullPaymentAmount * $allDiscounts) / 100) + $familyPercentagePriceFullPayment;
                $tuitionDiscountFull = ($fullPaymentAmount * 40) / 100;
                if ($totalDiscountsFull > $tuitionDiscountFull) {
                    $totalDiscountsFull = $tuitionDiscountFull;
                }
                $fullPaymentAmountWithDiscounts = $fullPaymentAmount - $totalDiscountsFull;

                $threeInstallmentPayment = json_decode($tuition->three_installment_payment, true);
                $threeInstallmentAdvance = str_replace(',', '', $threeInstallmentPayment['three_installment_advance_irr']);

                $sevenPayment = json_decode($tuition->seven_installment_payment, true);
                $sevenAdvance = str_replace(',', '', $sevenPayment['seven_installment_advance_irr']);
            }
        }


        /*
         * Payment Types:
        1 for full payment
        2 for 2 installments
        3 for 3 installments
         */

        // Save files if payment method is offline
        if ($paymentMethod == 1) {
            switch ($paymentType) {
                case 1:
                    $validator = Validator::make($request->all(), [
                        'document_file_full_payment1' => 'required|mimes:png,jpg,jpeg,pdf,bmp',
                        'document_file_full_payment2' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp',
                        'document_file_full_payment3' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp',
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }

                    if ($request->file('document_file_full_payment1') !== null) {
                        $extension = $request->file('document_file_full_payment1')->getClientOriginalExtension();

                        $bankSlip1 = 'Tuition_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileFullPayment1Src = $request->file('document_file_full_payment1')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip1
                        );
                    }
                    if ($request->file('document_file_full_payment2') !== null) {
                        $extension = $request->file('document_file_full_payment2')->getClientOriginalExtension();

                        $bankSlip2 = 'Tuition2_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileFullPayment2Src = $request->file('document_file_full_payment2')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip2
                        );
                    }
                    if ($request->file('document_file_full_payment3') !== null) {
                        $extension = $request->file('document_file_full_payment3')->getClientOriginalExtension();

                        $bankSlip3 = 'Tuition3_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileFullPayment3Src = $request->file('document_file_full_payment3')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip3
                        );
                    }
                    $filesSrc = [];

                    if (isset($documentFileFullPayment1Src) and $documentFileFullPayment1Src !== null) {
                        $filesSrc['file1'] = $documentFileFullPayment1Src;
                    }

                    if (isset($documentFileFullPayment2Src) and $documentFileFullPayment2Src !== null) {
                        $filesSrc['file2'] = $documentFileFullPayment2Src;
                    }

                    if (isset($documentFileFullPayment3Src) and $documentFileFullPayment3Src !== null) {
                        $filesSrc['file3'] = $documentFileFullPayment3Src;
                    }

                    break;
                case 2:
                case 3:
                case 5:
                case 6:
                    $validator = Validator::make($request->all(), [
                        'document_file_offline_installment1' => 'required|mimes:png,jpg,jpeg,pdf,bmp',
                        'document_file_offline_installment2' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp',
                        'document_file_offline_installment3' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp',
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }
                    if ($request->file('document_file_offline_installment1') !== null) {
                        $extension = $request->file('document_file_offline_installment1')->getClientOriginalExtension();

                        $bankSlip1 = 'Tuition_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileOfflineInstallment1Src = $request->file('document_file_offline_installment1')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip1
                        );
                    }
                    if ($request->file('document_file_offline_installment2') !== null) {
                        $extension = $request->file('document_file_offline_installment2')->getClientOriginalExtension();

                        $bankSlip1 = 'Tuition2_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileOfflineInstallment2Src = $request->file('document_file_offline_installment2')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip1
                        );
                    }
                    if ($request->file('document_file_offline_installment3') !== null) {
                        $extension = $request->file('document_file_offline_installment3')->getClientOriginalExtension();

                        $bankSlip3 = 'Tuition3_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileOfflineInstallment3Src = $request->file('document_file_offline_installment3')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip3
                        );
                    }

                    $filesSrc = [];

                    if (isset($documentFileOfflineInstallment1Src) and $documentFileOfflineInstallment1Src !== null) {
                        $filesSrc['file1'] = $documentFileOfflineInstallment1Src;
                    }

                    if (isset($documentFileOfflineInstallment2Src) and $documentFileOfflineInstallment2Src !== null) {
                        $filesSrc['file2'] = $documentFileOfflineInstallment2Src;
                    }

                    if (isset($documentFileOfflineInstallment3Src) and $documentFileOfflineInstallment3Src !== null) {
                        $filesSrc['file3'] = $documentFileOfflineInstallment3Src;
                    }

                    break;
                case 4:
                    $validator = Validator::make($request->all(), [
                        'document_file_full_payment_with_advance1' => 'required|mimes:png,jpg,jpeg,pdf,bmp',
                        'document_file_full_payment_with_advance2' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp',
                        'document_file_full_payment_with_advance3' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp',
                    ]);
                    if ($validator->fails()) {
                        return redirect()->back()->withErrors($validator)->withInput();
                    }

                    if ($request->file('document_file_full_payment_with_advance1') !== null) {
                        $extension = $request->file('document_file_full_payment_with_advance1')->getClientOriginalExtension();

                        $bankSlip1 = 'Tuition_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileFullPayment1Src = $request->file('document_file_full_payment_with_advance1')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip1
                        );
                    }
                    if ($request->file('document_file_full_payment_with_advance2') !== null) {
                        $extension = $request->file('document_file_full_payment_with_advance2')->getClientOriginalExtension();

                        $bankSlip1 = 'Tuition2_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileFullPayment2Src = $request->file('document_file_full_payment_with_advance2')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip1
                        );
                    }
                    if ($request->file('document_file_full_payment_with_advance3') !== null) {
                        $extension = $request->file('document_file_full_payment_with_advance3')->getClientOriginalExtension();

                        $bankSlip1 = 'Tuition3_'.now()->format('Y-m-d_H-i-s').'.'.$extension;
                        $documentFileFullPayment3Src = $request->file('document_file_full_payment_with_advance3')->storeAs(
                            'public/uploads/Documents/'.$student_id.'/Appliance_'."$appliance_id".'/Tuitions',
                            $bankSlip1
                        );
                    }
                    $filesSrc = [];

                    if (isset($documentFileFullPayment1Src) and $documentFileFullPayment1Src !== null) {
                        $filesSrc['file1'] = $documentFileFullPayment1Src;
                    }

                    if (isset($documentFileFullPayment2Src) and $documentFileFullPayment2Src !== null) {
                        $filesSrc['file2'] = $documentFileFullPayment2Src;
                    }

                    if (isset($documentFileFullPayment3Src) and $documentFileFullPayment3Src !== null) {
                        $filesSrc['file3'] = $documentFileFullPayment3Src;
                    }

                    break;
            }

            foreach ($filesSrc as $file) {
                Document::create([
                    'user_id' => auth()->user()->id,
                    'document_type_id' => 7,
                    'src' => $file,
                    'description' => $description,
                ]);
                Document::create([
                    'user_id' => $student_id,
                    'document_type_id' => 7,
                    'src' => $file,
                    'description' => $description,
                ]);
            }
        }

        // Make new tuition invoice
        $tuitionInvoice = TuitionInvoices::firstOrCreate([
            'appliance_id' => $appliance_id,
            'payment_type' => $paymentType,
        ]);

        // Make invoice details by payment type
        switch ($paymentType) {
            case 1:
                switch ($paymentMethod) {
                    case 1:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $fullPaymentAmountWithDiscounts;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 2;
                        $tuitionInvoiceDetails->date_of_payment = now();
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'files' => $filesSrc, 'tuition_details_id' => $tuition->id, 'tuition_type' => 'Full Payment'], true);
                        $tuitionInvoiceDetails->save();

                        $studentApplianceStatus->tuition_payment_status = 'Pending For Review';
                        $studentApplianceStatus->save();

                        return redirect()->route('TuitionInvoices.index')->with(['success' => 'You have successfully paid tuition amount. Please wait for financial approval!']);
                        break;
                    case 2:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $fullPaymentAmountWithDiscounts;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 0;
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'tuition_details_id' => $tuition->id, 'tuition_type' => 'Full Payment'], true);
                        $tuitionInvoiceDetails->save();

                        $invoice = (new Invoice)->amount($fullPaymentAmountWithDiscounts);

                        return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionPayment')->purchase(
                            $invoice,
                            function ($driver, $transactionID) use ($fullPaymentAmountWithDiscounts, $tuitionInvoiceDetails) {
                                $dataInvoice = new \App\Models\Invoice;
                                $dataInvoice->user_id = auth()->user()->id;
                                $dataInvoice->type = 'Tuition Payment (Full Payment)';
                                $dataInvoice->amount = $fullPaymentAmountWithDiscounts;
                                $dataInvoice->description = json_encode(['amount' => $fullPaymentAmountWithDiscounts, 'invoice_details_id' => $tuitionInvoiceDetails->id], true);
                                $dataInvoice->transaction_id = $transactionID;
                                $dataInvoice->save();
                            }
                        )->pay()->render();
                        break;
                }
                break;
            case 2:
                switch ($paymentMethod) {
                    case 1:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $twoInstallmentAdvance;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 2;
                        $tuitionInvoiceDetails->date_of_payment = now();
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'files' => $filesSrc, 'tuition_type' => 'Two Installment Advance', 'tuition_details_id' => $tuition->id], true);
                        $tuitionInvoiceDetails->save();

                        $studentApplianceStatus->tuition_payment_status = 'Pending For Review';
                        $studentApplianceStatus->save();

                        return redirect()->route('TuitionInvoices.index')->with(['success' => 'You have successfully paid tuition amount. Please wait for financial approval!']);
                        break;
                    case 2:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $twoInstallmentAdvance;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 0;
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'tuition_type' => 'Two Installment Advance', 'tuition_details_id' => $tuition->id], true);
                        $tuitionInvoiceDetails->save();

                        $invoice = (new Invoice)->amount($twoInstallmentAdvance);

                        return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionPayment')->purchase(
                            $invoice,
                            function ($driver, $transactionID) use ($twoInstallmentAdvance, $tuitionInvoiceDetails) {
                                $dataInvoice = new \App\Models\Invoice;
                                $dataInvoice->user_id = auth()->user()->id;
                                $dataInvoice->type = 'Three Installment Advance';
                                $dataInvoice->amount = $twoInstallmentAdvance;
                                $dataInvoice->description = json_encode(['amount' => $twoInstallmentAdvance, 'invoice_details_id' => $tuitionInvoiceDetails->id], true);
                                $dataInvoice->transaction_id = $transactionID;
                                $dataInvoice->save();
                            }
                        )->pay()->render();
                        break;
                }
                break;
            case 3:
                switch ($paymentMethod) {
                    case 1:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $fourAdvance;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 2;
                        $tuitionInvoiceDetails->date_of_payment = now();
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'files' => $filesSrc, 'tuition_type' => 'Four Installment Advance', 'tuition_details_id' => $tuition->id], true);
                        $tuitionInvoiceDetails->save();

                        $studentApplianceStatus->tuition_payment_status = 'Pending For Review';
                        $studentApplianceStatus->save();

                        return redirect()->route('TuitionInvoices.index')->with(['success' => 'You have successfully paid tuition amount. Please wait for financial approval!']);
                        break;
                    case 2:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $fourAdvance;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 0;
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'tuition_type' => 'Four Installment Advance', 'tuition_details_id' => $tuition->id], true);
                        $tuitionInvoiceDetails->save();

                        $invoice = (new Invoice)->amount($fourAdvance);

                        return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionPayment')->purchase(
                            $invoice,
                            function ($driver, $transactionID) use ($fourAdvance, $tuitionInvoiceDetails) {
                                $dataInvoice = new \App\Models\Invoice;
                                $dataInvoice->user_id = auth()->user()->id;
                                $dataInvoice->type = 'Four Installment Advance';
                                $dataInvoice->amount = $fourAdvance;
                                $dataInvoice->description = json_encode(['amount' => $fourAdvance, 'invoice_details_id' => $tuitionInvoiceDetails->id], true);
                                $dataInvoice->transaction_id = $transactionID;
                                $dataInvoice->save();
                            }
                        )->pay()->render();
                        break;
                }
                break;
            case 5:
                switch ($paymentMethod) {
                    case 1:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $threeInstallmentAdvance;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 2;
                        $tuitionInvoiceDetails->date_of_payment = now();
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'files' => $filesSrc, 'tuition_type' => 'Three Installment Advance', 'tuition_details_id' => $tuition->id], true);
                        $tuitionInvoiceDetails->save();

                        $studentApplianceStatus->tuition_payment_status = 'Pending For Review';
                        $studentApplianceStatus->save();

                        return redirect()->route('TuitionInvoices.index')->with(['success' => 'You have successfully paid tuition amount. Please wait for financial approval!']);
                        break;
                    case 2:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $threeInstallmentAdvance;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 0;
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'tuition_type' => 'Three Installment Advance', 'tuition_details_id' => $tuition->id], true);
                        $tuitionInvoiceDetails->save();

                        $invoice = (new Invoice)->amount($threeInstallmentAdvance);

                        return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionPayment')->purchase(
                            $invoice,
                            function ($driver, $transactionID) use ($threeInstallmentAdvance, $tuitionInvoiceDetails) {
                                $dataInvoice = new \App\Models\Invoice;
                                $dataInvoice->user_id = auth()->user()->id;
                                $dataInvoice->type = 'Three Installment Advance';
                                $dataInvoice->amount = $threeInstallmentAdvance;
                                $dataInvoice->description = json_encode(['amount' => $threeInstallmentAdvance, 'invoice_details_id' => $tuitionInvoiceDetails->id], true);
                                $dataInvoice->transaction_id = $transactionID;
                                $dataInvoice->save();
                            }
                        )->pay()->render();
                        break;
                }
                break;
            case 6:
                switch ($paymentMethod) {
                    case 1:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $sevenAdvance;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 2;
                        $tuitionInvoiceDetails->date_of_payment = now();
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'files' => $filesSrc, 'tuition_type' => 'Seven Installment Advance', 'tuition_details_id' => $tuition->id], true);
                        $tuitionInvoiceDetails->save();

                        $studentApplianceStatus->tuition_payment_status = 'Pending For Review';
                        $studentApplianceStatus->save();

                        return redirect()->route('TuitionInvoices.index')->with(['success' => 'You have successfully paid tuition amount. Please wait for financial approval!']);
                        break;
                    case 2:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = $sevenAdvance;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 0;
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'tuition_type' => 'Seven Installment Advance', 'tuition_details_id' => $tuition->id], true);
                        $tuitionInvoiceDetails->save();

                        $invoice = (new Invoice)->amount($sevenAdvance);

                        return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionPayment')->purchase(
                            $invoice,
                            function ($driver, $transactionID) use ($sevenAdvance, $tuitionInvoiceDetails) {
                                $dataInvoice = new \App\Models\Invoice;
                                $dataInvoice->user_id = auth()->user()->id;
                                $dataInvoice->type = 'Seven Installment Advance';
                                $dataInvoice->amount = $sevenAdvance;
                                $dataInvoice->description = json_encode(['amount' => $sevenAdvance, 'invoice_details_id' => $tuitionInvoiceDetails->id], true);
                                $dataInvoice->transaction_id = $transactionID;
                                $dataInvoice->save();
                            }
                        )->pay()->render();
                        break;
                }
                break;
            case 4:
                switch ($paymentMethod) {
                    case 1:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = ($fullPaymentAmount * 30) / 100;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 2;
                        $tuitionInvoiceDetails->date_of_payment = now();
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'files' => $filesSrc, 'tuition_details_id' => $tuition->id, 'tuition_type' => 'Full Payment With Advance'], true);
                        $tuitionInvoiceDetails->save();

                        $studentApplianceStatus->tuition_payment_status = 'Pending For Review';
                        $studentApplianceStatus->save();

                        return redirect()->route('TuitionInvoices.index')->with(['success' => 'You have successfully paid tuition amount. Please wait for financial approval!']);
                        break;
                    case 2:
                        $tuitionInvoiceDetails = new TuitionInvoiceDetails;
                        $tuitionInvoiceDetails->tuition_invoice_id = $tuitionInvoice->id;
                        $tuitionInvoiceDetails->amount = ($fullPaymentAmount * 30) / 100;
                        $tuitionInvoiceDetails->payment_method = $paymentMethod;
                        $tuitionInvoiceDetails->is_paid = 0;
                        $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'tuition_details_id' => $tuition->id, 'tuition_type' => 'Full Payment With Advance'], true);
                        $tuitionInvoiceDetails->save();

                        $invoice = (new Invoice)->amount(($fullPaymentAmount * 30) / 100);

                        return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionPayment')->purchase(
                            $invoice,
                            function ($driver, $transactionID) use ($fullPaymentAmount, $tuitionInvoiceDetails) {
                                $dataInvoice = new \App\Models\Invoice;
                                $dataInvoice->user_id = auth()->user()->id;
                                $dataInvoice->type = 'Tuition Payment (Full Payment With Advance)';
                                $dataInvoice->amount = ($fullPaymentAmount * 30) / 100;
                                $dataInvoice->description = json_encode(['amount' => ($fullPaymentAmount * 30) / 100, 'invoice_details_id' => $tuitionInvoiceDetails->id], true);
                                $dataInvoice->transaction_id = $transactionID;
                                $dataInvoice->save();
                            }
                        )->pay()->render();
                        break;
                }
                break;
        }

        return view('Finance.Tuition.Pay.index', compact('studentApplianceStatus', 'tuition', 'applicationInfo', 'paymentMethod'));
    }

    public function tuitionsStatus()
    {


        $students = [];
        if (auth()->user()->hasRole('Super Admin')) {
            //            $students = StudentApplianceStatus::with('studentInfo')->with('tuitionInvoices')->with('academicYearInfo')->with('documentSeconder')
            //                ->whereTuitionPaymentStatus('Paid')
            //                ->orderBy('academic_year', 'desc')->paginate(150);
            $academicYears = AcademicYear::get();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);
            //
            //            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            //            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
            //                ->whereIn('academic_year', $academicYears)
            //                ->whereTuitionPaymentStatus('Paid')
            //                ->orderBy('academic_year', 'desc')->paginate(150);
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();
        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $students = StudentInformation::whereGuardian(auth()->user()->id)->get()->pluck('student_id')->toArray();
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->whereIn('student_id', $students)
                ->whereTuitionPaymentStatus('Paid')
                ->orderBy('academic_year', 'desc')->get();
            $academicYears = $students->pluck('academic_year')->toArray();
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();
        }

        return view('Finance.TuitionsStatus.new.index', compact('students', 'academicYears'));
    }

    public function searchTuitionsStatus(Request $request)
    {
        $this->validate($request, [
            'academic_year' => 'nullable|exists:academic_years,id',
        ]);

        $students = [];
        $isParent = false;
        $academicYears = [];
        if (auth()->user()->hasRole('Super Admin')) {
            $data = StudentApplianceStatus::with([
                'studentInfo',
                'academicYearInfo',
                'documentSeconder',
                'tuitionInvoices' => function ($query) {
                    $query->with([
                        'invoiceDetails' => function ($query) {
                            $query->where('is_paid', '!=', 3);
                        },
                    ]);
                },
            ]);
            $data->whereAcademicYear($request->academic_year);
            $data->whereHas('tuitionInvoices', function ($query) {
                $query->whereHas('invoiceDetails', function ($query) {
                    $query->where('is_paid', '1');
                });
            });
            $data->whereTuitionPaymentStatus('Paid');
            $students = $data->orderBy('academic_year', 'desc')->get();
            $academicYears = AcademicYear::get();
        } elseif (auth()->user()->hasAnyRole(['Principal', 'Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
            $data = StudentApplianceStatus::with([
                'studentInfo',
                'academicYearInfo',
                'documentSeconder',
                'tuitionInvoices' => function ($query) {
                    $query->with([
                        'invoiceDetails' => function ($query) {
                            $query->where('is_paid', '!=', 3);
                        },
                    ]);
                },
            ]);
            $data->whereAcademicYear($request->academic_year);
            $data->whereIn('academic_year', $academicYears);
            $data->whereTuitionPaymentStatus('Paid');
            $students = $data->orderBy('academic_year', 'desc')->get();
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();
        } elseif (auth()->user()->hasExactRoles(['Parent'])) {
            $students = StudentInformation::whereGuardian(auth()->user()->id)->get()->pluck('student_id')->toArray();
            $students = StudentApplianceStatus::with('studentInfo')->with('academicYearInfo')->with('documentSeconder')
                ->whereIn('student_id', $students)
                ->whereAcademicYear($request->academic_year)
                ->whereTuitionPaymentStatus('Paid')
                ->orderBy('academic_year', 'desc')->get();
            $academicYears = $students->pluck('academic_year')->toArray();
            $academicYears = AcademicYear::whereIn('id', $academicYears)->get();
            $isParent = true;
        }

        $old_ids = [1, 2, 3];
        if (in_array($request->academic_year, $old_ids)) {
            return view('Finance.TuitionsStatus.old.index', compact('students', 'academicYears', 'isParent'));
        }

        return view('Finance.TuitionsStatus.new.index', compact('students', 'academicYears', 'isParent'));
    }

    public function allTuitions(Request $request)
    {
        $selectedAcademicYear = $request->academic_year;
        $academicYears = AcademicYear::get();
        if (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);
            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->get();
        }
        if (empty($selectedAcademicYear)) {
            return view('Finance.AllTuitions.index', compact('academicYears', 'selectedAcademicYear'));
        }
        $students = StudentApplianceStatus::with([
            'studentInformations' => function ($query) {
                $query->with([
                    'guardianInfo' => function ($query) {
                        $query->pluck('id');
                    },
                ]);
            },
        ])
            ->whereAcademicYear($selectedAcademicYear)
            ->whereNotNull('tuition_payment_status')
            ->get();

        $parents = [];
        foreach ($students as $student) {
            $parents[] = $student->studentInformations->guardianInfo?->id;
        }
        $parents = array_unique($parents);

        return view('Finance.AllTuitions.index', compact('selectedAcademicYear', 'academicYears', 'parents'));
    }
}
