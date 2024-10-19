@php use App\Models\Branch\ApplicationReservation; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Documents Uploaded For Confirm</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                @include('GeneralPages.errors.session.success')
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(!isset($studentAppliances) or $studentAppliances->isEmpty())
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
                                    No documents found.
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        #
                                    </div>
                                </th>
                                <th scope="col" class=" text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class=" text-center">
                                    Student ID
                                </th>
                                <th scope="col" class=" text-center">
                                    Student
                                </th>
                                <th scope="col" class=" text-center">
                                    ID+Guardian
                                </th>
                                <th scope="col" class=" text-center">
                                    Level
                                </th>
                                <th scope="col" class=" text-center">
                                    Evidence Form
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($studentAppliances as $appliance)
                                @php
                                    $applicationReservation=ApplicationReservation::with('levelInfo')->whereStudentId($appliance->studentInfo->id)->wherePaymentStatus(1)->latest()->first();
                                @endphp
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4 text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">{{ $appliance->academicYearInfo->name }}</div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">{{ $appliance->student_id }}</div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">{{ $appliance->studentInfo->generalInformationInfo->first_name_en }} {{ $appliance->studentInfo->generalInformationInfo->last_name_en }}</div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">{{ $appliance->studentInformations->guardian }}
                                            - {{ $appliance->studentInformations->guardianInfo->generalInformationInfo->first_name_en }} {{ $appliance->studentInformations->guardianInfo->generalInformationInfo->last_name_en }} </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">{{ $applicationReservation->levelInfo->name }}</div>
                                    </th>
                                    <td class="text-center border">
                                        <a href="/ConfirmEvidences/{{$appliance->id}}"
                                           type="button"
                                           class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                            <i class="las la-eye mt-1 mr-1"></i>
                                            Show
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
        @if(isset($studentAppliances) and $studentAppliances->isNotEmpty())
            <div class="pagination text-center">
                {{ $studentAppliances->onEachSide(5)->links() }}
            </div>
        @endif
    </div>
@endsection
