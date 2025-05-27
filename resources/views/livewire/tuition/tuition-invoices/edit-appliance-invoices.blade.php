@php use App\Models\Branch\ApplicationReservation;use App\Models\Branch\Interview; @endphp

<section>
    {{--    Tuition section--}}
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 mb-3 shadow-md"
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
                        <p class="font-bold">Before proceeding with any edits to the tuition installment plan, you are
                            strongly advised to double-check and confirm that the total account balance has been
                            calculated accurately.</p>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">Edit Tuition Invoices
                    : {{ $tuition_invoice_details[0]->tuitionInvoiceDetails->applianceInformation->id}}
                    - {{ $tuition_invoice_details[0]->tuitionInvoiceDetails->applianceInformation->student_id}}
                    - {{ $tuition_invoice_details[0]->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->first_name_en }} {{ $tuition_invoice_details[0]->tuitionInvoiceDetails->applianceInformation->studentInfo->generalInformationInfo->last_name_en }}</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                @include('GeneralPages.errors.session.success')
                @include('GeneralPages.errors.session.error')
                @session('change-invoice-amount-success')
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-800 dark:border-green-600 dark:text-green-200 flex items-center justify-between"
                    role="alert">
                    <button type="button" class="mr-2" onclick="this.parentElement.remove()">
                        <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-200" role="button"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/>
                        </svg>
                    </button>
                    <span class="flex-grow">{{ $value }}</span>
                </div>
                @endsession
                <div class="grid grid-cols-2 gap-2">
                    <div class="flex p-2 relative overflow-x-auto shadow-md sm:rounded-lg bg-white">
                        <h4 class="text-xl font-semibold ">Tuition Type:
                            @switch($tuition_invoice_details[0]->tuitionInvoiceDetails->payment_type)
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
                        </h4>
                    </div>
                </div>

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400 ">
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
                            <th scope="col" class="px-6 py-3 text-center action">
                                Action
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($tuition_invoice_details as $invoice)
                            <tr wire:key="invoice-{{ $invoice->id }}"
                                class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
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
                                    class="flex items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                    <input type="number" wire:model="amounts.{{ $invoice->id }}"
                                           value="{{$invoice->amount}}"
                                           class="bg-gray-50 border mr-1 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           required> IRR
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                    <div>
                                        <div
                                            class="text-base font-semibold">
                                            @switch((string)$invoice->is_paid)
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
                                        </div>
                                    </div>
                                </th>
                                <td class="px-3 py-1 text-center">
                                    <button
                                        wire:click="changeInvoiceAmount({{ $invoice->id }})"
                                        wire:target="changeInvoiceAmount"
                                        wire:loading.remove
                                        type="button"
                                        class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline edit-tuition-invoice">
                                        <div class="text-center">
                                            <i
                                                class="las la-pen "
                                                style="font-size: 20px"></i>
                                            Edit
                                        </div>
                                    </button>
                                    <p wire:target="changeInvoiceAmount" wire:loading>Please Wait!</p>
                                </td>
                            </tr>
                            @if($invoice->invoiceEditHistory->isNotEmpty())
                                <tr class="border-b">
                                    <td class="p-3" colspan="7">
                                        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400 ">
                                            <thead
                                                class="odd:bg-white odd:dark:bg-gray-800 even:bg-gray-50 even:dark:bg-gray-700 border-b dark:border-gray-600">
                                            <tr>
                                                <th scope="col" class="text-center p-3">
                                                    History Id
                                                </th>
                                                <th scope="col" class="text-center p-3">
                                                    From
                                                </th>
                                                <th scope="col" class="text-center p-3">
                                                    To
                                                </th>
                                                <th scope="col" class="text-center p-3">
                                                    User
                                                </th>
                                                <th scope="col" class="text-center p-3">
                                                    Date
                                                </th>
                                                <th scope="col" class="text-center p-3">
                                                    Files
                                                </th>
                                                <th scope="col" class="text-center p-3">
                                                    Action
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($invoice->invoiceEditHistory as $history)
                                                <tr
                                                    wire:key="history-{{ $history->id }}"
                                                    class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                                    <th scope="row"
                                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ $history->id }}
                                                    </th>
                                                    <th scope="row"
                                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ number_format(json_decode($history->description,true)['old_amount']) }}
                                                        IRR
                                                    </th>
                                                    <th scope="row"
                                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ number_format(json_decode($history->description,true)['new_amount']) }}
                                                        IRR
                                                    </th>
                                                    <th scope="row"
                                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ $history->userInfo->id }} -
                                                        {{ $history->userInfo->generalInformationInfo->first_name_en }}
                                                        {{ $history->userInfo->generalInformationInfo->last_name_en }}
                                                    </th>
                                                    <th scope="row"
                                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                                        {{ $history->created_at }}
                                                    </th>
                                                    <th scope="row"
                                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                                    </th>
                                                    <th scope="row"
                                                        class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                                        <x-secondary-button
                                                            wire:click="$dispatch('open-file-modal',[{{ $history->id }}])"
                                                            wire:target="fileModal"
                                                            wire:loading.remove
                                                        >Add File
                                                        </x-secondary-button>
                                                        <p wire:target="fileModal" wire:loading>Please Wait!</p>
                                                    </th>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                            <td class="w-4 p-4">
                            </td>
                            <th scope="row"
                                class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                            </th>
                            <th scope="row"
                                class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                            </th>
                            <th scope="row"
                                class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                            </th>
                            <th scope="row"
                                class="flex items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                                Sum Amount: {{ number_format($tuition_invoice_details->sum('amount')) }} IRR
                            </th>
                            <th scope="row"
                                class=" items-center text-center px-3 py-1 text-gray-900 whitespace-nowrap dark:text-white">
                            </th>
                            <td class="px-3 py-1 text-center">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <h4 class="text-xl font-semibold text-black dark:text-white ">Discounts Info:
                </h4>
                @session('change-discount-success')
                <div
                    class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative dark:bg-green-800 dark:border-green-600 dark:text-green-200 flex items-center justify-between"
                    role="alert">
                    <button type="button" class="mr-2" onclick="this.parentElement.remove()">
                        <svg class="fill-current h-6 w-6 text-green-500 dark:text-green-200" role="button"
                             xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <title>Close</title>
                            <path
                                d="M14.348 14.849a1 1 0 0 1-1.414 0L10 11.414l-2.93 2.93a1 1 0 1 1-1.414-1.414l2.93-2.93-2.93-2.93a1 1 0 1 1 1.414-1.414l2.93 2.93 2.93-2.93a1 1 0 1 1 1.414 1.414l-2.93 2.93 2.93 2.93a1 1 0 0 1 0 1.414z"/>
                        </svg>
                    </button>
                    <span class="flex-grow">{{ $value }}</span>
                </div>
                @endsession
                <div class="grid grid-cols-1 gap-2">
                    <div
                        class="flex text-center justify-center p-2 relative overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-gray-700 dark:text-gray-400">
                        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 text-center">
                                    ID
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Title
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Percentage
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            @foreach($discounts as $discount)
                                <tr>
                                    <td class="w-10 font-bold p-4 text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="w-1/3 font-bold p-4 text-center">
                                        {{ $discount->name }}
                                    </td>
                                    <td class="font-bold p-4 text-center">
                                        {{ $discount->percentage }}%
                                    </td>
                                    <td class="font-bold p-4 text-center">
                                        <input class="discount-checks" type="checkbox"
                                               value="{{ $discount->id }}"
                                               @checked(in_array($discount->id,$selected_discounts))
                                               wire:model="selected_discounts"
                                               wire:click="changeDiscounts"
                                               wire:target="changeDiscounts"
                                               wire:loading.remove
                                               name="discount[]">
                                        <p wire:target="changeDiscounts" wire:loading>Please Wait!</p>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
