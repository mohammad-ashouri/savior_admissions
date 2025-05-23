@php use App\Models\Branch\ApplianceConfirmationInformation;use App\Models\Branch\ApplicationReservation;use App\Models\User;use Illuminate\Support\Facades\Storage; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Students Status</h1>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">

                    <form id="search-student-appliance-statuses" action="{{ route('SearchStudentApplianceStatuses') }}"
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
                            @if(isset($_GET['academic_year']))
                                <div class="ml-3">
                                    <a href="/StudentStatuses">
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
                                    Appliance
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    IMG
                                </th>
                                <th scope="col" class="p-4 text-center">
                                    Guardian
                                </th>
                                <th scope="col" class=" text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class=" text-center">
                                    Information
                                </th>
                                <th scope="col" class=" text-center">
                                    First Name Fa
                                </th>
                                <th scope="col" class=" text-center">
                                    Last Name Fa
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
                                    Confirmation Data
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
                                    Document Approval Date
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
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-4 border text-center">
                                        {{ $student->id }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        @if(!empty($student->studentInfo->getPersonalPicture->src))
                                            <img
                                                src="{{ Storage::url($student->studentInfo->getPersonalPicture->src) }}"
                                                alt="Image not found!">
                                        @endif
                                    </td>
                                    @php
                                        @$guardian=User::whereId($student->studentInformations->guardian)->first();
                                    @endphp
                                    <td class="border text-center">
                                        @if($guardian)
                                            <button type="button" data-id="{{ $guardian->mobile }}"
                                                    data-info="{{ $guardian->generalInformationInfo?->first_name_en }} {{ $guardian->generalInformationInfo?->last_name_en }}"
                                                    class="show-guardian-mobile text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-3 py-1 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                <i class="las la-phone "></i>
                                            </button>
                                        @endif
                                    </td>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $student->academicYearInfo->name }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $student->student_id }} - {{ $student->studentInfo?->generalInformationInfo->first_name_en }} {{ $student->studentInfo?->generalInformationInfo->last_name_en }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $student->studentInfo?->generalInformationInfo->first_name_fa }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $student->studentInfo?->generalInformationInfo->last_name_fa }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $student->studentInfo?->generalInformationInfo->gender }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $student->interview_status }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @if(($student->interview_status=='Admitted' or $student->interview_status=='Rejected'))
                                            @php
                                                $applicationReservation=ApplicationReservation::with('levelInfo')
                                                ->whereHas('applicationInfo',function ($query) use ($student){
                                                    $query->whereHas('applicationTimingInfo',function ($query) use ($student){
                                                        $query->where('academic_year',$student->academic_year);
                                                    });
                                                })
                                                ->whereStudentId($student->student_id)
                                                ->wherePaymentStatus(1)
                                                ->latest()
                                                ->first();
                                            @endphp
                                            @if($applicationReservation != null)
                                                <a href="/ConfirmApplication/{{ $applicationReservation->application_id }}/{{$student->id}}"
                                                   type="button"
                                                   class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                    <i class="las la-eye mt-1"></i>
                                                </a>
                                            @endif
                                        @endif
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @if(($student->interview_status=='Admitted' or $student->interview_status=='Rejected'))
                                            @php
                                                $applianceConfirmationInformation=ApplianceConfirmationInformation::whereApplianceId($student->id)->latest()->first();
                                            @endphp
                                            @if($applianceConfirmationInformation != null)
                                                <button
                                                    data-appliance-confirmation-information-id="{{ $applianceConfirmationInformation->id }}"
                                                    type="button"
                                                    class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline get-appliance-confirmation-info">
                                                    <i class="las la-eye mt-1"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
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
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
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
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @if($student->documentSeconder)
                                            {{ $student->documentSeconder->generalInformationInfo->first_name_en }} {{ $student->documentSeconder->generalInformationInfo->last_name_en }}
                                        @else
                                            -
                                        @endif
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $student->date_of_document_approval }}
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
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
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
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
                                    </th>
                                    <th scope="row"
                                        class=" items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
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
            {{--            <div class="pagination text-center">--}}
            {{--                {{ $students->links() }}--}}
            {{--            </div>--}}
        @endif
    </div>
@endsection
