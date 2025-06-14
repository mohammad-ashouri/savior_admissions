@php use App\Models\Branch\ApplicationReservation; @endphp
<div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <h1 class="text-3xl font-semibold text-black dark:text-white ">All Students</h1>
        </div>
        <div class="grid grid-cols-1 gap-4 mb-4">
            <div class="flex justify-between">
                @can('students-create')
                    <div class="flex">
                        <a href="{{ route('Students.create') }}">
                            <button type="button"
                                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">

                                <svg class="w-6 h-6 mr-1" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                          d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                          clip-rule="evenodd"></path>
                                </svg>
                                New Student
                            </button>
                        </a>
                    </div>
                @endcan
            </div>
            <x-flash-messages/>

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
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-40 datatable0">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="p-4">
                                <div class="flex items-center">
                                    ID
                                </div>
                            </th>
                            <th scope="col" class="text-center">
                                First Name
                            </th>
                            <th scope="col" class="text-center">
                                Last Name
                            </th>
                            <th scope="col" class="text-center">
                                Birthdate
                            </th>
                            <th scope="col" class="text-center">
                                Gender
                            </th>
                            @if(!auth()->user()->hasExactRoles(['Parent']))
                                <th scope="col" class="text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class="text-center">
                                    Level
                                </th>
                            @endif
                            <th scope="col" class="text-center action">
                                Action
                            </th>
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($students as $student)
                            <tr
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="w-4 p-4">
                                    <div class="flex items-center">
                                        {{ $student->student_id }}
                                    </div>
                                </td>
                                <th scope="row"
                                    class=" items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div
                                            class="text-base font-semibold">{{ $student->studentInfo->generalInformationInfo->first_name_en }}</div>
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div
                                            class="text-base font-semibold">{{ $student->studentInfo->generalInformationInfo->last_name_en }}</div>
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div
                                            class="text-base font-semibold">{{ $student->studentInfo->generalInformationInfo->birthdate }}</div>
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div class="pl-3">
                                        <div
                                            class="text-base font-semibold">{{ $student->studentInfo->generalInformationInfo->gender }}</div>
                                    </div>
                                </th>
                                @if(!auth()->user()->hasExactRoles(['Parent']))
                                    <th scope="row"
                                        class=" items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $student->academicYearInfo->name }}</div>
                                        </div>
                                    </th>
                                    @php
                                        $getApplication=ApplicationReservation::with('levelInfo')
                                        ->join('applications','application_reservations.application_id','=','applications.id')
                                        ->join('application_timings','applications.application_timing_id','=','application_timings.id')
                                        ->where('application_timings.academic_year',$student->academicYearInfo->id)
                                        ->where('application_reservations.student_id',$student->student_id)
                                        ->where('application_reservations.payment_status',1)
                                        ->first()
                                        ;
                                    @endphp
                                    <th scope="row"
                                        class=" items-center text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div class="pl-3">
                                            <div
                                                class="text-base font-semibold">{{ $getApplication->levelInfo->name }}</div>
                                        </div>
                                    </th>
                                @endif
                                <td class="text-center">
                                    @can('students-show')
                                        <a href="{{ route('Students.show',$student->student_id) }}"
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
</div>
