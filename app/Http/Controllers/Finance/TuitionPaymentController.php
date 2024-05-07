<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\User;

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
            $tuitionInvoices=TuitionInvoices::whereIn('appliance_id',$myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails=TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id',$tuitionInvoices)->paginate(30);
        }
        return view('Finance.TuitionInvoices.index', compact('tuitionInvoiceDetails'));
    }

    public function show($tuition_id)
    {
        $me = User::find(auth()->user()->id);

        $tuitionInvoices = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->pluck('student_appliance_statuses.id')->toArray();
            $tuitionInvoices=TuitionInvoices::whereIn('appliance_id',$myStudents)->pluck('id')->toArray();
            $tuitionInvoiceDetails=TuitionInvoiceDetails::with('tuitionInvoiceDetails')->with('invoiceDetails')->with('paymentMethodInfo')->whereIn('tuition_invoice_id',$tuitionInvoices)->where('id',$tuition_id)->first();
        }
        return view('Finance.TuitionInvoices.show', compact('tuitionInvoiceDetails'));
    }

    public function payTuition($tuition_id)
    {

    }
}
