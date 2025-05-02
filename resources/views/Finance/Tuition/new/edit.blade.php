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
                                                    Three Installment Payment
                                                </div>
                                            </th>
                                            <th scope="col" class="p-4">
                                                <div class=" items-center text-center">
                                                    Seven Installment Payment
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
                                            @foreach($tuitions->allTuitions->where('status',1) as $tuition)
                                                <form class="tuition-details">
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
                                                            @if($tuition->tuitionInfo->academicYearInfo->status==1)
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
                                                            @else
                                                                {{@number_format($fullPaymentDetails->amount)}}
                                                            @endif
                                                        </td>
                                                        <td class="p-4">
                                                            @php
                                                                $threeInstallmentDetails=json_decode($tuition->three_installment_payment,true);
                                                                $threeInstallmentMinistryDetails=json_decode($tuition->three_installment_payment_ministry,true);
                                                            @endphp
                                                            @if($tuition->tuitionInfo->academicYearInfo->status==1)
                                                                <div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                        amount of three payment installment</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex ">
                                                                            <input type="text"
                                                                                   value="{{ isset($threeInstallmentDetails['three_installment_amount_irr']) ? $threeInstallmentDetails['three_installment_amount_irr'] : 0 }}"
                                                                                   id="three_installment_amount_irr"
                                                                                   name="three_installment_amount_irr"
                                                                                   placeholder="Enter full amount of three payment installment for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                        amount of three payment installment
                                                                        (ministry)</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex ">
                                                                            <input type="text"
                                                                                   value="{{ isset($threeInstallmentMinistryDetails['three_installment_amount_irr_ministry']) ? $threeInstallmentMinistryDetails['three_installment_amount_irr_ministry'] : 0 }}"
                                                                                   id="three_installment_amount_irr_ministry"
                                                                                   name="three_installment_amount_irr_ministry"
                                                                                   placeholder="Enter full amount of three payment installment for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                        payment in three installments</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex ">
                                                                            <input type="text"
                                                                                   value="{{ isset($threeInstallmentDetails['three_installment_advance_irr']) ? $threeInstallmentDetails['three_installment_advance_irr'] : 0 }}"
                                                                                   id="three_installment_advance_irr"
                                                                                   name="three_installment_advance_irr"
                                                                                   placeholder="Enter advance payment in three installments for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                        payment in three installments (ministry)</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex ">
                                                                            <input type="text"
                                                                                   value="{{ isset($threeInstallmentMinistryDetails['three_installment_advance_irr_ministry']) ? $threeInstallmentMinistryDetails['three_installment_advance_irr_ministry'] : 0 }}"
                                                                                   id="three_installment_advance_irr_ministry"
                                                                                   name="three_installment_advance_irr_ministry"
                                                                                   placeholder="Enter advance payment in three installments for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                        amount of each installment</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex">
                                                                            <input type="text"
                                                                                   value="{{ isset($threeInstallmentDetails['three_installment_each_installment_irr']) ? $threeInstallmentDetails['three_installment_each_installment_irr'] : 0 }}"
                                                                                   id="three_installment_each_installment_irr"
                                                                                   name="three_installment_each_installment_irr"
                                                                                   placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                        amount of each installment (ministry)</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex">
                                                                            <input type="text"
                                                                                   value="{{ isset($threeInstallmentMinistryDetails['three_installment_each_installment_irr_ministry']) ? $threeInstallmentMinistryDetails['three_installment_each_installment_irr_ministry'] : 0 }}"
                                                                                   id="three_installment_each_installment_irr_ministry"
                                                                                   name="three_installment_each_installment_irr_ministry"
                                                                                   placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div>
                                                                            <label
                                                                                class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                                of installment 1</label>
                                                                            <div class="flex justify-between">
                                                                                <input type="date"
                                                                                       value="{{ isset($threeInstallmentDetails['date_of_installment1_three']) ? $threeInstallmentDetails['date_of_installment1_three'] : 0 }}"
                                                                                       id="date_of_installment1_three"
                                                                                       name="date_of_installment1_three"
                                                                                       class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                       required>
                                                                            </div>
                                                                        </div>
                                                                        <div>
                                                                            <label
                                                                                class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                                of installment 2</label>
                                                                            <div class="flex justify-between">
                                                                                <input type="date"
                                                                                       value="{{ isset($threeInstallmentDetails['date_of_installment2_three']) ? $threeInstallmentDetails['date_of_installment2_three'] : 0 }}"
                                                                                       id="date_of_installment2_three"
                                                                                       name="date_of_installment2_three"
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
                                                                                       value="{{ isset($threeInstallmentDetails['date_of_installment3_three']) ? $threeInstallmentDetails['date_of_installment3_three'] : 0 }}"
                                                                                       id="date_of_installment3_three"
                                                                                       name="date_of_installment3_three"
                                                                                       class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                       required>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                    Amount Of Three Payment
                                                                    Installment {{@number_format($threeInstallmentDetails['three_installment_amount_irr'])}}</label>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                    payment in three
                                                                    installments {{ isset($threeInstallmentDetails['three_installment_advance']) ? $threeInstallmentDetails['three_installment_advance'] : 0 }}</label>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                    amount of each
                                                                    installment {{ isset($threeInstallmentDetails['three_installment_each_installment']) ? $threeInstallmentDetails['three_installment_each_installment'] : 0 }}</label>
                                                            @endif
                                                        </td>
                                                        <td class="p-4">
                                                            @php
                                                                $sevenInstallmentDetails=json_decode($tuition->seven_installment_payment,true);
                                                                $sevenInstallmentMinistryDetails=json_decode($tuition->seven_installment_payment_ministry,true);
                                                            @endphp
                                                            @if($tuition->tuitionInfo->academicYearInfo->status==1)
                                                                <div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                        amount of seven payment installment</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex ">
                                                                            <input type="text"
                                                                                   value="{{ isset($sevenInstallmentDetails['seven_installment_amount_irr']) ? $sevenInstallmentDetails['seven_installment_amount_irr'] : 0 }}"
                                                                                   id="seven_installment_amount_irr"
                                                                                   name="seven_installment_amount_irr"
                                                                                   placeholder="Enter full amount of seven payment installment for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                        amount of seven payment installment
                                                                        (ministry)</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex ">
                                                                            <input type="text"
                                                                                   value="{{ isset($sevenInstallmentMinistryDetails['seven_installment_amount_irr_ministry']) ? $sevenInstallmentMinistryDetails['seven_installment_amount_irr_ministry'] : 0 }}"
                                                                                   id="seven_installment_amount_irr_ministry"
                                                                                   name="seven_installment_amount_irr_ministry"
                                                                                   placeholder="Enter full amount of seven payment installment for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                        payment in seven installments</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex ">
                                                                            <input type="text"
                                                                                   value="{{ isset($sevenInstallmentDetails['seven_installment_advance_irr']) ? $sevenInstallmentDetails['seven_installment_advance_irr'] : 0 }}"
                                                                                   id="seven_installment_advance_irr"
                                                                                   name="seven_installment_advance_irr"
                                                                                   placeholder="Enter advance payment in seven installments for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                        payment in seven installments (ministry)</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex ">
                                                                            <input type="text"
                                                                                   value="{{ isset($sevenInstallmentMinistryDetails['seven_installment_advance_irr_ministry']) ? $sevenInstallmentMinistryDetails['seven_installment_advance_irr_ministry'] : 0 }}"
                                                                                   id="seven_installment_advance_irr_ministry"
                                                                                   name="seven_installment_advance_irr_ministry"
                                                                                   placeholder="Enter advance payment in seven installments for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-20 text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                        amount of each installment</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex">
                                                                            <input type="text"
                                                                                   value="{{ isset($sevenInstallmentDetails['seven_installment_each_installment_irr']) ? $sevenInstallmentDetails['seven_installment_each_installment_irr'] : 0 }}"
                                                                                   id="seven_installment_each_installment_irr"
                                                                                   name="seven_installment_each_installment_irr"
                                                                                   placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <label
                                                                        class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                        amount of each installment (ministry)</label>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        <div class="flex">
                                                                            <input type="text"
                                                                                   value="{{ isset($sevenInstallmentMinistryDetails['seven_installment_each_installment_irr_ministry']) ? $sevenInstallmentMinistryDetails['seven_installment_each_installment_irr_ministry'] : 0 }}"
                                                                                   id="seven_installment_each_installment_irr_ministry"
                                                                                   name="seven_installment_each_installment_irr_ministry"
                                                                                   placeholder="Enter the amount of each installment for {{$tuition->levelInfo->name}} in Rials"
                                                                                   class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                   required>
                                                                            <span
                                                                                class="inline-flex items-center px-1 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">IRR</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="grid grid-cols-1 gap-4 mb-4">
                                                                        @for($i=1;$i<=7;$i++)
                                                                        <div>
                                                                            <label
                                                                                class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Date
                                                                                of installment {{$i}}</label>
                                                                            <div class="flex justify-between">
                                                                                <input type="date"
                                                                                       value="{{ isset($sevenInstallmentDetails['date_of_installment' . $i .'_seven']) ? $sevenInstallmentDetails['date_of_installment' . $i .'_seven'] : 0 }}"
                                                                                       id="date_of_installment{{$i}}_seven"
                                                                                       name="date_of_installment{{$i}}_seven"
                                                                                       class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 price"
                                                                                       required>
                                                                            </div>
                                                                        </div>
                                                                        @endfor
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Full
                                                                    Amount Of Seven Payment
                                                                    Installment {{@number_format($sevenInstallmentDetails->amount)}}</label>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">Advance
                                                                    payment in seven
                                                                    installments {{ isset($sevenInstallmentDetails->seven_installment_advance) ? $sevenInstallmentDetails->seven_installment_advance : 0 }}</label>
                                                                <label
                                                                    class="block mb-1 mt-2 text-sm font-medium text-gray-900 dark:text-white">The
                                                                    amount of each
                                                                    installment {{ isset($sevenInstallmentDetails->seven_installment_each_installment) ? $sevenInstallmentDetails->seven_installment_each_installment : 0 }}</label>
                                                            @endif
                                                        </td>
                                                        <td class="p-4 text-center">
                                                            <input type="hidden" value="{{$tuition->id}}"
                                                                   name="tuition_details_id">
                                                            <button type="submit"
                                                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-2 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 delete-row">
                                                                <i class="las la-cloud-upload-alt"
                                                                   style="font-size: 20px"></i>
                                                            </button>
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
