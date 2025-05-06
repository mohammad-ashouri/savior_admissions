@php use App\Models\User;
 $me=User::find(auth()->user()->id);
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
                        <div class="grid gap-6 mb-6 md:grid-cols-3">
                            <div>
                                <label
                                    class="block mb-2  font-bold text-gray-900 dark:text-white">
                                    Student ID: </label>
                                {{ $interview->reservationInfo->studentInfo->id }}
                            </div>
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
                        <form id="set-interview" method="post" enctype="multipart/form-data"
                              action="{{route('interviews.SetInterview')}}">
                            @csrf
                            @if ($interview->first_interviewer == $me->id and $form=='i1')
                                @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                    @include('BranchInfo.Interviews.Forms.1.KG.Set.interviewer1')
                                @else
                                    @include('BranchInfo.Interviews.Forms.1.Levels.Set.interviewer1')
                                @endif
                            @endif
                            @if ($interview->second_interviewer == $me->id and $form=='i2')
                                @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                    @include('BranchInfo.Interviews.Forms.1.KG.Set.interviewer2')
                                @else
                                    @include('BranchInfo.Interviews.Forms.1.Levels.Set.interviewer2')
                                @endif
                            @endif
                            @if ($me->hasRole('Financial Manager') and $form=='fm')
                                @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                    @include('BranchInfo.Interviews.Forms.1.KG.Set.admissions_officer')
                                @else
                                    @include('BranchInfo.Interviews.Forms.1.Levels.Set.admissions_officer')
                                @endif
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
