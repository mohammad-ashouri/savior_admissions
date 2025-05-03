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
use Livewire\Attributes\Url;
use Livewire\Component;
use Spatie\LivewireFilepond\WithFilePond;

class Create extends Component
{
    use WithFilePond;

    /**
     * Make form for create post
     * @var \App\Livewire\Forms\Document\UploadDocumentByParent\Create
     */
    public \App\Livewire\Forms\Document\UploadDocumentByParent\Create $form;

    /**
     * Student id variable
     */
    #[Url]
    public int $student_id;

    /**
     * Student information variable
     * @var StudentInformation
     */
    public StudentInformation $student_information;

    /**
     * Student appliance status
     * @var StudentApplianceStatus
     */
    public StudentApplianceStatus $student_appliance_status;

    /**
     * Mount the component
     * @param $student_id
     * @return void
     */
    public function mount($student_id): void
    {
        $this->student_id = $student_id;

        $checkStudentApplianceStatus = StudentApplianceStatus::whereStudentId($student_id)->whereDocumentsUploaded(0)->firstOrFail();
        $studentInformation = StudentInformation::with('generalInformations')->whereStudentId($student_id)->whereGuardian(auth()->user()->id)->firstOrFail();
        $this->student_information = $studentInformation;
        $this->student_appliance_status = $checkStudentApplianceStatus;
    }

    public function store()
    {
        $this->form->validate();

        $fatherPassportFileName = 'FatherPassportScan_' . now()->format('Y-m-d_H-i-s');
        $fatherPassportFileExtension = $this->form->father_passport_file->getClientOriginalExtension();
        $fatherPassportFile = $this->form->father_passport_file->storeAs(
            'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
            "$fatherPassportFileName.$fatherPassportFileExtension"
        );

        $motherPassportFileName = 'MotherPassportScan_' . now()->format('Y-m-d_H-i-s');
        $motherPassportFileExtension = $this->form->mother_passport_file->getClientOriginalExtension();
        $motherPassportFileName = $this->form->mother_passport_file->storeAs(
            'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
            "$motherPassportFileName.$motherPassportFileExtension"
        );

        $studentPassportFileName = 'StudentPassportFile_' . now()->format('Y-m-d_H-i-s');
        $studentPassportFileExtension = $this->form->student_passport_file->getClientOriginalExtension();
        $studentPassportFileName = $this->form->student_passport_file->storeAs(
            'public/uploads/Documents/' . $this->student_appliance_status->student_id . '/Appliance_' . $this->student_appliance_status->id,
            "$studentPassportFileName.$studentPassportFileExtension"
        );

        $latestReportCard_FileName = '';
        if ($this->form->latest_report_card) {
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
        }

        $residenceDocumentFile_FileName = '';
        if ($this->form->residence_document_file) {
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
        }

        $files = json_encode(
            [
                'father_passport_file' => $fatherPassportFile,
                'mother_passport_file' => $motherPassportFileName,
                'latest_report_card' => $latestReportCard_FileName,
                'student_passport_file' => $studentPassportFileName,
                'residence_document_file' => $residenceDocumentFile_FileName,
            ], true);

        $this->form->toArray()['student_id'] = $this->student_appliance_status->student_id;

        $evidences = new Evidence;
        $evidences->appliance_id = $this->student_appliance_status->id;
        $evidences->informations = json_encode($this->form->toArray());
        $evidences->files = $files;
        $evidences->save();

        $this->student_appliance_status->documents_uploaded = 2;
        $this->student_appliance_status->description = null;
        $this->student_appliance_status->save();

        session()->flash('success', 'Documents uploaded successfully. Please wait for the confirmation of the documents sent.');
        return redirect()->to(route('dashboard'));
    }

    /**
     * Render the component
     * @return mixed
     */
    public function render(): mixed
    {
        return view('livewire.documents.upload-documents-parent.create', [
            'bloodGroups' => BloodGroup::get(),
            'guardianStudentRelationships' => GuardianStudentRelationship::get(),
            'countries' => Country::orderBy('en_short_name', 'asc')->get(),
            'nationalities' => Country::orderBy('nationality', 'asc')->get(),
        ])->layout('layouts.panel');
    }
}
