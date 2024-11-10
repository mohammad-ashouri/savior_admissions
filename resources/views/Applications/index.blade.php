@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Applications</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
                    </div>
                    <div class="flex">
                        @can('new-application-reserve')
                            <a href="{{ route('Applications.create') }}">
                                <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                                    <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    New Application
                                </button>
                                @endcan
                            </a>
                    </div>
                </div>
                @include('GeneralPages.errors.session.error')
                @include('GeneralPages.errors.session.success')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($applications) or $applications->isEmpty())
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
                                    No applications found!
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    #
                                </th>
                                <th scope="col" class="p-4">
                                    Reserve ID
                                </th>
                                <th scope="col" class="px-1 text-center">
                                    Student ID
                                </th>
                                <th scope="col" class="px-1 text-center">
                                    Student
                                </th>
                                <th scope="col" class="px-1 text-center">
                                    Grade
                                </th>
                                <th scope="col" class="px-1 text-center">
                                    Date
                                </th>
                                <th scope="col" class="px-1 text-center">
                                    Start From
                                </th>
                                <th scope="col" class="px-1 text-center">
                                    Ends To
                                </th>
                                <th scope="col" class="px-1 text-center">
                                    Reserved By
                                </th>
                                <th scope="col" class="px-1 text-center action">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($applications as $application)
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4  text-center border">
                                        {{$loop->iteration}}
                                    </td>
                                    <td class="w-4 text-center border">
                                        {{$application->id}}
                                    </td>
                                    <th scope="row"
                                        class=" border items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $application->studentInfo->id }}
                                    </th>
                                    <th scope="row"
                                        class=" border items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $application->studentInfo->generalInformationInfo->first_name_en }} {{ $application->studentInfo->generalInformationInfo->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" border items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $application->levelInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" border items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $application->applicationInfo->date }}
                                    </th>
                                    <th scope="row"
                                        class=" border items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $application->applicationInfo->start_from }}
                                    </th>
                                    <th scope="row"
                                        class=" border items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                       {{ $application->applicationInfo->ends_to }}
                                    </th>
                                    <th scope="row"
                                        class=" border items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $application->reservatoreInfo->generalInformationInfo->first_name_en }} {{ $application->reservatoreInfo->generalInformationInfo->last_name_en }}
                                    </th>
                                    <td class=" border px-1 text-center">
                                        <!-- Modal toggle -->
                                        @if($me->hasRole('Parent') and $application->payment_status!=0)
                                            @can('show-application-reserve')
                                                <a href="{{ route('Applications.show',$application->id) }}"
                                                   type="button"
                                                   class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                                    <div class="text-center">
                                                        <i class="las la-eye "></i>
                                                    </div>
                                                    Payment Details
                                                </a>
                                            @endcan
                                        @elseif((!$me->hasRole('Parent')))
                                            @can('show-application-reserve')
                                                <a href="{{ route('Applications.show',$application->id) }}"
                                                   type="button"
                                                   class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                                    <div class="text-center">
                                                        <i class="las la-eye "></i>
                                                    </div>
                                                    Payment Details
                                                </a>
                                            @endcan
                                        @endif
                                        @if($application->payment_status==0)
                                            <a href="PrepareToPayApplication/{{$application->id}}"
                                               type="button"
                                               class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                <div class="text-center">
                                                    <i class="las la-eye "></i>
                                                </div>
                                                Pay
                                            </a>
                                        @endif
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
