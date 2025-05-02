<?php

namespace App\Livewire\Forms\Document\UploadDocumentByParent;

use Livewire\Attributes\Validate;
use Livewire\Form;

class Create extends Form
{
    #[Validate(['required','integer','exists:blood_groups,id'])]
    public $blood_group;

    #[Validate(['nullable','string','max:255'])]
    public $other_considerations;

    #[Validate(['required','integer','exists:guardian_student_relationships,id'])]
    public $relationship;
}
