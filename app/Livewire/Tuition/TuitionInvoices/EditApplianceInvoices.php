<?php

namespace App\Livewire\Tuition\TuitionInvoices;

use App\Models\Branch\ApplicationTiming;
use App\Models\Branch\Interview;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Finance\Discount;
use App\Models\Finance\GrantedFamilyDiscount;
use App\Models\Finance\TuitionInvoiceDetails;
use App\Models\Finance\TuitionInvoiceEditHistory;
use App\Models\Finance\TuitionInvoices;
use App\Models\StudentInformation;
use App\Models\UserAccessInformation;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class EditApplianceInvoices extends Component
{
    use WithFilePond;

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
     * Selected tuition invoice details
     * @var TuitionInvoiceDetails
     */
    public TuitionInvoiceDetails $selected_invoice;

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
     * History file description
     * @var string
     */
    #[Validate('required|string|max:255')]
    public string $description;

    /**
     * History file
     * @var
     */
    #[Validate('required|file|mimes:jpg,bmp,pdf,jpeg,png')]
    public $file;

    /**
     * History id
     * @var int
     */
    #[Validate('required|integer|exists:tuition_invoice_edit_histories,id')]
    public int $history_id;

    /**
     * Amount of family discount in 2024-2025 academic years
     * @var int
     */
    public $family_discount = 0;

    public function changeFamilyDiscount()
    {
        GrantedFamilyDiscount::where('appliance_id', $this->appliance_status->id)->update([
            'discount_price' => $this->family_discount
        ]);

        session()->flash('success', 'Family discount have been changed.');

        $this->loadDiscounts();
    }

    /**
     * Listeners
     *
     * @var string[]
     */
    protected $listeners = [
        'refresh' => '$refresh',
        'open-file-modal' => 'openFileModal',
        'resetFileModalValues' => 'resetFileModalValues',
        'show-history-files' => 'showHistoryFiles',
    ];

    /**
     * Files for history
     * @var array
     */
    public array $files = [];

    /**
     * Invoice files
     * @var array
     */
    public array $invoice_files = [];

    /**
     * Tuition invoice replacement file
     * @var
     */
    public $tuition_invoice_replacement_file;

    // Getting principal and financial manager accesses
    public function getFilteredAccessesPF($userAccessInfo): array
    {
        $principalAccess = [];
        $financialManagerAccess = [];

        if (!empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (!empty($userAccessInfo->financial_manager)) {
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
        $invoice_details = TuitionInvoiceDetails::findOrFail($invoice_id);

        TuitionInvoiceEditHistory::create([
            'invoice_details_id' => $invoice_id,
            'description' => json_encode([
                'old_amount' => $invoice_details->amount,
                'new_amount' => $newAmount,
            ]),
            'user' => auth()->user()->id,
        ]);
        $invoice_details->update(['amount' => $newAmount]);
        $this->loadDiscounts();
        session()->flash('change-invoice-amount-success', 'Invoice amount have been changed.');
    }

    /**
     * Mount the component
     */
    public function mount($appliance_id): void
    {
        $this->appliance_status = StudentApplianceStatus::findOrFail($appliance_id);

        if (!auth()->user()->hasRole(['Super Admin', 'Financial Manager (Full Access)'])) {
            abort(403);
        }

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

        if (in_array($this->appliance_status->academic_year, [1, 2, 3])) {
            $this->family_discount = GrantedFamilyDiscount::where('appliance_id', $appliance_id)->first()->discount_price;
        }
        $this->loadDiscounts();
    }

    /**
     * Load academic year discounts
     */
    public function loadDiscounts(): void
    {
        $this->discounts = Discount::with('allDiscounts')
            ->whereAcademicYear($this->tuition_invoice_details[0]->tuitionInvoiceDetails->applianceInformation->academic_year)
            ->join('discount_details', 'discounts.id', '=', 'discount_details.discount_id')
            ->where('discount_details.status', 1)
            ->where('discount_details.interviewer_permission', 1)
            ->get();
    }

    /**
     * Change discounts after clicking on any checkboxes
     * @return void
     */
    public function changeDiscounts(): void
    {
        $interview_form = json_decode($this->interview->interview_form, true);
        $interview_form['discount'] = $this->selected_discounts;
        $interview_form = json_encode($interview_form);
        $this->interview->interview_form = $interview_form;
        $this->interview->save();
        session()->flash('change-discount-success', 'Discounts have been changed.');
        $this->loadDiscounts();
    }

    /**
     * Set initial values after file modal opened
     * @param $history_id
     * @return void
     */
    public function openFileModal($history_id): void
    {
        TuitionInvoiceEditHistory::findOrFail($history_id);
        $this->history_id = $history_id;
        $this->loadDiscounts();
    }

    /**
     * Reset modal values
     * @return void
     */
    public function resetFileModalValues(): void
    {
        $this->reset('description', 'file');
        $this->dispatch('filepond-reset');
        $this->resetErrorBag();
        $this->loadDiscounts();
    }

    public function uploadFile(): void
    {
        $this->validate();
        $history = TuitionInvoiceEditHistory::find($this->history_id);
        $file = $this->file->store('uploads/Documents/' . $this->appliance_status->student_id . '/Appliance_' . $this->appliance_status->id . '/Tuitions/Histories/' . $history->id, 'public');
        $historyFile = json_decode($history->file);
        $historyFile[] = ['description' => $this->description, 'file' => $file, 'created_at' => date('Y-m-d H:i:s'), 'user' => auth()->user()->id];
        $history->file = json_encode($historyFile);
        $history->save();

        $this->loadDiscounts();
        session()->flash('success', 'File uploaded successfully');
        $this->dispatch('close-upload-modal');
        $this->resetFileModalValues();
    }

    /**
     * Show history files
     * @param $history_id
     * @return void
     */
    public function showHistoryFiles($history_id): void
    {
        $history = TuitionInvoiceEditHistory::findOrFail($history_id);
        $this->files = json_decode($history->file, true) ?? [];
        $this->loadDiscounts();
    }

    /**
     * Show invoice payment files for offline payments
     * @param $invoice_id
     * @return void
     */
    public function showPaymentFiles($invoice_id): void
    {
        $this->selected_invoice = TuitionInvoiceDetails::find($invoice_id);
        $this->invoice_files = json_decode($this->selected_invoice->description, true)['files'];
        $this->loadDiscounts();
    }

    /**
     * Change tuition invoice files in offline payment
     * @param $file_key
     * @return void
     */
    public function changeTuitionInvoiceFile($file_key): void
    {
        $this->validate([
            'tuition_invoice_replacement_file' => 'required|file|max:5000|mimes:jpeg,png,jpg,bmp,pdf|max:2048',
        ]);

        $extension = $this->tuition_invoice_replacement_file->getClientOriginalExtension();
        $fileName = 'Tuition_' . date('Y-m-d_H-i-s') . '.' . $extension;

        $file = $this->tuition_invoice_replacement_file->storeAs(
            'uploads/Documents/' . $this->appliance_status->student_id . '/Appliance_' . $this->appliance_status->id . '/Tuitions/',
            $fileName,
            'public'
        );

        $invoice_file = json_decode($this->selected_invoice->description, true);
        $invoice_file['files'][$file_key] = $file;

        $this->selected_invoice->description = json_encode($invoice_file);
        $this->selected_invoice->save();

        session()->flash('success', 'Invoice file changed successfully');
        $this->dispatch('close-change-invoice-file-modal');
        $this->resetFileModalValues();
    }
}
