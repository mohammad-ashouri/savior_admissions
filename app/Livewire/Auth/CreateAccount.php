<?php

namespace App\Livewire\Auth;

use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Savior Schools | Create Account')]
#[Layout('Layouts.login')]
class CreateAccount extends Component
{
    public $first_name_en;
    public $last_name_en;
    public $first_name_fa;
    public $last_name_fa;
    public $father_name;
    public $gender;
    public $birthdate;
    public $nationality;
    public $birthplace;
    public $passport_number;
    public $faragir_code;
    public $country;
    public $state_city;
    public $email;
    public $mobile;
    public $address;
    public $phone;
    public $postal_code;
    public $password;
    public $password_confirmation;

    /**
     * Validation rules
     * @var string[]
     */
    protected $rules = [
        'first_name_en' => 'required|string|max:255',
        'last_name_en' => 'required|string|max:255',
        'first_name_fa' => 'required|string|max:255',
        'last_name_fa' => 'required|string|max:255',
        'father_name' => 'required|string|max:255',
        'gender' => 'required|in:Male,Female',
        'birthdate' => 'required|date',
        'nationality' => 'required|exists:countries,id',
        'birthplace' => 'required|exists:countries,id',
        'passport_number' => 'required|string|max:255',
        'faragir_code' => 'required|string|max:255',
        'country' => 'required|exists:countries,id',
        'state_city' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'mobile' => 'required|string|unique:users,mobile',
        'address' => 'required|string|max:255',
        'phone' => 'required|string|max:255',
        'postal_code' => 'required|string|max:10',
        'password' => 'required|min:8|confirmed',
    ];

    /**
     * Validation custom messages
     * @var string[]
     */
    protected $messages = [
        'first_name_en.required' => 'The first name field is required.',
        'last_name_en.required' => 'The last name field is required.',
        'first_name_fa.required' => 'The first name (farsi) field is required.',
        'last_name_fa.required' => 'The last name (farsi) field is required.',
        'passport_number.required' => 'The passport number field is required.',
        'state_city.required' => 'The residence city field is required.',
        'country.required' => 'The residence country field is required.',
    ];

    /**
     * Render the component
     * @return Application|Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function render(): Factory|View|\Illuminate\Foundation\Application|\Illuminate\View\View|Application
    {
        $countries = DB::table('countries')->get();
        return view('livewire.auth.create-account', [
            'countries' => $countries
        ]);
    }

    /**
     * Register new user
     * @return RedirectResponse
     */
    public function register(): RedirectResponse
    {
        $this->validate($this->rules, $this->messages);

        DB::transaction(function () {
            $user = User::create([
                'first_name_en' => $this->first_name_en,
                'last_name_en' => $this->last_name_en,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'password' => Hash::make($this->password),
                'status' => 2
            ]);

            GeneralInformation::create([
                'user_id' => $user->id,
                'first_name_fa' => $this->first_name_fa,
                'last_name_fa' => $this->last_name_fa,
                'father_name' => $this->father_name,
                'gender' => $this->gender,
                'birthdate' => $this->birthdate,
                'nationality' => $this->nationality,
                'birthplace' => $this->birthplace,
                'passport_number' => $this->passport_number,
                'faragir_code' => $this->faragir_code,
                'country' => $this->country,
                'state_city' => $this->state_city,
                'address' => $this->address,
                'phone' => $this->phone,
                'postal_code' => $this->postal_code,
                'status' => 2
            ]);

            $user->assignRole('Parent');
        });

        session()->flash(
            'success',
            'Your account has been created successfully. ' .
            'Please wait for the admissions officer to review and approve your registration. ' .
            'You will receive an SMS confirmation once your account is activated.'
        );
        return redirect()->route('login');
    }
}
