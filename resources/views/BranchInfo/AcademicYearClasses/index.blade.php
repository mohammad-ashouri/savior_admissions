@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Academic Year Classes</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
                    </div>
                    <div class="flex">
                        @can('academic-year-class-create')
                            <a href="{{ route('AcademicYearClasses.create') }}">
                                <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                                    <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    New Academic Year Class
                                </button>
                                @endcan
                            </a>
                    </div>
                </div>
                @include('GeneralPages.errors.session.success')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($academicYearClasses) or $academicYearClasses->isEmpty())
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
                                    There is not any classes to show!
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
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Level
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Education type
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Capacity
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Education Gender
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
                            @foreach($academicYearClasses as $academicYearClass)
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4">
                                        {{ $loop->iteration }}
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $academicYearClass->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $academicYearClass->academicYearInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $academicYearClass->levelInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $academicYearClass->educationTypeInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $academicYearClass->capacity }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $academicYearClass->educationGenderInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 @if($academicYearClass->status===1) bg-green-600 @else bg-red-600 @endif whitespace-nowrap dark:text-white">
                                        @if($academicYearClass->status===1)
                                            Active
                                        @else
                                            Deactive
                                        @endif
                                    </th>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Modal toggle -->
                                        @can('academic-year-class-edit')
                                            <a href="{{ route('AcademicYearClasses.edit',$academicYearClass->id) }}"
                                               type="button"
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
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection
