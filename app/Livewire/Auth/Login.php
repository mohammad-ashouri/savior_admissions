<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Savior Schools | Login')]
#[Layout('Layouts.login')]
class Login extends Component
{
    #[Validate([
        'required',
        'string',
        'regex:/^(\+98|0)?9\d{9}$/',
        'exists:users,mobile'])]
    public string $mobile;

    #[Validate('required|string|min:8|max:20')]
    public string $password;

    public bool $remember = false;

    /**
     * Role for captcha
     *
     * @return string[]
     */
    protected function rules(): array
    {
        return [
            'captcha' => 'required|captcha',
        ];
    }

    /**
     * Customize captcha message
     *
     * @return string[]
     */
    protected function messages(): array
    {
        return [
            'captcha.captcha' => 'Captcha is not valid.',
        ];
    }

    public $captcha;

    /**
     * User variable
     */
    public User $user;

    public function mount()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return;
    }

    /**
     * Login function
     */
    public function login()
    {
        $this->resetErrorBag();

        $maxAttempts = 5;
        $decayMinutes = 1;

        if (RateLimiter::tooManyAttempts('login-attempt:' . request()->ip(), $maxAttempts)) {
            $seconds = RateLimiter::availableIn('login-attempt:' . request()->ip());
            $this->resetErrorBag();
            $this->addError('mobile', "Too many login attempts. Please try again in {$seconds} seconds.");

            return;
        }
        RateLimiter::hit('login-attempt:' . request()->ip());

        $this->validate();

        $user = User::where('mobile', $this->mobile)->where('status', 2)->first();

        if ($user) {
            RateLimiter::hit('login-attempt:' . request()->ip(), $decayMinutes * 60);

            session()->flash(
                'error',
                'Account not yet approved. Your registration is still under review by our admissions officer. ' .
                'You will receive an SMS notification when your account is activated. Thank you for your patience.'
            );
            return;
        }

        $user = User::where('mobile', $this->mobile)->first();

        if (!$user) {
            RateLimiter::hit('login-attempt:' . request()->ip(), $decayMinutes * 60);

            return $this->addError('mobile', 'The provided credentials do not match our records.');
        }

        $credentials = [
            'mobile' => $this->mobile,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials, $this->remember)) {
            RateLimiter::clear('login-attempt:' . request()->ip());
            Session::put('user_id', $user->id);

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit('login-attempt:' . request()->ip(), $decayMinutes * 60);

        return $this->addError('password', 'The provided password is incorrect.');
    }

    public function render()
    {
        $this->reset('captcha');

        return view('livewire.auth.login');
    }
}
