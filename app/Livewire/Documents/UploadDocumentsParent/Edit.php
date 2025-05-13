<?php

namespace App\Livewire\Documents\UploadDocumentsParent;

use App\Models\Branch\Evidence;
use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\BloodGroup;
use App\Models\Catalogs\DocumentType;
use App\Models\Catalogs\GuardianStudentRelationship;
use App\Models\Country;
use App\Models\Document;
use App\Models\StudentInformation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class Edit extends Component
{
    use WithFilePond;

    /**
     * Make form for create post
     */
    public \App\Livewire\Forms\Document\UploadDocumentByParent\Edit $form;

    /**
     * Student id variable
     */
    #[Url]
    public int $student_id;

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
     * Mount the component
     */
    public function mount($student_id): void
    {
        $this->student_id = $student_id;

        $checkStudentApplianceStatus = StudentApplianceStatus::with('evidences')->whereStudentId($student_id)->whereDocumentsUploaded(3)->where('documents_uploaded_approval', 2)->firstOrFail();
        $studentInformation = StudentInformation::with('generalInformations')->whereStudentId($student_id)->whereGuardian(auth()->user()->id)->firstOrFail();
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

    public function update()
    {
        $files = json_decode($this->evidence->files, true);

        if ($this->form->father_passport_file && is_object($this->form->father_passport_file)) {
            $fatherPassportFileName = 'FatherPassportScan_' . now()->format('Y-m-d_H-i-s');
            $fatherPassportFileExtension = $this->form->father_passport_file->getClientOriginalExtension();
            $fatherPassportFile = $this->form->father_passport_file->storeAs(
                'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
                "$fatherPassportFileName.$fatherPassportFileExtension"
            );
        } else {
            $fatherPassportFile = $files['father_passport_file'];
        }

        if ($this->form->mother_passport_file && is_object($this->form->mother_passport_file)) {
            $motherPassportFileName = 'MotherPassportScan_' . now()->format('Y-m-d_H-i-s');
            $motherPassportFileExtension = $this->form->mother_passport_file->getClientOriginalExtension();
            $motherPassportFileName = $this->form->mother_passport_file->storeAs(
                'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
                "$motherPassportFileName.$motherPassportFileExtension"
            );
        } else {
            $motherPassportFileName = $files['mother_passport_file'];
        }

        if ($this->form->student_passport_file && is_object($this->form->student_passport_file)) {
            $studentPassportFileName = 'StudentPassportFile_' . now()->format('Y-m-d_H-i-s');
            $studentPassportFileExtension = $this->form->student_passport_file->getClientOriginalExtension();
            $studentPassportFileName = $this->form->student_passport_file->storeAs(
                'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
                "$studentPassportFileName.$studentPassportFileExtension"
            );
        } else {
            $studentPassportFileName = $files['student_passport_file'];
        }

        if ($this->form->student_passport_photo_file && is_object($this->form->student_passport_photo_file)) {
            $studentPassportPhotoFileName = 'StudentPassportPhotoFile_' . now()->format('Y-m-d_H-i-s');
            $studentPassportPhotoFileExtension = $this->form->student_passport_photo_file->getClientOriginalExtension();
            $studentPassportPhotoFileName = $this->form->student_passport_photo_file->storeAs(
                'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
                "$studentPassportPhotoFileName.$studentPassportPhotoFileExtension"
            );
        } else {
            $studentPassportPhotoFileName = $files['student_passport_photo_file'];
        }

        if ($this->form->latest_report_card && is_object($this->form->latest_report_card)) {
            $latestReportCard_FileName = 'LatestReportCard_' . now()->format('Y-m-d_H-i-s');
            $latestReportCard_FileExtension = $this->form->latest_report_card->getClientOriginalExtension();
            $latestReportCard_FileName = $this->form->latest_report_card->storeAs(
                'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
                "$latestReportCard_FileName.$latestReportCard_FileExtension"
            );
            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $latestReportCard_FileName;
            $document->save();

            $document = new Document;
            $document->user_id = $this->student_appliance_status->student_id;
            $document->document_type_id = DocumentType::whereName('Passport photo - page 1')->first()->id;
            $document->src = $latestReportCard_FileName;
            $document->save();
        } else {
            $latestReportCard_FileName = $files['latest_report_card'];
        }

        $residenceDocumentFile_FileName = '';
        if ($this->form->residence_document_file && is_object($this->form->residence_document_file)) {
            $residenceDocumentFile_FileName = 'LatestReportCard_' . now()->format('Y-m-d_H-i-s');
            $residenceDocument_FileExtension = $this->form->residence_document_file->getClientOriginalExtension();
            $residenceDocumentFile_FileName = $this->form->residence_document_file->storeAs(
                'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
                "$residenceDocumentFile_FileName.$residenceDocument_FileExtension"
            );
            $document = new Document;
            $document->user_id = auth()->user()->id;
            $document->document_type_id = DocumentType::whereName('Residence Document')->first()->id;
            $document->src = $residenceDocumentFile_FileName;
            $document->save();

            $document = new Document;
            $document->user_id = $this->student_appliance_status->student_id;
            $document->document_type_id = DocumentType::whereName('Residence Document')->first()->id;
            $document->src = $residenceDocumentFile_FileName;
            $document->save();
        } else {
            $residenceDocumentScan_FileName = $files['residence_document_file'];
        }

        $files = json_encode(
            [
                'father_passport_file' => $fatherPassportFile,
                'mother_passport_file' => $motherPassportFileName,
                'latest_report_card' => $latestReportCard_FileName,
                'student_passport_file' => $studentPassportFileName,
                'residence_document_file' => $residenceDocumentFile_FileName,
                'student_passport_photo_file' => $studentPassportPhotoFileName,
            ], true);

        $this->form->toArray()['student_id'] = $this->student_appliance_status->student_id;

        $this->evidence->appliance_id = $this->student_appliance_status->id;
        $this->evidence->informations = json_encode($this->form->toArray());
        $this->evidence->files = $files;
        $this->evidence->save();

        $this->student_appliance_status->documents_uploaded = 2;
        $this->student_appliance_status->description = null;
        $this->student_appliance_status->save();

        session()->flash('success', 'Documents uploaded successfully. Please wait for the confirmation of the documents sent.');

        return redirect()->to(route('dashboard'));
    }

    /**
     * Render the component
     */
    public function render(): mixed
    {
        return view('livewire.documents.upload-documents-parent.edit', [
            'bloodGroups' => BloodGroup::get(),
            'guardianStudentRelationships' => GuardianStudentRelationship::get(),
            'countries' => Country::orderBy('en_short_name', 'asc')->get(),
            'nationalities' => Country::orderBy('nationality', 'asc')->get(),
        ])->layout('Layouts.panel');
    }
}
