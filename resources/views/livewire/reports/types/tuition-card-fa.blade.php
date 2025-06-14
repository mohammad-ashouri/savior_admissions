@php use Morilog\Jalali\Jalalian; @endphp
<script>
    var report = new Stimulsoft.Report.StiReport();
    report.loadFile("{{ asset('reports/TuitionCardFa.mrt') }}");

    //Pass value to variable
    report.dictionary.variables.getByName("invoice_id").value = @json($this->student_appliance_status->tuitionInvoices->id);
    report.dictionary.variables.getByName("now_date").value = @json($this->now_date);
    report.dictionary.variables.getByName("academic_year").value = @json($this->student_appliance_status->academicYearInfo->persian_name);
    report.dictionary.variables.getByName("school_address").value = @json($this->student_appliance_status->academicYearInfo->schoolInfo->address_fa);
    report.dictionary.variables.getByName("student_id").value = @json($this->student_appliance_status->student_id);
    report.dictionary.variables.getByName("student_info").value = @json($this->student_appliance_status->studentInfo->generalInformationInfo->first_name_fa) +' ' + @json($this->student_appliance_status->studentInfo->generalInformationInfo->last_name_fa);
    report.dictionary.variables.getByName("student_passport_number").value = @json($this->evidences['student_passport_number']);
    report.dictionary.variables.getByName("country").value = @json($this->student_appliance_status->studentInformations->guardianInfo->generalInformationInfo->nationalityInfo?->nationality);
    report.dictionary.variables.getByName("guardian_info").value = @json($this->student_appliance_status->studentInformations->guardianInfo->generalInformationInfo->first_name_fa) +' ' + @json($this->student_appliance_status->studentInformations->guardianInfo->generalInformationInfo->last_name_fa);
    report.dictionary.variables.getByName("guardian_passport_number").value = @json($this->student_appliance_status->studentInformations->guardianInfo->generalInformationInfo->passport_number);
    report.dictionary.variables.getByName("mobile").value = @json($this->student_appliance_status->studentInformations->guardianInfo->mobile);
    report.dictionary.variables.getByName("address").value = @json($this->student_appliance_status->studentInformations->guardianInfo->generalInformationInfo->address);
    report.dictionary.variables.getByName("postal_code").value = @json($this->student_appliance_status->studentInformations->guardianInfo->generalInformationInfo->postal_code);
    report.dictionary.variables.getByName("tuition_amount").value = @json(number_format($this->payment_amount));
    report.dictionary.variables.getByName("tuition_type").value = @json($this->tuitionType);
    report.dictionary.variables.getByName("discount_price").value = @json(number_format($this->payment_amount-$this->total_amount));
    report.dictionary.variables.getByName("tuition_final_amount").value = @json(number_format($this->total_amount));

    //Tuition invoices
    report.dictionary.variables.getByName("AdvanceType").value = @json(isset($this->tuition_invoice_full_details['پیش پرداخت']) ? 'پیش پرداخت' : '-');
    report.dictionary.variables.getByName("AdvanceDate").value = @json(isset($this->tuition_invoice_full_details['پیش پرداخت']) ? $this->tuition_invoice_full_details['پیش پرداخت']['due_date'] : '-');
    report.dictionary.variables.getByName("AdvanceAmount").value = @json(isset($this->tuition_invoice_full_details['پیش پرداخت']) ? $this->tuition_invoice_full_details['پیش پرداخت']['amount'] : '-');
    report.dictionary.variables.getByName("AdvancePaid").value = @json(isset($this->tuition_invoice_full_details['پیش پرداخت']) ? $this->tuition_invoice_full_details['پیش پرداخت']['amount'] : '-');
    report.dictionary.variables.getByName("AdvanceDebt").value = @json(isset($this->tuition_invoice_full_details['پیش پرداخت']) ? $this->tuition_invoice_full_details['پیش پرداخت']['debt'] : '-');
    report.dictionary.variables.getByName("AdvancePaymentType").value = @json(isset($this->tuition_invoice_full_details['پیش پرداخت']) ? $this->tuition_invoice_full_details['پیش پرداخت']['payment_method'] : '-');
    report.dictionary.variables.getByName("AdvanceDateOfPayment").value = @json(isset($this->tuition_invoice_full_details['پیش پرداخت']) ? $this->tuition_invoice_full_details['پیش پرداخت']['date_of_payment'] : '-');

    report.dictionary.variables.getByName("P1Type").value = @json(isset($this->tuition_invoice_full_details['قسط اول']) ? 'قسط اول' : '-');
    report.dictionary.variables.getByName("P1Date").value = @json(isset($this->tuition_invoice_full_details['قسط اول']) ? $this->tuition_invoice_full_details['قسط اول']['due_date'] : '-');
    report.dictionary.variables.getByName("P1Amount").value = @json(isset($this->tuition_invoice_full_details['قسط اول']) ? $this->tuition_invoice_full_details['قسط اول']['amount'] : '-');
    report.dictionary.variables.getByName("P1Paid").value = @json(isset($this->tuition_invoice_full_details['قسط اول']) ? $this->tuition_invoice_full_details['قسط اول']['paid'] : '-');
    report.dictionary.variables.getByName("P1Debt").value = @json(isset($this->tuition_invoice_full_details['قسط اول']) ? $this->tuition_invoice_full_details['قسط اول']['debt'] : '-');
    report.dictionary.variables.getByName("P1PaymentType").value = @json(isset($this->tuition_invoice_full_details['قسط اول']) ? $this->tuition_invoice_full_details['قسط اول']['payment_method'] : '-');
    report.dictionary.variables.getByName("P1DateOfPayment").value = @json(isset($this->tuition_invoice_full_details['قسط اول']) ? $this->tuition_invoice_full_details['قسط اول']['date_of_payment'] : '-');

    report.dictionary.variables.getByName("P2Type").value = @json(isset($this->tuition_invoice_full_details['قسط دوم']) ? 'قسط دوم' : '-');
    report.dictionary.variables.getByName("P2Date").value = @json(isset($this->tuition_invoice_full_details['قسط دوم']) ? $this->tuition_invoice_full_details['قسط دوم']['due_date'] : '-');
    report.dictionary.variables.getByName("P2Amount").value = @json(isset($this->tuition_invoice_full_details['قسط دوم']) ? $this->tuition_invoice_full_details['قسط دوم']['amount'] : '-');
    report.dictionary.variables.getByName("P2Paid").value = @json(isset($this->tuition_invoice_full_details['قسط دوم']) ? $this->tuition_invoice_full_details['قسط دوم']['paid'] : '-');
    report.dictionary.variables.getByName("P2Debt").value = @json(isset($this->tuition_invoice_full_details['قسط دوم']) ? $this->tuition_invoice_full_details['قسط دوم']['debt'] : '-');
    report.dictionary.variables.getByName("P2PaymentType").value = @json(isset($this->tuition_invoice_full_details['قسط دوم']) ? $this->tuition_invoice_full_details['قسط دوم']['payment_method'] : '-');
    report.dictionary.variables.getByName("P2DateOfPayment").value = @json(isset($this->tuition_invoice_full_details['قسط دوم']) ? $this->tuition_invoice_full_details['قسط دوم']['date_of_payment'] : '-');

    report.dictionary.variables.getByName("P3Type").value = @json(isset($this->tuition_invoice_full_details['قسط سوم']) ? 'قسط سوم' : '-');
    report.dictionary.variables.getByName("P3Date").value = @json(isset($this->tuition_invoice_full_details['قسط سوم']) ? $this->tuition_invoice_full_details['قسط سوم']['due_date'] : '-');
    report.dictionary.variables.getByName("P3Amount").value = @json(isset($this->tuition_invoice_full_details['قسط سوم']) ? $this->tuition_invoice_full_details['قسط سوم']['amount'] : '-');
    report.dictionary.variables.getByName("P3Paid").value = @json(isset($this->tuition_invoice_full_details['قسط سوم']) ? $this->tuition_invoice_full_details['قسط سوم']['paid'] : '-');
    report.dictionary.variables.getByName("P3Debt").value = @json(isset($this->tuition_invoice_full_details['قسط سوم']) ? $this->tuition_invoice_full_details['قسط سوم']['debt'] : '-');
    report.dictionary.variables.getByName("P3PaymentType").value = @json(isset($this->tuition_invoice_full_details['قسط سوم']) ? $this->tuition_invoice_full_details['قسط سوم']['payment_method'] : '-');
    report.dictionary.variables.getByName("P3DateOfPayment").value = @json(isset($this->tuition_invoice_full_details['قسط سوم']) ? $this->tuition_invoice_full_details['قسط سوم']['date_of_payment'] : '-');

    report.dictionary.variables.getByName("P4Type").value = @json(isset($this->tuition_invoice_full_details['قسط چهارم']) ? 'قسط چهارم' : '-');
    report.dictionary.variables.getByName("P4Date").value = @json(isset($this->tuition_invoice_full_details['قسط چهارم']) ? $this->tuition_invoice_full_details['قسط چهارم']['due_date'] : '-');
    report.dictionary.variables.getByName("P4Amount").value = @json(isset($this->tuition_invoice_full_details['قسط چهارم']) ? $this->tuition_invoice_full_details['قسط چهارم']['amount'] : '-');
    report.dictionary.variables.getByName("P4Paid").value = @json(isset($this->tuition_invoice_full_details['قسط چهارم']) ? $this->tuition_invoice_full_details['قسط چهارم']['paid'] : '-');
    report.dictionary.variables.getByName("P4Debt").value = @json(isset($this->tuition_invoice_full_details['قسط چهارم']) ? $this->tuition_invoice_full_details['قسط چهارم']['debt'] : '-');
    report.dictionary.variables.getByName("P4PaymentType").value = @json(isset($this->tuition_invoice_full_details['قسط چهارم']) ? $this->tuition_invoice_full_details['قسط چهارم']['payment_method'] : '-');
    report.dictionary.variables.getByName("P4DateOfPayment").value = @json(isset($this->tuition_invoice_full_details['قسط چهارم']) ? $this->tuition_invoice_full_details['قسط چهارم']['date_of_payment'] : '-');

    report.dictionary.variables.getByName("P5Type").value = @json(isset($this->tuition_invoice_full_details['قسط پنجم']) ? 'قسط پنجم' : '-');
    report.dictionary.variables.getByName("P5Date").value = @json(isset($this->tuition_invoice_full_details['قسط پنجم']) ? $this->tuition_invoice_full_details['قسط پنجم']['due_date'] : '-');
    report.dictionary.variables.getByName("P5Amount").value = @json(isset($this->tuition_invoice_full_details['قسط پنجم']) ? $this->tuition_invoice_full_details['قسط پنجم']['amount'] : '-');
    report.dictionary.variables.getByName("P5Paid").value = @json(isset($this->tuition_invoice_full_details['قسط پنجم']) ? $this->tuition_invoice_full_details['قسط پنجم']['paid'] : '-');
    report.dictionary.variables.getByName("P5Debt").value = @json(isset($this->tuition_invoice_full_details['قسط پنجم']) ? $this->tuition_invoice_full_details['قسط پنجم']['debt'] : '-');
    report.dictionary.variables.getByName("P5PaymentType").value = @json(isset($this->tuition_invoice_full_details['قسط پنجم']) ? $this->tuition_invoice_full_details['قسط پنجم']['payment_method'] : '-');
    report.dictionary.variables.getByName("P5DateOfPayment").value = @json(isset($this->tuition_invoice_full_details['قسط پنجم']) ? $this->tuition_invoice_full_details['قسط پنجم']['date_of_payment'] : '-');

    report.dictionary.variables.getByName("P6Type").value = @json(isset($this->tuition_invoice_full_details['قسط ششم']) ? 'قسط ششم' : '-');
    report.dictionary.variables.getByName("P6Date").value = @json(isset($this->tuition_invoice_full_details['قسط ششم']) ? $this->tuition_invoice_full_details['قسط ششم']['due_date'] : '-');
    report.dictionary.variables.getByName("P6Amount").value = @json(isset($this->tuition_invoice_full_details['قسط ششم']) ? $this->tuition_invoice_full_details['قسط ششم']['amount'] : '-');
    report.dictionary.variables.getByName("P6Paid").value = @json(isset($this->tuition_invoice_full_details['قسط ششم']) ? $this->tuition_invoice_full_details['قسط ششم']['paid'] : '-');
    report.dictionary.variables.getByName("P6Debt").value = @json(isset($this->tuition_invoice_full_details['قسط ششم']) ? $this->tuition_invoice_full_details['قسط ششم']['debt'] : '-');
    report.dictionary.variables.getByName("P6PaymentType").value = @json(isset($this->tuition_invoice_full_details['قسط ششم']) ? $this->tuition_invoice_full_details['قسط ششم']['payment_method'] : '-');
    report.dictionary.variables.getByName("P6DateOfPayment").value = @json(isset($this->tuition_invoice_full_details['قسط ششم']) ? $this->tuition_invoice_full_details['قسط ششم']['date_of_payment'] : '-');

    report.dictionary.variables.getByName("P7Type").value = @json(isset($this->tuition_invoice_full_details['قسط هفتم']) ? 'قسط هفتم' : '-');
    report.dictionary.variables.getByName("P7Date").value = @json(isset($this->tuition_invoice_full_details['قسط هفتم']) ? $this->tuition_invoice_full_details['قسط هفتم']['due_date'] : '-');
    report.dictionary.variables.getByName("P7Amount").value = @json(isset($this->tuition_invoice_full_details['قسط هفتم']) ? $this->tuition_invoice_full_details['قسط هفتم']['amount'] : '-');
    report.dictionary.variables.getByName("P7Paid").value = @json(isset($this->tuition_invoice_full_details['قسط هفتم']) ? $this->tuition_invoice_full_details['قسط هفتم']['paid'] : '-');
    report.dictionary.variables.getByName("P7Debt").value = @json(isset($this->tuition_invoice_full_details['قسط هفتم']) ? $this->tuition_invoice_full_details['قسط هفتم']['debt'] : '-');
    report.dictionary.variables.getByName("P7PaymentType").value = @json(isset($this->tuition_invoice_full_details['قسط هفتم']) ? $this->tuition_invoice_full_details['قسط هفتم']['payment_method'] : '-');
    report.dictionary.variables.getByName("P7DateOfPayment").value = @json(isset($this->tuition_invoice_full_details['قسط هفتم']) ? $this->tuition_invoice_full_details['قسط هفتم']['date_of_payment'] : '-');

    // Assigning a report to the Viewer:
    viewer.report = report;

    // After loading the HTML page, display the visual part of the Viewer in the specified container.
    function onLoad() {
        viewer.renderHtml("viewerContent1");
    }
</script>
<div id="viewerContent1">
</div>
