@php use App\Models\StudentInformation;use App\Models\User;
@endphp
@extends('Layouts.panel')
@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All users</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
                        <form id="search-user" action="{{ route('searchUser') }}" method="get">
                            <div class="flex w-96">
                                <div>
                                    <input type="text" id="search-user-code" name="search-user-code"
                                           value="{{ @$_GET['search-user-code'] }}"
                                           class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter user code">
                                </div>
                                <div>
                                    <input type="text" id="search-first-name" name="search-first-name"
                                           value="{{ @$_GET['search-first-name'] }}"
                                           class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter first name">
                                </div>
                                <div>
                                    <input type="text" id="search-last-name" name="search-last-name"
                                           value="{{ @$_GET['search-last-name'] }}"
                                           class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter last name">
                                </div>
                                <div>
                                    <input type="text" id="search-mobile" name="search-mobile"
                                           value="{{ @$_GET['search-mobile'] }}"
                                           class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                           placeholder="Enter mobile">
                                </div>
                                <div>
                                    <select name="role" id="role"
                                            class="font-normal block w-40 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                    >
                                        <option value="" selected disabled>Select role</option>
                                        @foreach ($roles as $role)
                                            @if ((auth()->user()->hasRole(['Principal','Admissions Officer'])) and ($role->name == 'Super Admin' or $role->name == 'Principal' or $role->name == 'Admissions Officer' or $role->name == 'Financial Manager' or $role->name == 'Interviewer'))
                                                @continue
                                            @endif

                                            <option value="{{ $role->name }}"
                                                    @if($role->name == @$_GET['role']) selected @endif>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <button type="submit"
                                            class="text-white bg-blue-700 hover:bg-blue-800 w-full h-full focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                        <i class="fas fa-search mr-2" aria-hidden="true"></i>
                                        Filter
                                    </button>
                                </div>
                                @if(isset($_GET['search-user-code']) || isset($_GET['search-first-name']) || isset($_GET['search-last-name']) || isset($_GET['role']))
                                    <div class="ml-3">
                                        <a href="/users">
                                            <button type="button"
                                                    class="text-white bg-red-700 hover:bg-red-800 w-full h-full focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 RemoveFilter">
                                                <i class="fas fa-remove mr-2" aria-hidden="true"></i>
                                                Remove
                                            </button>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                    <div class="flex">
                        <a href="{{ route('users.create') }}">
                            <button type="button"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                                <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                Add user
                            </button>
                        </a>

                        {{--                        <button type="button"--}}
                        {{--                                class="4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2  text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">--}}
                        {{--                            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"--}}
                        {{--                                 xmlns="http://www.w3.org/2000/svg">--}}
                        {{--                                <path fill-rule="evenodd"--}}
                        {{--                                      d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"--}}
                        {{--                                      clip-rule="evenodd"></path>--}}
                        {{--                            </svg>--}}
                        {{--                            Export--}}
                        {{--                        </button>--}}
                    </div>
                </div>
                @include('GeneralPages.errors.session.error')
                @include('GeneralPages.errors.session.success')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($data))
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
                                    User with the entered profile was not found
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 ">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4">
                                    <div class="flex items-center">
                                        #
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Informations
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Position(s)
                                </th>
                                <th scope="col" class="px-6 py-3 text-center action">
                                    Actions
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($data as $user)
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4 p-4">
                                        <div class="flex items-center">
                                            {{ $user->id }}
                                        </div>
                                    </td>
                                    <th scope="row"
                                        class="flex items-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <img class="w-10 h-10 rounded-full"
                                             src="{{ Vite::asset('resources/images/Panel/default_user_icon.png') }}"
                                             alt="User Personal Image">
                                        <div class="pl-3">
                                            <div
                                                class="text-base text-left font-semibold">{{ $user->generalInformationInfo->first_name_en ?? '' }} {{ $user->generalInformationInfo->last_name_en ?? '' }}</div>
                                            <div class="font-normal text-left text-gray-500">{{ $user->mobile }}</div>
                                        </div>
                                    </th>
                                    <td class="px-6 py-4 text-center">
                                        @foreach($user->getRoleNames() as $v)
                                            <label class="badge badge-success">{{ $v }}</label>
                                        @endforeach
                                    </td>
                                    <td class="w-96 text-center">
                                        <div class="flex">
                                            @if($user->hasRole('Student'))
                                                @php
                                                    $studentInformation=StudentInformation::whereStudentId($user->id)->value('guardian');
                                                @endphp
                                                @if(isset($studentInformation))
                                                    <form id="search-user" action="{{ route('searchUser') }}"
                                                          method="get">
                                                        <input type="hidden" name="search-user-code"
                                                               value="{{ $studentInformation }}">
                                                        <button
                                                            class="min-w-max inline-flex font-medium text-white bg-teal-700 hover:bg-teal-800 focus:ring-4 focus:ring-teal-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-teal-600 dark:hover:bg-teal-700 focus:outline-none dark:focus:ring-teal-800 hover:underline">
                                                            <i class="las la-male" style="font-size: 20px"></i>
                                                            Show Parent
                                                        </button>
                                                    </form>
                                                @else
                                                    <div
                                                        class="bg-teal-100 border-t-4 border-teal-500 rounded-b w-36 text-teal-900 px-4 py-3 shadow-md mr-2"
                                                        role="alert">
                                                        <div class="flex">
                                                            <div>
                                                                Guardian not set!
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif
                                            <a href="{{ route('users.edit',$user->id) }}" type="button"
                                               class="min-w-max inline-flex font-medium text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 hover:underline">
                                                <i class="las la-pen-square" style="font-size: 20px"></i>
                                                Edit
                                            </a>
                                            <a href="/Documents/Show/{{$user->id}}" type="button"
                                               class="min-w-max inline-flex font-medium text-white bg-cyan-700 hover:bg-cyan-800 focus:ring-4 focus:ring-cyan-300 rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-cyan-600 dark:hover:bg-cyan-700 focus:outline-none dark:focus:ring-cyan-800 hover:underline">
                                                <i class="las la-file-image" style="font-size: 20px"></i>
                                                Documents</a>
                                            @can('impersonate')
                                                <a href="{{route('impersonate',$user->id)}}" type="button"
                                                   class="min-w-max inline-flex font-medium text-white bg-yellow-700 hover:bg-cyan-800 focus:ring-4 focus:ring-yellow-300 rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-yellow-600 dark:hover:bg-yellow-700 focus:outline-none dark:focus:ring-yellow-800 hover:underline">
                                                    <i class="las la-plane-departure" style="font-size: 20px"></i>
                                                    Impersonate</a>
                                            @endcan
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
        @if(!empty($data))
            <div class="pagination text-center">
                {{ $data->links() }}
            </div>
        @endif
    </div>
@endsection
