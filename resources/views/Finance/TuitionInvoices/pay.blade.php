@php use App\Models\Catalogs\PaymentMethod;use App\Models\Document; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Prepare For Pay Tuition Installment</h1>
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
                            <div>
                                <p class="font-bold">Tuition
                                    Type: </p> {{ json_decode($tuitionInvoiceDetails->description,true)['tuition_type'] }}
                            </div>
                            <div>
                                <p class="font-bold">Amount For
                                    Pay: </p> {{ number_format($tuitionInvoiceDetails->amount) }} IRR
                            </div>
                        </div>
                        <form action="{{ route('TuitionInvoices.payTuition') }}" method="post">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-4">
                                <div>
                                    <input type="hidden" name="tuition_invoice_id"
                                           value="{{ $tuitionInvoiceDetails->id }}">
                                    <label for="payment_method"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Select Payment Method</label>
                                    <select id="payment_method" name="payment_method"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select payment method" required>
                                        <option selected disabled value="">Select payment method</option>
                                        @foreach($paymentMethods as $paymentMethod)
                                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                Payment
                            </button>
                            <a href="{{ url()->previous() }}">
                                <button type="button"
                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    Back
                                </button>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
