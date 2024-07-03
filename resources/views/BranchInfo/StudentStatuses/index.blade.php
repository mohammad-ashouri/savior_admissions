@php use App\Models\Branch\ApplicationReservation; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Students Status</h1>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">

                    <form id="search-user" action="{{ route('SearchReservationInvoices') }}" method="get">
                        <div class="flex w-full">
                            <div class="mr-3">
                                <label for="academic_year"
                                       class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Academic Year</label>
                                <select id="academic_year" name="academic_year"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option value="" disabled selected>Select Academic Year...</option>
                                    @foreach($academicYears as $academicYear)
                                        <option
                                            @if(isset($_GET['academic_year']) and $_GET['academic_year']==$academicYear->id) selected
                                            @endif value="{{$academicYear->id}}">{{$academicYear->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
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
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 text-center">
                                    Appliance ID
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Student ID
                                </th>
                                <th scope="col" class=" text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class=" text-center">
                                    Information
                                </th>
                                <th scope="col" class=" text-center">
                                    Gender
                                </th>
                                <th scope="col" class=" text-center">
                                    Interview Status
                                </th>
                                <th scope="col" class=" text-center">
                                    Interview Form
                                </th>
                                <th scope="col" class=" text-center">
                                    Document Upload Status
                                </th>
                                <th scope="col" class=" text-center">
                                    Document Approval Status
                                </th>
                                <th scope="col" class=" text-center">
                                    Document Approval Seconder
                                </th>
                                <th scope="col" class=" text-center">
                                    Tuition Payment Status
                                </th>
                                <th scope="col" class=" text-center">
                                    Approval Status
                                </th>
                                <th scope="col" class=" text-center">
                                    Description
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($students as $student)
                                <tr
                                    class="bg-white border dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    <td class="w-4 border text-center">
                                        {{ $student->id }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $student->student_id }}
                                    </td>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">{{ $student->academicYearInfo->name }}</div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">{{ $student->studentInfo->generalInformationInfo->first_name_en }} {{ $student->studentInfo->generalInformationInfo->last_name_en }}</div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">{{ $student->studentInfo->generalInformationInfo->gender }}</div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            {{ $student->interview_status }}</div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @if(($student->interview_status=='Admitted' or $student->interview_status=='Rejected'))
                                            @php
                                                $applicationReservation=ApplicationReservation::with('levelInfo')->where('student_id',$student->student_id)->where('payment_status',1)->latest()->first();
                                            @endphp
                                            @if($applicationReservation != null)
                                                <a href="/ConfirmApplication/{{ $applicationReservation->application_id }}/{{$student->id}}"
                                                   type="button"
                                                   class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                    <i class="las la-eye mt-1 mr-1"></i>
                                                    Show
                                                </a>
                                            @endif
                                        @endif
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @switch($student->documents_uploaded)
                                                @case('0')
                                                    Pending For Upload
                                                    @break
                                                @case('1')
                                                    Admitted
                                                    @break
                                                @case('2')
                                                    Pending For Review
                                                    @break
                                                @case('3')
                                                    Rejected
                                                    @break
                                                @default
                                                    -
                                            @endswitch
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @switch($student->documents_uploaded_approval)
                                                @case(1)
                                                    Approved
                                                    @break
                                                @case(2)
                                                    Rejected
                                                    @break
                                                @default
                                                    -
                                            @endswitch
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @if($student->documentSeconder)
                                                {{ $student->documentSeconder->generalInformationInfo->first_name_en }} {{ $student->documentSeconder->generalInformationInfo->last_name_en }}
                                            @else
                                                -
                                            @endif
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @switch($student->tuition_payment_status)
                                                @case('Not Paid')
                                                    Not Paid Yet!
                                                    @break
                                                @case('Paid')
                                                    Paid
                                                    @break
                                                @case('Pending')
                                                    Pending For Pay
                                                    @break
                                                @case('Pending For Review')
                                                    Pending For Review
                                                    @break
                                                @default
                                                    -
                                            @endswitch
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @switch($student->approval_status)
                                                @case(1)
                                                    Approved
                                                    @break
                                                @case(2)
                                                    Rejected
                                                    @break
                                                @default
                                                    -
                                            @endswitch
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            {{$student->description}}
                                            @if($student->documents_uploaded_approval=='3' and $student->documents_uploaded_approval=='3')
                                                <form class="mt-2 extension_document" method="post"
                                                      action="{{route('Evidences.extensionOfDocumentUpload')}}">
                                                    @csrf
                                                    <input type="hidden" value="{{ $student->id }}" name="appliance_id">
                                                    <button type="submit"
                                                            class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                        Extension Of Document Upload
                                                    </button>
                                                </form>
                                            @endif
                                            @if($student->documents_uploaded_seconder!=null)
                                                <form class="mt-2" method="get"
                                                      action="{{route('Evidences.showEvidence',$student->id)}}">
                                                    <button type="submit"
                                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        Uploaded Documents
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

            </div>
        </div>
        @if(!empty($students))
            <div class="pagination text-center">
                {{ $students->links() }}
            </div>
        @endif
    </div>
@endsection
