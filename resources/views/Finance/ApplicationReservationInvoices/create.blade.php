@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> New Application</h1>
            </div>
            @include('GeneralPages.errors.session.error')

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <form id="application-payment" method="post" action="/ApplicationPayment">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label for="student"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Student</label>
                                    <select id="student" name="student"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select student" required>
                                        <option selected disabled value="">Select student</option>
                                        @foreach($myStudent as $student)
                                            <option @if(old('student')==$student->id) selected
                                                    @endif value="{{$student->id}}">
                                                {{ $student->generalInformations->first_name_en }} {{ $student->generalInformations->last_name_en }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="level"
                                           class="block mb-2 font-bold text-gray-900 dark:text-white">
                                        Level</label>
                                    <select id="level" name="level"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select level" required>
                                        <option selected disabled value="">Select level</option>
                                        @foreach($levels as $level)
                                            <option @if(old('level')==$level->id) selected
                                                    @endif value="{{$level->id}}">
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mb-6">
                                <label for="academic_year"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Academic Year</label>
                                <select id="academic_year" name="academic_year"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        title="Select academic year" required>
                                    <option selected disabled value="">Select academic year</option>
                                </select>
                            </div>
                            <div class="mb-6">
                                <label for="date_and_time"
                                       class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Date And Time</label>
                                <select id="date_and_time" name="date_and_time"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        title="Select date and time" required>
                                    <option selected disabled value="">Select date and time</option>
                                </select>
                            </div>
                            <button type="submit"
                                    class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                Next
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
