@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> New Application Timing</h1>
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
            @endif
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <form id="new-application-timing" method="post" action="{{route('ApplicationTimings.store')}}">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="academic_year"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Academic Year</label>
                                    <select id="academic_year" name="academic_year"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select academic year" required>
                                        <option selected disabled value="">Select academic year</option>
                                        @foreach($academicYears as $academicYear)
                                            <option @if(old('academic_year')==$academicYear->id) selected
                                                    @endif value="{{$academicYear->id}}">{{$academicYear->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="student_application_type"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Student Application Type</label>
                                    <select id="student_application_type" name="student_application_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select student application type" required>
                                        <option selected disabled value="">Select student application type</option>
                                        <option @if(old('student_application_type')=='All') selected @endif value="All">
                                            All
                                        </option>
                                        <option @if(old('student_application_type')=='Presently Studying') selected
                                                @endif value="Presently Studying">Presently Studying
                                        </option>
                                    </select>
                                </div>
                                <div>
                                    <label for="start_date"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        App Begins At</label>
                                    <input type="date" id="start_date" value="{{ old('start_date') }}" name="start_date"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           title="Select start date" required>
                                </div>
                                <div>
                                    <label for="start_time"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        App Start Time</label>
                                    <input type="time" id="start_time" value="{{ old('start_time') }}" name="start_time"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           title="Select start time" required>
                                </div>
                                <div>
                                    <label for="end_date"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        App Ends At</label>
                                    <input type="date" id="end_date" value="{{ old('end_date') }}" name="end_date"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           title="Select end date" required>
                                </div>
                                <div>
                                    <label for="end_time"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        App End Time</label>
                                    <input type="time" id="end_time" value="{{ old('end_time') }}" name="end_time"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           title="Select end time" required>
                                </div>
                                <div>
                                    <label for="interview_time"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        Interview Time</label>
                                    <div class="flex justify-between">
                                        <input type="text" id="interview_time" name="interview_time"
                                               value="{{ old('interview_time') }}" required
                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            Minutes
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label for="delay_between_reserve"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        App Delay Between Reserve</label>
                                    <div class="flex justify-between">
                                        <input type="text" id="delay_between_reserve" name="delay_between_reserve"
                                               value="{{ old('delay_between_reserve') }}" required
                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            Minutes
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label for="interviewers"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        Interviewers</label>
                                    <select id="interviewers" name="interviewers[]" multiple="multiple"
                                            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                    </select>
                                </div>
                                <div>
                                    <label for="interview_fee"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        Interview Fee</label>
                                    <div class="flex justify-between">
                                        <input type="number" id="interview_fee" name="interview_fee"
                                               value="{{ old('interview_fee') }}" required min="0"
                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            Rials
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Save
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
