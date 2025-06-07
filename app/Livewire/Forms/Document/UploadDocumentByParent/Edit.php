<?php

namespace App\Livewire\Forms\Document\UploadDocumentByParent;

use Livewire\Attributes\Validate;
use Livewire\Form;

class Edit extends Form
{
    #[Validate(['required', 'integer', 'exists:blood_groups,id'])]
    public $blood_group = null;

    #[Validate(['nullable', 'string', 'max:255'])]
    public $other_considerations;

    #[Validate(['required', 'integer', 'exists:guardian_student_relationships,id'])]
    public $relationship;

    #[Validate(['required', 'string', 'in:Married,Divorced'])]
    public $marital_status;

    #[Validate(['nullable', 'string', 'max:30'])]
    public $relation_name;

    #[Validate(['required', 'string', 'max:50'])]
    public $father_name;

    #[Validate(['required', 'string', 'max:50'])]
    public $father_family;

    #[Validate(['required', 'integer', 'max_digits:15'])]
    public $father_mobile;

    #[Validate(['nullable', 'email', 'max:50'])]
    public $father_email;

    #[Validate(['required', 'string', 'max:100'])]
    public $father_occupation;

    #[Validate(['required', 'string', 'max:100'], message: [
        'father_qualification.required' => 'The father degree field is required.',
    ])]
    public $father_qualification;

    #[Validate(['required', 'string', 'max:25'])]
    public $father_passport_number;

    #[Validate(['required', 'integer', 'exists:countries,id'])]
    public $father_nationality;

    #[Validate(['required', 'string', 'max:50'])]
    public $mother_name;

    #[Validate(['required', 'string', 'max:50'])]
    public $mother_family;

    #[Validate(['required', 'integer', 'max_digits:15'])]
    public $mother_mobile;

    #[Validate(['nullable', 'email', 'max:50'])]
    public $mother_email;

    #[Validate(['required', 'string', 'max:100'])]
    public $mother_occupation;

    #[Validate(['required', 'string', 'max:100'], message: [
        'mother_qualification.required' => 'The mother degree field is required.',
    ])]
    public $mother_qualification;

    #[Validate(['required', 'string', 'max:25'])]
    public $mother_passport_number;

    #[Validate(['required', 'integer', 'exists:countries,id'])]
    public $mother_nationality;

    #[Validate(['nullable', 'string', 'max:100'])]
    public $previous_school_name;

    #[Validate(['required', 'integer', 'exists:countries,id'])]
    public $previous_school_country;

    #[Validate(['nullable', 'string', 'max:300'])]
    public $student_skills;

    #[Validate(['nullable', 'string', 'max:300'])]
    public $miscellaneous;

    #[Validate(['required', 'string', 'max:25'])]
    public $student_passport_number;

    #[Validate(['required', 'date'])]
    public $passport_expiry_date;

    #[Validate(['required', 'string', 'max:25'])]
    public $student_iranian_visa;

    #[Validate(['required', 'date'])]
    public $iranian_residence_expiry;

    #[Validate(['required', 'string', 'max:25'])]
    public $student_iranian_faragir_code;

    #[Validate(['required', 'string', 'max:25'])]
    public $student_iranian_sanad_code;

    #[Validate(['required', 'file', 'mimes:pdf,jpg,bmp,jpeg,png'])]
    public $father_passport_file;

    #[Validate(['required', 'file', 'mimes:pdf,jpg,bmp,jpeg,png'])]
    public $mother_passport_file;

    #[Validate(['nullable', 'file', 'mimes:pdf,jpg,bmp,jpeg,png'])]
    public $latest_report_card;

    #[Validate(['required', 'file', 'mimes:pdf,jpg,bmp,jpeg,png'])]
    public $student_passport_file;

    #[Validate(['required', 'file', 'mimes:pdf,jpg,bmp,jpeg,png'])]
    public $student_passport_photo_file;

    #[Validate(['nullable', 'file', 'mimes:pdf,jpg,bmp,jpeg,png'])]
    public $residence_document_file;
}
