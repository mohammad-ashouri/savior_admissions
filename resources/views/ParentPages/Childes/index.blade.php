@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Your Children</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
                        <input type="text" id="search-navbar"
                               class="font-normal text-lg block w-full p-3 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                               placeholder="Search it...">
                    </div>
                    <div class="flex">
                        @can('childes-create')
                            <a href="{{ route('Childes.create') }}">
                                <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                                    <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    New Child
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
                    @if(empty($childes))
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
                                    There is not any children informations to show!
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
                                        ID
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    First Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Last Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Birthdate
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Gender
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Birthplace
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Nationality
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($childes as $child)
                                <tr
                                    class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            {{ $child->student_id }}
                                        </div>
                                    </td>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $child->generalInformations->first_name }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $child->generalInformations->last_name }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $child->generalInformations->birthdate }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $child->generalInformations->gender }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $child->generalInformations->birthplaceInfo->en_short_name }}</div>
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $child->generalInformations->nationalityInfo->nationality }}</div>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 text-center">
                                        <!-- Modal toggle -->
                                        @can('childes-show')
                                            <a href="{{ route('Childes.show',$child->id) }}"
                                               type="button"
                                               class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                                <div class="text-center">
                                                    <i class="las la-eye "></i>
                                                </div>
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
        @if(!empty($childes))
            <div class="pagination text-center">
                {{--                {{ $childes->links() }}--}}
            </div>
    @endif
@endsection
