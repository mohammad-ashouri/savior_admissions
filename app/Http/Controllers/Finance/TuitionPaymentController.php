<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\PaymentMethod;
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

        $tuitionInvoices = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->paginate(30);
        }
        else if ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year',$academicYears)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)->paginate(30);
        }

        return view('Finance.TuitionInvoices.index', compact('tuitionInvoiceDetails','me'));
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
        }
        else if ($me->hasRole('Principal') or $me->hasRole('Financial Manager')) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::where('user_id', $me->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::where('status', 1)->whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year',$academicYears)
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices = TuitionInvoices::whereIn('appliance_id', $myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails = TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id', $tuitionInvoices)
                ->where('id', $tuition_id)->first();
        }

        return view('Finance.TuitionInvoices.show', compact('tuitionInvoiceDetails'));
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
        $paymentMethod=$request->payment_method;
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

        $tuitionAmount=$tuitionInvoiceDetails->amount;
        switch ($paymentMethod){
            case 2:
                $invoice = (new Invoice)->amount($tuitionAmount);

                return Payment::via('behpardakht')->callbackUrl(env('APP_URL').'/VerifyTuitionInstallmentPayment')->purchase(
                    $invoice,
                    function ($driver, $transactionID) use ($tuitionAmount, $tuitionInvoiceDetails,$paymentMethod) {
                        $dataInvoice = new \App\Models\Invoice();
                        $dataInvoice->user_id = auth()->user()->id;
                        $dataInvoice->type = 'Tuition Payment '.json_decode($tuitionInvoiceDetails->description,true)['tuition_type'];
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
}
