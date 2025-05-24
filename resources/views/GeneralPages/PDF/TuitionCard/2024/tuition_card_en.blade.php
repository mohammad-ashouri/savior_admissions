<!DOCTYPE html>
<html dir="ltr" lang="en">
@php
    use App\Models\Branch\ApplicationTiming;use App\Models\Branch\Interview;use App\Models\Branch\StudentApplianceStatus;use App\Models\Catalogs\AcademicYear;use App\Models\Catalogs\Level;use App\Models\Country;use App\Models\Finance\DiscountDetail;use App\Models\Finance\Tuition;use App\Models\Finance\TuitionInvoices;use App\Models\StudentInformation;use Morilog\Jalali\Jalalian;
    $applicationInformation=ApplicationTiming::join('applications','application_timings.id','=','applications.application_timing_id')
                                                ->join('application_reservations','applications.id','=','application_reservations.application_id')
                                                ->where('application_reservations.student_id',$applianceStatus->student_id)
                                                ->where('application_reservations.payment_status',1)
                                                ->where('application_timings.academic_year',$applianceStatus->academic_year)
                                                ->where('application_reservations.deleted_at',null)
                                                ->latest('application_reservations.id')
                                                ->first();
    $levelInfo=Level::find($applicationInformation->level);
    $systemTuitionInfo=Tuition::join('tuition_details','tuitions.id','=','tuition_details.tuition_id')->where('tuition_details.level',$levelInfo->id)->first();
    $myTuitionInfo=TuitionInvoices::with(['invoiceDetails' => function ($query) {
        $query->where('is_paid', '!=', 3);
    }])->whereApplianceId($applianceStatus->id)->latest()->first();
    $totalAmount=0;

    if (in_array($applianceStatus->academic_year,[1,2,3])){
        $evidencesInfo=json_decode($applianceStatus->evidences->informations,true);
        if (isset($evidencesInfo['foreign_school']) and $evidencesInfo['foreign_school'] == 'Yes') {
            $foreignSchool = true;
        } else {
            $foreignSchool = false;
        }
    }else{
        $interview_form=Interview::where('interview_type',3)->where('application_id',$applicationInformation->application_id)->latest()->first();
        $interview_form = json_decode($interview_form->interview_form, true);
        if (isset($interview_form['foreign_school']) and $interview_form['foreign_school'] == 'Yes') {
            $foreignSchool = true;
        } else {
            $foreignSchool = false;
        }
    }

    foreach($myTuitionInfo->invoiceDetails as $invoices){
        $totalAmount=$invoices->amount+$totalAmount;
    }

    $paymentAmount=null;
    if ($foreignSchool){
        switch ($myTuitionInfo->payment_type){
            case 1:
            case 4:
                $paymentAmount=str_replace(',','',json_decode($systemTuitionInfo->full_payment_ministry,true)['full_payment_irr_ministry']);
                break;
            case 2:
                $paymentAmount=str_replace(',','',json_decode($systemTuitionInfo->two_installment_payment_ministry,true)['two_installment_amount_irr_ministry']);
                break;
            case 3:
                $paymentAmount=str_replace(',','',json_decode($systemTuitionInfo->four_installment_payment_ministry,true)['four_installment_amount_irr_ministry']);
                break;
        }
    }else{
        switch ($myTuitionInfo->payment_type){
            case 1:
            case 4:
                $paymentAmount=str_replace(',','',json_decode($systemTuitionInfo->full_payment,true)['full_payment_irr']);
                break;
            case 2:
                $paymentAmount=str_replace(',','',json_decode($systemTuitionInfo->two_installment_payment,true)['two_installment_amount_irr']);
                break;
            case 3:
                $paymentAmount=str_replace(',','',json_decode($systemTuitionInfo->four_installment_payment,true)['four_installment_amount_irr']);
                break;
        }
    }

    //Discounts
    $interviewForm=Interview::whereApplicationId($applicationInformation->application_id)->where('interview_type',3)->latest()->first();
    if (!isset(json_decode($interviewForm->interview_form,true)['discount'])){
        $discounts=[];
    }else{
        $discounts=json_decode($interviewForm->interview_form,true)['discount'];
    }
    $discounts=DiscountDetail::whereIn('id',$discounts)->get();

    if (isset($evidencesInfo['father_nationality'])){
        $fatherNationality=Country::find($evidencesInfo['father_nationality']);
    }

    $academicYearInfo=AcademicYear::with('schoolInfo')->find($applicationInformation->academic_year);
    $schoolAddress=$academicYearInfo->schoolInfo->address;
    $schoolBranch=$academicYearInfo->schoolInfo->name;
@endphp
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /*@page {*/
        /*    size: A4;*/
        /*}*/
        /*@media print {*/
        /*    @page {*/
        /*        scale: 0.6; !* 60% مقیاس پرینت *!*/
        /*    }*/
        /*}*/
        @font-face {
            font-family: IRANSansX;
            src: url('../../build/Fonts/IRANSansX-DemiBold.woff') format('woff');
        }

        body {
            font-family: IRANSansX, Arial, sans-serif;
            width: 20cm;
            height: 21cm;
        }

        .bg-white {
            background-color: white;
        }

        .container {
            margin: 0 auto;
            padding: 0 0.5rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            padding: 2px;
        }

        header img {
            max-height: 100px;
        }

        header .title-description {
            text-align: center;
        }

        header .invoice-details {
            text-align: right;
        }

        section {
            margin: 5px 0;
            overflow: hidden;
            border: 1px solid;
            border-radius: 10px;
        }

        .contact-info > div {
            text-align: right;
        }

        .flex {
            display: flex;
        }

        .justify-between {
            justify-content: space-between;
        }

        .contact-info {
            display: inline-flex;
            width: 100%;
        }

        .contact-info .name,
        .contact-info .contact-number {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            width: 100%;
        }

        .writing-rl {
            writing-mode: vertical-rl;
            transform: scale(-1);
            padding: 0.3rem 0;
            width: 60px;
        }

        .texthead {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .textbody {
            padding: 0.5rem;
            width: 100%;
        }

        .textbody p {
            margin-top: 2px;
        }


        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            /*padding: 10px;*/
            text-align: center;
        }

        #tuition_table tr th,
        tr td {
            /*padding: 1.2rem;*/
            border-left: 1px solid black;
        }

        #tuition_table tr td {
            /*padding: 1.2rem;*/
            border-left: 1px solid;
            border-color: black !important;;
        }

        #tuition_table tr th:first-child {
            border-left: 1px solid;
            border-color: black !important;;
        }

        #tuition_table tr td:first-child {
            border-left: 1px solid;
            border-color: black !important;;
        }

        #tuition_table tr td {
            border-top: 1px solid #9ddadf;
        }


        #table2 tr th,
        tr td {
            /*padding: 1.2rem;*/
            border-left: 1px solid black;
        }

        #table2 tr th:first-child {
            border-left: 0;
            border-left: 1px solid black;
        }

        #table2 tr td:first-child {
            border-left: 0;
            border-left: 1px solid black;
        }

        #table2 tr td {
            border-top: 1px solid black;
        }


        .font-bold {
            font-weight: 400;
        }

        .font-light {
            font-weight: 200;
        }


        .border-table {
            border: 1px solid;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .table-container {
            display: flex;
            /* padding: 1rem; */
            justify-content: space-between;
        }

        .table-container table {
            /*padding: 1rem;*/
        }

        .bg-header {
            background-color: #e8f6f7;
        }

        .bg-blue {
            background-color: #9ddadf;
        }

        .bg-border-black {
            border-color: black !important;
        }

        .bg-yellow {
            background-color: #ffe753;
        }

        .bg-border-yellow {
            border-color: black !important;
        }

        .mt-2rem {
            margin-top: 0.5rem;;
        }

        .w-100 {
            width: 100%;
        }

        #tuition_table th:nth-child(3),
        #tuition_table td:nth-child(3) {
            width: 150px;
        }

        .considerations {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .consideration-item {
            margin-bottom: 10px;
            position: relative;
            margin-left: 1.2em;
        }

        .consideration-item::before {
            content: "\2022";
            color: #9ddadf;
            font-size: 20px;
            position: absolute;
            left: -20px;
            top: 48%;
            transform: translateY(-50%);
        }


        footer {
            padding: 20px;
            background-color: #e8f6f7;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            /*margin-top: 1.5rem;*/
            margin-left: 10px;
        }

        .footer-text {
            line-height: 1.5;
        }

        .table-v p > span {
            font-weight: 400;
        }

        .ltr-text {
            direction: ltr !important;
            unicode-bidi: embed;
            text-align: left;
        }

    </style>
    <script>
        // window.print();

        function setPrintScale() {
            if (window.matchMedia('print').matches) {
                var scale = 0.6; // 60%
                document.body.style.transform = 'scale(' + scale + ')';
                document.body.style.transformOrigin = 'top';
            }
        }

        setPrintScale();
    </script>
    <title>Your Invoice</title>
</head>

<body class="container">

{{--Header--}}
<header class="bg-header">
    <div>
        <img src="/build/export-images/logo.png" alt="Logo">
    </div>
    <div class="title-description">
        <h1>Tuition Fee Card</h1>
        <p style="padding: 0; margin: 0">Monji International Educational Institute</p>
        <p style="padding: 0; margin: 0">{{$academicYearInfo->name}}</p>
    </div>
    <div class="invoice-details">
        <p class="font-bold">Invoice Number: <span class="font-light">{{ $myTuitionInfo->id }}</span></p>
        <p class="font-bold">Date: <span class="font-light">{{ now()->format('Y-m-d') }}</span></p>
        <p class="font-bold">Student ID: <span class="font-light">{{ $applianceStatus->student_id }}</span></p>
    </div>
</header>

{{--Education Center Details--}}
<section class="bg-border-black bg-white table-v">
    <div class="flex">
        <div class="texthead bg-blue">
            <div class="writing-rl">
                <h5>Institute Information</h5>
            </div>
        </div>
        <div style="margin-top: 5px" class="textbody">
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <p>Name: <span>{{$academicYearInfo->schoolInfo->name}}</span></p>
                </div>
                <div>
                    <p>National ID: <span>14011156661</span></p>
                </div>
            </div>
            <div class="flex justify-between">
                <p>Registration Number: <span>60789562</span></p>
                <p>Contact Number: <span>+98 25 3770 4544</span></p>
            </div>
            <div class="address">
                <p>Address: <span style="font-size: small !important;">{{$schoolAddress}}</span>
                </p>
                <p>Postal Code: <span>37156-57571</span></p>
            </div>
        </div>
    </div>
</section>

{{--Student Details--}}
<section class="bg-border-yellow bg-white table-v">
    <div class="flex">
        <div class="texthead bg-yellow">
            <div class="writing-rl">
                <h5>Student Information</h5>
            </div>
        </div>
        <div class="textbody">
            <div class="flex justify-between">
                <p>Full Name:
                    <span>{{ $applianceStatus->studentInformations->studentInfo->generalInformationInfo->first_name_en }} {{ $applianceStatus->studentInformations->studentInfo->generalInformationInfo->last_name_en }}</span>
                </p>
                <p>Passport Number/National ID: <span>{{ $evidencesInfo['student_passport_number'] }}</span></p>
            </div>
            <div class="flex justify-between">
                <p>Grade: <span>{{$levelInfo->name}}</span></p>
                <p>Nationality:
                    <span> {{$applianceStatus->studentInformations->guardianInfo->generalInformationInfo->nationalityInfo?->nationality}}</span>
                </p>
            </div>
            <div class="flex justify-between">
                <p>Guardian Full Name:
                    <span>{{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->first_name_en }} {{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->last_name_en }}</span>
                </p>
                <p>Contact Number:
                    <span>{{ $applianceStatus->studentInformations->guardianInfo->mobile }}</span>
                </p>
            </div>
            <div class="flex justify-between">
                <p>Address:
                    <span>{{$applianceStatus->studentInformations->guardianInfo->generalInformationInfo->address}}</span>
                </p>
                <p>Postal Code:
                    <span>{{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->postal_code }}</span>
                </p>
            </div>
        </div>
    </div>
</section>

{{--Paid Tuition Table--}}
<section id="tuition_table" class="border-table bg-border-blue radius-table bg-white">

    <div class="flex bg-white">
        <div class="texthead bg-blue">
            <div class="writing-rl">
                <h5>Fee Details</h5>
            </div>
        </div>
        <div class="table-container bg-white">
            <table class="table1" style="border: 1px solid black">
                <tr>
                    <th style="width: 10%">Currency</th>
                    <th style="width: 10%">Payment Type</th>
                    <th style="width: 15%">Cross Fee</th>
                    <th style="width: 15%;white-space: nowrap">Total Discount</th>
                    <th style="width: 15%; ">Net Fee</th>
                    @php $paidAmount=0 @endphp
                </tr>
                <tr>
                    <td style="white-space: nowrap" class="font-bold">
                        IRR
                    </td>
                    <td style="white-space: nowrap" class="font-bold">
                        @switch($myTuitionInfo->payment_type)
                            @case('1')
                                Full Payment
                                @break
                            @case('2')
                                Two installment
                                @break
                            @case('3')
                                Four Installment
                                @break
                            @case('4')
                                Full Payment With Advance
                                @break
                        @endswitch
                    </td>
                    <td style="white-space: nowrap">{{ number_format($paymentAmount) }} </td>

                    <td style="white-space: nowrap">{{ number_format($paymentAmount-$totalAmount) }}
                    </td>
                    <td style="white-space: nowrap">{{ number_format($totalAmount) }}
                    </td>
                </tr>
            </table>
        </div>
    </div>
</section>

{{--Payment Details--}}
<div class="flex w-100">
    <div id="table2" class="border-table bg-border-yellow radius-table mt-2rem bg-white">
        <div class="flex">
            <div class="texthead bg-yellow">
                <div class="writing-rl">
                    <h5>Fee Details Table </h5>
                </div>
            </div>
            <div class="table-container ">
                <table class="payment-details-table font-bold">
                    <tr>
                        <th>No</th>
                        <th style="white-space: nowrap">Due Date</th>
                        <th>Due Amount</th>
                        <th style="white-space: nowrap">Paid Date</th>
                        <th>Payment Method</th>
                        <th style="width: 120px;"></th>
                        @php $paidAmount = $debt = 0 @endphp
                    </tr>
                    @foreach($myTuitionInfo->invoiceDetails as $key=>$invoices)
                        @php
                            $invoiceDetailsDescription=json_decode($invoices->description,true);
                            $tuitionType=$invoiceDetailsDescription['tuition_type'];
                            $dueType=null;
                            if (strstr($tuitionType,'Three') and !strstr($tuitionType,'Advance')){
                                $dueType='Three';
                                $dueDates=json_decode($systemTuitionInfo->three_installment_payment,true);
                            }
                            if (strstr($tuitionType,'Seven') and !strstr($tuitionType,'Advance')){
                                $dueType='Seven';
                                $dueDates=json_decode($systemTuitionInfo->seven_installment_payment,true);
                            }
                            if (strstr($tuitionType,'Four') and !strstr($tuitionType,'Advance')){
                                $dueType='Four';
                                $dueDates=json_decode($systemTuitionInfo->four_installment_payment,true);
                            }
                            if (strstr($tuitionType,'Two') and !strstr($tuitionType,'Advance')){
                                $dueType='Two';
                                $dueDates=json_decode($systemTuitionInfo->two_installment_payment,true);
                            }
                            if (strstr($tuitionType,'Full') and strstr($tuitionType,'Advance') and strstr($tuitionType,'Installment')){
                                $dueType='Full';
                            }
                        @endphp
                        <tr style="padding: 4px">
                            <td style="white-space: nowrap">
                                {{ $loop->iteration }}
                            </td>
                            <td style="white-space: nowrap;padding: 0 20px 0 20px;">
                                @switch ($dueType)
                                    @case('Four')
                                        @php
                                            $jalaliDate = Jalalian::fromDateTime($dueDates["date_of_installment".$key."_four"]);
                                            $formattedJalaliDate = $jalaliDate->format('Y/m/d');
                                        @endphp
                                        {{ $formattedJalaliDate }}
                                        @break
                                    @case('Two')
                                        @php
                                            $jalaliDate = Jalalian::fromDateTime($dueDates["date_of_installment".$key."_two"]);
                                            $formattedJalaliDate = $jalaliDate->format('Y/m/d');
                                        @endphp
                                        {{ $formattedJalaliDate }}
                                        @break
                                    @case('Three')
                                        @php
                                            $jalaliDate = Jalalian::fromDateTime($dueDates["date_of_installment".$key."_three"]);
                                            $formattedJalaliDate = $jalaliDate->format('Y/m/d');
                                        @endphp
                                        {{ $formattedJalaliDate }}
                                        @break
                                    @case('Seven')
                                        @php
                                            $jalaliDate = Jalalian::fromDateTime($dueDates["date_of_installment".$key."_seven"]);
                                            $formattedJalaliDate = $jalaliDate->format('Y/m/d');
                                        @endphp
                                        {{ $formattedJalaliDate }}
                                        @break
                                    @case('Full')
                                        @php
                                            $jalaliDate = Jalalian::fromDateTime($invoices->date_of_payment);
                                            $endOfShahrivar = Jalalian::fromFormat('Y/m/d', $jalaliDate->getYear() . '/06/31');
                                            $formattedJalaliDate = $endOfShahrivar->format('Y/m/d');
                                        @endphp
                                        {{ $formattedJalaliDate }}
                                        @break
                                    @default
                                        -
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;padding: 0 20px 0 20px;">{{ number_format($invoices->amount) }} </td>
                            <td class="ltr-text" style="white-space: nowrap;text-align: center">
                                @if(isset($invoices->date_of_payment))
                                    {{$invoices->date_of_payment}}
                                @else
                                    -
                                @endif
                            </td>
                            <td style="white-space: nowrap; border-right: 1px solid black">
                                @if(isset($invoices->paymentMethodInfo->name))
                                    {{$invoices->paymentMethodInfo->name}}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @if($invoices->date_of_payment!=null)
                            @php
                                $paidAmount=$invoices->amount+$paidAmount;
                            @endphp
                        @endif
                        @if($invoices->date_of_payment==null)
                            @php
                                $debt=$totalAmount-$paidAmount;
                            @endphp
                        @endif
                    @endforeach
                    <tr style="border-top: 1px solid black;white-space: nowrap">
                        <td class="font-bold">Total</td>
                        <td>{{ number_format($totalAmount) }} </td>
                        <td class="font-bold">Paid Amount</td>
                        <td>{{ number_format($paidAmount) }} </td>
                        <td class="font-bold">Outstanding: {{ number_format($debt) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{--Considerations--}}
<div class="Considerations">
    <h3>Considerations</h3>
    <ul class="considerations ">
        @if($discounts->isNotEmpty())
            <li class="consideration-item font-bold">
                Discounts
            </li>
            @foreach($discounts as $discount)
                <li class="consideration-item">
                    {{ $discount->id." - " }}
                </li>
            @endforeach
        @endif
        @if(isset($allFamilyDiscounts->discount_price) and $allFamilyDiscounts->discount_price>0)
            <li class="consideration-item font-bold">
                Included Family Discounts ({{number_format($allFamilyDiscounts->discount_price)}} )
            </li>
        @endif
    </ul>
</div>

{{--Footer--}}
<footer class="">
    <div class="footer-text ">
        <p class="font-bold">
            I, <span
                style="font-weight: bold">{{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->first_name_en }} {{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->last_name_en }}</span>,
            have read the Disciplinary and the Financial Charters of Monji International Educational Institute, and
            agree to abide by them
        </p>
    </div>
    <div class="footer-content font-bold">
        <div class="footer-text">Signature and Fingerprint of Guardian</div>
        <div class="footer-text">Signature and Stamp of Admissions Officer</div>
        <div class="footer-text">Signature and Stamp of Finance</div>
    </div>
</footer>

</body>

</html>
