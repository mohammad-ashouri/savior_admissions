@php use App\Models\Catalogs\PaymentMethod;use App\Models\Document; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Tuition Payment Details</h1>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-3">
                            <div>
                                <p class="font-bold">Invoice ID: </p> {{ $tuitionInvoiceDetails->id }}
                            </div>
                            <div>
                                <p class="font-bold">Academic
                                    Year: </p> {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->academicYearInfo->name }}
                            </div>
                            <div>
                                <p class="font-bold">Student
                                    Info: </p> {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->first_name_en }} {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <p class="font-bold">Type Of Payment: </p>
                                @switch($tuitionInvoiceDetails->tuitionInvoiceDetails->payment_type)
                                    @case('1')
                                        Full Payment
                                        @break
                                    @case('2')
                                        Two installment
                                        @break
                                    @case('3')
                                        Four Installment
                                        @break
                                    @case('4')
                                        Full Payment With Advance
                                        @break
                                @endswitch
                            </div>
                            <div>
                                <p class="font-bold">Status: </p>
                                @if($me->hasRole('Principal') or $me->hasRole('Financial Manager') or $me->hasRole('Super Admin'))
                                    @if($tuitionInvoiceDetails->is_paid==2)
                                        <div class="flex">
                                            <select name="payment_status" id="payment_status"
                                                    class="font-normal block w-full p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            >
                                                <option @if($tuitionInvoiceDetails->is_paid==0) selected
                                                        @endif value="0">
                                                    Payment Processing
                                                </option>
                                                <option @if($tuitionInvoiceDetails->is_paid==1) selected
                                                        @endif value="1">
                                                    Approved
                                                </option>
                                                <option @if($tuitionInvoiceDetails->is_paid==2) selected
                                                        @endif value="2">
                                                    Pending To Review
                                                </option>
                                                <option @if($tuitionInvoiceDetails->is_paid==3) selected
                                                        @endif value="3">
                                                    Rejected
                                                </option>
                                            </select>
                                            <input type="hidden" id="invoice_id" value="{{$tuitionInvoiceDetails->id}}">
                                            <button type="button" id="set-payment-status"
                                                    class="text-white bg-green-500 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                Change
                                            </button>
                                        </div>
                                    @else
                                        @if($tuitionInvoiceDetails->is_paid==0)
                                            Payment Processing
                                        @elseif($tuitionInvoiceDetails->is_paid==1)
                                            Approved
                                        @elseif($tuitionInvoiceDetails->is_paid==2)
                                            Pending To Review
                                        @elseif($tuitionInvoiceDetails->is_paid==3)
                                            Rejected
                                        @endif
                                    @endif
                                @else
                                    @if($tuitionInvoiceDetails->is_paid==0)
                                        Payment Processing
                                    @elseif($tuitionInvoiceDetails->is_paid==1)
                                        Approved
                                    @elseif($tuitionInvoiceDetails->is_paid==2)
                                        Pending To Review
                                    @elseif($tuitionInvoiceDetails->is_paid==3)
                                        Rejected
                                    @endif
                                @endif
                            </div>
                        </div>
                        <a href="{{ url()->previous() }}">
                            <button type="button"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Back
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 rounded-lg dark:border-gray-700">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Payment Details</h1>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-4">
                            <div>
                                <p class="font-bold">Payment
                                    Method: </p> {{$tuitionInvoiceDetails->paymentMethodInfo->name}}
                            </div>
                            <div>
                                <p class="font-bold">Amount
                                    Paid: </p> {{ number_format($tuitionInvoiceDetails->amount) }} IRR
                            </div>
                            <div>
                                <p class="font-bold">Tuition
                                    Type: </p> {{ json_decode($tuitionInvoiceDetails->description,true)['tuition_type'] }}
                            </div>
                            <div>
                                <p class="font-bold">Date And Time Of
                                    Payment: </p> {{ $tuitionInvoiceDetails->date_of_payment }}
                            </div>
                        </div>
                        <div class="grid gap-6 mb-6 md:grid-cols-4">
                            @switch($tuitionInvoiceDetails->paymentMethodInfo->id)
                                @case('1')
                                    <div class="mt-3 ">
                                        <p class="font-bold mb-3">Payment Receipts</p>
                                        <div class="flex">
                                            @php
                                                $files=@json_decode($tuitionInvoiceDetails->description,true)['files'];
                                            @endphp
                                            @foreach($files as $key=>$file)
                                                @if(substr($file,-4)=='.pdf')
                                                    <div class="flex justify-center items-center">
                                                        <a target="_blank"
                                                           href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $file) }}">
                                                            <img class="pdf-documents-icons">
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="cursor-pointer img-hover-zoom img-hover-zoom--xyz "
                                                    >
                                                        <div
                                                            class="cursor-pointer img-hover-zoom img-hover-zoom--xyz my-gallery">
                                                            <a href="{{ env('APP_URL').'/'. str_replace( 'public','storage', $file) }}"
                                                               data-pswp-width="1669"
                                                               data-pswp-height="1500">
                                                                <img
                                                                    src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $file) }}"
                                                                    alt="Document Not Found!"/>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @break
                                @case('2')
                                    @php
                                        $paymentDetails=json_decode($tuitionInvoiceDetails->payment_details,true);
                                    @endphp
                                    <div>
                                        <p class="font-bold">Transaction ID: </p> {{ $paymentDetails['SaleOrderId'] }}
                                    </div>
                                    <div>
                                        <p class="font-bold">Reference ID: </p> {{ $paymentDetails['SaleReferenceId'] }}
                                    </div>
                                    <div>
                                        <p class="font-bold">Card Holder
                                            Pan: </p> {{ $paymentDetails['CardHolderPan'] }}
                                    </div>
                                    <div>
                                        <p class="font-bold">Date Of
                                            Payment: </p> {{ $tuitionInvoiceDetails->date_of_payment }}
                                    </div>
                                    @break
                            @endswitch
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
