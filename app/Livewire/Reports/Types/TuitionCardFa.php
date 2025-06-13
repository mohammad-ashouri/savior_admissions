<?php

namespace App\Livewire\Reports\Types;

use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\Level;
use App\Models\Finance\Tuition;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\UserAccessInformation;
use App\Traits\CheckPermissions;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class TuitionCardFa extends Component
{
    use CheckPermissions;

    #[Url]
    public $appliance_id;

    public $student_appliance_status;

    public $application_information;
    public $now_date;
    public $tuitionType;

    public $evidences = [];

    public $grade;

    public $payment_types;

    public $total_amount = 0;
    public $payment_amount = 0;

    public function setPaymentTypes()
    {
        $this->payment_types = [
            '1' => __('پرداخت کامل'),
            '2' => __('پرداخت دو قسطه'),
            '3' => __('پرداخت چهار قسطه'),
            '4' => __('شهریه کامل با پیش پرداخت'),
            '5' => __('پرداخت سه قسطه'),
            '6' => __('پرداخت هفت قسطه'),
        ];
    }

    public function mount()
    {
        $this->now_date = Jalalian::forge('today')->format('%A, %d %B %Y');
        $this->setPaymentTypes();

        if (!property_exists($this, 'appliance_id') || $this->appliance_id === null) {
            abort(404);
        }

        if (auth()->user()->hasRole('Super Admin')) {
            $this->student_appliance_status = StudentApplianceStatus::with('tuitionInvoices')
                ->where('tuition_payment_status', 'Paid')
                ->whereId($this->appliance_id)
                ->first();
        } elseif (auth()->user()->hasRole(['Principal', 'Financial Manager'])) {
            // Convert accesses to arrays and remove duplicates
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPF($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academic_years = AcademicYear::whereIn('school_id', $filteredArray)->orderByDesc('id')->get();

            $this->student_appliance_status = StudentApplianceStatus::with('tuitionInvoices')
                ->where('tuition_payment_status', 'Paid')
                ->whereId($this->appliance_id)
                ->whereIn('academic_year', $academic_years)
                ->first();
        } elseif (auth()->user()->hasRole(['Parent'])) {
            $myStudents = StudentInformation::whereGuardian(auth()->user()->id)->pluck('student_id')->toArray();
            $this->student_appliance_status = StudentApplianceStatus::with('tuitionInvoices')
                ->whereIn('student_id', $myStudents)
                ->whereId($this->appliance_id)
                ->where('tuition_payment_status', 'Paid')
                ->first();
        }

        if (empty($this->student_appliance_status)) {
            abort(404, message: 'Tuition card not found');
        }

        $this->evidences = json_decode($this->student_appliance_status->evidences->informations, true);

        $this->tuitionType = $this->payment_types[$this->student_appliance_status->tuitionInvoices->payment_type] ?? __('translated_fa.Unknown Payment Type');

        $this->application_information = ApplicationTiming::join('applications', 'application_timings.id', '=', 'applications.application_timing_id')
            ->join('application_reservations', 'applications.id', '=', 'application_reservations.application_id')
            ->where('application_reservations.student_id', $this->student_appliance_status->student_id)
            ->where('application_reservations.payment_status', 1)
            ->where('application_reservations.deleted_at', null)
            ->where('application_timings.academic_year', $this->student_appliance_status->academic_year)
            ->latest('application_reservations.id')
            ->first();

        $this->grade = Level::find($this->application_information->level);

        $this->setTuitionPaymentInfo();
    }

    public function setTuitionPaymentInfo()
    {
        $systemTuitionInfo = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')->where('tuition_details.level', $this->grade->id)->first();

        if (in_array($this->student_appliance_status->academic_year, [1, 2, 3])) {
            if (isset($this->evidences['foreign_school']) and $this->evidences['foreign_school'] == 'Yes') {
                $foreignSchool = true;
            } else {
                $foreignSchool = false;
            }
        } else {
            $interview_form = Interview::where('interview_type', 3)->where('application_id', $this->application_information->application_id)->latest()->first();
            $interview_form = json_decode($interview_form->interview_form, true);
            if (isset($interview_form['foreign_school']) and $interview_form['foreign_school'] == 'Yes') {
                $foreignSchool = true;
            } else {
                $foreignSchool = false;
            }
        }

        foreach ($this->student_appliance_status->tuitionInvoices->invoiceDetails as $invoices) {
            $this->total_amount = $invoices->amount + $this->total_amount;
        }

        $paymentAmount = null;
        if ($foreignSchool) {
            switch ($this->student_appliance_status->tuitionInvoices->payment_type) {
                case 1:
                case 4:
                    $this->payment_amount = str_replace(',', '', json_decode($systemTuitionInfo->full_payment_ministry, true)['full_payment_irr_ministry']);
                    break;
                case 2:
                    $this->payment_amount = str_replace(',', '', json_decode($systemTuitionInfo->two_installment_payment_ministry, true)['two_installment_amount_irr_ministry']);
                    break;
                case 3:
                    $this->payment_amount = str_replace(',', '', json_decode($systemTuitionInfo->four_installment_payment_ministry, true)['four_installment_amount_irr_ministry']);
                    break;
            }
        } else {
            switch ($this->student_appliance_status->tuitionInvoices->payment_type) {
                case 1:
                case 4:
                    $this->payment_amount = str_replace(',', '', json_decode($systemTuitionInfo->full_payment, true)['full_payment_irr']);
                    break;
                case 2:
                    $this->payment_amount = str_replace(',', '', json_decode($systemTuitionInfo->two_installment_payment, true)['two_installment_amount_irr']);
                    break;
                case 3:
                    $this->payment_amount = str_replace(',', '', json_decode($systemTuitionInfo->four_installment_payment, true)['four_installment_amount_irr']);
                    break;
            }
        }
    }

    public function render()
    {
        return view('livewire.reports.types.tuition-card-fa');
    }
}
