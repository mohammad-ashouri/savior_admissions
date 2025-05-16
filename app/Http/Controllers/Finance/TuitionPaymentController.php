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
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionDetail;
use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoiceDetailsPayment;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\User;
use App\Models\UserAccessInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;

class TuitionPaymentController extends Controller
{
    public function index()
    {
        $academicYears = $tuitionInvoices = $tuitionInvoiceDetails = [];
        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
            $myStudentsAcademicYears = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.academic_year')->toArray();
            $academicYears = AcademicYear::whereIn('id', $myStudentsAcademicYears)->get();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->orderByDesc('created_at')->get();
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::get();
        }

        return view('Finance.TuitionInvoices.index', compact( 'academicYears', 'tuitionInvoices', 'tuitionInvoiceDetails'));
    }

    public function search(Request $request)
    {
        $this->validate($request, [
            'academic_year' => 'nullable|integer|exists:academic_years,id',
        ]);

        $academicYear = $request->academic_year;

        $data = StudentApplianceStatus::with('studentInfo')->where('documents_uploaded_approval', '=', 1);
        if (! empty($academicYear)) {
            $data->whereAcademicYear($academicYear);
        }
        $studentApplianceStatus = $data->get()->pluck('id')->toArray();

        $tuitionInvoiceDetails = [];

        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
            $myStudentsAcademicYears = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.academic_year')->toArray();
            $academicYears = AcademicYear::whereIn('id', $myStudentsAcademicYears)->get();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->where('student_appliance_statuses.academic_year', $academicYear)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->orderByDesc('created_at')->get();
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $studentApplianceStatus)->orderBy('created_at', 'asc')->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')
                ->with('invoiceDetails')
                ->with('paymentMethodInfo')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->get();
            $academicYears = AcademicYear::get();
        }

        return view('Finance.TuitionInvoices.index', compact('tuitionInvoiceDetails',  'academicYears'));
    }

    public function show($tuition_id)
    {

        $tuitionInvoiceDetails = [];
        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->whereId($tuition_id)->first();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->whereId($tuition_id)->first();
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $tuitionInvoices = TuitionInvoices::orderBy('created_at', 'asc')->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')
                ->with('invoiceDetails')
                ->with('paymentMethodInfo')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->whereId($tuition_id)
                ->first();
        }

        return view('Finance.TuitionInvoices.show', compact('tuitionInvoiceDetails'));
    }

    public function prepareToPayTuition($tuition_id)
    {

        $tuitionInvoiceDetails = [];
        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with(['tuitionInvoiceDetails', 'invoiceDetails', 'paymentMethodInfo', 'paymentMethodInfo', 'customPayments'])
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->whereId($tuition_id)
                ->whereIsPaid(0)
                ->first();
        }

        $paymentMethods = PaymentMethod::where('id', 2)->orWhere('id', 1)->get();
        $customTuitionPaid = TuitionInvoiceDetailsPayment::with('tuitionInvoiceDetails')->where('status', '!=', 3)->where('invoice_details_id', $tuitionInvoiceDetails->id)->sum('amount');

        return view('Finance.TuitionInvoices.pay', compact('tuitionInvoiceDetails', 'paymentMethods', 'customTuitionPaid'));
    }

    public function payTuition(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'tuition_invoice_id' => 'required|exists:tuition_invoice_details,id',
            'payment_method' => 'required|exists:payment_methods,id|in:1,2',
            'payment_amount' => 'nullable|integer|min:200000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $tuition_id = $request->tuition_invoice_id;
        $paymentMethod = $request->payment_method;
        $paymentAmount = (int) $request->payment_amount;

        if ($paymentMethod == 1 and auth()->check() and ! auth()->user()->isImpersonated()) {
            abort(403, 'INCORRECT PAYMENT METHOD!');
        }

        $tuitionInvoiceDetails = [];

        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')
                ->with('invoiceDetails')
                ->with('paymentMethodInfo')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->whereId($tuition_id)
                ->whereIsPaid(0)
                ->first();
        }

        if (empty($tuitionInvoiceDetails)) {
            abort(403);
        }
        $tuitionAmount = (int) $tuitionInvoiceDetails->amount;

        $allCustomTuitionInvoices = TuitionInvoiceDetailsPayment::where('invoice_details_id', $tuitionInvoiceDetails->id)->where('status', '!=', 3)->sum('amount');
        if ($paymentAmount > $tuitionAmount - $allCustomTuitionInvoices) {
            return back()->withErrors([
                'payment_amount' => 'The payment amount cannot exceed '.number_format($tuitionAmount - $allCustomTuitionInvoices).' Rials',
            ]);
        }

        switch ($paymentMethod) {
            case 1:
                $validator = Validator::make($request->all(), [
                    'document_file_full_payment1' => 'required|mimes:png,jpg,jpeg,pdf,bmp',
                    'document_file_full_payment2' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp',
                    'document_file_full_payment3' => 'nullable|mimes:png,jpg,jpeg,pdf,bmp',
                    'description' => 'nullable|string|max:256',
                ]);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $appliance_id = $tuitionInvoiceDetails->tuitionInvoiceDetails->appliance_id;
                $student_id = $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->student_id;
                $description = $request->description;

                $applicationInfo = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
                    ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
                    ->join('interviews', 'applications.id', '=', 'interviews.application_id')
                    ->where('application_reservations.student_id', $student_id)
                    ->where('applications.reserved', 1)
                    ->where('application_reservations.payment_status', 1)
                    ->where('applications.interviewed', 1)
                    ->where('interviews.interview_type', 3)
//                    ->whereIn('application_timings.academic_year', $this->getActiveAcademicYears())
                    ->orderByDesc('application_reservations.id')
                    ->first();
                //                dd($applicationInfo);

                $tuition = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')
                    ->where('tuitions.academic_year', $applicationInfo->academic_year)
                    ->where('tuition_details.level', $applicationInfo->level)
                    ->first();

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
                if ($paymentAmount == $tuitionAmount) {
                    $tuitionInvoiceDetails = TuitionInvoiceDetails::find($tuitionInvoiceDetails->id);
                    $tuitionInvoiceDetails->payment_method = $paymentMethod;
                    $tuitionInvoiceDetails->is_paid = 2;
                    $tuitionInvoiceDetails->date_of_payment = now();
                    $tuitionInvoiceDetails->description = json_encode(['user_description' => $description, 'files' => $filesSrc, 'tuition_type' => json_decode($tuitionInvoiceDetails->description, true)['tuition_type'], 'tuition_details_id' => $tuition->id], true);
                    $tuitionInvoiceDetails->save();
                }
                if ($paymentAmount <= $tuitionAmount - $allCustomTuitionInvoices) {
                    TuitionInvoiceDetailsPayment::create([
                        'invoice_details_id' => $tuitionInvoiceDetails->id,
                        'payment_details' => json_encode(['user_description' => $description, 'files' => $filesSrc, 'tuition_type' => json_decode($tuitionInvoiceDetails->description, true)['tuition_type'], 'tuition_details_id' => $tuition->id], true),
                        'payment_method' => 1,
                        'amount' => $paymentAmount,
                        'status' => 2,
                        'adder' => auth()->user()->id,
                    ]);
                }

                return redirect()->route('TuitionInvoices.index')->with(['success' => 'You have successfully paid tuition installment. Please wait for financial approval!']);

                break;
            case 2:
                if ($paymentAmount == $tuitionAmount) {
                    $invoice = (new Invoice)->amount($paymentAmount);

                    return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionInstallmentPayment')->purchase(
                        $invoice,
                        function ($driver, $transactionID) use ($paymentAmount, $tuitionInvoiceDetails, $paymentMethod) {
                            $dataInvoice = new \App\Models\Invoice;
                            $dataInvoice->user_id = auth()->user()->id;
                            $dataInvoice->type = 'Tuition Payment '.json_decode($tuitionInvoiceDetails->description, true)['tuition_type'];
                            $dataInvoice->amount = $paymentAmount;
                            $dataInvoice->description = json_encode(['amount' => $paymentAmount, 'invoice_details_id' => $tuitionInvoiceDetails->id, 'payment_method' => $paymentMethod], true);
                            $dataInvoice->transaction_id = $transactionID;
                            $dataInvoice->save();
                        }
                    )->pay()->render();
                }
                if ($paymentAmount < $tuitionAmount - $allCustomTuitionInvoices) {
                    $invoice = (new Invoice)->amount($paymentAmount);

                    return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionInstallmentPayment')->purchase(
                        $invoice,
                        function ($driver, $transactionID) use ($paymentAmount, $tuitionInvoiceDetails, $paymentMethod) {
                            $dataInvoice = new \App\Models\Invoice;
                            $dataInvoice->user_id = auth()->user()->id;
                            $dataInvoice->type = 'Custom Tuition Payment '.json_decode($tuitionInvoiceDetails->description, true)['tuition_type'];
                            $dataInvoice->amount = $paymentAmount;
                            $dataInvoice->description = json_encode(['amount' => $paymentAmount, 'invoice_details_id' => $tuitionInvoiceDetails->id, 'payment_method' => $paymentMethod], true);
                            $dataInvoice->transaction_id = $transactionID;
                            $dataInvoice->save();
                        }
                    )->pay()->render();
                }
                break;
        }
        abort(522);
    }

    public function changeTuitionInvoiceStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required|exists:tuition_invoice_details,id',
            'status' => 'required|integer|in:1,3',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => 'Failed to change tuition invoice status!'], 422);
        }

        $tuition_id = $request->invoice_id;
        if (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')
                ->with('invoiceDetails')
                ->with('paymentMethodInfo')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->whereId($tuition_id)->first();
        } else {
            $tuitionInvoices = TuitionInvoices::pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->whereId($tuition_id)->first();
        }
        if (empty($tuitionInvoiceDetails)) {
            return response()->json(['message' => 'Failed to change tuition invoice status!'], 422);
        }

        $tuitionType = json_decode($tuitionInvoiceDetails->description, true)['tuition_type'];
        $tuitionInvoiceInfo = TuitionInvoices::find($tuitionInvoiceDetails->tuition_invoice_id);
        $studentAppliance = StudentApplianceStatus::find($tuitionInvoiceInfo->appliance_id);

        $applicationInfo = ApplicationReservation::join('applications', 'application_reservations.application_id', '=', 'applications.id')
            ->join('application_timings', 'applications.application_timing_id', '=', 'application_timings.id')
            ->join('interviews', 'applications.id', '=', 'interviews.application_id')
            ->where('application_reservations.student_id', $studentAppliance->student_id)
            ->where('applications.reserved', 1)
            ->where('application_reservations.payment_status', 1)
//            ->where('applications.interviewed', 1)
            ->where('interviews.interview_type', 3)
//            ->whereIn('application_timings.academic_year', $this->getActiveAcademicYears())
            ->orderByDesc('application_reservations.id')
            ->first();


        if ($tuitionType == 'Full Payment') {
            $tuitionInvoiceDetails = TuitionInvoiceDetails::find($tuition_id);

            $studentAppliance = StudentApplianceStatus::find($tuitionInvoiceDetails->tuitionInvoiceDetails->appliance_id);
            $guardianMobile = $studentAppliance->studentInformations->guardianInfo->mobile;
            switch ($request->status) {
                case 1:
                    $tuitionInvoiceDetails->is_paid = $request->status;
                    $tuitionInvoiceDetails->save();

                    $studentAppliance->tuition_payment_status = 'Paid';
                    //                    $studentAppliance->approval_status = 1;
                    $studentAppliance->documents_uploaded = 0;
                    $studentAppliance->save();

                    $message = "Tuition payment confirmation with id $tuition_id has been done successfully. To view more information, refer to the tuition invoices page from the Finance menu.\nSavior Schools";
                    $this->sendSMS($guardianMobile, $message);

                    return response()->json(['message' => 'Payment accepted!']);

                case 3:
                    $tuitionInvoiceDetails->is_paid = $request->status;
                    $tuitionInvoiceDetails->save();

                    $studentAppliance->tuition_payment_status = 'Pending';
                    $studentAppliance->approval_status = 0;
                    $studentAppliance->save();
                    $message = "Your tuition payment with ID: $tuition_id has been rejected. Please contact the financial expert of the relevant school.\nSavior Schools";
                    $this->sendSMS($guardianMobile, $message);

                    return response()->json(['message' => 'Payment rejected!']);
                default:
                    abort(503);
            }
        }

        switch ($request->status) {
            case 1:
                $tuitionInvoiceDetails = TuitionInvoiceDetails::find($tuition_id);
                $tuitionInvoiceDetails->is_paid = 1;
                $tuitionInvoiceDetails->save();

                if ($studentAppliance->tuition_payment_status == 'Paid' and $studentAppliance->approval_status == 1) {
                    $guardianMobile = $studentAppliance->studentInformations->guardianInfo->mobile;
                    $message = "Your payment with ID $tuition_id has been Approved. To view more information, refer to the tuition invoices page from the Finance menu.\nSavior Schools";
                    $this->sendSMS($guardianMobile, $message);

                    return response()->json(['message' => 'Approved!']);
                }

                if (in_array($studentAppliance->academic_year, [1, 2, 3])) {
                    $evidence = Evidence::where('appliance_id', $studentAppliance->id)->first()->informations;
                    if (isset(json_decode($evidence, true)['foreign_school']) and json_decode($evidence, true)['foreign_school'] == 'Yes') {
                        $foreignSchool = true;
                    } else {
                        $foreignSchool = false;
                    }
                } else {
                    $interview_form = json_decode($applicationInfo['interview_form'], true);
                    if (isset($interview_form['foreign_school']) and $interview_form['foreign_school'] == 'Yes') {
                        $foreignSchool = true;
                    } else {
                        $foreignSchool = false;
                    }
                }

                $studentAppliance->tuition_payment_status = 'Paid';
                //                $studentAppliance->approval_status = 1;
                $studentAppliance->documents_uploaded = 0;
                $studentAppliance->save();

                $allDiscountPercentages = $this->getAllDiscounts($studentAppliance->student_id, $studentAppliance->academic_year);

                // Found previous discounts
                $familyPercentagePriceThreeInstallment = $familyPercentagePriceSevenInstallment = $familyPercentagePriceFullPayment = 0;
                $tuitionDetails = TuitionDetail::find(json_decode($tuitionInvoiceDetails->description, true)['tuition_details_id']);

                switch ($tuitionInvoiceInfo->payment_type) {
                    case 2:
                        $counter = 1;
                        if ($foreignSchool) {
                            $tuitionDetailsForTwoInstallments = json_decode($tuitionDetails->two_installment_payment_ministry, true);
                            $twoInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForTwoInstallments['two_installment_amount_irr_ministry']);
                            $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForTwoInstallments['two_installment_each_installment_irr_ministry']);
                        } else {
                            $tuitionDetailsForTwoInstallments = json_decode($tuitionDetails->two_installment_payment, true);
                            $twoInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForTwoInstallments['two_installment_amount_irr']);
                            $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForTwoInstallments['two_installment_each_installment_irr']);
                        }
                        $totalDiscountsTwo = (($twoInstallmentPaymentAmount * $allDiscountPercentages) / 100);
                        $tuitionDiscountTwo = ($twoInstallmentPaymentAmount * 40) / 100;
                        if ($totalDiscountsTwo > $tuitionDiscountTwo) {
                            $totalDiscountsTwo = $tuitionDiscountTwo;
                        }
                        $totalDiscountTwo = $totalDiscountsTwo / 2;

                        while ($counter < 3) {
                            $newInvoice = new TuitionInvoiceDetails;
                            $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                            $newInvoice->amount = $amountOfEachInstallment - $totalDiscountTwo;
                            $newInvoice->is_paid = 0;
                            $newInvoice->description = json_encode(['tuition_type' => 'Two Installment - Installment '.$counter], true);
                            $newInvoice->save();
                            $counter++;
                        }
                        break;
                    case 3:
                        $counter = 1;
                        if ($foreignSchool) {
                            $tuitionDetailsForFourInstallments = json_decode($tuitionDetails->four_installment_payment_ministry, true);
                            $fourInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForFourInstallments['four_installment_amount_irr_ministry']);
                            $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForFourInstallments['four_installment_each_installment_irr_ministry']);
                        } else {
                            $tuitionDetailsForFourInstallments = json_decode($tuitionDetails->four_installment_payment, true);
                            $fourInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForFourInstallments['four_installment_amount_irr']);
                            $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForFourInstallments['four_installment_each_installment_irr']);
                        }
                        $totalDiscountsFour = (($fourInstallmentPaymentAmount * $allDiscountPercentages) / 100);
                        $tuitionDiscountFour = ($fourInstallmentPaymentAmount * 40) / 100;
                        if ($totalDiscountsFour > $tuitionDiscountFour) {
                            $totalDiscountsFour = $tuitionDiscountFour;
                        }
                        $totalDiscountFour = $totalDiscountsFour / 4;
                        while ($counter < 5) {
                            $newInvoice = new TuitionInvoiceDetails;
                            $newInvoice->tuition_invoice_id = $tuitionInvoiceDetails->tuition_invoice_id;
                            $newInvoice->amount = $amountOfEachInstallment - $totalDiscountFour;
                            $newInvoice->is_paid = 0;
                            $newInvoice->description = json_encode(['tuition_type' => 'Four Installment - Installment '.$counter], true);
                            $newInvoice->save();
                            $counter++;
                        }
                        break;
                    case 5:
                        $counter = 1;
                        if ($foreignSchool) {
                            $tuitionDetailsForThreeInstallments = json_decode($tuitionDetails->three_installment_payment_ministry, true);
                            $threeInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForThreeInstallments['three_installment_amount_irr_ministry']);
                            $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForThreeInstallments['three_installment_each_installment_irr_ministry']);
                        } else {
                            $tuitionDetailsForThreeInstallments = json_decode($tuitionDetails->three_installment_payment, true);
                            $threeInstallmentPaymentAmount = str_replace(',', '', $tuitionDetailsForThreeInstallments['three_installment_amount_irr']);
                            $amountOfEachInstallment = str_replace(',', '', $tuitionDetailsForThreeInstallments['three_installment_each_installment_irr']);
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
                            $newInvoice->description = json_encode(['tuition_type' => 'Three Installment - Installment '.$counter], true);
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
                            $newInvoice->description = json_encode(['tuition_type' => 'Seven Installment - Installment '.$counter], true);
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

                $guardianMobile = $studentAppliance->studentInformations->guardianInfo->mobile;
                $message = "Tuition payment confirmation with id $tuition_id has been done successfully. To view more information, refer to the tuition invoices page from the Finance menu.\nSavior Schools";
                $this->sendSMS($guardianMobile, $message);

                return response()->json(['message' => 'Installments were made!']);
            case 3:
                $tuitionInvoiceDetails = TuitionInvoiceDetails::find($tuition_id);

                $originalInvoice = $tuitionInvoiceDetails->replicate();
                $tuitionInvoiceDetails->is_paid = 3;
                $tuitionInvoiceDetails->save();

                if (! str_contains(json_decode($tuitionInvoiceDetails['description'], true)['tuition_type'], 'Installment Advance') and  json_decode($tuitionInvoiceDetails['description'], true)['tuition_type'] == 'Full Payment With Advance - Installment') {
                    $newInvoice = $originalInvoice->replicate();
                    $newInvoice->payment_method = null;
                    $newInvoice->date_of_payment = null;
                    $newInvoice->is_paid = 0;

                    $data = json_decode($newInvoice->description, true);
                    $filteredData = [
                        'tuition_type' => $data['tuition_type'],
                    ];
                    $newInvoice->description = json_encode($filteredData);

                    $newInvoice->save();
                }

                $tuitionInvoiceInfo = TuitionInvoices::find($tuitionInvoiceDetails->tuition_invoice_id);
                $studentAppliance = StudentApplianceStatus::find($tuitionInvoiceInfo->appliance_id);
                if ($studentAppliance->tuition_payment_status == 'Pending For Review') {
                    $studentAppliance->tuition_payment_status = 'Pending';
                    $studentAppliance->approval_status = 0;
                    $studentAppliance->save();
                }

                $guardianMobile = $studentAppliance->studentInformations->guardianInfo->mobile;
                $message = "Your tuition payment with ID: $tuition_id has been rejected. Please contact the financial expert of the relevant school.\nSavior Schools";
                $this->sendSMS($guardianMobile, $message);

                return response()->json(['message' => 'Payment rejected!']);

                break;
            default:
                return response()->json(['message' => 'Failed to change tuition invoice status!'], 422);
        }
    }

    public function applianceInvoices($applianceId)
    {
        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->where('student_appliance_statuses.id', $applianceId)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudent = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->where('student_appliance_statuses.id', $applianceId)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::where('appliance_id', $myStudent)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with(['tuitionInvoiceDetails', 'invoiceDetails', 'paymentMethodInfo'])
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->get();
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->where('student_appliance_statuses.id', $applianceId)
                ->pluck('student_appliance_statuses.id')->toArray();

            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
        }

        return view('Finance.TuitionInvoices.applianceInvoices', compact('tuitionInvoiceDetails'));
    }

    public function applianceInvoicesEdit($applianceId)
    {
        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->where('student_appliance_statuses.id', $applianceId)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudent = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->where('student_appliance_statuses.id', $applianceId)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::where('appliance_id', $myStudent)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with(['tuitionInvoiceDetails', 'invoiceDetails', 'paymentMethodInfo'])
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->get();
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->where('student_appliance_statuses.id', $applianceId)
                ->pluck('student_appliance_statuses.id')->toArray();

            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
        }

        $discounts = Discount::with('allDiscounts')
            ->whereAcademicYear($tuitionInvoiceDetails[0]->tuitionInvoiceDetails->applianceInformation->academic_year)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();

        return view('Finance.TuitionInvoices.editApplianceInvoices', compact('tuitionInvoices', 'tuitionInvoiceDetails', 'discounts'));
    }

    public function changeTuitionInvoiceDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tuition_invoice_id' => 'required|integer|exists:tuition_invoice_details,id',
            'data' => 'required|string',
            'job' => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $tuitionInvoice = TuitionInvoiceDetails::find($request->tuition_invoice_id);
        if ($request->job == 'change_tracking_code') {
            $tuitionInvoice->tracking_code = $request->data;
        }
        if ($request->job == 'change_financial_manager_description') {
            $tuitionInvoice->financial_manager_description = $request->data;
        }
        if ($request->job == 'change_payment_date') {
            $data = $this->convertPersianToEnglish($request->data);
            [$date, $time] = explode(' ', $data);
            $gregorianDate = Jalalian::fromFormat('Y/m/d H:i:s', "$date $time")
                ->toCarbon()
                ->format('Y-m-d H:i:s');

            $tuitionInvoice->date_of_payment = $gregorianDate;
        }
        $tuitionInvoice->editor = auth()->user()->id;
        $tuitionInvoice->save();

    }

    public function invoicesDetailsIndex()
    {
        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
            $myStudentsAcademicYears = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.academic_year')->toArray();
            $academicYears = AcademicYear::whereIn('id', $myStudentsAcademicYears)->get();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->orderByDesc('id')->get();
            $tuitionInvoiceDetails = [];
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $tuitionInvoices = TuitionInvoices::orderBy('created_at', 'asc')->pluck('id')->toArray();
            $academicYears = AcademicYear::get();
            $tuitionInvoiceDetails = [];
        }

        return view('Finance.InvoicesDetails.index', compact('tuitionInvoiceDetails',  'academicYears'));
    }

    public function searchInvoicesDetails(Request $request)
    {
        $this->validate($request, [
            'academic_year' => 'required|integer|exists:academic_years,id',
        ]);

        $academicYear = $request->academic_year;

        $data = StudentApplianceStatus::with('studentInfo')->where('documents_uploaded_approval', '=', 1);
        $data->whereAcademicYear($academicYear);
        $studentApplianceStatus = $data->get()->pluck('id')->toArray();

        $tuitionInvoiceDetails = [];

        if (auth()->user()->hasExactRoles(['Parent'])) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with(['tuitionInvoiceDetails', 'invoiceDetails', 'paymentMethodInfo'])
                ->where('is_paid', '1')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)->get();
            $myStudentsAcademicYears = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.academic_year')->toArray();
            $academicYears = AcademicYear::whereIn('id', $myStudentsAcademicYears)->get();
        } elseif (auth()->user()->hasRole(['Principal','Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->where('student_appliance_statuses.academic_year', $academicYear)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with(['tuitionInvoiceDetails', 'invoiceDetails', 'paymentMethodInfo'])
                ->where('is_paid', '1')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->get();
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->orderByDesc('id')->get();
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $tuitionInvoices = TuitionInvoices::with('applianceInformation')
                ->whereIn('appliance_id', $studentApplianceStatus)
                ->whereHas('applianceInformation', function ($query) use ($academicYear) {
                    $query->where('academic_year', $academicYear);
                })
                ->orderBy('created_at', 'asc')
                ->pluck('id')
                ->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with(['tuitionInvoiceDetails', 'invoiceDetails', 'paymentMethodInfo'])
                ->where('is_paid', '1')
                ->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->get();
            $academicYears = AcademicYear::get();
        }

        return view('Finance.InvoicesDetails.index', compact('tuitionInvoiceDetails',  'academicYears'));
    }

    public function changeCustomTuitionInvoiceDetails(Request $request)
    {
        $this->validate($request, [
            'invoice_id' => 'required|integer|exists:tuition_invoice_details_payments,id',
            'type' => 'required|string|in:Accept,Reject',
        ]);
        $tuitionInvoiceDetailsPayment = TuitionInvoiceDetailsPayment::where('id', request('invoice_id'))->firstOrFail();
        if (auth()->user()->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $tuitionInvoiceDetails = TuitionInvoiceDetails::with([
                'tuitionInvoiceDetails' => function ($query) use ($academicYears) {
                    $query->with([
                        'applianceInformation' => function ($query) use ($academicYears) {
                            $query->whereIn('academic_year', $academicYears);
                        },
                    ]);
                },
            ])
                ->where('id', $tuitionInvoiceDetailsPayment->invoice_details_id)
                ->firstOrFail();
        } elseif (! auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }
        switch ($request->type) {
            case 'Accept':
                $tuitionInvoiceDetailsPayment->status = 1;
                $tuitionInvoiceDetailsPayment->approval_date = now();
                break;
            case 'Reject':
                $tuitionInvoiceDetailsPayment->status = 3;
                break;
        }
        $tuitionInvoiceDetailsPayment->seconder = auth()->user()->id;
        $tuitionInvoiceDetailsPayment->save();

        return back()->with('success', 'Tuition Invoice Details Status Changed Successfully');
    }
}
