@php use App\Models\User;
$me=User::find(auth()->user()->id);
foreach ($interview->interview as $item) {
    if ($item->interview_type == 1 and $interview->first_interviewer == $me->id) {
        $interviewID=$item->id;
        $interviewFields=json_decode($item->interview_form,true);
    }
    if ($item->interview_type == 2 and $interview->second_interviewer == $me->id) {
        $interviewID=$item->id;
        $interviewFields=json_decode($item->interview_form,true);
    }
    if ($item->interview_type == 3 and $me->hasRole('Financial Manager')) {
        $interviewFields=json_decode($item->interview_form,true);
        $interviewID=$item->id;
        $interviewFiles=json_decode($item->files,true);
    }
}

@endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Interview Form</h1>
            </div>
            @include('GeneralPages.errors.session.error')

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        {!! Form::model($interview, ['method' => 'PATCH','id'=>'update-interview','enctype'=>'multipart/form-data','route' => ['Interviews.update', $interview->id]]) !!}
                        @csrf
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
                                if ($item->interview_type == 1 and $interview->first_interviewer == $me->id) {
                                    $interviewID=$item->id;
                                    $interviewFields=json_decode($item->interview_form,true);
                                }
                                if ($item->interview_type == 2 and $interview->second_interviewer == $me->id) {
                                    $interviewID=$item->id;
                                    $interviewFields=json_decode($item->interview_form,true);
                                }
                                if ($item->interview_type == 3 and $me->hasRole('Financial Manager')) {
                                    $interviewFields=json_decode($item->interview_form,true);
                                    $interviewID=$item->id;
                                    $interviewFiles=json_decode($item->files,true);
                                }
                            }
                        @endphp
                        @if ($interview->first_interviewer == $me->id)
                            @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                @include('BranchInfo.Interviews.Forms.1.KG.Edit.interviewer1')
                            @else
                                @include('BranchInfo.Interviews.Forms.1.Levels.Edit.interviewer1')
                            @endif
                        @elseif ($me->hasRole('Interviewer') and !$me->hasRole('Admissions Officer') and $interview->second_interviewer == $me->id)
                            @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                @include('BranchInfo.Interviews.Forms.1.KG.Edit.interviewer2')
                            @else
                                @include('BranchInfo.Interviews.Forms.1.Levels.Edit.interviewer2')
                            @endif
                        @elseif ($me->hasRole('Financial Manager'))
                            @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                @include('BranchInfo.Interviews.Forms.1.KG.Edit.admissions_officer')
                            @else
                                @include('BranchInfo.Interviews.Forms.1.Levels.Edit.admissions_officer')
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
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
