@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 sm:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
            <div class="grid grid-cols-1 gap-4 mb-4 text-black dark:text-white">
                <h1 class="text-2xl font-medium"> Interview Form</h1>
            </div>
            @if (count($errors) > 0)
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
                            @foreach ($errors->all() as $error)
                                <p class="font-bold">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="lg:col-span-2 col-span-3 ">
                    <div class="bg-white dark:bg-gray-800 dark:text-white p-8 rounded-lg mb-4">
                        <form id="set-interview" method="post" action="/SetInterview">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div>
                                    <label
                                        class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Name And Surname: </label>
                                    {{ $interview->reservationInfo->studentInfo->generalInformationInfo->first_name_en }} {{ $interview->reservationInfo->studentInfo->generalInformationInfo->last_name_en }}
                                </div>
                                <div>
                                    <label
                                        class="block mb-2  font-bold text-gray-900 dark:text-white">
                                        Class: </label>
                                    {{ $interview->reservationInfo->levelInfo->name }}
                                </div>
                            </div>
                            @switch($interview->reservationInfo->levelInfo->name)
                                @case('Kindergarten 1')
                                @case('Kindergarten 2')
                                    @include('BranchInfo.Interviews.Forms.2024.Set.Preschool')
                                    @break
                                @default
                                    @include('BranchInfo.Interviews.Forms.2024.Set.G1-G12')
                            @endswitch
                            <div id="last-step" class="text-center hidden">
                                <div class="text-left mb-4">
                                    <p class="font-bold mt-4">
                                        Discount <u>(Check only when needed)</u>
                                    </p>
                                    <div class="overflow-x-auto">
                                        @if($discounts->isEmpty())
                                            <div class="bg-teal-100 border-t-4 border-teal-500 mt-4 rounded-b text-teal-900 px-4 py-3 shadow-md"
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
                                                        There is not any discount items to show! Please contact the financial manager of your department and raise the issue.
                                                        <br/>
                                                        Note: If the student needs to register a discount and you do not choose, your interview will no longer be editable.
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                            <thead
                                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            <tr>
                                                <th scope="col" class="p-4 text-center">
                                                    ID
                                                </th>
                                                <th scope="col" class="p-4 text-center">
                                                    Title
                                                </th>
                                                <th scope="col" class="p-4 text-center">
                                                    Percentage
                                                </th>
                                                <th scope="col" class="p-4 text-center">
                                                    Action
                                                </th>
                                            </tr>
                                            </thead>
                                            <tbody
                                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                            @foreach($discounts as $discount)
                                                <tr>
                                                    <td class="w-10 font-bold p-4 text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td class="w-1/3 font-bold p-4 text-center">
                                                        {{ $discount->name }}
                                                    </td>
                                                    <td class="font-bold p-4 text-center">
                                                        {{ $discount->percentage }}%
                                                    </td>
                                                    <td class="font-bold p-4 text-center">
                                                        <input class="discount-checks" type="checkbox"
                                                               value="{{ $discount->id }}"
                                                               name="discount[]">
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>
                                <input type="hidden" name="application_id" id="application_id"
                                       value="{{ $interview->id }}">
                                <button type="submit"
                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                    Submit
                                </button>
                                <a href="{{ url()->previous() }}">
                                    <button type="button"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                        Back
                                    </button>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
