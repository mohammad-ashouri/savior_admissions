@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Schools</h1>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
                    </div>
                    <div class="flex">
                        @can('school-create')
                            <a href="{{ route('Schools.create') }}">
                                <button type="button"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                                    <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                              d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                              clip-rule="evenodd"></path>
                                    </svg>
                                    Add School
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
                                Gender
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
                        @foreach($schools as $School)
                            <tr
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-200 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <td class="w-4 p-4">
                                    {{ $loop->iteration }}
                                </td>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $School->name }}
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $School->persian_name }}
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $School->genderInfo->name }}
                                </th>
                                <th scope="row"
                                    class=" items-center text-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($School->status===1)
                                        Active
                                    @else
                                        Deactive
                                    @endif
                                </th>
                                <td class="px-6 py-4 text-center">
                                    <!-- Modal toggle -->
                                    @can('school-edit')
                                        <a href="{{ route('Schools.edit',$School->id) }}" type="button"
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
                                    {{--                                    @can('catalogs-delete')--}}
                                    {{--                                        {!! Form::open(['method' => 'DELETE','route' => ['Schools.destroy', $School->id],'style'=>'display:inline']) !!}--}}
                                    {{--                                        <button type="submit"--}}
                                    {{--                                                --}}{{--                                                                                                data-modal-target="deleteCatalog-modal"--}}
                                    {{--                                                --}}{{--                                                                                                data-modal-show="deleteCatalog-modal"--}}
                                    {{--                                                class="min-w-max inline-flex font-medium text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 hover:underline">--}}
                                    {{--                                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20"--}}
                                    {{--                                                 xmlns="http://www.w3.org/2000/svg">--}}
                                    {{--                                                <path fill-rule="evenodd"--}}
                                    {{--                                                      d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"--}}
                                    {{--                                                      clip-rule="evenodd"></path>--}}
                                    {{--                                            </svg>--}}
                                    {{--                                            Delete catalog--}}
                                    {{--                                        </button>--}}
                                    {{--                                        {!! Form::close() !!}--}}
                                    {{--                                    @endcan--}}
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
