@php use App\Models\Catalogs\PaymentMethod;use App\Models\Document; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Application Payment Details</h1>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-4">
                            <div>
                                <p class="font-bold">Reservation ID: </p> {{ $applicationInfo->id }}
                            </div>
                            <div>
                                <p class="font-bold">Student
                                    Information: </p>{{ $applicationInfo->studentInfo->generalInformationInfo->first_name_en }} {{ $applicationInfo->studentInfo->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <p class="font-bold">Level: </p>{{ $applicationInfo->levelInfo->name }}
                            </div>
                            <div>
                                <p class="font-bold">Application Date: </p>{{ $applicationInfo->applicationInfo->date }}
                            </div>
                            <div>
                                <p class="font-bold"> Start From
                                    : </p>{{ $applicationInfo->applicationInfo->start_from }}
                            </div>
                            <div>
                                <p class="font-bold"> Ends To : </p>{{ $applicationInfo->applicationInfo->ends_to }}
                            </div>
                            <div>
                                <p class="font-bold"> First Interviewer
                                    : </p>{{ $applicationInfo->applicationInfo->firstInterviewerInfo->generalInformationInfo->first_name_en }} {{ $applicationInfo->applicationInfo->firstInterviewerInfo->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <p class="font-bold"> Second Interviewer
                                    : </p>{{ $applicationInfo->applicationInfo->secondInterviewerInfo->generalInformationInfo->first_name_en }} {{ $applicationInfo->applicationInfo->secondInterviewerInfo->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <p class="font-bold"> Reserved By
                                    : </p>{{ $applicationInfo->reservatoreInfo->generalInformationInfo->first_name_en }} {{ $applicationInfo->reservatoreInfo->generalInformationInfo->last_name_en }}
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
            @can('reservation-payment-details-show')
                <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                    <h1 class="text-2xl font-medium"> Payment Details</h1>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="lg:col-span-2 col-span-3 ">
                        <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                            <div class="grid gap-6 mb-8 md:grid-cols-4">
                                <div>
                                    <p class="font-bold">Invoice
                                        ID: </p>{{ $applicationInfo->applicationInvoiceInfo->id }}
                                </div>
                                <div>
                                    <p class="font-bold"> Payment Method : </p>
                                    @php
                                        $paymentMethod=PaymentMethod::find(json_decode($applicationInfo->applicationInvoiceInfo->payment_information,true)['payment_method'])
                                    @endphp
                                    {{ $paymentMethod->name }}
                                </div>
                                <div>
                                    <p class="font-bold"> Fee Paid
                                        : </p>{{ number_format($applicationInfo->applicationInfo->applicationTimingInfo->fee) }}
                                    Rials
                                </div>
                                <div>
                                    <p class="font-bold"> Status: </p>
                                    @switch($applicationInfo->payment_status)
                                        @case(0)
                                            Awaiting payment
                                            @break
                                        @case(1)
                                            Paid
                                            @break
                                        @case(2)
                                            Awaiting confirmation
                                            @break
                                        @case(3)
                                            Rejected
                                            @break
                                    @endswitch
                                </div>
                                <div>
                                    <p class="font-bold"> Paid
                                        At: </p> {{$applicationInfo->applicationInvoiceInfo->created_at}}
                                </div>
                                @if($applicationInfo->applicationInvoiceInfo->description)
                                    <div>
                                        <p class="font-bold">
                                            Description: </p> {{$applicationInfo->applicationInvoiceInfo->description}}
                                    </div>
                                @endif
                            </div>
                            @switch($paymentMethod->id)
                                @case(1)
                                    <div>
                                        <div class="mb-2">
                                            <p class="font-bold"> Receipt: </p>
                                        </div>
                                        @php
                                            $paymentMethod=Document::find(json_decode($applicationInfo->applicationInvoiceInfo->payment_information,true)['document_id']);
                                            $paymentMethod->src = str_replace('public', 'storage', $paymentMethod->src);
                                        @endphp
                                        @if(substr($paymentMethod->src,-4)=='.pdf')
                                            <div class="flex justify-center items-center">
                                                <a target="_blank"
                                                   href="{{ env('APP_URL')}}/{{$paymentMethod->src }}">
                                                    <img class="pdf-documents-icons">
                                                </a>
                                            </div>
                                        @else
                                            <div
                                                class="w-96 h-96 cursor-pointer img-hover-zoom img-hover-zoom--xyz my-gallery">
                                                <a href="{{ env('APP_URL')}}/{{$paymentMethod->src }}"
                                                   data-pswp-width="1669"
                                                   data-pswp-height="1500">
                                                    <img
                                                        src="{{ env('APP_URL')}}/{{$paymentMethod->src }}"
                                                        alt="Document Not Found!"/>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    @break
                                @case(2)
                                    <div>
                                        <div class="mb-2">
                                            <p class="font-bold mb-2"> Payment Information </p>
                                            <hr>
                                            <div class="flex">
                                                @foreach(json_decode(json_decode($applicationInfo->applicationInvoiceInfo->payment_information,true)[0],true) as $key=>$value)
                                                    @switch($key)
                                                        @case('SaleOrderId')
                                                            <div class="mt-2">
                                                                <p class="font-bold">
                                                                    Sale Order Id</p> {{ $value }}
                                                            </div>
                                                            @break
                                                        @case('SaleReferenceId')
                                                            <div class="mt-2 ml-8">
                                                                <p class="font-bold">
                                                                    Sale Reference Id</p> {{ $value }}
                                                            </div>
                                                            @break
                                                        @case('CardHolderPan')
                                                            <div class="mt-2 ml-8">
                                                                <p class="font-bold">
                                                                    Card Info</p> {{ $value }}
                                                            </div>
                                                            @break
                                                    @endswitch
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                            @endswitch
                        </div>
                    </div>
                </div>

        </div>

        @endcan
    </div>
    </div>
@endsection
