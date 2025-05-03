@php use App\Models\Catalogs\PaymentMethod;use App\Models\Document;use Morilog\Jalali\Jalalian; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Tuition Payment Details</h1>
            </div>
            @include('GeneralPages.errors.session.error')
            @include('GeneralPages.errors.session.success')
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
                                    Info: </p> {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->student_id }}
                                - {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->first_name_en }} {{ $tuitionInvoiceDetails->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->last_name_en }}
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
                                    @case('5')
                                        Three Installment
                                        @break
                                    @case('6')
                                        Seven Installment
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
                        <div class="grid gap-6 mb-6 md:grid-cols-6">
                            <div>
                                <p class="font-bold">Payment
                                    Method: </p> {{isset($tuitionInvoiceDetails->paymentMethodInfo->name) ? $tuitionInvoiceDetails->paymentMethodInfo->name : 'Custom Payment'}}
                            </div>
                            <div>
                                <p class="font-bold">Tuition Payment
                                    Due: </p> {{ number_format($tuitionInvoiceDetails->amount) }} IRR
                            </div>
                            @php
                                $amountPaid=$debt=0;
                                if(isset($tuitionInvoiceDetails->paymentMethodInfo->id) and $tuitionInvoiceDetails->paymentMethodInfo->id!=3){
                                    if ($tuitionInvoiceDetails->is_paid==1){
                                        $amountPaid = $tuitionInvoiceDetails->amount;
                                    }
                                }else{
                                    $amountPaid =$tuitionInvoiceDetails->customPayments->where('status','!=',3)->where('status','!=',2)->sum('amount');
                                    $debt=$tuitionInvoiceDetails->amount-$amountPaid;
                                }
                            @endphp

                            <div>
                                <p class="font-bold">Amount
                                    Paid: </p> {{ number_format($amountPaid) }} IRR
                            </div>
                            <div>
                                <p class="font-bold">Debt: </p> {{ number_format($debt) }} IRR
                            </div>
                            <div>
                                <p class="font-bold">Tuition
                                    Type: </p> {{ json_decode($tuitionInvoiceDetails->description,true)['tuition_type'] }}
                            </div>
                            <div>
                                <p class="font-bold">Invoice Created At: </p> {{ $tuitionInvoiceDetails->created_at }}
                            </div>
                        </div>
                        <div class="grid gap-6 mb-6 md:grid-cols-4">
                            @if($tuitionInvoiceDetails->is_paid==1 or $tuitionInvoiceDetails->is_paid==2)
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
                                        <div class="mt-3 ">
                                            <input type="hidden" id="tuition_invoice_id"
                                                   value="{{$tuitionInvoiceDetails->id}}">

                                            <p class="font-bold mb-3">Payment Date</p>
                                            <div class="flex">
                                                @if(auth()->user()->hasRole('Financial Manager') or auth()->user()->hasRole('Super Admin'))
                                                    <input type="text"
                                                           id="date_of_payment"
                                                           name="date_of_payment"
                                                           value="{{ !is_null($tuitionInvoiceDetails->date_of_payment) ? Jalalian::fromDateTime($tuitionInvoiceDetails->date_of_payment)->format('Y/m/d H:i:s') : '' }}"
                                                           class="persian_date_with_clock rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                           required>
                                                @else
                                                    <p class="font-bold mb-3">{{ $tuitionInvoiceDetails->date_of_payment }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3 ">
                                            <p class="font-bold mb-3">Tracking Code</p>
                                            <div class="flex">
                                                @if(auth()->user()->hasRole('Financial Manager') or auth()->user()->hasRole('Super Admin'))
                                                    <input type="text"
                                                           value="{{ $tuitionInvoiceDetails->tracking_code }}"
                                                           id="tracking_code"
                                                           name="tracking_code"
                                                           placeholder="Enter tracking code"
                                                           class=" rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                           required>
                                                @else
                                                    <p class="font-bold mb-3">{{ $tuitionInvoiceDetails->tracking_code }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="mt-3 ">
                                            <p class="font-bold mb-3">Description</p>
                                            <div class="flex">
                                                @if(auth()->user()->hasRole('Financial Manager') or auth()->user()->hasRole('Super Admin'))
                                                    <textarea
                                                            id="description"
                                                            rows="5"
                                                            name="description"
                                                            placeholder="Enter description"
                                                            class=" rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price">{{ $tuitionInvoiceDetails->financial_manager_description }}</textarea>

                                                @else
                                                    <p class="font-bold mb-3">{{ $tuitionInvoiceDetails->financial_manager_description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        @break
                                    @case('2')
                                        @php
                                            $paymentDetails=json_decode($tuitionInvoiceDetails->payment_details,true);
                                        @endphp
                                        <div>
                                            <p class="font-bold">Transaction
                                                ID: </p> {{ $paymentDetails['SaleOrderId'] }}
                                        </div>
                                        <div>
                                            <p class="font-bold">Reference
                                                ID: </p> {{ $paymentDetails['SaleReferenceId'] }}
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
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 mt-4 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Custom Payment Invoices</h1>
            </div>
            <div class="mt-4 bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                <div class=" gap-6 mb-6 ">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                            <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="text-center">
                                    #
                                </th>
                                <th scope="col" class="text-center">
                                    ID
                                </th>
                                <th scope="col" class="px-2 py-3 text-center">
                                    Payment Method
                                </th>
                                <th scope="col" class="px-2 py-3 text-center nofilter">
                                    Payment Information
                                </th>
                                <th scope="col" class="px-2 py-3 text-center">
                                    Amount
                                </th>
                                <th scope="col" class="px-2 py-3 text-center">
                                    Date
                                </th>
                                <th scope="col" class="px-2 py-3 text-center">
                                    Seconder
                                </th>
                                <th scope="col" class="px-2 py-3 text-center">
                                    Approval Date
                                </th>
                                <th scope="col" class="px-2 py-3 text-center">
                                    Status
                                </th>
                                @if(auth()->user()->hasRole('Super Admin') or auth()->user()->hasRole('Financial Manager'))
                                    <th scope="col" class="px-2 py-3 text-center">
                                        Action
                                    </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tuitionInvoiceDetails->customPayments as $customPaymentInvoice)
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="w-4 p-4">
                                        {{ $customPaymentInvoice->id }}
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $customPaymentInvoice->paymentMethodInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch($customPaymentInvoice->payment_method)
                                            @case(1)
                                                @foreach(json_decode($customPaymentInvoice->payment_details,true)['files'] as $key=>$info)
                                                    <a href="{{Storage::url($info)}}"
                                                       type="button"
                                                       target="_blank"
                                                       class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                        <i class="las la-eye mr-1 mt-1"></i>
                                                        {{ $key }}
                                                    </a>
                                                @endforeach
                                                @break
                                            @case(2)
                                                @foreach(json_decode($customPaymentInvoice->payment_details,true) as $key=>$info)
                                                    {{ $key }}: {{ $info }} <br>
                                                @endforeach
                                                @break
                                        @endswitch
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ number_format($customPaymentInvoice->amount) }} IRR
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $customPaymentInvoice->created_at }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $customPaymentInvoice->seconder }}
                                        - {{ $customPaymentInvoice->seconderInfo?->generalInformationInfo->first_name_en }} {{ $customPaymentInvoice->seconderInfo?->generalInformationInfo->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $customPaymentInvoice->approval_date }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch($customPaymentInvoice->status)
                                            @case(1)
                                                Paid
                                                @break
                                            @case(2)
                                                Pending To Confirm
                                                @break
                                            @case(3)
                                                Rejected
                                                @break
                                        @endswitch
                                    </th>
                                    @if(auth()->user()->hasRole('Super Admin') or auth()->user()->hasRole('Financial Manager'))
                                        <th scope="row"
                                            class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                            @if($customPaymentInvoice->status===2)
                                                <form method="post" class="custom-payment-invoice-form"
                                                      action="{{ route('ChangeCustomTuitionInvoiceDetails') }}">
                                                    <div class="flex">
                                                        @csrf
                                                        <div class="mr-3">
                                                            <select name="type" id="type" required
                                                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5  dark:border-gray-600 dark:placeholder-gray-400 dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                                                <option selected disabled value="">Select status
                                                                </option>
                                                                <option value="Accept">Accept</option>
                                                                <option value="Reject">Reject</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <input type="hidden"
                                                                   value="{{$customPaymentInvoice->id}}"
                                                                   name="invoice_id">
                                                            <button
                                                                    type="submit"
                                                                    class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                                <i class="las la-eye mt-1 mr-1"></i>
                                                                Confirm
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            @endif
                                        </th>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
