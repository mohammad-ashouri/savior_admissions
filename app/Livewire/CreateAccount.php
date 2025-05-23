<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\GeneralInformation;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

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

    public function render()
    {
        $countries = DB::table('countries')->get();
        return view('livewire.create-account', [
            'countries' => $countries
        ]);
    }

    public function createAccount()
    {
        $this->validate();

        DB::transaction(function () {
            $user = User::create([
                'first_name_en' => $this->first_name_en,
                'last_name_en' => $this->last_name_en,
                'email' => $this->email,
                'mobile' => $this->mobile,
                'password' => Hash::make($this->password),
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
                'status' => 0
            ]);

            $user->assignRole('student');
        });

        session()->flash('success', 'حساب کاربری شما با موفقیت ایجاد شد.');
        return redirect()->route('login');
    }
} 