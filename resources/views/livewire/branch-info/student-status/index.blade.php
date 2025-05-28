@php use App\Models\Branch\ApplianceConfirmationInformation;use App\Models\Branch\ApplicationReservation;use App\Models\User; @endphp
<div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
        <div class="grid grid-cols-1 gap-4 mb-4">
            <h1 class="text-3xl font-semibold text-black dark:text-white ">All Students Status</h1>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-4">
            <div class="flex justify-between">

                <form wire:submit.prevent="search">
                    <div class="flex w-full">
                        <div class="mr-3">
                            <select wire:model="academic_year"
                                    class="bg-gray-50 border p-3 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option value="" selected disabled>Choose academic year...</option>
                                @foreach($academic_years as $academicYear)
                                    <option
                                        @if(isset($_GET['academic_year']) and $_GET['academic_year']==$academicYear->id) selected
                                        @endif value="{{$academicYear->id}}">{{$academicYear->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="items-center justify-center">
                            <button type="submit" wire:loading.remove wire:target="search"
                                    class="text-white bg-blue-700 hover:bg-blue-800 w-full h-full focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <i class="fas fa-search mr-2" aria-hidden="true"></i>
                                Filter
                            </button>
                            <div class="text-center ">
                                <p class="font-bold text-blue-600 text-center" wire:target="search" wire:loading>Please
                                    Wait!</p>
                            </div>
                        </div>
                        @if($academic_year)
                            <div class="ml-3">
                                <button type="button" wire:loading.remove wire:click="$reset"
                                        class="text-white bg-red-700 hover:bg-red-800 w-full h-full focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm pl-2 px-3 py-2.5 text-center inline-flex items-center mr-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 RemoveFilter">
                                    <i class="fas fa-remove mr-2" aria-hidden="true"></i>
                                    Remove
                                </button>
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
                            <tr wire:key="appliance-id-{{ $student->id }}"
                                class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
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
                                    {{ $student->student_id }}
                                    - {{ $student->studentInfo?->generalInformationInfo->first_name_en }} {{ $student->studentInfo?->generalInformationInfo->last_name_en }}
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
                                        @case(3)
                                            Dropout
                                            @break
                                        @default
                                            -
                                    @endswitch
                                </th>
                                <th scope="row"
                                    class="border text-center text-gray-900 whitespace-nowrap dark:text-white w-auto min-w-[300px]">
                                    <div class="flex flex-col gap-2 p-2">
                                        <div class="text-sm break-words">{{$student->description}}</div>
                                        <div class="flex flex-wrap gap-2 justify-center">
                                            @if($student->documents_uploaded_approval=='3' and $student->documents_uploaded_approval=='3')
                                                <form class="extension_document" method="post"
                                                      action="{{route('Evidences.extensionOfDocumentUpload')}}">
                                                    @csrf
                                                    <input type="hidden" value="{{ $student->id }}" name="appliance_id">
                                                    <button type="submit"
                                                            class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 whitespace-nowrap">
                                                        Extension Of Document Upload
                                                    </button>
                                                </form>
                                            @endif
                                            @if($student->documents_uploaded_seconder!=null)
                                                <form method="get"
                                                      action="{{route('Evidences.showEvidence',$student->id)}}">
                                                    <button type="submit"
                                                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 whitespace-nowrap">
                                                        Uploaded Documents
                                                    </button>
                                                </form>
                                            @endif

                                            @if($student->approval_status==1 and auth()->user()->can('dropout'))
                                                <button wire:click="openDropoutModal({{ $student->id }})" type="button"
                                                        wire:loading.remove
                                                        wire:target="openDropoutModal({{ $student->id }})"
                                                        class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 whitespace-nowrap">
                                                    Dropout
                                                </button>
                                                <div class="text-center ">
                                                    <p class="font-bold text-blue-600 text-center"
                                                       wire:target="openDropoutModal({{ $student->id }})" wire:loading>
                                                        Please
                                                        Wait!</p>
                                                </div>
                                            @endif
                                        </div>
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

    @if($this->selected_student)
        <form wire:submit.prevent="dropout">
            <div id="dropoutModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
                 role="dialog"
                 aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="sm:flex sm:items-start">
                                <div
                                    class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                    <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                        Are you sure you want to mark this student as dropout?
                                    </h3>
                                    <h3 class="text-sm leading-6 font-medium text-gray-900" id="modal-title">
                                        Student: {{ $selected_student->student_id }}
                                        - {{ $selected_student->studentInfo->generalInformationInfo->first_name_en }} {{ $selected_student->studentInfo->generalInformationInfo->last_name_en }}
                                    </h3>
                                    <div class="mt-4">
                                        <div class="mb-4">
                                            <label for="dropout_description"
                                                   class="block text-sm font-medium text-gray-700">Dropout
                                                Description</label>
                                            <textarea wire:model="description" id="description" rows="3"
                                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                                      placeholder="Please provide a detailed explanation for the dropout..."></textarea>
                                            <x-input-error :messages="$errors->get('description')"/>
                                        </div>
                                        <div class="mb-4">
                                            <label for="dropout_file" class="block text-sm font-medium text-gray-700">Document
                                                (optional)</label>
                                            <x-filepond::upload wire:model="files"
                                                                :allowMultiple="false"
                                                                :instantUpload="true"
                                                                server-headers='@json(["X-CSRF-TOKEN" => csrf_token()])'
                                                                :chunkSize="2000000"
                                                                :accept="'application/pdf,image/jpg,image/bmp,image/jpeg,image/png'"/>
                                            <x-input-error :messages="$errors->get('files')"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button wire:click="dropout" type="button" wire:target="dropout" wire:loading.remove
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Confirm Dropout
                            </button>
                            <div class="text-center ml-3">
                                <p class="font-bold text-blue-600 text-center" wire:target="dropout" wire:loading>Please
                                    Wait!</p>
                            </div>
                            <button wire:click="closeDropoutModal" type="button" wire:target="dropout"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    @endif
</div>

<!-- Dropout Modal -->

<script>
    let table;

    function initializeDataTable() {
        if (table) {
            table.destroy();
        }

        table = new DataTable('.datatable', {
            "ordering": true,
            "searching": true,
            "paging": true,
            "info": true,
            "pageLength": 25,
            "lengthChange": true,
            select: false,
            colReorder: true,
            responsive: true,
            "language": {
                "paginate": {
                    "first": "&laquo;&laquo;",
                    "last": "&raquo;&raquo;",
                    "previous": "&laquo;",
                    "next": "&raquo;"
                }
            },
            dom: '<"top"lfB>rt<"bottom"ip><"clear">',
            buttons: [
                'copy',
                {
                    extend: 'excelHtml5',
                    text: 'Excel',
                    title: document.title,
                    filename: function () {
                        let date = new Date();
                        let formattedDate = date.getFullYear() + '-' +
                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            date.getDate().toString().padStart(2, '0') + '_' +
                            date.getHours().toString().padStart(2, '0') + '-' +
                            date.getMinutes().toString().padStart(2, '0');
                        return document.title + '_' + formattedDate;
                    }, exportOptions: {
                        columns: ':not(.action)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF (Portrait)',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    title: 'Report (Portrait)',
                    filename: function () {
                        let date = new Date();
                        let formattedDate = date.getFullYear() + '-' +
                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            date.getDate().toString().padStart(2, '0') + '_' +
                            date.getHours().toString().padStart(2, '0') + '-' +
                            date.getMinutes().toString().padStart(2, '0');
                        return document.title + '_' + formattedDate;
                    },
                    customize: function (doc) {
                        const pageSize = doc.pageSize;
                        doc.watermark = {
                            text: 'Savior',
                            color: 'grey',
                            fontSize: 60,
                            width: 300,
                            height: 300,
                            opacity: 0.3,
                            absolutePosition: {
                                x: (pageSize.width - 300) / 2,
                                y: (pageSize.height - 300) / 2
                            }
                        };
                    }, exportOptions: {
                        columns: ':not(.action)'
                    }
                },
                {
                    extend: 'pdfHtml5',
                    text: 'PDF (Landscape)',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Report (Landscape)',
                    filename: function () {
                        let date = new Date();
                        let formattedDate = date.getFullYear() + '-' +
                            (date.getMonth() + 1).toString().padStart(2, '0') + '-' +
                            date.getDate().toString().padStart(2, '0') + '_' +
                            date.getHours().toString().padStart(2, '0') + '-' +
                            date.getMinutes().toString().padStart(2, '0');
                        return document.title + '_' + formattedDate;
                    },
                    customize: function (doc) {
                        const pageSize = doc.pageSize;
                        doc.watermark = {
                            text: 'Savior',
                            color: 'grey',
                            fontSize: 60,
                            width: 300,
                            height: 300,
                            opacity: 0.3,
                            absolutePosition: {
                                x: (pageSize.width - 300) / 2,
                                y: (pageSize.height - 300) / 2
                            }
                        };
                    }, exportOptions: {
                        columns: ':not(.action)'
                    }
                },
                'print',
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(.action)'
                    }
                }
            ]
        });

        $('.datatable thead').prepend('<tr class="filter-row"></tr>');

        table.columns().every(function () {
            let column = this;
            let header = $(column.header());

            let select = $('<th><select><option value="">All</option></select></th>')
                .appendTo('.datatable thead tr.filter-row')
                .find('select')
                .on('change', function () {
                    let val = $.fn.DataTable.util.escapeRegex($(this).val());
                    column.search(val ? '^' + val + '$' : '', true, false).draw();
                });

            if (header.hasClass('nofilter')) {
                return;
            }

            let uniqueData = [];
            column.data().unique().each(function (d, j) {
                if (d) {
                    let cleanData = $('<div>').html(d).text().trim();
                    if (cleanData && !uniqueData.includes(cleanData)) {
                        uniqueData.push(cleanData);
                    }
                }
            });

            uniqueData.sort(function (a, b) {
                let numA = parseInt(a.match(/\d+/)) || 0;
                let numB = parseInt(b.match(/\d+/)) || 0;
                if (numA === numB) {
                    return a.localeCompare(b);
                }
                return numA - numB;
            });

            uniqueData.forEach(function (cleanData) {
                select.append('<option value="' + cleanData + '">' + cleanData + '</option>');
            });
        });
    }

    document.addEventListener("DOMContentLoaded", () => {
        Livewire.on('initialize-data-table', function () {
            setTimeout(initializeDataTable, 100);
        })
    });

</script>

