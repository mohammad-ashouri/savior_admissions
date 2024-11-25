@extends('Layouts.panel')
@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Interview Form</h1>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-4">
                            <div>
                                <label
                                    class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Name And Surname: </label>
                                {{ $applicationReservation->studentInfo->generalInformationInfo->first_name_en }} {{ $applicationReservation->studentInfo->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <label
                                    class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Class: </label>
                                {{ $applicationReservation->levelInfo->name }}
                            </div>
                            <div>
                                <label
                                    class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    First Interviewer: </label>
                                {{ $applicationReservation->applicationInfo->firstInterviewerInfo->generalInformationInfo->first_name_en }}
                                {{ $applicationReservation->applicationInfo->firstInterviewerInfo->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <label
                                    class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Second Interviewer: </label>
                                {{ $applicationReservation->applicationInfo->secondInterviewerInfo->generalInformationInfo->first_name_en }}
                                {{ $applicationReservation->applicationInfo->secondInterviewerInfo->generalInformationInfo->last_name_en }}
                            </div>
                        </div>
                        <hr>
                        @if ($applicationReservation->level == 1 or $applicationReservation->level==2)
                            @include('BranchInfo.Interviews.Forms.1.ApplianceConfirmation.KG.index')
                        @else
                            @include('BranchInfo.Interviews.Forms.1.ApplianceConfirmation.Levels.index')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
