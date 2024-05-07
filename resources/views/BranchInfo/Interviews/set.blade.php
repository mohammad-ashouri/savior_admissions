@php use App\Models\User;
 $me=User::find(auth()->user()->id);
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
                        <form id="set-interview" method="post" action="{{route('interviews.SetInterview')}}">
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
                            @if ($interview->first_interviewer == $me->id)
                                @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                    @include('BranchInfo.Interviews.Forms.1.KG.Set.interviewer1')
                                @else
                                    @include('BranchInfo.Interviews.Forms.1.Levels.Set.interviewer1')
                                @endif
                            @elseif ($me->hasRole('Interviewer') and !$me->hasRole('Admissions Officer') and $interview->second_interviewer == $me->id)
                                @if ($interview->reservationInfo->level == 1 or $interview->reservationInfo->level == 2)
                                    @include('BranchInfo.Interviews.Forms.1.KG.Set.interviewer2')
                                @else
                                    @include('BranchInfo.Interviews.Forms.1.Levels.Set.interviewer2')
                                @endif
                            @elseif (!$me->hasRole('Interviewer'))
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
