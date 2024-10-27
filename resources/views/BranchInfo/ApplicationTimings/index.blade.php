@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Application Timings</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
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
                @include('GeneralPages.errors.session.success')

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
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        #
                                    </div>
                                </th>
                                <th scope="col" class=" text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class=" text-center">
                                    Start Date and Time
                                </th>
                                <th scope="col" class=" text-center">
                                    End Date and Time
                                </th>
                                <th scope="col" class=" text-center">
                                    Interview Time
                                </th>
                                <th scope="col" class=" text-center">
                                    Delay Between Reserve
                                </th>
                                <th scope="col" class=" text-center">
                                    Fee
                                </th>
                                <th scope="col" class=" text-center">
                                    First Interviewer
                                </th>
                                <th scope="col" class=" text-center">
                                    Second Interviewer
                                </th>
                                <th scope="col" class=" text-center">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($applicationTimings as $applicationTiming)
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4 text-center border">
                                        {{ $loop->iteration }}
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $applicationTiming->academicYearInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $applicationTiming->start_date . " " . $applicationTiming->start_time }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $applicationTiming->end_date . " " . $applicationTiming->end_time }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $applicationTiming->interview_time }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $applicationTiming->delay_between_reserve }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ number_format($applicationTiming->fee) . ' Rials' }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $applicationTiming->firstInterviewer->generalInformationInfo->first_name_en }} {{ $applicationTiming->firstInterviewer->generalInformationInfo->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center border text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $applicationTiming->secondInterviewer->generalInformationInfo->first_name_en }} {{ $applicationTiming->secondInterviewer->generalInformationInfo->last_name_en }}
                                    </th>
                                    <td class="flex text-center">
                                        <!-- Modal toggle -->
                                        @can('application-timing-show')
                                            <a href="{{ route('ApplicationTimings.show',$applicationTiming->id) }}"
                                               type="button"
                                               class="text-white mr-2 bg-blue-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 ">
                                                <i class="las la-eye" style="font-size: 22px"></i>
                                            </a>
                                        @endcan
                                        @can('application-timing-delete')
                                            <form class="RemoveApplicationTiming" method="post"
                                                  action="/ApplicationTimings/{{ $applicationTiming->id }}">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button type="submit" title="Remove Application Timing"
                                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm  px-2 py-2 text-center inline-flex items-center  dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 ">
                                                    <i class="las la-trash" style="font-size: 22px"></i>
                                                </button>
                                            </form>
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
    </div>
@endsection
