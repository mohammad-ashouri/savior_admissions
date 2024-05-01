@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 mt-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium">
                    Tuition Payment
                </h1>
            </div>
            @if (count($errors) > 0)
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
                            @foreach ($errors->all() as $error)
                                <p class="font-bold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @elseif( session()->has('success') )
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
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-3 col-span-3 ">
                    <div class=" bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="col-span-1 gap-4 mb-4 text-black dark:text-white">
                            <h1 class="text-2xl font-medium"></h1>
                        </div>
                        <form id="pay-tuition" method="post" action="{{ route('Tuitions.Pay') }}">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <p class="font-bold">Student ID: </p> {{ $studentApplianceStatus->student_id }}
                                </div>
                                <div>
                                    <p class="font-bold">Student
                                        Information: </p> {{ $studentApplianceStatus->studentInfo->generalInformationInfo->first_name_en }} {{ $studentApplianceStatus->studentInfo->generalInformationInfo->last_name_en }}
                                </div>
                                <div>
                                    <p class="font-bold">Academic
                                        Year: </p> {{ $studentApplianceStatus->academicYearInfo->name }}
                                </div>
                                <div>
                                    <p class="font-bold">Level For Educate: </p> {{ $applicationInfo->levelInfo->name }}
                                </div>
                            </div>
                            <hr>
                            <div class="grid gap-6 mb-6 md:grid-cols-2 ">
                                <div class="">
                                    <label for="payment_type"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Select Payment Type</label>
                                    <select id="payment_type" name="payment_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select student" required>
                                        <option selected disabled value="">Select type</option>
                                        <option value="1">Full payment</option>
                                        <option value="2">Installment</option>
                                    </select>
                                </div>
                                <div>

                                </div>
                                <div>
                                    <div id="full-payment-div" hidden="">
                                        <div
                                            class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
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
                                                    <p class="font-bold">You must pay the total fee through the online
                                                        (Iranian) payment portal.<br> After payment, you will be
                                                        transferred to the invoices page.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="installment-div" hidden="">
                                        <div
                                            class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
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
                                                    <p class="font-bold">In the installment method, you must pay your
                                                        tuition according to the table below. After paying the first
                                                        tuition, you can pay each tuition separately on your tuition
                                                        page.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4">
                                            @php
                                                $firstPayPrice=$tuition->price/5;
                                                $tuitionInstallments=(($tuition->price-(($tuition->price*$discountPercentages)/100)-(($tuition->price*$familyDiscount)/100))-$firstPayPrice)/4;
                                            @endphp
                                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                                <thead
                                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" class="p-4">
                                                        <div class="flex items-center">
                                                            #
                                                        </div>
                                                    </th>
                                                    <th scope="col" class="px-6 py-1 text-center">
                                                        Date Created
                                                    </th>
                                                    <th scope="col" class="px-6 py-1 text-center">
                                                        Due Date
                                                    </th>
                                                    <th scope="col" class="px-6 py-1 text-center">
                                                        Due Amount
                                                    </th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="w-4 p-4 text-center">
                                                        1
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        @php
                                                            $date = new DateTime($studentApplianceStatus->updated_at);
                                                        @endphp
                                                        {{$date->format('Y/m/d')}}
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        @php
                                                            $date->modify('+72 hours')
                                                        @endphp
                                                        {{$date->format('Y/m/d')}}
                                                    </td>
                                                    <td class="w-20 p-4 text-center">
                                                        IRR {{number_format($firstPayPrice)}}
                                                    </td>
                                                </tr>
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="w-4 p-4 text-center">
                                                        2
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        @php
                                                            $date = new DateTime($studentApplianceStatus->updated_at);
                                                        @endphp
                                                        {{$date->format('Y/m/d')}}
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        2024/10/26
                                                    </td>
                                                    <td class="w-20 p-4 text-center">
                                                        IRR {{number_format($tuitionInstallments)}}
                                                    </td>
                                                </tr>
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="w-4 p-4 text-center">
                                                        3
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        @php
                                                            $date = new DateTime($studentApplianceStatus->updated_at);
                                                        @endphp
                                                        {{$date->format('Y/m/d')}}
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        2024/11/26
                                                    </td>
                                                    <td class="w-20 p-4 text-center">
                                                        IRR {{number_format($tuitionInstallments)}}
                                                    </td>
                                                </tr>
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="w-4 p-4 text-center">
                                                        4
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        @php
                                                            $date = new DateTime($studentApplianceStatus->updated_at);
                                                        @endphp
                                                        {{$date->format('Y/m/d')}}
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        2024/12/26
                                                    </td>
                                                    <td class="w-20 p-4 text-center">
                                                        IRR {{number_format($tuitionInstallments)}}
                                                    </td>
                                                </tr>
                                                <tr
                                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                    <td class="w-4 p-4 text-center">
                                                        5
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        @php
                                                            $date = new DateTime($studentApplianceStatus->updated_at);
                                                        @endphp
                                                        {{$date->format('Y/m/d')}}
                                                    </td>
                                                    <td class="w-4 p-4 text-center">
                                                        2025/01/26
                                                    </td>
                                                    <td class="w-20 p-4 text-center">
                                                        IRR {{number_format($tuitionInstallments)}}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                        <tbody>
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="col" class="p-4">
                                                Fee:
                                            </th>
                                            <td class="p-4 w-56 items-center">
                                                IRR {{ number_format($tuition->price) }}
                                            </td>
                                        </tr>
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="col" class="p-4">
                                                Discount:
                                            </th>
                                            <td class="p-4 w-56 items-center">
                                                IRR {{ number_format(($tuition->price*$discountPercentages)/100) }}
                                            </td>
                                        </tr>
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="col" class="p-4">
                                                Family Discount:
                                            </th>
                                            <td class="w-56 p-4">
                                                IRR {{ number_format(($tuition->price*$familyDiscount)/100) }}
                                            </td>
                                        </tr>
                                        <tr
                                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <th scope="col" class="p-4">
                                                Total Fee:
                                            </th>
                                            <td class="w-56 p-4">
                                                IRR {{ number_format($tuition->price-(($tuition->price*$discountPercentages)/100)-(($tuition->price*$familyDiscount)/100)) }}
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <input type="hidden" value="{{$studentApplianceStatus->student_id}}" name="student_id">
                            <input type="hidden" value="{{$studentApplianceStatus->id}}" name="appliance_id">
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
