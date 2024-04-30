<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Shetabit\Multipay\Invoice;
use Shetabit\Payment\Facade\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;

class PaymentController extends Controller
{
    public function behpardakhtPayment(Request $request){}
    public function verify(Request $request)
    {
        dd($request);
        $transaction_id=\App\Models\Invoice::where('type','Application Reservation')->latest()->first();
        $transaction_id=$transaction_id->transaction_id;
        try {
            $receipt = Payment::transactionId($transaction_id)->verify();

            // You can show payment referenceId to the user.
            echo $receipt->getReferenceId();

        } catch (InvalidPaymentException $exception) {
            /**
            when payment is not verified, it will throw an exception.
            We can catch the exception to handle invalid payments.
            getMessage method, returns a suitable message that can be used in user interface.
             **/
            echo $exception->getMessage();
        }
    }
}
