@php use App\Models\User;
 $me=User::find(auth()->user()->id);

 foreach ($interview->interview as $item) {
    if ($item->interview_type == 1) {
    $interviewFields=json_decode($item->interview_form,true);
    }
    if ($item->interview_type == 2) {
    $interviewFields=json_decode($item->interview_form,true);
    }
    if ($item->interview_type == 3) {
    $interviewFields=json_decode($item->interview_form,true);
    $interviewFiles=json_decode($item->files,true);
    }
}

@endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Interview Form</h1>
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
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-2">
                            <div>
                                <label
                                    class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Name And Surname: </label>
                                {{ $interview->reservationInfo->studentInfo->generalInformationInfo->first_name_en }} {{ $interview->reservationInfo->studentInfo->generalInformationInfo->last_name_en }}
                            </div>
                            <div>
                                <label
                                    class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Class: </label>
                                {{ $interview->reservationInfo->levelInfo->name }}
                            </div>
                        </div>
                        @php
                            foreach ($interview->interview as $item) {
                                if ($item->interview_type == 1) {
                                    $interviewFields=json_decode($item->interview_form,true);
                                }
                                if ($item->interview_type == 2) {
                                    $interviewFields=json_decode($item->interview_form,true);
                                }
                                if ($item->interview_type == 3) {
                                    $interviewFields=json_decode($item->interview_form,true);
                                    $interviewFiles=json_decode($item->files,true);
                                }
                            }
                            @endphp
                        @if ($interview->first_interviewer == $me->id)
                            @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                @include('BranchInfo.Interviews.Forms.1.KG.Show.interviewer1')
                            @else
                                @include('BranchInfo.Interviews.Forms.1.Levels.Show.interviewer1')
                            @endif
                        @elseif ($me->hasRole('Interviewer') and $interview->second_interviewer == $me->id)
                            @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                @include('BranchInfo.Interviews.Forms.1.KG.Show.interviewer2')
                            @else
                                @include('BranchInfo.Interviews.Forms.1.Levels.Show.interviewer2')
                            @endif
                        @elseif ($me->hasRole('Financial Manager'))
                            @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                @include('BranchInfo.Interviews.Forms.1.KG.Show.admissions_officer')
                            @else
                                @include('BranchInfo.Interviews.Forms.1.Levels.Show.admissions_officer')
                            @endif
                        @endif
                        <div id="last-step" class="text-center hidden">
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
        </div>
    </div>
@endsection
