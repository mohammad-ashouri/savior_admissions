@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium">Edit Tuition For This Academic
                    Year: {{ $tuitions->academicYearInfo->name }}</h1>
            </div>
            @include('GeneralPages.errors.session.error')

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-3 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"> All tuitions</h1>
                        </div>
                        <div class="grid gap-6 mb-6 md:grid-cols-1">
                            <div class="grid gap-6 mb-6">
                                <div>
                                    <table id=""
                                           class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                                        <thead
                                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Level
                                                </div>
                                            </th>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Full Payment
                                                </div>
                                            </th>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Two Installment Payment
                                                </div>
                                            </th>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Four Installment Payment
                                                </div>
                                            </th>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Submit
                                                </div>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(!empty($tuitions->allTuitions))
                                            @foreach($tuitions->allTuitions as $tuition)
                                                @if($tuition->status!=1)
                                                    @continue
                                                @endif
                                                <form>
                                                    <tr
                                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                        <td class="p-4 text-center">
                                                            {{$tuition->levelInfo->name}}
                                                        </td>
                                                        <td class="p-4">
                                                            @php
                                                                $fullPaymentDetails=json_decode($tuition->full_payment,true);
                                                                $fullPaymentMinistryDetails=json_decode($tuition->full_payment_ministry,true);
                                                            @endphp
                                                            <label
                                                                class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                amount of full payment</label>
                                                            <div class="flex justify-between">
                                                                <input type="text"
                                                                       value="{{ isset($fullPaymentDetails['full_payment_irr']) ? $fullPaymentDetails['full_payment_irr'] : 0}}"
                                                                       id="full_payment_irr"
                                                                       name="full_payment_irr"
                                                                       placeholder="Enter the full payment tuition fee for {{$tuition->levelInfo->name}} in Rials"
                                                                       class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                       required>
                                                                <span
                                                                    class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                            </div>
                                                            <div class="flex justify-between mt-3">
                                                                <input type="text"
                                                                       value="{{ isset($fullPaymentDetails['full_payment_usd']) ? $fullPaymentDetails['full_payment_usd'] : 0 }}"
                                                                       id="full_payment_usd"
                                                                       name="full_payment_usd"
                                                                       placeholder="Enter the full payment tuition fee for {{$tuition->levelInfo->name}} in USD"
                                                                       class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                       required>
                                                                <span
                                                                    class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                            </div>
                                                            <label
                                                                class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                amount of full payment (ministry)</label>
                                                            <div class="flex justify-between">
                                                                <input type="text"
                                                                       value="{{ isset($fullPaymentMinistryDetails['full_payment_irr_ministry']) ? $fullPaymentMinistryDetails['full_payment_irr_ministry'] : 0 }}"
                                                                       id="full_payment_irr_ministry"
                                                                       name="full_payment_irr_ministry"
                                                                       placeholder="Enter the full payment tuition fee for {{$tuition->levelInfo->name}} in Rials"
                                                                       class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                       required>
                                                                <span
                                                                    class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                            </div>
                                                            <div class="flex justify-between mt-3">
                                                                <input type="text"
                                                                       value="{{ isset($fullPaymentMinistryDetails['full_payment_usd_ministry']) ? $fullPaymentMinistryDetails['full_payment_usd_ministry'] : 0 }}"
                                                                       id="full_payment_usd_ministry"
                                                                       name="full_payment_usd_ministry"
                                                                       placeholder="Enter the full payment tuition fee for {{$tuition->levelInfo->name}} in USD"
                                                                       class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                       required>
                                                                <span
                                                                    class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                            </div>
                                                        </td>
                                                        <td class="p-4">
                                                            @php
                                                                $twoInstallmentDetails=json_decode($tuition->two_installment_payment,true);
                                                                $twoInstallmentMinistryDetails=json_decode($tuition->two_installment_payment_ministry,true);
                                                            @endphp
                                                            <div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                    amount of two payment installment</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentDetails['two_installment_amount_irr']) ? $twoInstallmentDetails['two_installment_amount_irr'] : 0 }}"
                                                                               id="two_installment_amount_irr"
                                                                               name="two_installment_amount_irr"
                                                                               placeholder="Enter full amount of two payment installment for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentDetails['two_installment_amount_usd']) ? $twoInstallmentDetails['two_installment_amount_usd'] : 0 }}"
                                                                               id="two_installment_amount_usd"
                                                                               name="two_installment_amount_usd"
                                                                               placeholder="Enter full amount of two payment installment for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                    amount of two payment installment
                                                                    (ministry)</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentMinistryDetails['two_installment_amount_irr_ministry']) ? $twoInstallmentMinistryDetails['two_installment_amount_irr_ministry'] : 0 }}"
                                                                               id="two_installment_amount_irr_ministry"
                                                                               name="two_installment_amount_irr_ministry"
                                                                               placeholder="Enter full amount of two payment installment for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentMinistryDetails['two_installment_amount_usd_ministry']) ? $twoInstallmentMinistryDetails['two_installment_amount_usd_ministry'] : 0 }}"
                                                                               id="two_installment_amount_usd_ministry"
                                                                               name="two_installment_amount_usd_ministry"
                                                                               placeholder="Enter full amount of two payment installment for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                    payment in two installments</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentDetails['two_installment_advance_irr']) ? $twoInstallmentDetails['two_installment_advance_irr'] : 0 }}"
                                                                               id="two_installment_advance_irr"
                                                                               name="two_installment_advance_irr"
                                                                               placeholder="Enter advance payment in two installments for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentDetails['two_installment_advance_usd']) ? $twoInstallmentDetails['two_installment_advance_usd'] : 0 }}"
                                                                               id="two_installment_advance_usd"
                                                                               name="two_installment_advance_usd"
                                                                               placeholder="Enter advance payment in two installments for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                    payment in two installments (ministry)</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentMinistryDetails['two_installment_advance_irr_ministry']) ? $twoInstallmentMinistryDetails['two_installment_advance_irr_ministry'] : 0 }}"
                                                                               id="two_installment_advance_irr_ministry"
                                                                               name="two_installment_advance_irr_ministry"
                                                                               placeholder="Enter advance payment in two installments for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentMinistryDetails['two_installment_advance_usd_ministry']) ? $twoInstallmentMinistryDetails['two_installment_advance_usd_ministry'] : 0 }}"
                                                                               id="two_installment_advance_usd_ministry"
                                                                               name="two_installment_advance_usd_ministry"
                                                                               placeholder="Enter advance payment in two installments for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                    amount of each installment</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentDetails['two_installment_each_installment_irr']) ? $twoInstallmentDetails['two_installment_each_installment_irr'] : 0 }}"
                                                                               id="two_installment_each_installment_irr"
                                                                               name="two_installment_each_installment_irr"
                                                                               placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentDetails['two_installment_each_installment_usd']) ? $twoInstallmentDetails['two_installment_each_installment_usd'] : 0 }}"
                                                                               id="two_installment_each_installment_usd"
                                                                               name="two_installment_each_installment_usd"
                                                                               placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                    amount of each installment (ministry)</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentMinistryDetails['two_installment_each_installment_irr_ministry']) ? $twoInstallmentMinistryDetails['two_installment_each_installment_irr_ministry'] : 0 }}"
                                                                               id="two_installment_each_installment_irr_ministry"
                                                                               name="two_installment_each_installment_irr_ministry"
                                                                               placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($twoInstallmentMinistryDetails['two_installment_each_installment_usd_ministry']) ? $twoInstallmentMinistryDetails['two_installment_each_installment_usd_ministry'] : 0 }}"
                                                                               id="two_installment_each_installment_usd_ministry"
                                                                               name="two_installment_each_installment_usd_ministry"
                                                                               placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div>
                                                                        <label
                                                                            class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                            of installment 1</label>
                                                                        <div class="flex justify-between">
                                                                            <input type="date"
                                                                                   value="{{ isset($twoInstallmentDetails['date_of_installment1_two']) ? $twoInstallmentDetails['date_of_installment1_two'] : 0 }}"
                                                                                   id="date_of_installment1_two"
                                                                                   name="date_of_installment1_two"
                                                                                   class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ml-2">
                                                                        <label
                                                                            class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                            of installment 2</label>
                                                                        <div class="flex justify-between">
                                                                            <input type="date"
                                                                                   value="{{ isset($twoInstallmentDetails['date_of_installment2_two']) ? $twoInstallmentDetails['date_of_installment2_two'] : 0 }}"
                                                                                   id="date_of_installment2_two"
                                                                                   name="date_of_installment2_two"
                                                                                   class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="p-4">
                                                            @php
                                                                $fourInstallmentDetails=json_decode($tuition->four_installment_payment,true);
                                                                $fourInstallmentMinistryDetails=json_decode($tuition->four_installment_payment_ministry,true);
                                                            @endphp
                                                            <div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                    amount of four payment installment</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentDetails['four_installment_amount_irr']) ? $fourInstallmentDetails['four_installment_amount_irr'] : 0 }}"
                                                                               id="four_installment_amount_irr"
                                                                               name="four_installment_amount_irr"
                                                                               placeholder="Enter full amount of four payment installment for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentDetails['four_installment_amount_usd']) ? $fourInstallmentDetails['four_installment_amount_usd'] : 0 }}"
                                                                               id="four_installment_amount_usd"
                                                                               name="four_installment_amount_usd"
                                                                               placeholder="Enter full amount of four payment installment for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                    amount of four payment installment
                                                                    (ministry)</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentMinistryDetails['four_installment_amount_irr_ministry']) ? $fourInstallmentMinistryDetails['four_installment_amount_irr_ministry'] : 0 }}"
                                                                               id="four_installment_amount_irr_ministry"
                                                                               name="four_installment_amount_irr_ministry"
                                                                               placeholder="Enter full amount of four payment installment for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentMinistryDetails['four_installment_amount_usd_ministry']) ? $fourInstallmentMinistryDetails['four_installment_amount_usd_ministry'] : 0 }}"
                                                                               id="four_installment_amount_usd_ministry"
                                                                               name="four_installment_amount_usd_ministry"
                                                                               placeholder="Enter full amount of four payment installment for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                    payment in four installments</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentDetails['four_installment_advance_irr']) ? $fourInstallmentDetails['four_installment_advance_irr'] : 0 }}"
                                                                               id="four_installment_advance_irr"
                                                                               name="four_installment_advance_irr"
                                                                               placeholder="Enter advance payment in four installments for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentDetails['four_installment_advance_usd']) ? $fourInstallmentDetails['four_installment_advance_usd'] : 0 }}"
                                                                               id="four_installment_advance_usd"
                                                                               name="four_installment_advance_usd"
                                                                               placeholder="Enter advance payment in four installments for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                    payment in four installments (ministry)</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentMinistryDetails['four_installment_advance_irr_ministry']) ? $fourInstallmentMinistryDetails['four_installment_advance_irr_ministry'] : 0 }}"
                                                                               id="four_installment_advance_irr_ministry"
                                                                               name="four_installment_advance_irr_ministry"
                                                                               placeholder="Enter advance payment in four installments for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentMinistryDetails['four_installment_advance_usd_ministry']) ? $fourInstallmentMinistryDetails['four_installment_advance_usd_ministry'] : 0 }}"
                                                                               id="four_installment_advance_usd_ministry"
                                                                               name="four_installment_advance_usd_ministry"
                                                                               placeholder="Enter advance payment in four installments for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                    amount of each installment</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentDetails['four_installment_each_installment_irr']) ? $fourInstallmentDetails['four_installment_each_installment_irr'] : 0 }}"
                                                                               id="four_installment_each_installment_irr"
                                                                               name="four_installment_each_installment_irr"
                                                                               placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentDetails['four_installment_each_installment_usd']) ? $fourInstallmentDetails['four_installment_each_installment_usd'] : 0 }}"
                                                                               id="four_installment_each_installment_usd"
                                                                               name="four_installment_each_installment_usd"
                                                                               placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                    amount of each installment (ministry)</label>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div class="flex">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentMinistryDetails['four_installment_each_installment_irr_ministry']) ? $fourInstallmentMinistryDetails['four_installment_each_installment_irr_ministry'] : 0 }}"
                                                                               id="four_installment_each_installment_irr_ministry"
                                                                               name="four_installment_each_installment_irr_ministry"
                                                                               placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in Rials"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                    </div>
                                                                    <div class="flex ">
                                                                        <input type="text"
                                                                               value="{{ isset($fourInstallmentMinistryDetails['four_installment_each_installment_usd_ministry']) ? $fourInstallmentMinistryDetails['four_installment_each_installment_usd_ministry'] : 0 }}"
                                                                               id="four_installment_each_installment_usd_ministry"
                                                                               name="four_installment_each_installment_usd_ministry"
                                                                               placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in USD"
                                                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                               required>
                                                                        <span
                                                                            class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">USD</span>
                                                                    </div>
                                                                </div>
                                                                <div class="grid grid-cols-2 gap-4 mb-4">
                                                                    <div>
                                                                        <label
                                                                            class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                            of installment 1</label>
                                                                        <div class="flex justify-between">
                                                                            <input type="date"
                                                                                   value="{{ isset($fourInstallmentDetails['date_of_installment1_four']) ? $fourInstallmentDetails['date_of_installment1_four'] : 0 }}"
                                                                                   id="date_of_installment1_four"
                                                                                   name="date_of_installment1_four"
                                                                                   class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ml-2">
                                                                        <label
                                                                            class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                            of installment 2</label>
                                                                        <div class="flex justify-between">
                                                                            <input type="date"
                                                                                   value="{{ isset($fourInstallmentDetails['date_of_installment2_four']) ? $fourInstallmentDetails['date_of_installment2_four'] : 0 }}"
                                                                                   id="date_of_installment2_four"
                                                                                   name="date_of_installment2_four"
                                                                                   class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                    <div>
                                                                        <label
                                                                            class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                            of installment 3</label>
                                                                        <div class="flex justify-between">
                                                                            <input type="date"
                                                                                   value="{{ isset($fourInstallmentDetails['date_of_installment3_four']) ? $fourInstallmentDetails['date_of_installment3_four'] : 0 }}"
                                                                                   id="date_of_installment3_four"
                                                                                   name="date_of_installment3_four"
                                                                                   class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                    <div class="ml-2">
                                                                        <label
                                                                            class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                            of installment 4</label>
                                                                        <div class="flex justify-between">
                                                                            <input type="date"
                                                                                   value="{{ isset($fourInstallmentDetails['date_of_installment4_four']) ? $fourInstallmentDetails['date_of_installment4_four'] : 0 }}"
                                                                                   id="date_of_installment4_four"
                                                                                   name="date_of_installment4_four"
                                                                                   class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="p-4 text-center">
                                                            @if($tuition->tuitionInfo->academicYearInfo->status==1)
                                                                <input type="hidden" value="{{$tuition->id}}"
                                                                       name="tuition_details_id">
                                                                <button type="submit"
                                                                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 delete-row">
                                                                    <i class="las la-cloud-upload-alt"
                                                                       style="font-size: 20px"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </form>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
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
    </div>
@endsection
