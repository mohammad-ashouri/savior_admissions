@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Invoices Details</h1>
            </div>
            <div class="flex justify-between mb-3">
                <div class="relative hidden md:block w-96">
                    <form action="{{ route('searchInvoicesDetails') }}" method="get">
                        <div class="flex w-96">
                            <div class="mr-3">
                                <select id="academic_year" name="academic_year"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-48 p-3 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    @foreach($academicYears as $academicYear)
                                        <option
                                            @if(isset($_GET['academic_year']) and $_GET['academic_year']==$academicYear->id) selected
                                            @endif value="{{$academicYear->id}}">{{$academicYear->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit"
                                        class="text-white bg-blue-700 hover:bg-blue-800 w-full h-full focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <i class="fas fa-search mr-2" aria-hidden="true"></i>
                                    Filter
                                </button>
                            </div>
                            @if(isset($_GET['student_id']))
                                <div class="ml-3">
                                    <a href="/TuitionInvoices">
                                        <button type="button"
                                                class="text-white bg-red-700 hover:bg-red-800 w-full h-full focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 RemoveFilter">
                                            <i class="fas fa-remove mr-2" aria-hidden="true"></i>
                                            Remove
                                        </button>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                @include('GeneralPages.errors.session.success')
                @include('GeneralPages.errors.session.error')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($tuitionInvoiceDetails) or $tuitionInvoiceDetails->isEmpty())
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                             role="alert">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="fill-current h-6 w-6 text-teal-500 mr-4"
                                         xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20">
                                        <path
                                            d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">There is not any invoices to show!</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="text-center">
                                    Invoice Id
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Student
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Guardian
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Type Of Payment
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Payment Method
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Tuition Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Date Of Creation
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Date Of Payment
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Tracking Code
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Transaction ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Reference ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Card Holder Pan
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-center action">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($tuitionInvoiceDetails as $invoice)
                                @php
                                    $transactionId=$trackingCode=$referenceId=$cardHolderPan=null;
                                    switch ($invoice->payment_method){
                                        case('1'):
                                            $trackingCode=$invoice->tracking_code;
                                            break;
                                        case ('2'):
                                            $paymentDetails=json_decode($invoice->payment_details,true);
                                            $transactionId=$paymentDetails['SaleOrderId'];
                                            $referenceId=$paymentDetails['SaleReferenceId'];
                                            $cardHolderPan=$paymentDetails['CardHolderPan'];
                                            break;
                                    }
                                @endphp
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4">
                                        {{ $invoice->id }}
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $invoice->tuitionInvoiceDetails->applianceInformation->academicYearInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $invoice->tuitionInvoiceDetails->applianceInformation->student_id }}
                                        - {{ $invoice->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->first_name_en }} {{ $invoice->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">{{ $invoice->tuitionInvoiceDetails->applianceInformation->studentInformations?->guardianInfo?->id }}
                                        - {{ $invoice->tuitionInvoiceDetails->applianceInformation->studentInformations?->guardianInfo?->mobile }}
                                        - {{ $invoice->tuitionInvoiceDetails->applianceInformation->studentInformations->guardianInfo?->generalInformationInfo?->first_name_en }} {{ $invoice->tuitionInvoiceDetails->applianceInformation->studentInformations->guardianInfo?->generalInformationInfo?->last_name_en }}</th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch($invoice->tuitionInvoiceDetails->payment_type)
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
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        @if(isset($invoice->paymentMethodInfo->name))
                                            {{$invoice->paymentMethodInfo->name}}
                                        @else
                                            Not Paid!
                                        @endif
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ @json_decode($invoice->description,true)['tuition_type'] }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ number_format($invoice->amount).' IRR' }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $invoice->created_at }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $invoice->date_of_payment }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $trackingCode }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $transactionId }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $referenceId }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $cardHolderPan }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch($invoice->is_paid)
                                            @case('1')
                                                Paid
                                                @break
                                            @case('2')
                                                Pending To Review
                                                @break
                                            @case('3')
                                                Rejected
                                                @break
                                            @default
                                                Pending To Pay
                                        @endswitch
                                    </th>
                                    <td class="px-3 py-1 text-center">
                                        @if($invoice->is_paid)
                                            <a href="{{ route('TuitionInvoices.show',$invoice->id) }}">
                                                <button type="button"
                                                        class="flex text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-3 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                    <div class="text-center">
                                                        <i class="las la-eye mr-1"></i>
                                                    </div>
                                                    Details
                                                </button>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
