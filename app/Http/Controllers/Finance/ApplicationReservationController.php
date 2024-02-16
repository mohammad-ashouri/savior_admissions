<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApplicationReservationController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:reservation-invoice-list', ['only' => ['index']]);
        $this->middleware('permission:reservation-invoice-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:reservation-invoice-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:reservation-invoice-delete', ['only' => ['destroy']]);
        $this->middleware('permission:reservation-invoice-show', ['only' => ['show']]);
        $this->middleware('permission:reservation-invoice-search', ['only' => ['searchApplicationTiming']]);
    }

    public function index()
    {
        return view('Finance.ApplicationReservationInvoices.index');
    }
}
