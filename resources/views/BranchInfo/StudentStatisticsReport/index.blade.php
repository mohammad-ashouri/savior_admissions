@php use App\Models\Branch\Evidence; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">Student Statistics Report</h1>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">

                    <form id="search-student-appliance-statuses" action="{{ route('SearchStudentStatisticsReport') }}"
                          method="get">
                        <div class="flex w-full">
                            <div class="mr-3">
                                <select id="academic_year" name="academic_year"
                                        class="bg-gray-50 border p-3 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    @foreach($academicYears as $academicYear)
                                        <option
                                            @if(isset($_GET['academic_year']) and $_GET['academic_year']==$academicYear->id) selected
                                            @endif value="{{$academicYear->id}}">{{$academicYear->name}}</option>
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
                            @if(isset($_GET['student_id']))
                                <div class="ml-3">
                                    <a href="{{ route('StudentStatisticsReport') }}">
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
                    <button type="button" id="export-details"
                            class="4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center mr-2  text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                        <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                  d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        Export
                    </button>
                    <script>
                        function spinner(text = 'Please Wait!') {
                            $('#spinner-text').text('Please Wait');

                            if ($('#spinner').hasClass('hidden')) {
                                $('#spinner').removeClass('hidden');
                            } else {
                                $('#spinner').addClass('hidden');
                            }
                        }

                    </script>
                </div>
                @include('GeneralPages.errors.session.success')
                @include('GeneralPages.errors.session.error')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($students))
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
                                    There is not any student informations to show!
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 text-center">
                                    Grade
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Student Id
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Full Name
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Father's Name
                                </th>
                                <th scope="col" class=" text-center">
                                    Mother's Name
                                </th>
                                <th scope="col" class=" text-center">
                                    D.O.B
                                </th>
                                <th scope="col" class=" text-center">
                                    P.O.B
                                </th>
                                <th scope="col" class=" text-center">
                                    Passport Number
                                </th>
                                <th scope="col" class=" text-center">
                                    Nationality
                                </th>
                                <th scope="col" class=" text-center">
                                    Visa Number
                                </th>
                                <th scope="col" class=" text-center">
                                    D.O.A
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($students as $student)
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4 border text-center">
                                        {{ $student->levelInfo->levelInfo->name }}
                                    </td>
                                    <td class="w-4 border text-center">
                                        {{ $student->student_id }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $student->studentInfo->generalInformationInfo->first_name_en }} {{ $student->studentInfo->generalInformationInfo->last_name_en }}
                                    </td>
                                    @php
                                        $evidencesInfo=Evidence::where('appliance_id',$student->id)->latest()->first();
                                        $evidencesInfo=json_decode($evidencesInfo->informations,true);
                                    @endphp
                                    <td class="w-4 p-2 border text-center">
                                        {{ $evidencesInfo['father_name'] }} {{ $evidencesInfo['father_family'] }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $evidencesInfo['mother_name'] }} {{ $evidencesInfo['mother_family'] }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $student->studentInfo->generalInformationInfo?->birthdate }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $student->studentInfo->generalInformationInfo->birthplaceInfo?->en_short_name }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $evidencesInfo['student_passport_number'] }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $student->studentInfo->generalInformationInfo->nationalityInfo?->nationality }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $evidencesInfo['student_iranian_visa'] }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $student->updated_at }}
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
