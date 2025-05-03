<?php

namespace App\Http\Controllers;

use App\Models\Branch\ApplicationReservation;
use App\Models\Branch\Applications;
use App\Models\Branch\Evidence;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Finance\ApplicationReservationsInvoices;
use App\Models\Finance\GrantedFamilyDiscount;
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionDetail;
use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoiceDetailsPayment;
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

                    $applicationReservation = ApplicationReservation::wherePaymentStatus(0)->whereId($invoiceDescription['reservation_id'])->first();
                    $applicationReservation->payment_status = 1;
                    $applicationReservation->save();

                    $applicationReservationInvoice = new ApplicationReservationsInvoices;
                    $applicationReservationInvoice->a_reservation_id = $applicationReservation->id;
                    $applicationReservationInvoice->payment_information = json_encode(['payment_method' => 2, json_encode($request->all(), true)], true);
                    $applicationReservationInvoice->save();

                    $application = Applications::find($applicationReservation->application_id);
                    $application->reserved = 1;
                    $application->save();

                    $applianceStatus = StudentApplianceStatus::whereStudentId($applicationReservation->student_id)->whereAcademicYear($applicationReservation->applicationInfo->applicationTimingInfo->academic_year)->first();

                    if (empty($applianceStatus)) {
                        $applianceStatus = new StudentApplianceStatus;
                        $applianceStatus->student_id = $applicationReservation->student_id;
                        $applianceStatus->academic_year = $applicationReservation->applicationInfo->applicationTimingInfo->academic_year;
                        $applianceStatus->interview_status = 'Pending Interview';
                    } else {
                        $applianceStatus->interview_status = 'Pending Interview';
                    }
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
//                    $studentAppliance->approval_status = 1;
                    $studentAppliance->documents_uploaded = 0;
                    $studentAppliance->save();

                    $student_id = $studentAppliance->student_id;
                    $tuitionDetails = TuitionDetail::find(json_decode($tuitionInvoiceDetails->description, true)['tuition_details_id']);
                    $allDiscountPercentages = $this->getAllDiscounts($student_id);

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

                    if (json_decode($applicationInfo['interview_form'],true)['foreign_school']=='Yes') {
                        $foreignSchool = true;
                    } else {
                        $foreignSchool = false;
                    }

                    $familyPercentagePriceThreeInstallment = $familyPercentagePriceSevenInstallment = $familyPercentagePriceFullPayment = 0;

                    switch ($tuitionInvoiceInfo->payment_type) {
                        case 5:
                            $counter = 1;
                            if ($foreignSchool) {
                                $tuitionDetailsForThreeInstallments = json_decode($tuitionDetails->three_installment_payment_ministry, true);
                                $threeInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForThreeInstallments['three_installment_amount_irr_ministry']);
                                $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForThreeInstallments['seven_installment_each_installment_irr_ministry']);
                            } else {
                                $tuitionDetailsForThreeInstallments = json_decode($tuitionDetails->three_installment_payment, true);
                                $threeInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForThreeInstallments['three_installment_amount_irr']);
                                $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForThreeInstallments['seven_installment_each_installment_irr']);
                            }
                            $totalDiscountsThree = (($threeInstallmentPaymentAmount * $allDiscountPercentages) / 100) + $familyPercentagePriceThreeInstallment;
                            $tuitionDiscountThree = ($threeInstallmentPaymentAmount * 40) / 100;
                            if ($totalDiscountsThree > $tuitionDiscountThree) {
                                $totalDiscountsThree = $tuitionDiscountThree;
                            }
                            $totalDiscountThree = $totalDiscountsThree / 7;

                            while ($counter < 4) {
                                $newInvoice = new TuitionInvoiceDetails;
                                $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                                $newInvoice->amount = $amountOfEachInstallment - $totalDiscountThree;
                                $newInvoice->is_paid = 0;
                                $newInvoice->description = json_encode(['tuition_type' => 'Three Installment - Installment ' . $counter], true);
                                $newInvoice->save();
                                $counter++;
                            }
                            break;
                        case 6:
                            $counter = 1;
                            if ($foreignSchool) {
                                $tuitionDetailsForSevenInstallments = json_decode($tuitionDetails->seven_installment_payment_ministry, true);
                                $sevenInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForSevenInstallments['seven_installment_amount_irr_ministry']);
                                $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForSevenInstallments['seven_installment_each_installment_irr_ministry']);
                            } else {
                                $tuitionDetailsForSevenInstallments = json_decode($tuitionDetails->seven_installment_payment, true);
                                $sevenInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForSevenInstallments['seven_installment_amount_irr']);
                                $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForSevenInstallments['seven_installment_each_installment_irr']);
                            }
                            $totalDiscountsSeven = (($sevenInstallmentPaymentAmount * $allDiscountPercentages) / 100) + $familyPercentagePriceSevenInstallment;
                            $tuitionDiscountSeven = ($sevenInstallmentPaymentAmount * 40) / 100;
                            if ($totalDiscountsSeven > $tuitionDiscountSeven) {
                                $totalDiscountsSeven = $tuitionDiscountSeven;
                            }
                            $totalDiscountSeven = $totalDiscountsSeven / 7;
                            while ($counter < 8) {
                                $newInvoice = new TuitionInvoiceDetails;
                                $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                                $newInvoice->amount = $amountOfEachInstallment - $totalDiscountSeven;
                                $newInvoice->is_paid = 0;
                                $newInvoice->description = json_encode(['tuition_type' => 'Seven Installment - Installment ' . $counter], true);
                                $newInvoice->save();
                                $counter++;
                            }
                            break;
                        case 4:
                            if ($foreignSchool) {
                                $tuitionDetailsForFullPayment = json_decode($tuitionDetails->full_payment_ministry, true);
                                $fullPaymentAmount = str_replace(',', '', $tuitionDetailsForFullPayment['full_payment_irr_ministry']);
                            } else {
                                $tuitionDetailsForFullPayment = json_decode($tuitionDetails->full_payment, true);
                                $fullPaymentAmount = str_replace(',', '', $tuitionDetailsForFullPayment['full_payment_irr']);
                            }
                            $fullPaymentAmountAdvance = ($fullPaymentAmount * 30) / 100;
                            $totalDiscountsFull = (($fullPaymentAmount * $allDiscountPercentages) / 100) + $familyPercentagePriceFullPayment;
                            $tuitionDiscountFull = ($fullPaymentAmount * 40) / 100;
                            if ($totalDiscountsFull > $tuitionDiscountFull) {
                                $totalDiscountsFull = $tuitionDiscountFull;
                            }
                            $fullPaymentAmountInstallment = $fullPaymentAmount - $totalDiscountsFull - $fullPaymentAmountAdvance;
                            $newInvoice = new TuitionInvoiceDetails;
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
                    $transactionRefId = $request->SaleOrderId;

                    $tuitionInvoiceDetails = TuitionInvoiceDetails::find($invoiceDescription['invoice_details_id']);
                    $allCustomTuitionInvoices = TuitionInvoiceDetailsPayment::where('invoice_details_id', $tuitionInvoiceDetails->id)->sum('amount');

                    if ($tuitionInvoiceDetails->amount == $invoiceDescription['amount']) {
                        $tuitionInvoiceDetails->is_paid = 1;
                        $tuitionInvoiceDetails->invoice_id = $transactionDetail->id;
                        $tuitionInvoiceDetails->payment_method = $invoiceDescription['payment_method'];
                        $tuitionInvoiceDetails->payment_details = json_encode($request->all(), true);
                        $tuitionInvoiceDetails->date_of_payment = now();
                        $tuitionInvoiceDetails->save();

                        $tuitionInvoiceInfo = TuitionInvoices::find($tuitionInvoiceDetails->tuition_invoice_id);

                        $studentAppliance = StudentApplianceStatus::find($tuitionInvoiceInfo->appliance_id);
                        $studentAppliance->tuition_payment_status = 'Paid';
//                        $studentAppliance->approval_status = 1;
                        $studentAppliance->documents_uploaded = 0;
                        $studentAppliance->save();
                        $messageText = "You have successfully paid tuition installment. \nTransaction number: $transactionRefId \nSavior Schools";
                    } elseif ($tuitionInvoiceDetails->amount - $allCustomTuitionInvoices == $invoiceDescription['amount']) {
                        $tuitionInvoiceDetails->is_paid = 1;
                        $tuitionInvoiceDetails->payment_method = 3;
                        $tuitionInvoiceDetails->save();

                        $tuitionInvoiceInfo = TuitionInvoices::find($tuitionInvoiceDetails->tuition_invoice_id);

                        $studentAppliance = StudentApplianceStatus::find($tuitionInvoiceInfo->appliance_id);
                        $studentAppliance->tuition_payment_status = 'Paid';
//                        $studentAppliance->approval_status = 1;
                        $studentAppliance->documents_uploaded = 0;
                        $studentAppliance->save();
                        $messageText = "You have successfully paid tuition installment. \nTransaction number: $transactionRefId \nSavior Schools";
                    } else {
                        TuitionInvoiceDetailsPayment::create([
                            'invoice_details_id' => $tuitionInvoiceDetails->id,
                            'invoice_id' => $transactionDetail->id,
                            'payment_details' => json_encode($request->all(), true),
                            'payment_method' => 3,
                            'amount' => $invoiceDescription['amount'],
                            'status' => 1,
                            'adder' => auth()->user()->id,
                        ]);
                        $messageText = "Your entered tuition amount has been successfully paid. \nTransaction number: $transactionRefId \nSavior Schools";
                    }

                    $reservatoreMobile = $user->mobile;
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
