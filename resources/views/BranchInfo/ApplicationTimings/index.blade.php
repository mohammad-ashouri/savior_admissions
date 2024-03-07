@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Application Timings</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
                        <input type="text" id="search-navbar"
                               class="font-normal text-lg block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               placeholder="Search it...">
                    </div>
                    <div class="flex">
                        @can('application-timing-create')
                            <a href="{{ route('ApplicationTimings.create') }}">
                                <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                                    <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    New Application Timing
                                </button>
                                @endcan
                            </a>
                    </div>
                </div>
                @if( session()->has('success') )
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
                                <p class="font-bold">{{ session()->get('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($applicationTimings))
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
                                    There is not any application timings to show!
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        #
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Students Application Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Start Date and Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    End Date and Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Interview Time
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Delay Between Reserve
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Fee
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($applicationTimings as $applicationTiming)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            {{ $loop->iteration }}
                                        </div>
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $applicationTiming->academicYearInfo->name }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $applicationTiming->students_application_type }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $applicationTiming->start_date . " " . $applicationTiming->start_time }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $applicationTiming->end_date . " " . $applicationTiming->end_time }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $applicationTiming->interview_time }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $applicationTiming->delay_between_reserve }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ number_format($applicationTiming->fee) . ' Rials' }}</div>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Modal toggle -->
                                        @can('application-timing-show')
                                            <a href="{{ route('ApplicationTimings.show',$applicationTiming->id) }}"
                                               type="button"
                                               class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                                <i class="las la-eye mt-1 mr-1"></i>
                                                Details
                                            </a>
                                        @endcan
                                        @can('application-timing-edit')
                                            {{--                                            <a href="{{ route('ApplicationTimings.edit',$applicationTiming->id) }}"--}}
                                            {{--                                               type="button"--}}
                                            {{--                                               class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">--}}
                                            {{--                                                <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"--}}
                                            {{--                                                     xmlns="http://www.w3.org/2000/svg">--}}
                                            {{--                                                    <path--}}
                                            {{--                                                        d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">--}}
                                            {{--                                                    </path>--}}
                                            {{--                                                    <path fill-rule="evenodd"--}}
                                            {{--                                                          d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"--}}
                                            {{--                                                          clip-rule="evenodd"></path>--}}
                                            {{--                                                </svg>--}}
                                            {{--                                                Edit--}}
                                            {{--                                            </a>--}}
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
        @if(!empty($applicationTimings))
            <div class="pagination text-center">
                {{ $applicationTimings->links() }}
            </div>
    @endif
@endsection
