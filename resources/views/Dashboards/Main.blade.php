@extends('Layouts.panel')
@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                Dear
                {{ $myInfo->generalInformationInfo->first_name_en }} {{ $myInfo->generalInformationInfo->last_name_en }}
                . Welcome to savior school panel
            </div>
            @if($myInfo->hasRole('Super Admin'))
                @include('Dashboards.Roles.SuperAdmin')
            @endif
            @if($myInfo->hasRole('Parent(Father)') or $myInfo->hasRole('Parent(Mother)'))
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
