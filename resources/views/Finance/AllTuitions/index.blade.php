@php use App\Models\Branch\ApplicationReservation;use App\Models\Branch\ApplicationTiming;use App\Models\Branch\StudentApplianceStatus;use App\Models\Catalogs\Level;use App\Models\GeneralInformation; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Tuitions</h1>
            </div>
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
                        @can('search-tuition-status')
                            <form id="search-user" method="get">
                                <div class="flex w-96">
                                    <div class="">
                                        <select id="academic_year" name="academic_year"
                                                class="font-normal block w-48 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                            <option value="" disabled selected>Select Academic Year...</option>
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
                                            <a href="/TuitionsStatus">
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
                        @endcan
                    </div>
                </div>
                @include('GeneralPages.errors.session.success')
                @include('GeneralPages.errors.session.error')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($parents))
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
                                    Please select an academic year!
                                </div>
                            </div>
                        </div>
                    @else
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 datatable">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 border text-center">
                                    #
                                </th>
                                <th scope="col" class="p-4 border text-center">
                                    Guardian
                                </th>
                                <th scope="col" class="p-4 border text-center">
                                    Student(s)
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @php
                                $sumTuition=$sumDebt=$sumPaid=0;
                            @endphp
                            @foreach($parents as $parent)
                                @php
                                    $parentInfo=GeneralInformation::whereUserId($parent)->first();
                                    $students=StudentApplianceStatus::with([
                                        'studentInformations',
                                    ])
                                    ->whereHas('studentInformations',function ($query) use ($parentInfo) {
                                            $query->whereGuardian($parentInfo->user_id);
                                        })
                                    ->whereAcademicYear($selectedAcademicYear)
                                    ->whereNotNull('tuition_payment_status')
                                    ->get();
                                @endphp
                                <tr
                                    class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-200 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                    <td class="w-2 border text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="w-40 p-2 border text-center">

                                        {{ $parentInfo->first_name_en }}
                                        {{ $parentInfo->last_name_en }}
                                        - {{ $parentInfo->user_id }}
                                    </td>
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900  dark:text-white">
                                        <div
                                            class="text-base font-semibold">
                                            @foreach($students as $student)
                                                @php
                                                    $application=ApplicationReservation::with([
                                                        'levelInfo',
                                                        'applicationInfo'=>function ($query) use ($academicYear) {
                                                            $query->with([
                                                                'applicationTimingInfo'=>function ($query) use ($academicYear) {
                                                                    $query->whereAcademicYear($academicYear);
                                                                }
                                                            ]);
                                                        }
                                                        ])->whereStudentId($student->student_id)->wherePaymentStatus(1)->latest()->first();
                                                @endphp
                                                <table
                                                    class="w-full mt-2 text-sm text-left text-gray-500 dark:text-gray-400 students">
                                                    <thead
                                                        class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                    <tr>
                                                        <th scope="col" class=" border text-center">
                                                            ID
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Information
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Grade
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Status
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Payment Type
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Total Tuition
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Total Paid
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Debt Balance
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Tuition Card
                                                        </th>
                                                        <th scope="col" class=" border text-center">
                                                            Action
                                                        </th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr>
                                                        <td class="w-4 p-2 border text-center">
                                                            {{ $student->student_id }}
                                                        </td>
                                                        <td class="w-56 p-2 border text-center">
                                                            {{ $student->studentInfo->generalInformationInfo->first_name_en }} {{ $student->studentInfo->generalInformationInfo->last_name_en }}
                                                        </td>
                                                        <td class="w-20 p-2 border text-center">
                                                            {{ $application->levelInfo->name }}
                                                        </td>
                                                        <td class="w-20 p-2 border text-center">
                                                            {{ $student->tuition_payment_status }}
                                                        </td>
                                                        <td class="w-56 p-2 border text-center">
                                                            @if($student->tuition_payment_status=='Paid')
                                                                @switch($student->tuitionInvoices->payment_type)
                                                                    @case('1')
                                                                        Full Payment
                                                                        @break
                                                                    @case('2')
                                                                        Two Installments
                                                                        @break
                                                                    @case('3')
                                                                        Four Installments
                                                                        @break
                                                                    @case('4')
                                                                        Full payment (With 30% Advance)
                                                                        @break
                                                                @endswitch
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="w-40 p-2 border text-center">
                                                            @if($student->tuitionInvoices!=null)
                                                                @php
                                                                    $totalTuition=0;
                                                                    foreach ($student->tuitionInvoices->invoiceDetails as $invoices){
                                                                        $totalTuition=$totalTuition+$invoices->amount;
                                                                    }
                                                                    $sumTuition+=$totalTuition;
                                                                @endphp
                                                                {{ number_format($totalTuition) }} IRR
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="w-40 p-2 border text-center">
                                                            @if($student->tuitionInvoices!=null)
                                                                @php
                                                                    $totalPaid=0;
                                                                    foreach ($student->tuitionInvoices->invoiceDetails as $invoices){
                                                                        if ($invoices->is_paid==0){ continue; }
                                                                        $totalPaid=$totalPaid+$invoices->amount;
                                                                    }
                                                                    $sumPaid+=$totalPaid;
                                                                @endphp
                                                                {{ number_format($totalPaid) }} IRR
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="w-40 p-2 border text-center">
                                                            @if($student->tuitionInvoices!=null)
                                                                @php
                                                                    $debtBalance=0;
                                                                    foreach ($student->tuitionInvoices->invoiceDetails as $invoices){
                                                                        if ($invoices->is_paid==1){ continue; }
                                                                        $debtBalance=$debtBalance+$invoices->amount;
                                                                    }
                                                                    $sumDebt+=$debtBalance;
                                                                @endphp
                                                                {{ number_format($debtBalance) }} IRR
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="w-56 p-2 border text-center">
                                                            @if($student->tuitionInvoices!=null)
                                                                <a target="_blank" href="{{ route('tuitionCard.en',$student->id) }}"
                                                                   type="button"
                                                                   class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                                    <div class="text-center">
                                                                        <i title="Click for view english tuition card "
                                                                           class="las la-address-card "
                                                                           style="font-size: 20px"></i>
                                                                        English
                                                                    </div>
                                                                </a>
                                                                <a target="_blank" href="{{ route('tuitionCard.fa',$student->id) }}"
                                                                   type="button"
                                                                   class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                                    <div class="text-center">
                                                                        <i title="Click for view persian tuition card "
                                                                           class="las la-address-card "
                                                                           style="font-size: 20px"></i>
                                                                        Persian
                                                                    </div>
                                                                </a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="w-56 p-2 border text-center">
                                                            <form
                                                                action="{{route('Evidences.showEvidence',$student->id)}}">
                                                                <button type="submit"
                                                                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                                    Uploaded Documents
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            @endforeach
                                        </div>
                                    </th>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 sum-table">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="p-4 border text-center">
                                    Total Tuition
                                </th>
                                <th scope="col" class="p-4 border text-center">
                                    Total Paid
                                </th>
                                <th scope="col" class="p-4 border text-center">
                                    Debt Balance
                                </th>
                            </tr>
                            <tr>
                                <th scope="row"
                                    class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div
                                        class="text-base font-semibold">
                                        {{ number_format($sumTuition) }} IRR
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div
                                        class="text-base font-semibold">
                                        {{ number_format($sumPaid) }} IRR
                                    </div>
                                </th>
                                <th scope="row"
                                    class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                    <div
                                        class="text-base font-semibold">
                                        {{ number_format($sumDebt) }} IRR
                                    </div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                    @endif
                </div>

            </div>
        </div>
    </div>
    <script>
        let table = new DataTable('.datatable', {
            "ordering": true,
            "searching": true,
            "paging": true,
            "info": true,
            "pageLength": 10,
            "lengthChange": true,
            responsive: true,
            "language": {
                "paginate": {
                    "first": "&laquo;&laquo;",
                    "last": "&raquo;&raquo;",
                    "previous": "&laquo;",
                    "next": "&raquo;"
                }
            },
        });
    </script>
@endsection
