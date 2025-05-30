@php use App\Models\Branch\ApplicationReservation;use App\Models\Branch\StudentApplianceStatus;use App\Models\Finance\TuitionInvoices; @endphp
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
                    <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        @if(empty($activeAcademicYears))
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
                                        <p class="font-bold">No active academic year found!</p>
                                    </div>
                                </div>
                            </div>
                        @else
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
                                            @foreach($myStudents as $student)
                                                <option
                                                    @php
                                                        $studentsOnInterview=ApplicationReservation::join('applications','application_reservations.application_id','=','applications.id')
                                                        ->join('application_timings','applications.application_timing_id','=','application_timings.id')
                                                        ->whereIn('application_timings.academic_year',$activeAcademicYears)
                                                        ->where('applications.reserved',1)
                                                        ->where('application_reservations.student_id',$student->student_id)
                                                        ->where('applications.interviewed',0)
                                                        ->exists();

                                                        $allAcademicYearAppliances=StudentApplianceStatus::where('student_id',$student->student_id)->pluck('id')->toArray();

                                                        $checkUnpaidTuition=TuitionInvoices::with([
                                                            'invoiceDetails'=>function ($query) {
                                                                $query->where('is_paid','=','0');
                                                            }
                                                        ])
                                                        ->whereHas('invoiceDetails', function($query) {
                                                            $query->where('is_paid', 0);
                                                        })
                                                        ->whereIn('appliance_id',$allAcademicYearAppliances)
                                                        ->exists();
                                                    @endphp
                                                    @if($studentsOnInterview or $checkUnpaidTuition) disabled @endif
                                                    @if(old('student')==$student->id) selected
                                                    @endif value="{{$student->student_id}}">
                                                    {{ $student->generalInformations->first_name_en }} {{ $student->generalInformations->last_name_en }} {{ $studentsOnInterview ? '(On interview)' : '' }} {{ $checkUnpaidTuition ? '(Unpaid Tuition!)' : '' }}
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
                                        Interview Date And Time</label>
                                    <select id="date_and_time" name="date_and_time"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select date and time" required>
                                        <option selected disabled value="">Select date and time</option>
                                    </select>
                                </div>
                                <div class="mb-6">
                                    <label for="interview_type"
                                           class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Interview Type</label>
                                    <select id="interview_type" name="interview_type"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                            title="Select interview type" required>
                                        <option selected disabled value="">Select interview type</option>
                                        <option value="On-Campus">On-Campus</option>
                                        <option value="On-Sight">On-Sight (Only for people outside Iran)</option>
                                    </select>
                                </div>
                                <div id="onsight-alert" hidden=""
                                     class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-6"
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
                                            <p class="font-bold">After your payment is completed and confirmed, please
                                                refer to the link below at the appointed time of the interview.</p>
                                        </div>
                                    </div>
                                </div>
                                <div hidden="hidden" class="mb-6 AgreementDIV">
                                    <div>
                                        <p class="font-bold mb-2">
                                            Educational Charter of the Students of Savior International Educational
                                            Complex
                                        </p>
                                        <textarea disabled
                                                  class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full h-96 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 CharterText"
                                        ></textarea>
                                    </div>
                                    <div class="mt-3">
                                        <a target="_blank" id="financial_charter_file"
                                           class="font-bold text-blue-500 mb-2 animate__animated animate__fadeIn animate__infinite">
                                            Click to download financial rules and charters file.
                                        </a>
                                    </div>
                                </div>
                                <div class="flex items-center h-5">
                                    <input id="agreement" type="checkbox" value="agreed" required
                                           class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800"
                                    >
                                    <label for="agreement"
                                           class="ml-2 text-sm font-medium text-gray-900 dark:text-white">I
                                        have read and agreed
                                        all the rules and regulations (financial and ethical) and by continuing with
                                        this
                                        application.</label>
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                            class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                        I Agree and read all of roles
                                    </button>
                                    <a href="{{ url()->previous() }}">
                                        <button type="button"
                                                class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                            Back
                                        </button>
                                    </a>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
