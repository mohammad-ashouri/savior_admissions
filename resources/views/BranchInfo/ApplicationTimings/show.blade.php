@php use Couchbase\User; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Details Of Application Timing</h1>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <p class="font-bold">Academic Year: </p> {{ $applicationTiming->academicYearInfo->name }}
                            </div>
                            <div>
                                <p class="font-bold">Students Application Type: </p> {{ $applicationTiming->students_application_type }}
                            </div>
                            <div>
                                <p class="font-bold">Start Date and Time: </p> {{ $applicationTiming->start_date . " " . $applicationTiming->start_time }}
                            </div>
                            <div>
                                <p class="font-bold">End Date and Time: </p> {{ $applicationTiming->end_date . " " . $applicationTiming->end_time }}
                            </div>
                            <div>
                                <p class="font-bold">Interview Time: </p> {{ $applicationTiming->interview_time }}
                            </div>
                            <div>
                                <p class="font-bold">Delay Between Reserve: </p> {{ $applicationTiming->delay_between_reserve }}
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
    </div>
@endsection
