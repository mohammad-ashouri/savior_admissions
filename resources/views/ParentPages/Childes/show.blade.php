@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Child's Details </h1>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-2 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <div class="grid gap-6 mb-6 md:grid-cols-4">
                            <div>
                                <p class="font-bold">First Name: </p> {{ $generalInformations->first_name }}
                            </div>
                            <div>
                                <p class="font-bold">Last Name: </p> {{ $generalInformations->last_name }}
                            </div>
                            <div>
                                <p class="font-bold">Birthdate: </p> {{ $generalInformations->birthdate }}
                            </div>
                            <div>
                                <p class="font-bold">Gender: </p> {{ $generalInformations->gender }}
                            </div>
                            <div>
                                <p class="font-bold">
                                    Birthplace: </p> {{ $generalInformations->birthplaceInfo->en_short_name }}
                            </div>
                            <div>
                                <p class="font-bold">
                                    Nationality: </p> {{ $studentInformations->nationalityInfo->nationality }}
                            </div>
                            <div>
                                <p class="font-bold">Identification
                                    Type: </p> {{ $studentInformations->identificationTypeInfo->name }}
                            </div>
                            <div>
                                <p class="font-bold">Identification
                                    Code: </p> {{ $studentInformations->current_identification_code }}
                            </div>
                        </div>
                        <a href="{{ url()->previous() }}">
                            <button type="button"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Back
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-4 rounded-lg dark:border-gray-700 ">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Extra Details </h1>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-2 ">
                    <div class="general-info bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">

                        @if(isset($studentInformations->extraInformations) and !$studentInformations->extraInformations->isEmpty())
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead
                                    class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            #
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            Title
                                        </div>
                                    </th>
                                    <th scope="col" class="p-4">
                                        <div class="flex items-center">
                                            Description
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($studentInformations->extraInformations as $extraInformation)
                                    <tr
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                {{ $loop->iteration }}
                                            </div>
                                        </td>
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                {{ $extraInformation->name }}
                                            </div>
                                        </td>
                                        <td class="w-4 p-4">
                                            <div class="flex items-center">
                                                {{ $extraInformation->description }}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                @endforeach
                            </table>
                        @else
                            <div
                                class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md"
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
                                        <p class="font-bold">There is not any extra informations!</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
