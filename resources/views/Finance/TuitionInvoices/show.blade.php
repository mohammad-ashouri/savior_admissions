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
                        <div class="grid gap-6 mb-6 md:grid-cols-4">
                            <div>
                                <p class="font-bold">Invoice ID: </p> {{ $tuitionInvoiceDetails->id }}
                            </div>
                            <div>
                                <p class="font-bold">Academic
                                    Year: </p> {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->academicYearInfo->name }}
                            </div>
                            <div>
                                <p class="font-bold">Academic
                                    Year: </p> {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->first_name_en }} {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->last_name_en }}
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
                                @endswitch
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
                                                <button data-modal-target="openImage"
                                                        data-modal-toggle="openImage"
                                                        data-image-src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $file) }}"
                                                        class="block w-full md:w-auto text-white  focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm text-center show-image"
                                                        type="button">
                                                    <img
                                                         class="h-96 text-blue-500 align-center max-w-full rounded-lg image-preview"
                                                         style="width: 500px; height: 350px"
                                                         src="{{ env('APP_URL').'/'. str_replace( 'public','storage', $file) }}"
                                                         alt="Document Not Found!">
                                                </button>
                                            </div>
                                        @endif
                                    @endforeach
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
