@php use App\Models\Catalogs\PaymentMethod;use App\Models\Document; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Prepare For Pay Tuition Installment</h1>
            </div>
            @include('GeneralPages.errors.session.error')
            @include('GeneralPages.errors.session.success')
            <div class="grid grid-cols-2 gap-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <form action="{{ route('TuitionInvoices.payTuition') }}" method="post" id="pay-installment"
                          enctype="multipart/form-data">
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
                                        @case('5')
                                            Three Installment
                                            @break
                                        @case('6')
                                            Seven Installment
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
                                            @continue($paymentMethod->id==3)
                                            @continue($paymentMethod->id==1 and auth()->check() and !auth()->user()->isImpersonated())
                                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="set-amount-div" class="hidden  gap-1 mb-6 md:grid-cols-1">
                                    <label
                                        class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                        for="payment_amount">Please enter the desired amount for your
                                        payment; <br>otherwise, click on the "Payment" button.</label>
                                    <input
                                        class="block mb-1 mr-2 w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        id="payment_amount_display"
                                        type="text"
                                        min="20000"
                                        max="{{ $tuitionInvoiceDetails->amount-$customTuitionPaid }}"
                                        value="{{ $tuitionInvoiceDetails->amount-$customTuitionPaid }}">
                                    <input type="hidden" name="payment_amount" id="payment_amount"
                                           min="20000"
                                           max="{{ $tuitionInvoiceDetails->amount-$customTuitionPaid }}"
                                           value="{{ $tuitionInvoiceDetails->amount-$customTuitionPaid }}">

                                    <p id="numberInWords" class="text-sm text-gray-700 dark:text-gray-400 mb-4"></p>

                                    <script src="/build/plugins/number-to-words/numberToWords.min.js"></script>
                                    <script>
                                        const initialValue = {{ $tuitionInvoiceDetails->amount - $customTuitionPaid }};
                                        const paymentDisplay = document.getElementById('payment_amount_display');
                                        const paymentHidden = document.getElementById('payment_amount');
                                        const numberInWordsElement = document.getElementById('numberInWords');

                                        paymentDisplay.value = new Intl.NumberFormat().format(initialValue);
                                        numberInWordsElement.textContent = numberToWords.toWords(initialValue);

                                        new Cleave('#payment_amount_display', {
                                            numeral: true,
                                            numeralThousandsGroupStyle: 'thousand',
                                            numeralDecimalMark: '',
                                            delimiter: ',',
                                            onValueChanged: (e) => {
                                                const rawValue = e.target.rawValue || '0';
                                                paymentHidden.value = rawValue;
                                                const numericValue = parseInt(rawValue, 10) || 0;
                                                numberInWordsElement.textContent = numberToWords.toWords(numericValue) + ' Rials';
                                            }
                                        });
                                    </script>
                                    <div class=" mb-5">
                                        <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1 list-disc">
                                            <li class="mb-2">
                                                The payment amount cannot exceed the installment amount.
                                            </li>
                                            <li>
                                                If you pay less than the required installment amount, your tuition
                                                installment will be considered unpaid until the full amount is settled.
                                            </li>
                                            <li>
                                                The entered amount must be in Rials.
                                            </li>
                                            <li>
                                                The minimum payment amount is 200,000 Rials.
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div id="bank-slip-div" class="hidden grid gap-1 mb-6 md:grid-cols-1">
                                    <label
                                        class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                        for="document_file_full_payment1">Select your bank slip file 1
                                        (Required)</label>
                                    <input
                                        class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        name="document_file_full_payment1" id="document_file_full_payment1"
                                        type="file"
                                        accept=".png,.jpg,.jpeg,.pdf,.bmp">
                                    <img class="w-full h-auto" id="image_preview_full_payment1" src=""
                                         alt="Preview Image"
                                         style="display:none; ">
                                    <label
                                        class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                        for="document_file_full_payment2">Select your bank slip file 2
                                        (Optional)</label>
                                    <input
                                        class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        name="document_file_full_payment2" id="document_file_full_payment2"
                                        type="file"
                                        accept=".png,.jpg,.jpeg,.pdf,.bmp">
                                    <img class="w-full h-auto" id="image_preview_full_payment2" src=""
                                         alt="Preview Image"
                                         style="display:none; ">
                                    <label
                                        class="block mb-2 mt-4 text-sm font-medium text-gray-900 dark:text-white"
                                        for="document_file_full_payment3">Select your bank slip file 3
                                        (Optional)</label>
                                    <input
                                        class="mb-4 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-600 dark:border-gray-600 dark:placeholder-gray-400"
                                        name="document_file_full_payment3" id="document_file_full_payment3"
                                        type="file"
                                        accept=".png,.jpg,.jpeg,.pdf,.bmp">
                                    <img class="w-full h-auto" id="image_preview_full_payment3" src=""
                                         alt="Preview Image"
                                         style="display:none; ">
                                    <div class="info mb-5">
                                        <div class="dark:text-white font-medium mb-1">File requirements:
                                        </div>
                                        <div class="dark:text-gray-400 font-normal text-sm pb-1">Ensure that
                                            these
                                            requirements
                                            are met:
                                        </div>
                                        <ul class="text-gray-500 dark:text-gray-400 text-xs font-normal ml-4 space-y-1">
                                            <li>
                                                The files must be in this format: png, jpg, jpeg, pdf, bmp
                                            </li>
                                            <li>
                                                Maximum size: 5 MB
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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
                                    Status
                                </th>
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

                                                @break
                                            @case(2)
                                                @foreach(json_decode($customPaymentInvoice->payment_details,true) as $key=>$info)
                                                    @continue($key=='ResCode')
                                                    @continue($key=='CardHolderInfo')
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
