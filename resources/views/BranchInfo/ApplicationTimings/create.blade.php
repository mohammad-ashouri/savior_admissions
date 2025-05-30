@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> New Application Timing</h1>
            </div>
            @include('GeneralPages.errors.session.error')

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <form id="new-application-timing" method="post" action="{{route('ApplicationTimings.store')}}">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="academic_year"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
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
                                    <label for="grades"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        Grades</label>
                                    <select id="grades" name="grades[]" multiple
                                            class="select2 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select grades" required>
                                        <option selected disabled value="">Select grades</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="start_date"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        App Begins At</label>
                                    <input type="date" id="start_date" value="{{ old('start_date') }}" name="start_date" min="{{ date('Y-m-d') }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           title="Select start date" required>
                                </div>
                                <div>
                                    <label for="end_date"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        App Ends At</label>
                                    <input type="date" id="end_date" value="{{ old('end_date') }}" name="end_date" min="{{ date('Y-m-d') }}"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           title="Select end date" required>
                                </div>
                                <div>
                                    <label for="start_time"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        App Start Time</label>
                                    <input type="time" id="start_time" value="{{ old('start_time') }}" name="start_time"
                                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           title="Select start time" required>
                                </div>
                                <div>
                                    <label for="end_time"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
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
                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
                                        <input type="number" id="delay_between_reserve" name="delay_between_reserve"
                                               value="{{ old('delay_between_reserve') }}" required min="1" max="30"
                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            Minutes
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <label for="first_interviewer"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        First Interviewer</label>
                                    <select id="first_interviewer" name="first_interviewer"
                                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            required>
                                    </select>
                                </div>
                                <div>
                                    <label for="second_interviewer"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        Second Interviewer</label>
                                    <select id="second_interviewer" name="second_interviewer"
                                            class=" bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
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
                                               class="rounded-s-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                        <span
                                            class="inline-flex items-center px-3 text-sm text-gray-900 bg-gray-200 border rounded-e-0 border-gray-300 rounded-e-md dark:bg-gray-600 dark:text-gray-400 dark:border-gray-600">
                                            Rials
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label for="meeting_link"
                                       class="block mb-2 font-bold text-gray-900 dark:text-white">
                                    Meeting Link</label>
                                <div class="flex justify-between">
                                    <input type="text" id="meeting_link" name="meeting_link"
                                           value="{{ old('meeting_link') }}" required placeholder="Enter meeting link for this interview team. (For insight interviews)."
                                           class="rounded-lg bg-gray-50 border text-gray-900 focus:ring-blue-500 focus:border-blue-500 block flex-1 min-w-0 w-full text-sm border-gray-300 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
