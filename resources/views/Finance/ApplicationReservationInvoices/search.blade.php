@php use App\Models\Catalogs\AcademicYear;use App\Models\Catalogs\PaymentMethod; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Application Reservation Invoices</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <form id="search-user" action="{{ route('SearchReservationInvoices') }}" method="get">
                        <div class="flex w-full">
                            <div class="mr-3">
                                <label for="academic_year"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Academic Year</label>
                                <select id="academic_year" name="academic_year"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled selected>Select Academic Year...</option>
                                    @foreach($academicYears as $academicYear)
                                        <option
                                            @if(isset($_GET['academic_year']) and $_GET['academic_year']==$academicYear->id) selected
                                            @endif value="{{$academicYear->id}}">{{$academicYear->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <button type="submit"
                                        class="text-white bg-blue-700 hover:bg-blue-800 w-full mt-7 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    <i class="fas fa-search mr-2" aria-hidden="true"></i>
                                    Filter
                                </button>
                            </div>
                            @if(isset($_GET['academic_year']) || isset($_GET['date_of_payment']) || isset($_GET['payment_method']) || isset($_GET['status']))
                                <div class="ml-3">
                                    <a href="/ReservationInvoices">
                                        <button type="button"
                                                class="text-white bg-red-700 hover:bg-red-800 w-full mt-7 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                            <i class="fas fa-remove mr-2" aria-hidden="true"></i>
                                            Remove Filter
                                        </button>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
                @include('GeneralPages.errors.session.success')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($applications) or $applications->isEmpty())
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
                                    There is not any application reservation invoices to show!
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 w-4">
                                    Reserve ID
                                </th>
                                <th scope="col" class="text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class="text-center">
                                    Student ID
                                </th>
                                <th scope="col" class="text-center">
                                    Student
                                </th>
                                <th scope="col" class="text-center">
                                    Date Of Create
                                </th>
                                <th scope="col" class="text-center">
                                    Date Of Payment
                                </th>
                                <th scope="col" class="text-center">
                                    Amount
                                </th>
                                <th scope="col" class="text-center">
                                    Payment Method
                                </th>
                                <th scope="col" class="text-center">
                                    Status
                                </th>
                                <th scope="col" class="text-center action">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($applications as $application)
                                <tr
                                    class="bg-white border dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="text-center">
                                        {{$application->id}}
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="">
                                            <div
                                                class="font-semibold">{{ $application->applicationInfo->applicationTimingInfo->academicYearInfo->name }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="">
                                            <div
                                                class=" font-semibold">{{ $application->studentInfo->id }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="">
                                            <div
                                                class=" font-semibold">{{ $application->studentInfo->generalInformationInfo->first_name_en }} {{ $application->studentInfo->generalInformationInfo->last_name_en }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="">
                                            <div
                                                class=" font-semibold">{{ $application->created_at }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="">
                                            <div
                                                class=" font-semibold">
                                                @if($application->payment_status==0)
                                                    Not Paid Yet!
                                                @else
                                                    {{$application->created_at}}
                                                @endif
                                            </div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="">
                                            <div
                                                class=" font-semibold">{{ number_format($application->applicationInfo->applicationTimingInfo->fee) . " Rials" }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="">
                                            @if($application->applicationInvoiceInfo)
                                                @php
                                                    $method=PaymentMethod::find(json_decode($application->applicationInvoiceInfo->payment_information,true)['payment_method']);
                                                @endphp
                                                <div
                                                    class=" font-semibold">{{ $method->name }}</div>
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="">
                                            <div
                                                class=" font-semibold">
                                                @switch($application->payment_status)
                                                    @case(0)
                                                        Payment Processing
                                                        @break
                                                    @case(1)
                                                        Paid
                                                        @break
                                                    @case(2)
                                                        Awaiting Confirmation
                                                        @break
                                                    @case(3)
                                                        Rejected
                                                        @break
                                                @endswitch
                                            </div>

                                        </div>
                                    </th>
                                    <td class="text-center border">
                                        <!-- Modal toggle -->
                                        @if($application->payment_status!=0)
                                            {{--                                            @can('reservation-invoice-show')--}}
                                            {{--                                                <a href="{{ route('ReservationInvoices.show',$application->application_reservations_id) }}"--}}
                                            {{--                                                   type="button"--}}
                                            {{--                                                   class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">--}}
                                            {{--                                                    <div class="text-center">--}}
                                            {{--                                                        <i class="las la-eye "></i>--}}
                                            {{--                                                    </div>--}}
                                            {{--                                                    Details--}}
                                            {{--                                                </a>--}}
                                            {{--                                            @endcan--}}
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
