<!DOCTYPE html>
<html dir="rtl" lang="en">
@php
    use App\Models\Branch\ApplicationTiming;use App\Models\Branch\Interview;use App\Models\Branch\StudentApplianceStatus;use App\Models\Catalogs\AcademicYear;use App\Models\Catalogs\Level;use App\Models\Finance\DiscountDetail;use App\Models\Finance\Tuition;use App\Models\Finance\TuitionInvoices;use App\Models\StudentInformation;


    $evidencesInfo=json_decode($applianceStatus->evidences->informations,true);
    $applicationInformation=ApplicationTiming::join('applications','application_timings.id','=','applications.application_timing_id')
                                                ->join('application_reservations','applications.id','=','application_reservations.application_id')
                                                ->where('application_reservations.student_id',$applianceStatus->student_id)
                                                ->where('application_timings.academic_year',$applianceStatus->academic_year)->latest('application_reservations.id')->first();
    $levelInfo=Level::find($applicationInformation->level);

    $systemTuitionInfo=Tuition::join('tuition_details','tuitions.id','=','tuition_details.tuition_id')->where('tuition_details.level',$levelInfo->id)->first();
    $myTuitionInfo=TuitionInvoices::with('invoiceDetails')->where('appliance_id',$applianceStatus->id)->first();
    $totalAmount=0;

    foreach($myTuitionInfo->invoiceDetails as $invoices){
        $totalAmount=$invoices->amount+$totalAmount;
    }

    $paymentAmount=null;
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

    //Discounts
    $interviewForm=Interview::where('application_id',$applicationInformation->application_id)->where('interview_type',3)->latest()->first();
    $discounts=DiscountDetail::whereIn('id',json_decode($interviewForm->interview_form,true)['discount'])->get();

@endphp
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @page {
            size: a4;
        }

        @font-face {
            font-family: IRANSansX;
            src: url('../../build/Fonts/IRANSansX-DemiBold.woff') format('woff');
        }

        body {
            font-family: IRANSansX, Arial, sans-serif;
        }

        .bg-white {
            background-color: white;
        }

        .container {
            margin: 0 auto;
            padding: 0 1rem;
        }

        header {
            display: flex;
            justify-content: space-between;
            padding: 10px;
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
            margin: 20px 0;
            overflow: hidden;
            border: 2px solid;
            border-radius: 50px;
        }

        .contact-info > div {
            text-align: right;
        }

        .address {
            margin-top: 20px;
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
            padding: 1.3rem 0;
            width: 60px;
        }

        .texthead {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .textbody {
            padding: 1rem;
            width: 100%;
        }


        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 10px;
            text-align: center;
        }

        #tuition_table tr th,
        tr td {
            padding: 1.2rem;
            border-left: 1px solid #9ddadf;
        }

        #tuition_table tr td {
            padding: 1.2rem;
            border-left: 1px solid;
            border-color: #9ddadf !important;;
        }

        #tuition_table tr th:first-child {
            border-left: 1px solid;
            border-color: #9ddadf !important;;
        }

        #tuition_table tr td:first-child {
            border-left: 1px solid;
            border-color: #9ddadf !important;;
        }

        #tuition_table tr td {
            border-top: 1px solid #9ddadf;
        }


        #table2 tr th,
        tr td {
            padding: 1.2rem;
            border-left: 1px solid #ffe753;
        }

        #table2 tr th:first-child {
            border-left: 0;
            border-left: 1px solid #ffe753;
        }

        #table2 tr td:first-child {
            border-left: 0;
            border-left: 1px solid #ffe753;
        }

        #table2 tr td {
            border-top: 1px solid #ffe753;
        }


        .font-bold {
            font-weight: 400;
        }

        .font-light {
            font-weight: 200;
        }


        .border-table {
            border: 1px solid;
            border-radius: 20px;
            overflow: hidden;
            position: relative;
        }

        .title-section {
            background-color: #9ddadf;
            padding: 10px;
            text-align: center;
            font-weight: bold;
            border-top-right-radius: 20px;
            border-top-left-radius: 20px;
            color: rgb(0, 0, 0);
        }

        .table-container {
            display: flex;
            /* padding: 1rem; */
            justify-content: space-between;
        }

        .table-container table {
            padding: 1rem;
        }

        .bg-header {
            background-color: #e8f6f7;
        }

        .bg-blue {
            background-color: #9ddadf;
        }

        .bg-border-blue {
            border-color: #9ddadf !important;
        }

        .bg-yellow {
            background-color: #ffe753;
        }

        .bg-border-yellow {
            border-color: #ffe753 !important;
        }

        .text-white {
            color: white;
        }

        .p-1r {
            padding-right: 1rem;
        }

        .p-1l {
            padding-left: 1rem;
        }

        .m-0 {
            margin: 0;
        }

        .p-0 {
            padding: 0;
        }

        .mt-2rem {
            margin-top: 2rem;;
        }

        .w50 {
            width: 50%;
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
            margin-right: 1.2em;
        }

        .consideration-item::before {
            content: "\2022";
            color: #9ddadf;
            font-size: 50px;
            position: absolute;
            right: -20px;
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
            margin-top: 1.5rem;
            margin-left: 10px;
        }

        .footer-text {
            font-size: 16px;
            line-height: 1.5;
        }

        .font-norm {
            font-family: normal;
        }

        .table-v p > span {
            font-weight: 400;
        }

        .table-v p {
            font-weight: 600;
        }

        .ltr-text {
            direction: ltr !important;
            unicode-bidi: embed;
            text-align: left;
        }

    </style>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var spans = document.querySelectorAll('span');

            spans.forEach(function(span) {
                span.classList.add('ltr-text');
            });
        });


        window.print();
        function setPrintScale() {
            if (window.matchMedia('print').matches) {
                var scale = 0.6; // 60%
                document.body.style.transform = 'scale(' + scale + ')';
                document.body.style.transformOrigin = 'top';
            }
        }

        setPrintScale();
    </script>
    <title>کارت شهریه</title>

</head>

<body class="container">

{{--Header--}}
<header class="bg-header">
    <div>
        <img src="/build/export-images/logo.png" alt="Logo">
    </div>
    <div class="title-description">
        <h1>{{ __('translated_fa.Tuition Card') }}</h1>
        <p>{{ __('translated_fa.Monji Noor Education Institute') }}</p>
    </div>
    <div class="invoice-details">
        <p class="font-bold">{{ __('translated_fa.Invoice Number') }}: <span
                class="font-light">{{ $myTuitionInfo->id }}</span></p>
        <p class="font-bold">{{ __('translated_fa.Date') }}: <span
                class="font-light">{{ now()->format('Y-m-d') }}</span></p>
    </div>
</header>

{{--Education Center Details--}}
<section class="bg-border-blue bg-white table-v">
    <div class="flex">
        <div class="texthead bg-blue">
            <div class="writing-rl">
                <h5>{{ __('translated_fa.Education Center Details') }}</h5>
            </div>
        </div>
        <div class="textbody">
            <div class="contact-info">
                <div class="name">
                    <p>{{ __('translated_fa.Name') }}:
                        <span>{{ __('translated_fa.Monji Noor Education Institute') }}</span></p>
                </div>
                <div class="contact-number">
                    <p>{{ __('translated_fa.Contact Number') }}: <span>+98 25 3770 4544</span></p>
                </div>
            </div>
            <div class="flex justify-between">
                <p>{{ __('translated_fa.Postal Code') }}: <span style="direction: ltr">37157-47748</span></p>
                <p>{{ __('translated_fa.Registration Number') }}: <span>60789562</span></p>
                <p>{{ __('translated_fa.National ID') }}: <span>60235789562</span></p>
            </div>
            <div class="address">
                <p>{{ __('translated_fa.Address') }}: <span>{{ __('translated_fa.Education Center Address') }}</span>
                </p>
            </div>
        </div>
    </div>
</section>

{{--Student Details--}}
<section class="bg-border-yellow bg-white table-v">
    <div class="flex">
        <div class="texthead bg-yellow">
            <div class="writing-rl">
                <h5>{{ __('translated_fa.Student Details') }}</h5>
            </div>
        </div>
        <div class="textbody">
            <div class="flex justify-between">
                <p>{{ __('translated_fa.Full Name of Student') }}:
                    <span>{{ $applianceStatus->studentInformations->studentInfo->generalInformationInfo->first_name_en }} {{ $applianceStatus->studentInformations->studentInfo->generalInformationInfo->last_name_en }}</span>
                </p>
                <p>{{ __('translated_fa.Passport Number') }}:
                    <span>{{ $evidencesInfo['student_passport_number'] }}</span></p>
                <p>{{ __('translated_fa.Level of education') }}: <span>{{$levelInfo->name}}</span></p>
            </div>
            <div class="flex justify-between">
                <p>{{ __('translated_fa.Full Name of Parent/Guardian') }}:
                    <span>{{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->first_name_en }} {{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->last_name_en }}</span>
                </p>
                <p>{{ __('translated_fa.Passport Number') }}:
                    <span>{{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->passport_number }}</span>
                </p>
                <p>{{ __('translated_fa.Student ID') }}: <span>{{ $applianceStatus->student_id }}</span></p>
            </div>
            <div class="flex justify-between">
                <p>{{ __('translated_fa.Country') }}: <span>Afghanistan</span></p>
                <p>{{ __('translated_fa.Contact Number') }}:
                    <span>{{ $applianceStatus->studentInformations->guardianInfo->mobile }}</span></p>
            </div>
        </div>
    </div>
</section>

{{--Tuition Table--}}
<div id="tuition_table" class="border-table bg-border-blue radius-table bg-white">
    <h3 class="title-section bg-blue p-1r m-0 radius-table">{{ __('translated_fa.Tuition table for the academic year') }}
        : {{ $applianceStatus->academicYearInfo->name }}</h3>
    <div class="table-container">
        <table>
            <tr>
                <th style="width: 15%">{{ __('translated_fa.Currency of Payment') }}</th>
                <th style="width: 25%">{{ __('translated_fa.Full Payment tuition') }}</th>
                <th style="width: 25%">{{ __('translated_fa.Two Installment tuition') }}</th>
                <th style="width: 25%">{{ __('translated_fa.Four Installment tuition') }}</th>
                <th style="width: 15%">{{ __('translated_fa.Level') }}</th>
            </tr>
            <tr>
                <td class="font-bold">{{ __('translated_fa.Iranian Rial') }}</td>
                <td>{{ json_decode($systemTuitionInfo->full_payment,true)['full_payment_irr'] }} {{ __('translated_fa.IRR') }}</td>
                <td>{{ json_decode($systemTuitionInfo->two_installment_payment,true)['two_installment_amount_irr'] }}
                    {{ __('translated_fa.IRR') }}
                </td>
                <td>{{ json_decode($systemTuitionInfo->four_installment_payment,true)['four_installment_amount_irr'] }}
                    {{ __('translated_fa.IRR') }}
                </td>
                <td>{{$levelInfo->name}}</td>
            </tr>
        </table>
    </div>
</div>

{{--Paid Tuition Table--}}
<div style="margin-top: 1%" id="tuition_table" class="border-table bg-border-blue radius-table bg-white">
    <h3 class="title-section bg-blue p-1r m-0 radius-table">{{ __('translated_fa.Your tuition') }}</h3>
    <div class="table-container">
        <table>
            <tr>
                <th style="width: 10%">{{ __('translated_fa.Payment Type') }}</th>
                <th style="width: 15%">{{ __('translated_fa.Total Payment Amount') }}</th>
                <th style="width: 15%">{{ __('translated_fa.Total Discounts') }} (درصد)</th>
                <th style="width: 15%">{{ __('translated_fa.Total Discounts') }} ({{ __('translated_fa.Amount') }})</th>
                <th style="width: 15%">{{ __('translated_fa.Total Fee') }}</th>
            </tr>
            <tr>
                <td class="font-bold">
                    @switch($myTuitionInfo->payment_type)
                        @case('1')
                            {{ __('translated_fa.Full Payment') }}
                            @break
                        @case('2')
                            {{ __('translated_fa.Two installment') }}
                            @break
                        @case('3')
                            {{ __('translated_fa.Four Installment') }}
                            @break
                        @case('4')
                            {{ __('translated_fa.Four Installment') }}{{ __('translated_fa.Full Payment With Advance') }}
                            @break
                    @endswitch
                </td>
                <td>{{ number_format($paymentAmount) }} {{ __('translated_fa.IRR') }}</td>
                <td>{{ $allDiscounts }}</td>
                <td>{{ number_format(($paymentAmount*$allDiscounts)/100) }}</td>
                <td>{{ number_format($totalAmount) }} {{ __('translated_fa.IRR') }}
                </td>
            </tr>
        </table>
    </div>
</div>

{{--Payment Details--}}
<div class="flex w-100">
    <div class="w-100 p-1r">
        <div id="table2" class="border-table bg-border-yellow radius-table mt-2rem bg-white">
            <h3 class="title-section bg-yellow p-1r m-0 radius-table">{{ __('translated_fa.Payment Details') }}</h3>
            <div class="table-container ">
                <table class="font-bold">
                    <tr>
                        <th>{{ __('translated_fa.Type') }}</th>
                        <th>{{ __('translated_fa.Amount') }}</th>
                        <th>{{ __('translated_fa.Due Date') }}</th>
                        <th>{{ __('translated_fa.Date received') }}</th>
                        <th>{{ __('translated_fa.Payment Method') }}</th>
                    </tr>
                    @foreach($myTuitionInfo->invoiceDetails as $key=>$invoices)
                        @php
                            $invoiceDetailsDescription=json_decode($invoices->description,true);
                            $tuitionType=$invoiceDetailsDescription['tuition_type'];
                            $dueType=null;
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
                        <tr>
                            <td>
                                @switch($tuitionType)
                                    @case('Two Installment Advance')
                                        دو قسطه - پیش پرداخت
                                        @break
                                    @case('Two Installment - Installment 1')
                                        دو قسطه - قسط اول
                                        @break
                                    @case('Two Installment - Installment 2')
                                        دو قسطه - قسط دوم
                                        @break
                                    @case('Four Installment Advance')
                                        چهار قسطه - پیش پرداخت
                                        @break
                                    @case('Four Installment - Installment 1')
                                        چهار قسطه - قسط اول
                                        @break
                                    @case('Four Installment - Installment 2')
                                        چهار قسطه - قسط دوم
                                        @break
                                    @case('Four Installment - Installment 3')
                                        چهار قسطه - قسط سوم
                                        @break
                                    @case('Four Installment - Installment 4')
                                        چهار قسطه - قسط چهارم
                                        @break
                                    @case('Full Payment With Advance - Installment')
                                        پرداخت کامل با پیش پرداخت - پرداخت دوم
                                        @break
                                    @case('Full Payment With Advance')
                                        پرداخت کامل با پیش پرداخت - پرداخت اول
                                        @break
                                    @case('Full Payment')
                                        پرداخت کامل
                                        @break
                                @endswitch
                            </td>
                            <td>{{ number_format($invoices->amount) }} {{ __('translated_fa.IRR') }}</td>
                            <td>
                                @switch ($dueType)
                                    @case('Four')
                                        {{ $dueDates["date_of_installment".$key."_four"] }}
                                        @break
                                    @case('Two')
                                        {{ $dueDates["date_of_installment".$key."_two"] }}
                                        @break
                                    @case('Full')
                                        2024-09-21
                                        @break
                                @endswitch
                            </td>
                            <td>
                                @if(isset($invoices->date_of_payment))
                                    {{ $invoices->date_of_payment }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if(isset($invoices->paymentMethodInfo->name))
                                    {{$invoices->paymentMethodInfo->name}}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    <tr style="border-top: 1px solid #ffe753;">
                        <td class="font-bold">{{ __('translated_fa.Total') }}</td>
                        <td style="border: none;">{{ number_format($totalAmount) }} {{ __('translated_fa.IRR') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

{{--Considerations--}}
<div style="page-break-after: auto" class="Considerations">
    <h1>{{ __('translated_fa.Considerations') }}</h1>
    <ul class="considerations ">
        <li class="consideration-item font-bold">
            {{ __('translated_fa.Discounts') }}
        </li>
        @foreach($discounts as $discount)
            <li class="consideration-item">
                {{ $discount->name }}
            </li>
        @endforeach
        @if($allFamilyDiscounts['students_count']>1)
            <li class="consideration-item font-bold">
                {{ __('translated_fa.Included Family Discounts') }}
            </li>
        @endif
    </ul>
</div>

{{--Footer--}}
<footer class="mt-2rem">
    <div class="footer-text font-bold">
        اینجانب
        {{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->first_name_en }} {{ $applianceStatus->studentInformations->guardianInfo->generalInformationInfo->last_name_en }}
        ،
        والدین / قیم
        {{ $applianceStatus->studentInformations->studentInfo->generalInformationInfo->first_name_en }} {{ $applianceStatus->studentInformations->studentInfo->generalInformationInfo->last_name_en }}
        ،
        بدین وسیله با کلیه قوانین و مقررات موسسه آموزشی بین المللی منجی نور موافقت می نمایم.
    </div>
    <div class="footer-content font-bold">
        <div class="footer-text">{{ __('translated_fa.Signature and Fingerprint of Parent/Guardian') }}</div>
        <div class="footer-text">{{ __('translated_fa.Signature and Stamp of Admissions') }}</div>
        <div class="footer-text">{{ __('translated_fa.Signature and Stamp of Finance') }}</div>
    </div>
</footer>

</body>

</html>
