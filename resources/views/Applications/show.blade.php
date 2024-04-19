@php use App\Models\Catalogs\PaymentMethod;use App\Models\Document; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Application Payment Details</h1>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-4">
                            <div>
                                <p class="font-bold">Reserve ID: </p> {{ $applicationInfo->id }}
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
                                <p class="font-bold"> Reservatore
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
                                    @endswitch
                                </div>
                                <div>
                                    <p class="font-bold"> Paid
                                        at: </p> {{$applicationInfo->applicationInvoiceInfo->created_at}}
                                </div>
                                @if($applicationInfo->applicationInvoiceInfo->description)
                                    <div>
                                        <p class="font-bold">
                                            Description: </p> {{$applicationInfo->applicationInvoiceInfo->description}}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div class="mb-2">
                                    <p class="font-bold"> Receipt: </p>
                                </div>
                                @php
                                    $paymentMethod=Document::find(json_decode($applicationInfo->applicationInvoiceInfo->payment_information,true)['document_id']);
                                    $paymentMethod->src = str_replace('public', 'storage', $paymentMethod->src);
                                @endphp
                                <img class="w-96" src="{{ env('APP_URL')}}/{{$paymentMethod->src }}" alt="Payment image not found!">
                        </div>
                    </div>
                </div>

            </div>
            @endcan
        </div>
    </div>
@endsection
