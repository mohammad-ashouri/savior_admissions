@extends('Layouts.panel')
@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                Dear
                {{ $me->generalInformationInfo->first_name_en }} {{ $me->generalInformationInfo->last_name_en }}
                . Welcome to savior school panel
            </div>

            <div id="spinner" class=" fixed top-0 left-0 w-screen h-screen flex justify-center items-center bg-black bg-opacity-50 z-50">
                <div class="animate-spin rounded-full h-14 w-14 border-t-2 border-b-2 border-gray-900"></div>
                <p id="spinner-text" class="ml-4 font-bold text-black animate__animated animate__heartBeat animate__infinite infinite">Loading...</p>
            </div>
            @if($me->hasRole('Super Admin'))
                @include('Dashboards.Roles.SuperAdmin')
            @endif
            @if($me->hasRole('Parent'))
                @include('Dashboards.Roles.Parent')
            @endif

{{--            //        if ($me->hasRole('Super Admin')){--}}
{{--            //            $view='SuperAdmin';--}}
{{--            //        }elseif ($me->hasRole('Principal')){--}}
{{--            //            $view='Principal';--}}
{{--            //        }elseif ($me->hasRole('Admissions Officer')){--}}
{{--            //            $view='AdmissionsOfficer';--}}
{{--            //        }elseif ($me->hasRole('Financial Manager')){--}}
{{--            //            $view='FinancialManager';--}}
{{--            //        }elseif ($me->hasRole('Interviewer')){--}}
{{--            //            $view='Interviewer';--}}
{{--            //        }elseif ($me->hasRole('Parent(Father)') or $me->hasRole('Parent(Mother)')){--}}
{{--            //            $view='Parent';--}}
{{--            //        }elseif ($me->hasRole('Student')){--}}
{{--            //            $view='Student';--}}
{{--            //        }--}}
        </div>
    </div>
@endsection
