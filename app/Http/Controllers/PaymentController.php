<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class PaymentController extends Controller
{
    public function verify(Request $request)
    {
        $transaction_id=\App\Models\Invoice::where('type','Application Reservation')->latest()->first();
        $transaction_id=$transaction_id->transaction_id;
//        dd($request->all());
        try {
            $receipt = Payment::transactionId($transaction_id)->verify();

            return redirect()->route('dashboard');
            // You can show payment referenceId to the user.

        } catch (InvalidPaymentException $exception) {
            /**
            when payment is not verified, it will throw an exception.
            We can catch the exception to handle invalid payments.
            getMessage method, returns a suitable message that can be used in user interface.
             **/

            dd(\session('id'));
            return redirect()->route('dashboard');

            echo $exception->getMessage();
        }
    }
}
