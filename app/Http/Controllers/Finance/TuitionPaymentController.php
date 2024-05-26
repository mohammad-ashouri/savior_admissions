<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\PaymentMethod;
use App\Models\Finance\GrantedFamilyDiscount;
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionDetail;
use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class TuitionPaymentController extends Controller
{
    public function index()
    {
        $me = User::find(auth()->user()->id);

        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->paginate(30);
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->paginate(30);
        } elseif ($me->hasRole('Super Admin')) {
            $tuitionInvoices = TuitionInvoices::orderBy('created_at', 'asc')->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->paginate(30);
        }

        return view('Finance.TuitionInvoices.index', compact('tuitionInvoiceDetails', 'me'));
    }

    public function show($tuition_id)
    {
        $me = User::find(auth()->user()->id);

        $tuitionInvoiceDetails = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->where('id', $tuition_id)->first();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->where('id', $tuition_id)->first();
        } elseif ($me->hasRole('Super Admin')) {
            $tuitionInvoices = TuitionInvoices::orderBy('created_at', 'asc')->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->where('id', $tuition_id)->first();
        }

        return view('Finance.TuitionInvoices.show', compact('tuitionInvoiceDetails', 'me'));
    }

    public function prepareToPayTuition($tuition_id)
    {
        $me = User::find(auth()->user()->id);

        $tuitionInvoiceDetails = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')
                ->with('invoiceDetails')
                ->with('paymentMethodInfo')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->where('id', $tuition_id)
                ->where('is_paid', 0)
                ->first();
        }

        $paymentMethods = PaymentMethod::where('id', 2)->get();

        return view('Finance.TuitionInvoices.pay', compact('tuitionInvoiceDetails', 'paymentMethods'));
    }

    public function payTuition(Request $request)
    {
        $me = User::find(auth()->user()->id);

        $validator = Validator::make($request->all(), [
            'tuition_invoice_id' => 'required|exists:tuition_invoice_details,id',
            'payment_method' => 'required|exists:payment_methods,id|in:2',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Failed To Pay Tuition Installment', 'errors' => json_encode($validator), 'values' => $request->all()]), request()->ip(), request()->userAgent());

            return redirect()->back()->withErrors($validator)->withInput();
        }
        $tuition_id = $request->tuition_invoice_id;
        $paymentMethod = $request->payment_method;
        $tuitionInvoiceDetails = [];

        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')
                ->with('invoiceDetails')
                ->with('paymentMethodInfo')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->where('id', $tuition_id)
                ->where('is_paid', 0)
                ->first();
        }

        if (empty($tuitionInvoiceDetails)) {
            $this->logActivity(json_encode(['activity' => 'Failed To Pay Tuition Installment', 'errors' => 'Access Denied', 'values' => $request->all()]), request()->ip(), request()->userAgent());
            abort(403);
        }

        $tuitionAmount = $tuitionInvoiceDetails->amount;
        switch ($paymentMethod) {
            case 2:
                $invoice = (new Invoice)->amount($tuitionAmount);

                return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionInstallmentPayment')->purchase(
                    $invoice,
                    function ($driver, $transactionID) use ($tuitionAmount, $tuitionInvoiceDetails, $paymentMethod) {
                        $dataInvoice = new \App\Models\Invoice();
                        $dataInvoice->user_id = auth()->user()->id;
                        $dataInvoice->type = 'Tuition Payment '.json_decode($tuitionInvoiceDetails->description, true)['tuition_type'];
                        $dataInvoice->amount = $tuitionAmount;
                        $dataInvoice->description = json_encode(['amount' => $tuitionAmount, 'invoice_details_id' => $tuitionInvoiceDetails->id, 'payment_method' => $paymentMethod], true);
                        $dataInvoice->transaction_id = $transactionID;
                        $dataInvoice->save();
                    }
                )->pay()->render();
                break;
        }

        dd($request->all());
    }

    public function changeTuitionInvoiceStatus(Request $request)
    {
        $me = User::find(auth()->user()->id);

        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:tuition_invoice_details,id',
            'status' => 'required|integer|in:1,3',
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Change Tuition Invoice Status Failed', 'values' => json_encode($request->all(), true), 'errors' => json_encode($validator->errors())]), request()->ip(), request()->userAgent());

            return response()->json(['message' => 'Failed to change tuition invoice status!'], 422);
        }
        $tuitionInvoiceDetails = null;
        $tuition_id = $request->invoice_id;
        if ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->where('id', $tuition_id)->first();
        }

        if (empty($tuitionInvoiceDetails)) {
            $this->logActivity(json_encode(['activity' => 'Change Tuition Invoice Status Failed', 'values' => json_encode($request->all(), true), 'errors' => 'Tuition Id Is Wrong!']), request()->ip(), request()->userAgent());

            return response()->json(['message' => 'Failed to change tuition invoice status!'], 422);
        }

        switch ($request->status) {
            case 1:
                $tuitionInvoiceDetails = TuitionInvoiceDetails::find($tuition_id);
                $tuitionInvoiceDetails->is_paid = 1;
                $tuitionInvoiceDetails->save();

                $tuitionInvoiceInfo = TuitionInvoices::find($tuitionInvoiceDetails->tuition_invoice_id);
                $studentAppliance = StudentApplianceStatus::find($tuitionInvoiceInfo->appliance_id);
                $studentAppliance->tuition_payment_status = 'Paid';
                $studentAppliance->approval_status = 1;
                $studentAppliance->save();

                $allDiscountPercentages = $this->getAllDiscounts($studentAppliance->student_id);

                //Found previous discounts
                $allStudentsWithGuardian = StudentInformation::where('guardian', $studentAppliance->studentInformations->guardianInfo->id)->pluck('student_id')->toArray();
                $allApplianceStudents = StudentApplianceStatus::whereIn('student_id', $allStudentsWithGuardian)->whereIn('academic_year', $this->getActiveAcademicYears())->where('tuition_payment_status', 'Paid')->pluck('id')->toArray();
                $allGrantedFamilyDiscounts = GrantedFamilyDiscount::whereIn('appliance_id', $allApplianceStudents)->get();

                $applicationInfo = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
                    ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
                    ->join('interviews', 'applications.id', '=', 'interviews.application_id')
                    ->where('application_reservations.student_id', $studentAppliance->student_id)
                    ->where('applications.reserved', 1)
                    ->where('application_reservations.payment_status', 1)
                    ->where('applications.interviewed', 1)
                    ->where('interviews.interview_type', 3)
                    ->whereIn('application_timings.academic_year', $this->getActiveAcademicYears())
                    ->orderByDesc('application_reservations.id')
                    ->first();

                if ($allGrantedFamilyDiscounts->isEmpty()) {
                    $newGrantedFamilyDiscount = new GrantedFamilyDiscount();
                    $newGrantedFamilyDiscount->appliance_id = $studentAppliance->id;
                    $newGrantedFamilyDiscount->level = $applicationInfo->level;
                    $newGrantedFamilyDiscount->discount_percent = 0;
                    $newGrantedFamilyDiscount->discount_price = 0;
                    $newGrantedFamilyDiscount->signed_child_number = 1;
                    $newGrantedFamilyDiscount->save();
                } else {
                    $student_id = $studentAppliance->student_id;

                    $allDiscountPercentages = $this->getAllDiscounts($student_id);
                    $previousDiscountPrice = $this->getAllFamilyDiscountPrice($me);

                    //Calculate discount for minimum level
                    $minimumLevel = $this->getMinimumApplianceLevelInfo($me);

                    $minimumLevelTuitionDetails = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
                        ->where('tuitions.academic_year', $minimumLevel['academic_year'])->where('tuition_details.level', $minimumLevel['level']->level)->first();

                    $minimumSignedStudentNumber = $this->getMinimumSignedChildNumber($me);
                    $minimumLevelTuitionDetailsFullPayment = (int) str_replace(',', '', json_decode($minimumLevelTuitionDetails->full_payment, true)['full_payment_irr']);
                    $minimumLevelTuitionDetailsTwoInstallment = (int) str_replace(',', '', json_decode($minimumLevelTuitionDetails->two_installment_payment, true)['two_installment_amount_irr']);
                    $minimumLevelTuitionDetailsFourInstallment = (int) str_replace(',', '', json_decode($minimumLevelTuitionDetails->four_installment_payment, true)['four_installment_amount_irr']);
                    $familyPercentagePriceFullPayment = $familyPercentagePriceTwoInstallment = $familyPercentagePriceFourInstallment = 0;
                    switch ($minimumSignedStudentNumber) {
                        case '1':
                            $familyPercentagePriceFullPayment = abs((($minimumLevelTuitionDetailsFullPayment * 25) / 100) - $previousDiscountPrice);
                            $familyPercentagePriceTwoInstallment = abs((($minimumLevelTuitionDetailsTwoInstallment * 25) / 100) - $previousDiscountPrice);
                            $familyPercentagePriceFourInstallment = abs((($minimumLevelTuitionDetailsFourInstallment * 25) / 100) - $previousDiscountPrice);

                            $newGrantedFamilyDiscount = new GrantedFamilyDiscount();
                            $newGrantedFamilyDiscount->appliance_id = $studentAppliance->id;
                            $newGrantedFamilyDiscount->level = $applicationInfo->level;
                            $newGrantedFamilyDiscount->discount_percent = 25;
                            switch ($tuitionInvoiceInfo->payment_type) {
                                case 2:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceTwoInstallment;
                                    break;
                                case 3:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceFourInstallment;
                                    break;
                                case 4:
                                case 1:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceFullPayment;
                                    break;
                            }
                            $newGrantedFamilyDiscount->signed_child_number = 2;
                            $newGrantedFamilyDiscount->save();
                            break;
                        case '2':
                            $familyPercentagePriceFullPayment = abs((($minimumLevelTuitionDetailsFullPayment * 30) / 100) - $previousDiscountPrice);
                            $familyPercentagePriceTwoInstallment = abs((($minimumLevelTuitionDetailsTwoInstallment * 30) / 100) - $previousDiscountPrice);
                            $familyPercentagePriceFourInstallment = abs((($minimumLevelTuitionDetailsFourInstallment * 30) / 100) - $previousDiscountPrice);

                            $newGrantedFamilyDiscount = new GrantedFamilyDiscount();
                            $newGrantedFamilyDiscount->appliance_id = $studentAppliance->id;
                            $newGrantedFamilyDiscount->level = $applicationInfo->level;
                            $newGrantedFamilyDiscount->discount_percent = 30;
                            switch ($tuitionInvoiceInfo->payment_type) {
                                case 2:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceTwoInstallment;
                                    break;
                                case 3:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceFourInstallment;
                                    break;
                                case 4:
                                case 1:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceFullPayment;
                                    break;
                            }
                            $newGrantedFamilyDiscount->signed_child_number = 3;
                            $newGrantedFamilyDiscount->save();
                            break;
                        case '3':
                            $familyPercentagePriceFullPayment = abs((($minimumLevelTuitionDetailsFullPayment * 40) / 100) - $previousDiscountPrice);
                            $familyPercentagePriceTwoInstallment = abs((($minimumLevelTuitionDetailsTwoInstallment * 40) / 100) - $previousDiscountPrice);
                            $familyPercentagePriceFourInstallment = abs((($minimumLevelTuitionDetailsFourInstallment * 40) / 100) - $previousDiscountPrice);

                            $newGrantedFamilyDiscount = new GrantedFamilyDiscount();
                            $newGrantedFamilyDiscount->appliance_id = $studentAppliance->id;
                            $newGrantedFamilyDiscount->level = $applicationInfo->level;
                            $newGrantedFamilyDiscount->discount_percent = 40;
                            switch ($tuitionInvoiceInfo->payment_type) {
                                case 2:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceTwoInstallment;
                                    break;
                                case 3:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceFourInstallment;
                                    break;
                                case 4:
                                case 1:
                                    $newGrantedFamilyDiscount->discount_price = $familyPercentagePriceFullPayment;
                                    break;
                            }
                            $newGrantedFamilyDiscount->signed_child_number = 4;
                            $newGrantedFamilyDiscount->save();
                            break;
                        default:
                    }
                    //                    dd($minimumLevel);
                }
                //                dd($applicationInfo);
                $tuitionDetails = TuitionDetail::find(json_decode($tuitionInvoiceDetails->description, true)['tuition_details_id']);

                switch ($tuitionInvoiceInfo->payment_type) {
                    case 2:
                        $counter = 1;
                        $tuitionDetailsForTwoInstallments = json_decode($tuitionDetails->two_installment_payment, true);
                        $twoInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForTwoInstallments['two_installment_amount_irr']);
                        $totalFeeTwoInstallment = $twoInstallmentPaymentAmount - (($twoInstallmentPaymentAmount * $allDiscountPercentages) / 100);
                        $twoInstallmentPaymentAmountAdvance = str_replace(',', '', $tuitionDetailsForTwoInstallments['two_installment_advance_irr']);
                        while ($counter < 3) {
                            $newInvoice = new TuitionInvoiceDetails();
                            $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                            $newInvoice->amount = ($totalFeeTwoInstallment - $twoInstallmentPaymentAmountAdvance) / 2;
                            $newInvoice->is_paid = 0;
                            $newInvoice->description = json_encode(['tuition_type' => 'Two Installment - Installment '.$counter], true);
                            $newInvoice->save();
                            $counter++;
                        }
                        break;
                    case 3:
                        $counter = 1;
                        $tuitionDetailsForFourInstallments = json_decode($tuitionDetails->four_installment_payment, true);
                        $fourInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForFourInstallments['four_installment_amount_irr']);
                        $totalFeeFourInstallment = $fourInstallmentPaymentAmount - (($fourInstallmentPaymentAmount * $allDiscountPercentages) / 100);
                        $fourInstallmentPaymentAmountAdvance = str_replace(',', '', $tuitionDetailsForFourInstallments['four_installment_advance_irr']);

                        while ($counter < 5) {
                            $newInvoice = new TuitionInvoiceDetails();
                            $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                            $newInvoice->amount = ($totalFeeFourInstallment - $fourInstallmentPaymentAmountAdvance) / 4;
                            $newInvoice->is_paid = 0;
                            $newInvoice->description = json_encode(['tuition_type' => 'Four Installment - Installment '.$counter], true);
                            $newInvoice->save();
                            $counter++;
                        }
                        break;
                    case 4:
                        $tuitionDetailsForFullPayment = json_decode($tuitionDetails->full_payment, true);
                        $fullPaymentAmount = str_replace(',', '', $tuitionDetailsForFullPayment['full_payment_irr']);
                        $fullPaymentAmountAdvance = ($fullPaymentAmount * 30) / 100;
                        $fullPaymentAmountInstallment = ($fullPaymentAmount - (($fullPaymentAmount * $allDiscountPercentages) / 100)) - $fullPaymentAmountAdvance;

                        $newInvoice = new TuitionInvoiceDetails();
                        $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                        $newInvoice->amount = $fullPaymentAmountInstallment;
                        $newInvoice->is_paid = 0;
                        $newInvoice->description = json_encode(['tuition_type' => 'Full Payment With Advance - Installment'], true);
                        $newInvoice->save();
                        break;
                }

                $guardianMobile = $studentAppliance->studentInformations->guardianInfo->mobile;
                $message = "Tuition payment confirmation with id $tuition_id has been done successfully. To view more information, refer to the tuition invoices page from the Finance menu.\nSavior Schools";
                $this->sendSMS($guardianMobile, $message);

                return response()->json(['message' => 'Installments were made!']);
            default:
                $this->logActivity(json_encode(['activity' => 'Change Tuition Invoice Status Failed', 'values' => json_encode($request->all(), true), 'errors' => 'Status Is Wrong!']), request()->ip(), request()->userAgent());

                return response()->json(['message' => 'Failed to change tuition invoice status!'], 422);
        }
    }

    public function applianceInvoices($applianceId)
    {
        $me = User::find(auth()->user()->id);

        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
        } elseif ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->where('student_appliance_statuses.id', $applianceId)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
        } elseif ($me->hasRole('Super Admin')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->where('student_appliance_statuses.id', $applianceId)
                ->pluck('student_appliance_statuses.id')->toArray();

            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
        }

        return view('Finance.TuitionInvoices.applianceInvoices', compact('tuitionInvoiceDetails', 'me'));
    }
}
