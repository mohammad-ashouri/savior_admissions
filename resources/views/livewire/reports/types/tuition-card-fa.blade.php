@php use Morilog\Jalali\Jalalian; @endphp


<div id="viewerContent1">
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


        // Assigning a report to the Viewer:
        viewer.report = report;

        // After loading the HTML page, display the visual part of the Viewer in the specified container.
        function onLoad() {
            viewer.renderHtml("viewerContent1");
        }
    </script>
</div>
