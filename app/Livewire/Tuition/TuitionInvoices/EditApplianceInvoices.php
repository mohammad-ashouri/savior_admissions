<?php

namespace App\Livewire\Tuition\TuitionInvoices;

use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\Discount;
use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoiceEditHistory;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\UserAccessInformation;
use Livewire\Component;

class EditApplianceInvoices extends Component
{
    /**
     * Student appliance status variable
     */
    public StudentApplianceStatus $appliance_status;

    /**
     * My students by permission variable
     */
    public array $my_students;

    /**
     * All tuition invoices for this appliance status
     */
    public array $tuition_invoices;

    /**
     * All invoices for this tuition
     */
    public $tuition_invoice_details;

    /**
     * All discounts for this academic year
     */
    public $discounts;

    /**
     * Selected discounts in interview form
     */
    public array $selected_discounts = [];

    /**
     * Invoice amounts
     */
    public array $amounts = [];

    /**
     * For change and show discounts
     */
    public Interview $interview;

    /**
     * Listeners
     *
     * @var string[]
     */
    protected $listeners = ['refresh' => '$refresh'];

    // Getting principal and financial manager accesses
    public function getFilteredAccessesPF($userAccessInfo): array
    {
        $principalAccess = [];
        $financialManagerAccess = [];

        if (! empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (! empty($userAccessInfo->financial_manager)) {
            $financialManagerAccess = explode('|', $userAccessInfo->financial_manager);
        }

        return array_filter(array_unique(array_merge($principalAccess, $financialManagerAccess)));
    }

    /**
     * Change invoice amount
     */
    public function changeInvoiceAmount($invoice_id): void
    {
        $newAmount = $this->amounts[$invoice_id];
        TuitionInvoiceDetails::findOrFail($invoice_id)->update(['amount' => $newAmount]);

        TuitionInvoiceEditHistory::create([
            'invoice_details_id' => $invoice_id,
            'description' => $invoice_id,
            'user' => auth()->user()->id,
        ]);
        $this->dispatch('refresh')->self();
    }

    /**
     * Mount the component
     */
    public function mount($appliance_id): void
    {
        $this->appliance_status = StudentApplianceStatus::findOrFail($appliance_id);

        if (auth()->user()->hasRole(['Principal', 'Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();

            $this->my_students = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->whereIn('student_appliance_statuses.academic_year', $academicYears)
                ->where('student_appliance_statuses.id', $appliance_id)
                ->pluck('student_appliance_statuses.id')->toArray();
            $this->tuition_invoices = TuitionInvoices::where('appliance_id', $this->my_students)->pluck('id')->toArray();
            $this->tuition_invoice_details = TuitionInvoiceDetails::with(['tuitionInvoiceDetails', 'invoiceDetails', 'paymentMethodInfo'])
                ->whereIn('tuition_invoice_id', $this->tuition_invoices)
                ->get();
        } elseif (auth()->user()->hasRole('Super Admin')) {
            $this->my_students = StudentInformation::join('student_appliance_statuses', 'student_informations.student_id', '=', 'student_appliance_statuses.student_id')
                ->whereNotNull('tuition_payment_status')
                ->where('student_appliance_statuses.id', $appliance_id)
                ->pluck('student_appliance_statuses.id')->toArray();

            $this->tuition_invoices = TuitionInvoices::whereIn('appliance_id', $this->my_students)->pluck('id')->toArray();
            $this->tuition_invoice_details = TuitionInvoiceDetails::with('tuitionInvoiceDetails')
                ->with('invoiceDetails')
                ->with('paymentMethodInfo')
                ->whereIn('tuition_invoice_id', $this->tuition_invoices)
                ->get();
        } else {
            abort(403);
        }

        foreach ($this->tuition_invoice_details as $invoice) {
            $this->amounts[$invoice->id] = $invoice->amount;
        }

        $applicationInformation = ApplicationTiming::join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
            ->join('application_reservations', 'applications.id', '=', 'application_reservations.application_id')
            ->where('application_reservations.student_id', $this->appliance_status->student_id)
            ->where('application_reservations.payment_status', 1)
            ->where('application_timings.academic_year', $this->appliance_status->academic_year)
            ->where('application_reservations.deleted_at', null)
            ->latest('application_reservations.id')
            ->first();

        $this->interview = Interview::where('application_id', $applicationInformation->application_id)->where('interview_type', 3)->latest()->firstOrFail();
        $interview_form = json_decode($this->interview->interview_form, true);
        if (isset($interview_form['discount'])) {
            $this->selected_discounts = $interview_form['discount'];
        }

        $this->loadDiscounts();
    }

    public function loadDiscounts()
    {
        $this->discounts = Discount::with('allDiscounts')
            ->whereAcademicYear($this->tuition_invoice_details[0]->tuitionInvoiceDetails->applianceInformation->academic_year)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();
    }
    public function changeDiscounts(): void
    {
        $interview_form = json_decode($this->interview->interview_form, true);
        $interview_form['discount'] = $this->selected_discounts;
        $interview_form = json_encode($interview_form);
        $this->interview->interview_form = $interview_form;
        $this->interview->save();
        session()->flash('success','Discounts have been changed.');
        $this->loadDiscounts();
    }
}
