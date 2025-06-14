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
    public $paid_amount = 0;
    public $debt = 0;

    public $system_tuition_info;

    public $tuition_invoice_full_details = [];

    public function setPaymentTypes()
    {
        $this->payment_types = [
            '1' => __('شهریه کامل'),
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
        $this->setSystemTuitionInfo();
        $this->setTuitionPaymentInfo();
        $this->getTuitionInvoices();
    }

    public function setSystemTuitionInfo()
    {
        $this->system_tuition_info = Tuition::join('tuition_details', 'tuitions.id', '=', 'tuition_details.tuition_id')->where('tuition_details.level', $this->grade->id)->first();
    }

    public function setTuitionPaymentInfo()
    {

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
                $this->payment_amount = str_replace(',', '', json_decode($this->system_tuition_info->full_payment_ministry, true)['full_payment_irr_ministry']);
                    break;
                case 2:
                    $this->payment_amount = str_replace(',', '', json_decode($this->system_tuition_info->two_installment_payment_ministry, true)['two_installment_amount_irr_ministry']);
                    break;
                case 3:
                    $this->payment_amount = str_replace(',', '', json_decode($this->system_tuition_info->four_installment_payment_ministry, true)['four_installment_amount_irr_ministry']);
                    break;
            }
        } else {
            switch ($this->student_appliance_status->tuitionInvoices->payment_type) {
                case 1:
                case 4:
                $this->payment_amount = str_replace(',', '', json_decode($this->system_tuition_info->full_payment, true)['full_payment_irr']);
                    break;
                case 2:
                    $this->payment_amount = str_replace(',', '', json_decode($this->system_tuition_info->two_installment_payment, true)['two_installment_amount_irr']);
                    break;
                case 3:
                    $this->payment_amount = str_replace(',', '', json_decode($this->system_tuition_info->four_installment_payment, true)['four_installment_amount_irr']);
                    break;
            }
        }
    }

    function translateTuitionType($tuitionType)
    {
        switch ($tuitionType) {
            case 'Two Installment Advance':
            case 'Three Installment Advance':
            case 'Four Installment Advance':
            case 'Seven Installment Advance':
            case 'Full Payment With Advance':
                return 'پیش پرداخت';
            case 'Four Installment - Installment 1':
            case 'Full Payment With Advance - Installment':
            case 'Seven Installment - Installment 1':
            case 'Three Installment - Installment 1':
            case 'Two Installment - Installment 1':
                return 'قسط اول';
            case 'Four Installment - Installment 2':
            case 'Seven Installment - Installment 2':
            case 'Three Installment - Installment 2':
            case 'Two Installment - Installment 2':
                return 'قسط دوم';
            case 'Four Installment - Installment 3':
            case 'Seven Installment - Installment 3':
            case 'Three Installment - Installment 3':
                return 'قسط سوم';
            case 'Four Installment - Installment 4':
            case 'Seven Installment - Installment 4':
                return 'قسط چهارم';
            case 'Seven Installment - Installment 5':
                return 'قسط پنجم';
            case 'Seven Installment - Installment 6':
                return 'قسط ششم';
            case 'Seven Installment - Installment 7':
                return 'قسط هفتم';
            case 'Full Payment':
                return 'پرداخت کامل';
            default:
                return '-';
        }
    }

    function translatePaymentMethod($paymentMethodName)
    {
        switch ($paymentMethodName) {
            case '1':
                return 'پرداخت آفلاین';
            case '3':
                return 'پرداخت سفارشی';
            case '2':
                return 'پرداخت آنلاین - به پرداخت ملت';
            default:
                return 'پرداخت نشده';
        }
    }

    public function getTuitionInvoices()
    {
        $data = [];

        foreach ($this->student_appliance_status->tuitionInvoices->invoiceDetails as $key => $detail) {
            // Decode the JSON description
            $description = json_decode($detail->description, true);

            // Get the tuition_type from description
            $tuitionType = $description['tuition_type'] ?? 'Unknown';

            $dueType = null;
            if (strstr($tuitionType, 'Three') and !strstr($tuitionType, 'Advance')) {
                $dueType = 'Three';
                $dueDates = json_decode($this->system_tuition_info->three_installment_payment, true);
            }
            if (strstr($tuitionType, 'Seven') and !strstr($tuitionType, 'Advance')) {
                $dueType = 'Seven';
                $dueDates = json_decode($this->system_tuition_info->seven_installment_payment, true);
            }
            if (strstr($tuitionType, 'Four') and !strstr($tuitionType, 'Advance')) {
                $dueType = 'Four';
                $dueDates = json_decode($this->system_tuition_info->four_installment_payment, true);
            }
            if (strstr($tuitionType, 'Two') and !strstr($tuitionType, 'Advance')) {
                $dueType = 'Two';
                $dueDates = json_decode($this->system_tuition_info->two_installment_payment, true);
            }
            if (strstr($tuitionType, 'Full') and strstr($tuitionType, 'Advance') and strstr($tuitionType, 'Installment')) {
                $dueType = 'Full';
            }
            if (strstr($tuitionType, 'Full Payment With Advance')) {
                $dueType = 'Full Payment With Advance';
            }

            switch ($dueType) {
                case 'Four':
                    $jalaliDate = Jalalian::fromDateTime($dueDates["date_of_installment" . $key . "_four"]);
                    $formattedJalaliDate = $jalaliDate->format('Y/m/d');
                    break;
                case 'Two':
                    $jalaliDate = Jalalian::fromDateTime($dueDates["date_of_installment" . $key . "_two"]);
                    $formattedJalaliDate = $jalaliDate->format('Y/m/d');
                    break;
                case 'Three':
                    $jalaliDate = Jalalian::fromDateTime($dueDates["date_of_installment" . $key . "_three"]);
                    $formattedJalaliDate = $jalaliDate->format('Y/m/d');
                    break;
                case 'Seven':
                    $jalaliDate = Jalalian::fromDateTime($dueDates["date_of_installment" . $key . "_seven"]);
                    $formattedJalaliDate = $jalaliDate->format('Y/m/d');
                    break;
                case 'Full Payment With Advance':
                    $formattedJalaliDate = 'پایان شهریور';
                    break;
                default:
                    $formattedJalaliDate = '-';
            }

            $debt = $paid_amount = 0;
            if ($detail->is_paid == 0) {
                $paid_amount = $detail->customPayments->where('status', 1)->pluck('amount')->sum();
                $debt = $detail->amount - $paid_amount;
                $this->paid_amount += $paid_amount;
                $this->debt += $debt;

                $givenDate = Jalalian::fromFormat('Y/m/d', $formattedJalaliDate)->toCarbon();
                $today = Jalalian::now()->toCarbon();
                if ($paid_amount != 0) {
                    $payment_status = 'پرداخت ناقص';
                }

                if ($givenDate->lt($today) or $givenDate->eq($today)) {
                    $payment_status = 'سررسید شده';
                } else {
                    $payment_status = 'پرداخت نشده';
                }
            } elseif ($detail->is_paid == 1) {
                $paid_amount = $detail->amount;
                $this->paid_amount += $paid_amount;
                $payment_status = 'پرداخت شده';
            }

            // Prepare the detail information
            $this->tuition_invoice_full_details[$this->translateTuitionType($tuitionType)] = [
                'invoice_id' => $detail->id,
                'due_date' => $formattedJalaliDate,
                'amount' => number_format($detail->amount),
                'payment_method' => $this->translatePaymentMethod($detail->payment_method),
                'payment_status' => $payment_status,
                'date_of_payment' => $detail->is_paid == 1 ? $detail->jalali_date_of_payment : '-',
                'paid' => number_format($paid_amount),
                'debt' => number_format($debt),
            ];
        }
    }

    public function render()
    {
        return view('livewire.reports.types.tuition-card-fa');
    }
}
