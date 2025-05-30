@php use App\Models\Branch\ApplicationTiming;use App\Models\Catalogs\Level;use App\Models\Finance\Tuition;use App\Models\Finance\TuitionInvoices;use Carbon\Carbon;use Morilog\Jalali\Jalalian; @endphp
@extends('Layouts.panel')

@section('content')
    <div id="content" class="p-4 md:ml-14 transition-all duration-300 bg-light-theme-color-base dark:bg-gray-800">
        <div class="p-4 rounded-lg dark:border-gray-700 mt-20 ">
            <div class="grid grid-cols-1 gap-4 mb-4">
                <h1 class="text-3xl font-semibold text-black dark:text-white ">All Tuitions Status</h1>
            </div>

            <div class="grid grid-cols-1 gap-4 mb-4">
                <div class="flex justify-between">
                    <div class="relative hidden md:block w-96">
{{--                        @can('search-tuition-status')--}}
                            <form id="search-user" action="{{ route('SearchTuitionStatus') }}" method="get">
                                <div class="flex w-96">
                                    <div class="">
                                        <select id="academic_year" name="academic_year"
                                                class="font-normal block w-48 p-3 mr-4 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
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
{{--                        @endcan--}}
                    </div>
                </div>
                @include('GeneralPages.errors.session.success')
                @include('GeneralPages.errors.session.error')

                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    @if(empty($students) or !isset($_GET['academic_year']))
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
                                    There is not any tuition informations to show!
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
                                    Appliance ID
                                </th>
                                <th scope="col" class="p-4 border text-center">
                                    Student ID
                                </th>
                                <th scope="col" class=" border text-center">
                                    Academic Year
                                </th>
                                <th scope="col" class=" border text-center">
                                    Information
                                </th>
                                <th scope="col" class=" border text-center">
                                    Grade
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
                                    Date (Full Payment)
                                </th>
                                <th scope="col" class=" border text-center">
                                    Advance
                                </th>
                                <th scope="col" class=" border text-center">
                                    Date
                                </th>
                                <th scope="col" class=" border text-center">
                                    Installment 1
                                </th>
                                <th scope="col" class=" border text-center">
                                    Date
                                </th>
                                <th scope="col" class=" border text-center">
                                    Installment 2
                                </th>
                                <th scope="col" class=" border text-center">
                                    Date
                                </th>
                                <th scope="col" class=" border text-center">
                                    Installment 3
                                </th>
                                <th scope="col" class=" border text-center">
                                    Date
                                </th>
                                <th scope="col" class=" border text-center">
                                    Installment 4
                                </th>
                                <th scope="col" class=" border text-center">
                                    Date
                                </th>
                                <th scope="col" class=" border text-center">
                                    Installment 5
                                </th>
                                <th scope="col" class=" border text-center">
                                    Date
                                </th>
                                <th scope="col" class=" border text-center">
                                    Installment 6
                                </th>
                                <th scope="col" class=" border text-center">
                                    Date
                                </th>
                                <th scope="col" class=" border text-center">
                                    Installment 7
                                </th>
                                <th scope="col" class=" border text-center">
                                    Date
                                </th>
                                <th scope="col" class=" border text-center action">
                                    Tuition Card
                                </th>
                                <th scope="col" class=" border text-center action">
                                    Action
                                </th>
                            </tr>
                            </thead>

                            <tbody>
                            @php
                                $allTotalTuitions=$allIsPaid=$allIsNotPaid=0;
                            @endphp
                            @foreach($students as $student)
                                @php
                                    $applicationInformation=ApplicationTiming::join('applications','application_timings.id','=','applications.application_timing_id')
                                        ->join('application_reservations','applications.id','=','application_reservations.application_id')
                                        ->where('application_reservations.student_id',$student->student_id)
                                        ->where('application_timings.academic_year',$student->academic_year)->latest('application_reservations.id')->first();
                                    $levelInfo=Level::find($applicationInformation->level);
                                    $tuitionInfo=Tuition::with(['allTuitions'=>function ($query) use ($levelInfo){
                                        $query->whereLevel($levelInfo->id);
                                    }])->whereAcademicYear($student->academicYearInfo->id)->first();
                                    $threeInstallmentTuitionInfo=json_decode($tuitionInfo->allTuitions->first()->three_installment_payment,true);
                                    $sevenInstallmentTuitionInfo=json_decode($tuitionInfo->allTuitions->first()->seven_installment_payment,true);
                                    $currentDate = now()->format('Y-m-d');
                                @endphp
                                <tr class="odd:bg-white even:bg-gray-300 bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <td class="w-2 border text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="w-2 border text-center">
                                        {{ $student->id }}
                                    </td>
                                    <td class="w-4 p-2 border text-center">
                                        {{ $student->student_id }}
                                    </td>
                                    {{--                                    Academic year--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900  dark:text-white">
                                        {{ $student->academicYearInfo->name }}
                                    </th>
                                    {{--                                    Information--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ $student->studentInfo->generalInformationInfo->first_name_en }} {{ $student->studentInfo->generalInformationInfo->last_name_en }}
                                    </th>
                                    {{--                                    Grade--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        {{ trim($levelInfo->name) }}
                                    </th>
                                    {{--                                    Payment type--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('1')
                                                Full Payment
                                                @break
                                            @case('5')
                                                Three Installments
                                                @break
                                            @case('6')
                                                Seven Installments
                                                @break
                                            @case('4')
                                                Full payment (With 30% Advance)
                                                @break
                                        @endswitch
                                    </th>
                                    {{-- Total tuition --}}
                                    <th scope="row"
                                        class="p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @if($student->tuitionInvoices != null)
                                            @php
                                                $totalTuition = 0;
                                                $totalPaid = 0;
                                                foreach ($student->tuitionInvoices->invoiceDetails as $invoices) {
                                                    if ($invoices->is_paid!=0 and $invoices->is_paid!=1){
                                                        continue;
                                                    }
                                                    $totalTuition += $invoices->amount;
                                                    if ($invoices->is_paid==1) {
                                                        $totalPaid += $invoices->amount;
                                                    }elseif ($invoices->is_paid==0) {
                                                        $allIsNotPaid += $invoices->amount;
                                                    }
                                                }
                                                    $allTotalTuitions += $totalTuition;
                                                    $allIsPaid += $totalPaid;

                                                $paidPercentage = $totalTuition > 0 ? ($totalPaid / $totalTuition) * 100 : 0;
                                            @endphp

                                            {{ number_format($totalTuition) }} IRR

                                            <div style="display: flex; width: 100%; height: 10px; margin-top: 5px;">
                                                <div style="width: {{ $paidPercentage }}%;" class="bg-green-500"></div>
                                                <div style="width: {{ 100 - $paidPercentage }}%;"
                                                     class="bg-red-500"></div>
                                            </div>
                                        @endif
                                    </th>
                                    {{--                                    Total paid--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @if($student->tuitionInvoices!=null)
                                            @php
                                                $totalPaid=0;
                                                foreach ($student->tuitionInvoices->invoiceDetails as $invoices){
                                                    if ($invoices->is_paid!=1){ continue; }
                                                    $totalPaid=$totalPaid+$invoices->amount;
                                                }
                                            @endphp
                                            {{ number_format($totalPaid) }} IRR
                                        @endif
                                    </th>

                                    {{--                                    Debt balance--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @if($student->tuitionInvoices!=null)
                                            @php
                                                $debtBalance=0;
                                                foreach ($student->tuitionInvoices->invoiceDetails as $invoices){
                                                    if ($invoices->is_paid!=0){ continue; }
                                                    $debtBalance=$debtBalance+$invoices->amount;
                                                }
                                            @endphp
                                            {{ number_format($debtBalance) }} IRR
                                        @endif
                                    </th>
                                    {{-- Date (Full Payment) --}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('1')
                                                @php
                                                    $filteredRowFP = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Full Payment';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRowFP->date_of_payment) ? $filteredRowFP->date_of_payment : '-' }}
                                    </th>
                                    {{--                                    Advance--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('1')
                                                -
                                                @break
                                            @case('4')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Full Payment With Advance';
                                                    });
                                                @endphp
                                                {{ number_format($filteredRow->amount) }} IRR
                                                @break
                                            @case('5')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment Advance'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return !isset($description['tuition_type']) || $description['tuition_type'] == 'Seven Installment Advance';
                                                    });
                                                @endphp
                                                {{ number_format($filteredRow->amount) }} IRR
                                                @break
                                        @endswitch
                                    </th>
                                    {{--                                    Advance - Date--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('4')
                                                @php
                                                    $filteredRowA = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Full Payment With Advance';
                                                    });
                                                @endphp
                                                @break
                                            @case('5')
                                                @php
                                                    $filteredRowA = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment Advance'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRowA = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return !isset($description['tuition_type']) || $description['tuition_type'] == 'Seven Installment Advance';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRowA->date_of_payment) ? $filteredRowA->date_of_payment : '-' }}
                                    </th>
                                    {{--                                    Installment 1--}}
                                    <th scope="row"
                                        class="
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('5')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment - Installment 1'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                              @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $threeInstallmentTuitionInfo['date_of_installment1_three'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                            @case('4')
                                                @php
                                                    if (!function_exists('getEndOfShahrivarInGregorian')) {
                                                        function getEndOfShahrivarInGregorian($gregorianDate) {
                                                            $inputDate = Carbon::createFromFormat('Y-m-d', $gregorianDate);

                                                            $jalaliYear = Jalalian::fromCarbon($inputDate)->getYear();
                                                            $endOfShahrivar = Jalalian::fromFormat('Y-m-d', "$jalaliYear-06-31")->toCarbon();

                                                            return $endOfShahrivar->toDateString();
                                                        }
                                                    }
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Full Payment With Advance - Installment'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });

                                                    $currentDate = Carbon::now()->format('Y-m-d');
                                                @endphp

                                                @if($filteredRow->is_paid == 1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid == 0 && getEndOfShahrivarInGregorian($threeInstallmentTuitionInfo['date_of_installment1_three']) < $currentDate)
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 1'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $sevenInstallmentTuitionInfo['date_of_installment1_seven'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                        @endswitch
                                         p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('1')
                                                -
                                                @break
                                            @case('4')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Full Payment With Advance - Installment';
                                                    });
                                                @endphp
                                                {{ number_format($filteredRow->amount) }} IRR
                                                @break
                                            @case('5')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment - Installment 1'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 1'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                        @endswitch
                                    </th>
                                    {{--                                    Installment 1 - Date--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('4')
                                                @php
                                                    $filteredRow1 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Full Payment With Advance - Installment';
                                                    });
                                                @endphp
                                                @break
                                            @case('5')
                                                @php
                                                    $filteredRow1 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment - Installment 1';
                                                    });
                                                @endphp
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow1 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 1';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRow1->date_of_payment) ? $filteredRow1->date_of_payment : '-' }}
                                    </th>
                                    {{--                                    Installment 2--}}
                                    <th scope="row"
                                        class="
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('5')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment - Installment 2'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $threeInstallmentTuitionInfo['date_of_installment2_three'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 2'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $sevenInstallmentTuitionInfo['date_of_installment2_seven'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                        @endswitch
                                         p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('1')
                                                -
                                                @break
                                            @case('5')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment - Installment 2'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 2'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @default
                                                -
                                        @endswitch
                                    </th>
                                    {{--                                    Installment 2 - Date--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('5')
                                                @php
                                                    $filteredRow2 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment - Installment 2';
                                                        return 0;
                                                    });
                                                @endphp
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow2 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 2';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRow2->date_of_payment) ? $filteredRow2->date_of_payment : '-' }}
                                    </th>
                                    {{--                                    Installment 3--}}
                                    <th scope="row"
                                        class="
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('5')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Three Installment - Installment 3'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $threeInstallmentTuitionInfo['date_of_installment3_three'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 3'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $sevenInstallmentTuitionInfo['date_of_installment3_seven'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                        @endswitch
                                         p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('5')
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @case('6')
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @default
                                                -
                                        @endswitch
                                    </th>
                                    {{--                                    Installment 3 - Date--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('5')
                                                @php
                                                    $filteredRow3 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return !isset($description['tuition_type']) || $description['tuition_type'] == 'Three Installment - Installment 3';
                                                    });
                                                @endphp
                                                @break
                                            @case('6')
                                                @php
                                                    $filteredRow3 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return !isset($description['tuition_type']) || $description['tuition_type'] == 'Seven Installment - Installment 3';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRow3->date_of_payment) ? $filteredRow3->date_of_payment : '-' }}
                                    </th>
                                    {{--                                    Installment 4--}}
                                    <th scope="row"
                                        class="
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 4'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $sevenInstallmentTuitionInfo['date_of_installment4_seven'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                        @endswitch
                                         p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @default
                                                -
                                        @endswitch
                                    </th>
                                    {{--                                    Installment 4 - Date--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                @php
                                                    $filteredRow4 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return !isset($description['tuition_type']) || $description['tuition_type'] == 'Seven Installment - Installment 4';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRow4->date_of_payment) ? $filteredRow4->date_of_payment : '-' }}
                                    </th>
                                    {{--                                    Installment 5--}}
                                    <th scope="row"
                                        class="
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 5'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $sevenInstallmentTuitionInfo['date_of_installment5_seven'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                        @endswitch
                                         p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @default
                                                -
                                        @endswitch
                                    </th>
                                    {{--                                    Installment 5 - Date--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                @php
                                                    $filteredRow4 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return !isset($description['tuition_type']) || $description['tuition_type'] == 'Seven Installment - Installment 5';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRow4->date_of_payment) ? $filteredRow4->date_of_payment : '-' }}
                                    </th>
                                    {{--                                    Installment 6--}}
                                    <th scope="row"
                                        class="
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 6'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $sevenInstallmentTuitionInfo['date_of_installment6_seven'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                        @endswitch
                                         p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @default
                                                -
                                        @endswitch
                                    </th>
                                    {{--                                    Installment 6 - Date--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                @php
                                                    $filteredRow4 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return !isset($description['tuition_type']) || $description['tuition_type'] == 'Seven Installment - Installment 6';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRow4->date_of_payment) ? $filteredRow4->date_of_payment : '-' }}
                                    </th>
                                    {{--                                    Installment 7--}}
                                    <th scope="row"
                                        class="
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                @php
                                                    $filteredRow = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);

                                                        if (isset($description['tuition_type']) && $description['tuition_type'] == 'Seven Installment - Installment 7'){
                                                            return $detail->amount;
                                                        }
                                                        return 0;
                                                    });
                                                @endphp
                                                @if($filteredRow->is_paid==1)
                                                    bg-green-400
                                                @elseif($filteredRow->is_paid==0 and Carbon::createFromFormat('Y-m-d', $sevenInstallmentTuitionInfo['date_of_installment7_seven'])->lessThan(Carbon::createFromFormat('Y-m-d', $currentDate)))
                                                    bg-red-400
                                                @else
                                                    bg-blue-400
                                                @endif
                                                @break
                                        @endswitch
                                         p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                {{ number_format(@$filteredRow->amount) }} IRR
                                                @break
                                            @default
                                                -
                                        @endswitch
                                    </th>
                                    {{--                                    Installment 7 - Date--}}
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        @switch(@$student->tuitionInvoices->payment_type)
                                            @case('6')
                                                @php
                                                    $filteredRow7 = $student->tuitionInvoices->invoiceDetails->first(function ($detail) {
                                                        $description = json_decode($detail->description, true);
                                                        return !isset($description['tuition_type']) || $description['tuition_type'] == 'Seven Installment - Installment 7';
                                                    });
                                                @endphp
                                                @break
                                        @endswitch
                                        {{ isset($filteredRow7->date_of_payment) ? $filteredRow7->date_of_payment : '-' }}
                                    </th>
                                    <th scope="row"
                                        class="flex w-48 border justify-center text-center text-gray-900 dark:text-white">
                                        <a href="{{ route('tuitionCard.en',$student->id) }}"
                                           type="button"
                                           class="font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mr-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                            <div class="text-center">
                                                <i title="Click for view english tuition card "
                                                   class="las la-address-card "
                                                   style="font-size: 20px"></i>
                                                En
                                            </div>
                                        </a>
                                        <a href="{{ route('tuitionCard.fa',$student->id) }}"
                                           type="button"
                                           class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                            <div class="text-center">
                                                <i title="Click for view english tuition card "
                                                   class="las la-address-card "
                                                   style="font-size: 20px"></i>
                                                Fa
                                            </div>
                                        </a>
                                    </th>
                                    <th scope="row"
                                        class=" justify-center border text-center text-gray-900 whitespace-nowrap dark:text-white action">
                                        @if(auth()->user()->hasRole(['Super Admin','Financial Manager (Full Access)']))
                                            <a href="{{ route('applianceInvoices.edit',$student->id) }}"
                                               type="button"
                                               class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                                <div class="text-center">
                                                    <i title=" "
                                                       class="las la-pen "
                                                       style="font-size: 20px"></i>
                                                    Edit
                                                </div>
                                            </a>
                                        @endif
                                        <a href="{{ route('applianceInvoices',$student->id) }}"
                                           type="button"
                                           class="min-w-max inline-flex font-medium text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300  rounded-lg text-sm px-3 py-2.5 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800 hover:underline">
                                            <div class="text-center">
                                                <i
                                                        class="las la-money-bill "
                                                        style="font-size: 20px"></i>
                                                Invoices
                                            </div>
                                        </a>
                                    </th>
                                </tr>
                                {{ $filteredRowFP=null }}
                                {{ $filteredRowA=null }}
                                {{ $filteredRow1=null }}
                                {{ $filteredRow2=null }}
                                {{ $filteredRow3=null }}
                                {{ $filteredRow4=null }}
                                {{ $totalTuition=null }}
                                {{ $totalPaid=null }}
                            @endforeach
                            </tbody>
                        </table>
                        @if(!auth()->user()->hasExactRoles(['Parent']))
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
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
                                            {{ number_format($allTotalTuitions) }} IRR
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                                class="text-base font-semibold">
                                            {{ number_format($allIsPaid) }} IRR
                                        </div>
                                    </th>
                                    <th scope="row"
                                        class=" p-2 items-center border text-center text-gray-900 whitespace-nowrap dark:text-white">
                                        <div
                                                class="text-base font-semibold">
                                            {{ number_format($allIsNotPaid) }} IRR
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
