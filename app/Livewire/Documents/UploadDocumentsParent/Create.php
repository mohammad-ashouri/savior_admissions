<?php

namespace App\Livewire\Documents\UploadDocumentsParent;

use App\Models\Branch\StudentApplianceStatus;
use App\Models\Catalogs\BloodGroup;
use App\Models\Catalogs\GuardianStudentRelationship;
use App\Models\Country;
use App\Models\StudentInformation;
use Livewire\Component;

class Create extends Component
{
    /**
     * Student id variable
     */
    public int $student_id;

    /**
     * Student information variable
     * @var StudentInformation
     */
    public StudentInformation $student_information;

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
