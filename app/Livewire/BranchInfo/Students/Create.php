<?php

namespace App\Livewire\BranchInfo\Students;

use App\Models\Catalogs\CurrentIdentificationType;
use App\Models\Country;
use App\Models\GeneralInformation;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Create extends Component
{
    public $birthplaces;
    public $nationalities;
    public $identificationTypes;

    #[Validate('required|string|max:50')]
    public $first_name_fa;
    #[Validate('required|string|max:50')]
    public $last_name_fa;
    #[Validate('required|string|max:50')]
    public $first_name_en;
    #[Validate('required|string|max:50')]
    public $last_name_en;
    #[Validate('required|date')]
    public $birthdate;
    #[Validate('required|int|exists:countries,id')]
    public $birthplace = '';
    #[Validate('required|int|exists:countries,id')]
    public $nationality = '';
    #[Validate('required|string|in:Male,Female')]
    public $gender = '';
    #[Validate('required|string|unique:general_informations,faragir_code')]
    public $faragir_code;

    public function mount()
    {
        $this->birthplaces = Country::orderBy('en_short_name', 'asc')->get();
        $this->nationalities = Country::orderBy('nationality', 'asc')->select('nationality', 'id')->distinct()->get();
        $this->identificationTypes = CurrentIdentificationType::get();
    }

    public function store()
    {
        LivewireAlert::title('Are you sure?')
            ->withConfirmButton('Yes, Save')
            ->withDenyButton('No')
            ->onConfirm('saveData')
            ->asConfirm()
            ->show();
    }

    public function saveData()
    {
        $this->validate();
        //Check student information
        $allMyStudents = StudentInformation::whereGuardian(auth()->user()->id)->get()->pluck('student_id')->toArray();
        $allGeneralInformation = GeneralInformation::whereIn('user_id', $allMyStudents)
            ->whereFirstNameEn($this->first_name_en)
            ->whereLastNameEn($this->last_name_en)
            ->whereGender($this->gender)
            ->first();

        if (!empty($allGeneralInformation)) {
            $this->addError('error', 'Duplicate student entered. Please check your student list!');
        }

        $lastStudent = User::whereHas('roles', function ($query) {
            $query->whereName('Student');
        })->orderByDesc('id')->first();

        $user = new User;
        $user->id = $lastStudent->id + 1;
        $user->password = Hash::make('Aa12345678');
        $user->status = 0;
        $user->save();
        $user->assignRole('Student');

        $generalInformation = new GeneralInformation;
        $generalInformation->user_id = $user->id;
        $generalInformation->first_name_fa = $this->first_name_fa;
        $generalInformation->last_name_fa = $this->last_name_fa;
        $generalInformation->first_name_en = $this->first_name_en;
        $generalInformation->last_name_en = $this->last_name_en;
        $generalInformation->birthdate = $this->birthdate;
        $generalInformation->birthplace = $this->birthplace;
        $generalInformation->nationality = $this->nationality;
        $generalInformation->gender = $this->gender;
        $generalInformation->save();

        $studentInformation = new StudentInformation;
        $studentInformation->student_id = $user->id;
        $studentInformation->guardian = auth()->user()->id;
        $studentInformation->current_nationality = $this->nationality;
        //        $studentInformation->current_identification_type = $current_identification_type;
        $studentInformation->current_identification_code = $this->faragir_code;
        $studentInformation->save();
    }
}
