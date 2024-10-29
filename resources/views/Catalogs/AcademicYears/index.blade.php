@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Academic Years</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
{{--                        <form method="get" action="{{ route('AcademicYears.search') }}">--}}
{{--                            <div class="flex">--}}
{{--                                <input type="text" id="search-navbar" name="name" required--}}
{{--                                       value="@if(isset($name)){{$name}}@else{{ old('name') }}@endif"--}}
{{--                                       class="font-normal block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"--}}
{{--                                       placeholder="Search it...">--}}
{{--                                <button type="submit"--}}
{{--                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-3 text-center inline-flex items-center ml-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">--}}
{{--                                    <i class="las la-search" style="font-size: 24px"></i>--}}
{{--                                    Search--}}
{{--                                </button>--}}
{{--                            </div>--}}
{{--                        </form>--}}
                    </div>
                    <div class="flex">
                        @can('academic-year-create')
                            <a href="{{ route('AcademicYears.create') }}">
                                <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                                    <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    New Academic Year
                                </button>
                                @endcan
                            </a>
                    </div>
                </div>
                @include('GeneralPages.errors.session.error')
                @include('GeneralPages.errors.session.success')


                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    #
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Name (English)
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Name (Persian)
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                School
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Start date
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                finish date
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-center">
                                Action
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($academicYears as $academicYear)
                            <tr
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-200 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        {{ $loop->iteration }}
                                    </div>
                                </td>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div class="text-base font-semibold">{{ $academicYear->name }}</div>
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div class="text-base font-semibold">{{ $academicYear->persian_name }}</div>
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div class="text-base font-semibold">{{ $academicYear->schoolInfo->name }}</div>
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div class="text-base font-semibold">{{ $academicYear->start_date }}</div>
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div class="text-base font-semibold">{{ $academicYear->end_date }}</div>
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div
                                            class="text-base font-semibold @if($academicYear->status===1) text-green-600 @else text-red-600 @endif">
                                            @if($academicYear->status===1)
                                                Active
                                            @else
                                                Deactive
                                            @endif
                                        </div>
                                    </div>
                                </th>
                                <td class="px-6 py-4 text-center">
                                    <!-- Modal toggle -->
                                    @can('academic-year-edit')
                                        <a href="{{ route('AcademicYears.edit',$academicYear->id) }}" type="button"
                                           class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z">
                                                </path>
                                                <path fill-rule="evenodd"
                                                      d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            Edit
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
