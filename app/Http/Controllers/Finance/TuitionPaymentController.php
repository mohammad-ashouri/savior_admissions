<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\StudentInformation;
use App\Models\User;

class TuitionPaymentController extends Controller
{
    public function index()
    {
        $me = User::find(auth()->user()->id);

        $invoices = [];
        if ($me->hasRole('Parent')) {
            $myStudents = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->where('student_informations.guardian', auth()->user()->id)
                ->whereNotNull('tuition_payment_status')
                ->get();
            dd($myStudents);
        }

        return view('Finance.TuitionInvoices.index', compact('invoices'));

    }
}
