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
        $invoice = new Invoice;

// Set invoice amount.
        $invoice->amount(1000);

// Add invoice details: There are 4 syntax available for this.
// 1
        $invoice->detail(['detailName' => 'your detail goes here']);
// 2
        $invoice->detail('detailName','your detail goes here');
// 3
        $invoice->detail(['name1' => 'detail1','name2' => 'detail2']);
// 4
        $invoice->detail('detailName1','your detail1 goes here')
            ->detail('detailName2','your detail2 goes here');
    }
}
