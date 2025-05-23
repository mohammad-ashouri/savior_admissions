<?php

namespace App\Livewire\Auth;

use AllowDynamicProperties;
use App\Models\Auth\RegisterToken;
use App\Models\GeneralInformation;
use App\Models\User;
use App\Traits\SMS;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[AllowDynamicProperties] #[Title('Savior Schools | Create Account')]
#[Layout('Layouts.login')]
class CreateAccount extends Component
{
    use SMS;

    public $form_name = 'send_token';

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

    public $captcha;
    public $token;
    /**
     * Remaining token expiration time
     */
    public int $remainingTime = 0;

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
        'passport_number' => 'required|string|max:255|unique:general_informations,passport_number',
        'faragir_code' => 'required|string|max:24|unique:general_informations,faragir_code',
        'country' => 'required|exists:countries,id',
        'state_city' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'mobile' => [
            'required',
            'string',
            'regex:/^\+\d{1,3}\d{9,15}$/',
            'unique:users,mobile',
        ],
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

    protected $listeners = [
        'timer-expired' => 'rollbackForm'
    ];

    public function rollbackForm()
    {
        $this->resetErrorBag();
        $this->reset();
        $this->form_name = 'send_token';
    }

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

    public function sendToken()
    {
        // Enhanced rate limiting with IP and mobile fingerprint
        $throttleKey = 'mobile-verification:' . request()->ip() . '|' . $this->mobile;

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $this->remainingTime = RateLimiter::availableIn($throttleKey);
            $this->resetErrorBag();
            $this->addError('mobile', "Too many verification attempts. Please try again in {$this->remainingTime} seconds.");
            return;
        }

        // Strict validation
        $this->validate([
            'mobile' => [
                'required',
                'string',
                'regex:/^\+\d{1,3}\d{9,15}$/',
                'unique:users,mobile',
                function ($attribute, $value, $fail) {
                    if (!$this->isValidCarrierNumber($value)) {
                        $fail('Invalid mobile number for SMS delivery.');
                    }
                }
            ],
            'captcha' => 'required|captcha|digits:5', // Force exact 5 characters
        ]);

        // Security: Prevent token reuse within 2 minutes
        $twoMinutesAgo = now()->subMinutes(2);
        $lastToken = RegisterToken::where('value', $this->mobile)
            ->where('register_method', 'Mobile')
            ->where('status', 0)
            ->where('created_at', '>', $twoMinutesAgo)
            ->first();

        if ($lastToken) {
            $this->tokenSent = true;
            $this->form_name = 'token_sent';
            $this->dispatch('token-sent');
            $this->remainingTime = max(0, $lastToken->created_at->addMinutes(2)->diffInSeconds(now()));
            RateLimiter::hit($throttleKey);
            return;
        }

        $token = random_int(100000, 999999); // 6-digit token

        RegisterToken::where('value', $this->mobile)
            ->where('register_method', 'Mobile')
            ->where('status', 0)
            ->delete();

        $tokenEntry = RegisterToken::create([
            'register_method' => 'Mobile',
            'value' => $this->mobile,
            'token' => $token,
            'status' => 0,
            'ip_address' => request()->ip()
        ]);

        $smsContent = "Savior Schools verification code: {$token}\n"
            . "Valid for 2 minutes. Do not share this code.\n"
            . "If you didn't request this, contact support immediately.";

        $this->sendSms($this->mobile, $smsContent);

        $this->remainingTime = 120;
        $this->tokenSent = true;
        RateLimiter::hit($throttleKey);

        $this->dispatch('token-sent');
        $this->form_name = 'token_sent';
    }

    /**
     * Validate mobile carrier number
     */
    private function isValidCarrierNumber(string $number): bool
    {
        // Remove + sign if present
        $cleanNumber = ltrim($number, '+');

        // Example: Validate Iranian mobile numbers (98 is country code)
        if (str_starts_with($cleanNumber, '98')) {
            return preg_match('/^98(9[0-9]{9})$/', $cleanNumber);
        }

        // Add other country validations as needed
        // return true for other countries if you don't have specific validation
        return true;
    }

    public function verify()
    {
        $this->validate([
            'token' => 'required|integer|digits:6'
        ]);

        $twoMinutesAgo = Carbon::now()->subMinutes(2);

        $lastToken = RegisterToken::whereValue($this->mobile)
            ->where('register_method', 'Mobile')
            ->whereStatus(0)
            ->whereToken($this->token)
            ->where('created_at', '>', $twoMinutesAgo)
            ->first();

        if (empty($lastToken)) {
            $this->addError('token', 'Your registration code is invalid.');
            return;
        }

        $this->form_name = 'register';
    }

    /**
     * Register new user
     */
    public function register()
    {
        $this->validate($this->rules, $this->messages);

        DB::transaction(function () {
            $user = User::create([
                'email' => $this->email,
                'mobile' => $this->mobile,
                'password' => Hash::make($this->password),
                'status' => 2
            ]);

            GeneralInformation::create([
                'user_id' => $user->id,
                'first_name_en' => $this->first_name_en,
                'last_name_en' => $this->first_name_en,
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
