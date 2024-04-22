<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Payment;

class PaymentController extends Controller
{
    public function behpardakhtPayment(Request $request)
    {

// Create new invoice.
        $invoice = (new Invoice)->amount(1000);

// Purchase the given invoice.
        (new \Shetabit\Multipay\Payment)->via('behpardakht')->purchase(
            $invoice,
            function($driver, $transactionId) {
                // We can store $transactionId in database.
            }
        );
    }
}
