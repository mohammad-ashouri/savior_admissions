@php use Couchbase\User; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Details Of Application Timing</h1>
            </div>
            @if( session()->has('success') )
                <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-5"
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
            @elseif (count($errors) > 0)
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
            @endif
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
                                <p class="font-bold">Interviewers: </p>
                                @foreach(json_decode($applicationTiming->interviewers,true) as $interviewer)
                                    @php
                                        $interviewerInfo=\App\Models\User::find($interviewer);
                                    @endphp
                                    * {{ $interviewerInfo->name . " " . $interviewerInfo->family }}<br/>
                                @endforeach
                            </div>
                            <div>
                                <p class="font-bold">Interview Fee: </p> {{ $applicationTiming->fee . ' Rials' }}
                            </div>
                        </div>
                        <a href="/Applications">
                            <button type="button"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Back
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 rounded-lg dark:border-gray-700">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> List Of Interviews Created</h1>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <table class="w-full text-sm text-center text-gray-500 dark:text-gray-400">
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
                                        Interviewer
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
                                    <div class=" items-center">
                                        Action
                                    </div>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($applicationTiming->interviews as $interview)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="w-4 pl-4">
                                        <div class="flex items-center">
                                            {{ $loop->iteration }}
                                        </div>
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            {{ $interview->date }}
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            {{ $interview->start_from }}
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            {{ $interview->ends_to }}
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            @php
                                                $interviewerInfo=\App\Models\User::find($interview->interviewer);
                                            @endphp
                                            class="text-base font-semibold">
                                            {{ $interviewerInfo->name }} {{ $interviewerInfo->family }}
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @if($interview->reserved==0)
                                                No
                                            @elseif($interview->reserved==1)
                                                Yes
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @if($interview->Interviewed==0)
                                                No
                                            @elseif($interview->Interviewed==1)
                                                Yes
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @if($interview->status==0)
                                                <p class="text-red-400">Deactive</p>
                                            @elseif($interview->status==1)
                                                <p class="text-green-400">Active</p>
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class="px-2 py-2 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="flex justify-center space-x-2">
                                            @if($interview->reserved==1)
                                                <div
                                                    class="text-base font-semibold">
                                                    <form class="RemoveReservation" method="post"
                                                          action="/Interviews/RemoveFromReserve/{{ $interview->id }}">
                                                        @csrf
                                                        <button type="submit" title="Remove From Reservation"
                                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ">
                                                            <i class="las la-calendar-times"
                                                               style="font-size: 22px"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                            <div
                                                class="text-base font-semibold">
                                                <form class="ChangeInterviewStatus" method="post"
                                                      action="/Interviews/ChangeInterviewStatus/{{ $interview->id }}">
                                                    @csrf
                                                    @if($interview->status==1)
                                                    <button type="submit" title="Change Status Of Interview"
                                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 ">
                                                            <i class="las la-eye-slash" style="font-size: 22px"></i>
                                                    </button>
                                                    @else
                                                        <button type="submit" title="Change Status Of Interview"
                                                                class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 ">
                                                            <i class="las la-eye" style="font-size: 22px"></i>
                                                        </button>
                                                    @endif
                                                </form>
                                            </div>
                                            <div
                                                class="text-base font-semibold">
                                                <form class="RemoveInterview" method="post"
                                                      action="/Interviews/{{ $interview->id }}">
                                                    @csrf
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <button type="submit" title="Remove Interview"
                                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 ">
                                                        <i class="las la-trash" style="font-size: 22px"></i>
                                                    </button>
                                                </form>
                                            </div>
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
    </div>
@endsection
