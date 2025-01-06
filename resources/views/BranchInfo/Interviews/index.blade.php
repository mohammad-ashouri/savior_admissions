@php use App\Models\Branch\Interview;use App\Models\Branch\StudentApplianceStatus;use App\Models\User;
 $me=User::find(auth()->user()->id);
@endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">Interviews</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                @include('GeneralPages.errors.session.success')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($interviews))
                        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
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
                                    There is not any interviews to show!
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        #
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Application ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Student ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Student
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Date and Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Level
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Interview Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Reservatore
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    First Interviewer
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Second Interviewer
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-center action">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($interviews as $interview)
                                @if(@$interview->reservationInfo->payment_status!=1 or !isset($interview->reservationInfo->studentInfo->id))
                                    @continue
                                @endif
                                @php
                                    $studentApplianceStatus=StudentApplianceStatus::whereAcademicYear($interview->applicationTimingInfo->academic_year)->whereStudentId($interview->reservationInfo->studentInfo->id)->orderByDesc('id')->first();
                                @endphp
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4 text-center border p-4">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="w-4 text-center border">
                                        {{ $interview->id }}
                                    </td>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $interview->reservationInfo->studentInfo->id }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $interview->reservationInfo->studentInfo->generalInformationInfo->first_name_en }} {{ $interview->reservationInfo->studentInfo->generalInformationInfo->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $interview->date . ' - ' . $interview->start_from . ' - ' .  $interview->ends_to}}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $interview->reservationInfo->levelInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $interview->reservationInfo->interview_type }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $interview->reservationInfo->reservatoreInfo?->generalInformationInfo?->first_name_en }} {{ $interview->reservationInfo->reservatoreInfo?->generalInformationInfo?->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $interview->firstInterviewerInfo->generalInformationInfo->first_name_en }} {{ $interview->firstInterviewerInfo->generalInformationInfo->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $interview->secondInterviewerInfo->generalInformationInfo->first_name_en }} {{ $interview->secondInterviewerInfo->generalInformationInfo->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch($interview->reservationInfo->payment_status)
                                            @case(0)
                                                Awaiting for pay
                                                @break
                                            @case(1)
                                                @switch($interview->Interviewed)
                                                    @case(0)
                                                        {{$studentApplianceStatus->interview_status}}
                                                        @break
                                                    @case(1)
                                                        Interviewed
                                                        @break
                                                    @case(2)
                                                        Absence
                                                        @break
                                                @endswitch
                                                @break
                                            @case(2)
                                                Waiting for payment confirmation
                                                @break
                                        @endswitch
                                    </th>
                                    <td class="border text-center">
                                        <div class="flex">
                                            <!-- Modal toggle -->
                                            @php
                                                $studentApplianceStatus=StudentApplianceStatus::whereAcademicYear($interview->applicationTimingInfo->academic_year)->whereStudentId($interview->reservationInfo->studentInfo->id)->orderByDesc('id')->first();
                                            @endphp
                                            @if($interview->reservationInfo->payment_status==1)
                                                @switch($interview->Interviewed)
                                                    @case(0)
                                                        @switch($studentApplianceStatus->interview_status)
                                                            @case('Rejected')
                                                            @case('Accepted')
                                                                {{$studentApplianceStatus->interview_status}}
                                                                @break
                                                            @default
                                                                @if($interview->firstInterviewerInfo->id==$me->id or $interview->secondInterviewerInfo->id==$me->id or $me->hasRole('Financial Manager'))
                                                                    @php
                                                                        $checkInterview=Interview::whereApplicationId($interview->id)
                                                                        ->where('interviewer',auth()->user()->id)
                                                                        ->exists();
                                                                    @endphp
                                                                    @if(!$checkInterview)
                                                                        @can('interview-set')
                                                                            <a href="/SetInterview/{{ $interview->id }}"
                                                                               type="button"
                                                                               class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                                                <i class="las la-eye mt-1 mr-1"></i>
                                                                                Set
                                                                            </a>
                                                                        @endcan
                                                                        @if($interview->firstInterviewerInfo->id==$me->id)
                                                                            <form class="submit-absence" method="post"
                                                                                  action="{{ route('interviews.submitAbsence') }}">
                                                                                @csrf
                                                                                <input type="hidden"
                                                                                       value="{{ $interview->id }}"
                                                                                       name="application_id">
                                                                                <button
                                                                                    type="button"
                                                                                    class="min-w-max inline-flex font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 hover:underline">
                                                                                    <i class="las la-times-circle mt-1 mr-1"></i>
                                                                                    Submit Absence
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    @else
                                                                        @can('interview-show')
                                                                            <a href="/Interviews/{{ $interview->id }}"
                                                                               type="button"
                                                                               class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                                                <i class="las la-eye mt-1"></i>
                                                                            </a>
                                                                        @endcan
                                                                        @if(!$studentApplianceStatus->interview_status!='Rejected' and !$studentApplianceStatus->interview_status!='Admitted')
                                                                            @can('interview-edit')
                                                                                <a href="/Interviews/{{ $interview->id }}/edit"
                                                                                   type="button"
                                                                                   class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                                                                    <i class="las la-pen mt-1 mr-1"></i>
                                                                                    Edit
                                                                                </a>
                                                                            @endcan
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                @break
                                                        @endswitch
                                                        @if($interview->reservationInfo->interview_type=='On-Sight' and $me->hasRole('Parent'))
                                                            <a href="{{$interview->applicationTimingInfo->meeting_link}}"
                                                               type="button"
                                                               class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                                                <i class="las la-eye mt-1 mr-1"></i>
                                                                Interview Meeting
                                                            </a>
                                                        @endif
                                                        @break
                                                    @case('1')
                                                        @can('interview-show')
                                                            <a href="/ConfirmApplication/{{ $interview->id }}/{{$studentApplianceStatus?->id}}"
                                                               type="button"
                                                               class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                                <i class="las la-eye mt-1"></i>
                                                            </a>
                                                        @endcan
                                                        @if($studentApplianceStatus?->approval_status=='0' and $me->hasRole('Financial Manager'))
                                                            @can('interview-edit')
                                                                <a href="/Interviews/{{ $interview->id }}/edit"
                                                                   type="button"
                                                                   class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                                                    <i class="las la-pen mt-1 mr-1"></i>
                                                                    Edit
                                                                </a>
                                                            @endcan
                                                        @endif
                                                        @break
                                                @endswitch
                                            @endif
                                        </div>

                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
