<?php

namespace App\Http\Controllers;

use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Finance\ApplicationReservationsInvoices;
use App\Models\Finance\GrantedFamilyDiscount;
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionDetail;
use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Shetabit\Payment\Facade\Payment;

class PaymentController extends Controller
{
    public function verifyApplicationPayment(Request $request)
    {
        $transaction_id = \App\Models\Invoice::where('type', 'Application Reservation')->where('transaction_id', $request->RefId)->latest()->first();
        $user = User::find($transaction_id->user_id);

        if (Auth::loginUsingId($user->id)) {
            Session::put('id', $user->id);
        } else {
            abort(419);
        }

        switch ($request->ResCode) {
            case 0:
                $receipt = Payment::transactionId($request->RefId)->verify();

                if ($receipt) {
                    $transactionDetail = \App\Models\Invoice::where('type', 'Application Reservation')->where('transaction_id', $request->RefId)->where('amount', $request->FinalAmount)->latest()->first();

                    if (empty($transactionDetail)) {
                        abort(419);
                    }

                    $invoiceDescription = json_decode($transactionDetail->description, true);

                    $applicationReservation = ApplicationReservation::where('payment_status', 0)->whereId($invoiceDescription['reservation_id'])->first();
                    $applicationReservation->payment_status = 1;
                    $applicationReservation->save();

                    $applicationReservationInvoice = new ApplicationReservationsInvoices();
                    $applicationReservationInvoice->a_reservation_id = $applicationReservation->id;
                    $applicationReservationInvoice->payment_information = json_encode(['payment_method' => 2, json_encode($request->all(), true)], true);
                    $applicationReservationInvoice->save();

                    $application = Applications::find($applicationReservation->application_id);
                    $application->reserved = 1;
                    $application->save();

                    $applianceStatus = new StudentApplianceStatus();
                    $applianceStatus->student_id = $applicationReservation->student_id;
                    $applianceStatus->academic_year = $applicationReservation->applicationInfo->applicationTimingInfo->academic_year;
                    $applianceStatus->interview_status = 'Pending Interview';
                    $applianceStatus->save();

                    $reservatoreMobile = $user->mobile;
                    $transactionRefId = $request->SaleOrderId;
                    $messageText = "You have successfully made your payment. Your application has been reserved.\nTransaction number: $transactionRefId \nSavior Schools";
                    $this->sendSMS($reservatoreMobile, $messageText);
                } else {
                    return redirect()->route('Applications.index')->withErrors(['Failed to verify application payment.']);
                }

                return redirect()->route('Applications.index')->with(['success' => "You have successfully paid for your application. Transaction number: $transactionRefId"]);
                break;
            case 17:
                return redirect()->route('Applications.index')->withErrors(['You refused to pay application amount!']);

                break;
            default:
                abort(419);
        }
    }

    public function verifyTuitionPayment(Request $request)
    {
        $transaction_id = \App\Models\Invoice::where('transaction_id', $request->RefId)->latest()->first();
        $user = User::find($transaction_id->user_id);

        if (Auth::loginUsingId($user->id)) {
            Session::put('id', $user->id);
        } else {
            abort(419);
        }

        switch ($request->ResCode) {
            case 0:
                $receipt = Payment::transactionId($request->RefId)->verify();

                if ($receipt) {
                    $transactionDetail = \App\Models\Invoice::where('transaction_id', $request->RefId)->latest()->first();

                    if (empty($transactionDetail)) {
                        abort(419);
                    }

                    $invoiceDescription = json_decode($transactionDetail->description, true);

                    $tuitionInvoiceDetails = TuitionInvoiceDetails::find($invoiceDescription['invoice_details_id']);
                    $tuitionInvoiceDetails->is_paid = 1;
                    $tuitionInvoiceDetails->invoice_id = $transactionDetail->id;
                    $tuitionInvoiceDetails->payment_details = $request->all();
                    $tuitionInvoiceDetails->date_of_payment = now();
                    $tuitionInvoiceDetails->save();

                    $tuitionInvoiceInfo = TuitionInvoices::find($tuitionInvoiceDetails->tuition_invoice_id);

                    $studentAppliance = StudentApplianceStatus::find($tuitionInvoiceInfo->appliance_id);
                    $studentAppliance->tuition_payment_status = 'Paid';
                    $studentAppliance->approval_status = 1;
                    $studentAppliance->save();
                    $allDiscounts = $this->getAllDiscounts($studentAppliance->student_id);

                    $student_id = $studentAppliance->student_id;
                    $tuitionDetails = TuitionDetail::find(json_decode($tuitionInvoiceDetails->description, true)['tuition_details_id']);
                    $allDiscountPercentages = $this->getAllDiscounts($student_id);

                    //Found previous discounts
                    $familyPercentagePriceTwoInstallment = 0;
                    $allStudentsWithGuardian = StudentInformation::whereGuardian($studentAppliance->studentInformations->guardianInfo->id)->pluck('student_id')->toArray();
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
                        $familyPercentagePriceFullPayment = $familyPercentagePriceTwoInstallment = $familyPercentagePriceFourInstallment = 0;
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
                        $previousDiscountPrice = $this->getAllFamilyDiscountPrice($studentAppliance->studentInformations->guardianInfo->id);

                        //Calculate discount for minimum level
                        $minimumLevel = $this->getMinimumApplianceLevelInfo($studentAppliance->studentInformations->guardianInfo->id);

                        $minimumLevelTuitionDetails = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
                            ->where('tuitions.academic_year', $minimumLevel['academic_year'])->where('tuition_details.level', $minimumLevel['level']->level)->first();
                        if ($minimumLevelTuitionDetails->level > $applicationInfo->level) {
                            $minimumLevelTuitionDetails = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
                                ->where('tuitions.academic_year', $applicationInfo->academic_year)->where('tuition_details.level', $applicationInfo->level)->first();
                        }

                        $minimumSignedStudentNumber = $this->getMinimumSignedChildNumber($studentAppliance->studentInformations->guardianInfo->id);
                        $minimumLevelTuitionDetailsFullPayment = (int) str_replace(',', '', json_decode($minimumLevelTuitionDetails->full_payment, true)['full_payment_irr']);
                        $minimumLevelTuitionDetailsTwoInstallment = (int) str_replace(',', '', json_decode($minimumLevelTuitionDetails->two_installment_payment, true)['two_installment_amount_irr']);
                        $minimumLevelTuitionDetailsFourInstallment = (int) str_replace(',', '', json_decode($minimumLevelTuitionDetails->four_installment_payment, true)['four_installment_amount_irr']);
                        $familyPercentagePriceFullPayment = $familyPercentagePriceTwoInstallment = $familyPercentagePriceFourInstallment = 0;

                        switch ($minimumSignedStudentNumber) {
                            case '1':
                                $familyPercentagePriceFullPayment = (($minimumLevelTuitionDetailsFullPayment * 25) / 100) - $previousDiscountPrice;
                                $familyPercentagePriceTwoInstallment =(($minimumLevelTuitionDetailsTwoInstallment * 25) / 100) - $previousDiscountPrice;
                                $familyPercentagePriceFourInstallment = (($minimumLevelTuitionDetailsFourInstallment * 25) / 100) - $previousDiscountPrice;

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
                                $familyPercentagePriceFullPayment = (($minimumLevelTuitionDetailsFullPayment * 30) / 100) - $previousDiscountPrice;
                                $familyPercentagePriceTwoInstallment =(($minimumLevelTuitionDetailsTwoInstallment * 30) / 100) - $previousDiscountPrice;
                                $familyPercentagePriceFourInstallment = (($minimumLevelTuitionDetailsFourInstallment * 30) / 100) - $previousDiscountPrice;

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
                                $familyPercentagePriceFullPayment = (($minimumLevelTuitionDetailsFullPayment * 40) / 100) - $previousDiscountPrice;
                                $familyPercentagePriceTwoInstallment =(($minimumLevelTuitionDetailsTwoInstallment * 40) / 100) - $previousDiscountPrice;
                                $familyPercentagePriceFourInstallment =(($minimumLevelTuitionDetailsFourInstallment * 40) / 100) - $previousDiscountPrice;

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
                    }

                    switch ($tuitionInvoiceInfo->payment_type) {
                        case 2:
                            $counter = 1;
                            $tuitionDetailsForTwoInstallments = json_decode($tuitionDetails->two_installment_payment, true);
                            $twoInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForTwoInstallments['two_installment_amount_irr']);
                            $totalFeeTwoInstallment = $twoInstallmentPaymentAmount - (($twoInstallmentPaymentAmount * $allDiscountPercentages) / 100);
                            $twoInstallmentPaymentAmountAdvance = str_replace(',', '', $tuitionDetailsForTwoInstallments['two_installment_advance_irr']);

                            $totalDiscountsTwo=(($twoInstallmentPaymentAmount*$allDiscountPercentages)/100)+$familyPercentagePriceTwoInstallment;
                            $tuitionDiscountTwo=($twoInstallmentPaymentAmount*40)/100;
                            if($totalDiscountsTwo>$tuitionDiscountTwo){
                                $totalDiscountsTwo=$tuitionDiscountTwo;
                            }

                            while ($counter < 3) {
                                $newInvoice = new TuitionInvoiceDetails();
                                $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                                $newInvoice->amount = (($totalFeeTwoInstallment - $twoInstallmentPaymentAmountAdvance) / 2)-($totalDiscountsTwo/2);
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

                            $totalDiscountsFour=(($fourInstallmentPaymentAmount*$allDiscountPercentages)/100)+$familyPercentagePriceFourInstallment;
                            $tuitionDiscountFour=($fourInstallmentPaymentAmount*40)/100;
                            if($totalDiscountsFour>$tuitionDiscountFour){
                                $totalDiscountsFour=$tuitionDiscountFour;
                            }
                            while ($counter < 5) {
                                $newInvoice = new TuitionInvoiceDetails();
                                $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                                $newInvoice->amount = (($totalFeeFourInstallment - $fourInstallmentPaymentAmountAdvance) / 4)-($totalDiscountsFour/4);
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
                            $totalDiscountsFull=(($fullPaymentAmount*$allDiscountPercentages)/100)+$familyPercentagePriceFullPayment;
                            $tuitionDiscountFull=($fullPaymentAmount*40)/100;
                            if($totalDiscountsFull>$tuitionDiscountFull){
                                $totalDiscountsFull=$tuitionDiscountFull;
                            }
                            $fullPaymentAmountInstallment = $fullPaymentAmount - $totalDiscountsFull - $fullPaymentAmountAdvance;
                            $newInvoice = new TuitionInvoiceDetails();
                            $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                            $newInvoice->amount = $fullPaymentAmountInstallment;
                            $newInvoice->is_paid = 0;
                            $newInvoice->description = json_encode(['tuition_type' => 'Full Payment With Advance - Installment'], true);
                            $newInvoice->save();
                            break;
                    }

                    $reservatoreMobile = $user->mobile;
                    $transactionRefId = $request->SaleOrderId;
                    $messageText = "You have successfully paid tuition. \nTransaction number: $transactionRefId \nSavior Schools";
                    $this->sendSMS($reservatoreMobile, $messageText);
                } else {
                    return redirect()->route('dashboard')->withErrors(['Failed to verify application payment.']);
                }

                return redirect()->route('dashboard')->with(['success' => "You have successfully paid tuition amount. Transaction number: $transactionRefId"]);
                break;
            case 17:
                $transactionDetail = \App\Models\Invoice::where('transaction_id', $request->RefId)->latest()->first();
                $invoiceDescription = json_decode($transactionDetail->description, true);
                $tuitionInvoiceDetail = TuitionInvoiceDetails::find($invoiceDescription['invoice_details_id']);

                if ($tuitionInvoiceDetail) {
                    $tuitionInvoiceInfo = TuitionInvoices::find($tuitionInvoiceDetail->tuition_invoice_id);
                    $tuitionInvoiceInfo->delete();

                    $tuitionInvoiceDetail->delete();
                }

                $transactionDetail->delete();

                return redirect()->route('dashboard')->withErrors(['You refused to pay tuition amount!']);

                break;
            default:
                abort(419);
        }
    }

    public function verifyTuitionInstallmentPayment(Request $request)
    {
        $transaction_id = \App\Models\Invoice::where('transaction_id', $request->RefId)->latest()->first();
        $user = User::find($transaction_id->user_id);

        if (Auth::loginUsingId($user->id)) {
            Session::put('id', $user->id);
        } else {
            abort(419);
        }

        switch ($request->ResCode) {
            case 0:
                $receipt = Payment::transactionId($request->RefId)->verify();

                if ($receipt) {
                    $transactionDetail = \App\Models\Invoice::where('transaction_id', $request->RefId)->latest()->first();

                    if (empty($transactionDetail)) {
                        abort(419);
                    }

                    $invoiceDescription = json_decode($transactionDetail->description, true);

                    $tuitionInvoiceDetails = TuitionInvoiceDetails::find($invoiceDescription['invoice_details_id']);
                    $tuitionInvoiceDetails->is_paid = 1;
                    $tuitionInvoiceDetails->invoice_id = $transactionDetail->id;
                    $tuitionInvoiceDetails->payment_method = $invoiceDescription['payment_method'];
                    $tuitionInvoiceDetails->payment_details = json_encode($request->all(), true);
                    $tuitionInvoiceDetails->date_of_payment = now();
                    $tuitionInvoiceDetails->save();

                    $tuitionInvoiceInfo = TuitionInvoices::find($tuitionInvoiceDetails->tuition_invoice_id);

                    $studentAppliance = StudentApplianceStatus::find($tuitionInvoiceInfo->appliance_id);
                    $studentAppliance->tuition_payment_status = 'Paid';
                    $studentAppliance->approval_status = 1;
                    $studentAppliance->save();

                    $reservatoreMobile = $user->mobile;
                    $transactionRefId = $request->SaleOrderId;
                    $messageText = "You have successfully paid tuition installment. \nTransaction number: $transactionRefId \nSavior Schools";
                    $this->sendSMS($reservatoreMobile, $messageText);
                } else {
                    return redirect()->route('dashboard')->withErrors(['Failed to verify application payment.']);
                }

                return redirect()->route('dashboard')->with(['success' => "You have successfully paid tuition amount. Transaction number: $transactionRefId"]);
                break;
            case 17:
                $transactionDetail = \App\Models\Invoice::where('transaction_id', $request->RefId)->delete();

                return redirect()->route('TuitionInvoices.index')->withErrors(['You refused to pay tuition installment amount!']);

                break;
            default:
                abort(419);
        }

        dd($request->all());
    }
}
