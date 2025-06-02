<?php

namespace App\Livewire\Documents\UploadDocumentsParent;

use App\Models\Branch\Evidence;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\AcademicYear;
use App\Models\Catalogs\BloodGroup;
use App\Models\Catalogs\GuardianStudentRelationship;
use App\Models\Country;
use App\Models\StudentInformation;
use App\Models\UserAccessInformation;
use App\Traits\CheckPermissions;
use App\Traits\SMS;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;

class Show extends Component
{
    use SMS, CheckPermissions;

    /**
     * Make form for create post
     */
    public \App\Livewire\Forms\Document\UploadDocumentByParent\Edit $form;

    /**
     * Appliance id variable
     */
    #[Url]
    public int $appliance_id;

    /**
     * Student information variable
     */
    public StudentInformation $student_information;

    /**
     * Evidence variable
     */
    public Evidence $evidence;

    /**
     * Student appliance status
     */
    public StudentApplianceStatus $student_appliance_status;

    /**
     * Evidence information array
     * @var array
     */
    public array $evidence_informations;

    /**
     * Evidence files array
     * @var array
     */
    public array $evidence_files;

    /**
     * Documents uploaded status
     * @var string
     */
    public string $status;

    /**
     * Seconder description
     * @var ?string
     */
    public ?string $description = null;

    public function getFilteredAccessesPA($userAccessInfo): array
    {
        $principalAccess = [];
        $admissionsOfficerAccess = [];

        if (!empty($userAccessInfo->principal)) {
            $principalAccess = explode('|', $userAccessInfo->principal);
        }

        if (!empty($userAccessInfo->admissions_officer)) {
            $admissionsOfficerAccess = explode('|', $userAccessInfo->admissions_officer);
        }

        return array_filter(array_unique(array_merge($principalAccess, $admissionsOfficerAccess)));
    }

    /**
     * Mount the component
     */
    public function mount($appliance_id): void
    {
        $this->appliance_id = $appliance_id;

        if (auth()->user()->hasRole('Super Admin')) {
            $academicYears = AcademicYear::pluck('id')->toArray();
        } elseif (auth()->user()->hasRole(['Admissions Officer','Principal'])) {
            $myAllAccesses = UserAccessInformation::whereUserId(auth()->user()->id)->first();
            $filteredArray = $this->getFilteredAccessesPA($myAllAccesses);

            // Finding academic years with status 1 in the specified schools
            $academicYears = AcademicYear::whereIn('school_id', $filteredArray)->pluck('id')->toArray();
        } else {
            abort(403);
        }

        $this->student_appliance_status = StudentApplianceStatus::with('studentInfo')
            ->with('academicYearInfo')
            ->with('evidences')
            ->whereId($appliance_id)
            ->whereIn('academic_year', $academicYears)
//            ->whereInterviewStatus('Admitted')
            ->orderBy('documents_uploaded')
            ->firstOrFail();

        $checkStudentApplianceStatus = StudentApplianceStatus::with('evidences')
            ->whereId($this->student_appliance_status->id)
            ->where('tuition_payment_status', 'Paid')
            ->firstOrFail();
        $studentInformation = StudentInformation::with('generalInformations')->whereStudentId($this->student_appliance_status->student_id)->firstOrFail();
        $evidences = Evidence::find($checkStudentApplianceStatus->evidences->id);

        $this->student_information = $studentInformation;
        $this->student_appliance_status = $checkStudentApplianceStatus;
        $this->evidence = $evidences;
        $this->evidence_informations = json_decode($this->evidence->informations, true);
        $this->evidence_files = json_decode($this->evidence->files, true);

        $mapping = [];
        foreach ($this->evidence_informations as $index => $key) {
            if (strstr('_file', $index)) {
                continue;
            }
            $mapping [$index] = $index;
        }

        foreach ($mapping as $source => $target) {
            if (isset($this->evidence_informations[$source])) {
                $this->form->{$target} = $this->evidence_informations[$source];
            }
        }

        $mapping = [];
        foreach ($this->evidence_files as $index => $key) {
            $mapping [$index] = $index;
        }

        foreach ($mapping as $source => $target) {
            if (isset($this->evidence_files[$source])) {
                $this->form->{$target} = Storage::url($this->evidence_files[$source]);
            }
        }
    }

    public function setStatus()
    {
        $studentInfo = StudentInformation::with('guardianInfo')->whereStudentId($this->student_appliance_status->student_id)->first();
        $guardianMobile = $studentInfo->guardianInfo->mobile;

        switch ($this->status) {
            case 'Accept':
//                $this->student_appliance_status->interview_status = 'Admitted';
                $this->student_appliance_status->documents_uploaded = 1;
                $this->student_appliance_status->documents_uploaded_approval = 1;
//                $this->student_appliance_status->tuition_payment_status = 'Pending';
                $this->student_appliance_status->date_of_document_approval = Carbon::now();
                $this->student_appliance_status->description = null;
                $this->sendSMS($guardianMobile, "Your documents have been approved. Please wait for principal approval.\nSavior Schools");
                break;
            case 'Reject':
                $this->student_appliance_status->documents_uploaded = 3;
                $this->student_appliance_status->documents_uploaded_approval = 2;
                $this->student_appliance_status->description = 'Documents Rejected';
                $this->student_appliance_status->date_of_document_approval = Carbon::now();
                $this->sendSMS($guardianMobile, "Your documents were rejected. To review and see the reason for rejection, please refer to your panel.\nSavior Schools");
                break;
            default:
                abort(403);
        }
        $this->student_appliance_status->seconder_description = $this->description;
        $this->student_appliance_status->documents_uploaded_seconder = auth()->user()->id;
        $this->student_appliance_status->save();

        return redirect()->to(route('Evidences'))
            ->with('success', 'Determining the status of the documents was done successfully');
    }

    public function render()
    {
        return view('livewire.documents.upload-documents-parent.show', [
            'bloodGroups' => BloodGroup::get(),
            'guardianStudentRelationships' => GuardianStudentRelationship::get(),
            'countries' => Country::orderBy('en_short_name', 'asc')->get(),
            'nationalities' => Country::orderBy('nationality', 'asc')->get(),
        ])->layout('Layouts.panel');
    }
}
