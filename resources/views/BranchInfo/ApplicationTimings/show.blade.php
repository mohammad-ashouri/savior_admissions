@php use Couchbase\User; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Details Of Application Timing</h1>
            </div>
            @include('GeneralPages.errors.session.success')
            @include('GeneralPages.errors.session.error')

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <p class="font-bold">Academic
                                    Year: </p> {{ $applicationTiming->academicYearInfo->name }}
                            </div>
                            <div>
                                <p class="font-bold">Students Application
                                    Type: </p> {{ $applicationTiming->students_application_type }}
                            </div>
                            <div>
                                <p class="font-bold">Start Date and
                                    Time: </p> {{ $applicationTiming->start_date . " " . $applicationTiming->start_time }}
                            </div>
                            <div>
                                <p class="font-bold">End Date and
                                    Time: </p> {{ $applicationTiming->end_date . " " . $applicationTiming->end_time }}
                            </div>
                            <div>
                                <p class="font-bold">Interview Time: </p> {{ $applicationTiming->interview_time }}
                            </div>
                            <div>
                                <p class="font-bold">Delay Between
                                    Reserve: </p> {{ $applicationTiming->delay_between_reserve }}
                            </div>
                            <div>
                                <p class="font-bold">First Interviewer </p>
                                {{ $applicationTiming->firstInterviewer->generalInformationInfo->first_name_en }} {{ $applicationTiming->firstInterviewer->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <p class="font-bold">Second Interviewer </p>
                                {{ $applicationTiming->secondInterviewer->generalInformationInfo->first_name_en }} {{ $applicationTiming->secondInterviewer->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <p class="font-bold">Interview
                                    Fee: </p> {{ number_format($applicationTiming->fee) . ' Rials' }}
                            </div>
                            <div>
                                <p class="font-bold">Meeting Link: </p> <a class="text-blue-500 underline" href="{{ $applicationTiming->meeting_link }}">{{ $applicationTiming->meeting_link }}</a>
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
        @can('applications-list')
            <div class="p-4 rounded-lg dark:border-gray-700">
                <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                    <h1 class="text-2xl font-medium"> List Of Applications Created</h1>
                </div>
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="lg:col-span-2 col-span-3 ">
                        <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4 overflow-x-auto">
                            <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400 datatable">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            #
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            Date
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            Start From
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            Ends To
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            First Interviewer
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            Second Interviewer
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            Reserved
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            Interviewed
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center">
                                            Status
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class=" items-center action">
                                            Action
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($applicationTiming->applications as $application)
                                    <tr
                                        class="@if($application->reserved==1) bg-green-300 @elseif($application->status==0) bg-red-300 @else bg-white @endif border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 pl-4">
                                            <div class="flex items-center">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <th scope="row"
                                            class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div
                                                class="text-base font-semibold">
                                                {{ $application->date }}
                                            </div>
                                        </th>
                                        <th scope="row"
                                            class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div
                                                class="text-base font-semibold">
                                                {{ $application->start_from }}
                                            </div>
                                        </th>
                                        <th scope="row"
                                            class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div
                                                class="text-base font-semibold">
                                                {{ $application->ends_to }}
                                            </div>
                                        </th>
                                        <th scope="row"
                                            class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div
                                                @php
                                                    $interviewer=\App\Models\User::with('generalInformationInfo')->find($application->first_interviewer );
                                                @endphp
                                                class="text-base font-semibold">
                                                {{ $interviewer->generalInformationInfo->first_name_en . " " . $interviewer->generalInformationInfo->last_name_en }}
                                            </div>
                                        </th>
                                        <th scope="row"
                                            class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div
                                                @php
                                                    $interviewer=\App\Models\User::with('generalInformationInfo')->find($application->second_interviewer );
                                                @endphp
                                                class="text-base font-semibold">
                                                {{ $interviewer->generalInformationInfo->first_name_en . " " . $interviewer->generalInformationInfo->last_name_en }}
                                            </div>
                                        </th>
                                        <th scope="row"
                                            class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div
                                                class="text-base font-semibold">
                                                @if($application->reserved==0)
                                                    No
                                                @elseif($application->reserved==1)
                                                    Yes
                                                @endif
                                            </div>
                                        </th>
                                        <th scope="row"
                                            class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div
                                                class="text-base font-semibold">
                                                @if($application->Interviewed==0)
                                                    No
                                                @elseif($application->Interviewed==1)
                                                    Yes
                                                @endif
                                            </div>
                                        </th>
                                        <th scope="row"
                                            class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div
                                                class="text-base font-semibold">
                                                @if($application->status==0)
                                                    <p class="text-red-700">Deactive</p>
                                                @elseif($application->status==1)
                                                    <p class="text-green-700">Active</p>
                                                @endif
                                            </div>
                                        </th>
                                        <th scope="row"
                                            class="px-2 py-2 text-gray-900 whitespace-nowrap dark:text-white">
                                            <div class="flex justify-center space-x-2">
                                                @can('remove-application-from-reserve')
                                                    @if($application->reserved==1)
                                                        <div
                                                            class="text-base font-semibold">
                                                            <form class="RemoveApplicationReservation" method="post"
                                                                  action="/Applications/RemoveFromReserve/{{ $application->id }}">
                                                                @csrf
                                                                <button type="submit" title="Remove From Reservation"
                                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ">
                                                                    <i class="las la-calendar-times"
                                                                       style="font-size: 22px"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                @endcan
                                                @can('change-status-of-application')
                                                    <div
                                                        class="text-base font-semibold">
                                                        <form class="ChangeApplicationStatus" method="post"
                                                              action="/Applications/ChangeInterviewStatus/{{ $application->id }}">
                                                            @csrf
                                                            @if($application->status==1)
                                                                <button type="submit" title="Change Status Of Interview"
                                                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 ">
                                                                    <i class="las la-eye-slash"
                                                                       style="font-size: 22px"></i>
                                                                </button>
                                                            @else
                                                                <button type="submit" title="Change Status Of Interview"
                                                                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 ">
                                                                    <i class="las la-eye" style="font-size: 22px"></i>
                                                                </button>
                                                            @endif
                                                        </form>
                                                    </div>
                                                @endcan
                                                @can('remove-application')
                                                    <div
                                                        class="text-base font-semibold">
                                                        <form class="RemoveApplication" method="post"
                                                              action="/Applications/{{ $application->id }}">
                                                            @csrf
                                                            <input name="_method" type="hidden" value="DELETE">
                                                            <button type="submit" title="Remove Interview"
                                                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 ">
                                                                <i class="las la-trash" style="font-size: 22px"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endcan
                                            </div>
                                        </th>
                                    </tr>

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endcan
    </div>
@endsection
