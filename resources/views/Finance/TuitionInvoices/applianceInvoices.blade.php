@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Tuition Invoices</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                @if( session()->has('success') )
                    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
                         role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold">{{ session()->get('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @if (count($errors) > 0)
                    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-3"
                         role="alert">
                        <div class="flex">
                            <div class="py-1">
                                <svg class="fill-current h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path
                                        d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                </svg>
                            </div>
                            <div>
                                @foreach ($errors->all() as $error)
                                    <p class="font-bold">{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
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
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                                    Student ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Student
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
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($tuitionInvoiceDetails as $invoice)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            {{ $invoice->id }}
                                        </div>
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div
                                                class="text-base font-semibold">{{ $invoice->tuitionInvoiceDetails->applianceInformation->academicYearInfo->name }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div
                                                class="text-base font-semibold">{{ $invoice->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->first_name_en }} {{ $invoice->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->last_name_en }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div
                                                class="text-base font-semibold">{{ $invoice->tuitionInvoiceDetails->applianceInformation->student_id }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div
                                                class="text-base font-semibold">
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
                                                @endswitch
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div
                                                class="text-base font-semibold">
                                                @if(isset($invoice->paymentMethodInfo->name))
                                                    {{$invoice->paymentMethodInfo->name}}
                                                @else
                                                    Not Paid!
                                                @endif
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div
                                                class="text-base font-semibold">
                                                {{ @json_decode($invoice->description,true)['tuition_type'] }}
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div
                                                class="text-base font-semibold">
                                                {{ number_format($invoice->amount).' IRR' }}
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div>
                                            <div
                                                class="text-base font-semibold">
                                                @switch($invoice->is_paid)
                                                    @case('1')
                                                        Paid
                                                        @break
                                                    @case('2')
                                                        Pending To Review
                                                        @break
                                                    @default
                                                        Pending To Pay
                                                @endswitch
                                            </div>
                                        </div>
                                    </th>
                                    <td class="px-3 py-1 text-center">
                                        @switch($invoice->is_paid)
                                            @case('1')
                                                <a href="{{ route('TuitionInvoices.show',$invoice->id) }}">
                                                    <button type="button"
                                                            class="flex text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-3 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        <div class="text-center">
                                                            <i class="las la-eye mr-1"></i>
                                                        </div>
                                                        Details
                                                    </button>
                                                </a>
                                                @break
                                            @case('0')
                                                @if($me->hasRole('Parent'))
                                                    <a href="/PayTuitionInstallment/{{ $invoice->id }}">
                                                        <button type="button"
                                                                class="flex text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-3 py-2 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                            <div class="text-center">
                                                                <i class="las la-money mr-1"></i>
                                                            </div>
                                                            Pay
                                                        </button>
                                                    </a>
                                                @endif
                                                @break
                                            @case('2')
                                                @if($me->hasRole('Financial Manager') or $me->hasRole('Principal') or $me->hasRole('Super Admin')) @endif
                                                <a href="/TuitionInvoices/{{ $invoice->tuition_invoice_id }}">
                                                    <button type="button"
                                                            class="flex text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-3 py-2 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                        <div class="text-center">
                                                            <i class="las la-money mr-1"></i>
                                                        </div>
                                                        Details
                                                    </button>
                                                </a>
                                                @break
                                        @endswitch
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
