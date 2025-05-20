<?php

namespace App\Livewire\Auth;

use App\Models\Auth\PasswordResetToken;
use App\Models\User;
use App\Traits\SMS;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Savior Schools | Password Recovery')]
#[Layout('Layouts.login')]
class LostPassword extends Component
{
    use SMS;

    #[Validate([
        'required',
        'string',
        'regex:/^(\+98|0)?9\d{9}$/',
        'exists:users,mobile',
    ])]
    public string $mobile;

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
     * Captcha variable
     */
    public string $captcha;

    /**
     * Remaining token expiration time
     */
    public int $remainingTime = 0;

    /**
     * Sent token status
     */
    public bool $tokenSent = false;

    /**
     * Sent reset token model
     */
    public ?PasswordResetToken $currentToken = null;

    /**
     * Show password form if token is not wrong
     *
     * @var bool
     */
    public $showPasswordForm = false;

    public $verification_code;

    /**
     * Entered password
     */
    public $password;

    /**
     * Password confirmation
     */
    public $password_confirmation;

    /**
     * User model
     */
    public User $user;

    /**
     * Customize validation messages
     *
     * @return string[]
     */
    protected function messages(): array
    {
        return [
            'mobile.regex' => 'Please enter a valid Iranian mobile number.',
            'mobile.exists' => 'This mobile number is not registered.',
            'captcha.captcha' => 'Invalid security code. Please try again.',
        ];
    }

    /**
     * Request password token after validation
     */
    public function requestResetToken(): void
    {
        $this->validate();
        $this->resetErrorBag();

        $user = User::where('mobile', $this->mobile)->first();

        $this->currentToken = PasswordResetToken::where('user_id', $user->id)
            ->where('created_at', '>', now()->subMinutes(2))
            ->first();

        if ($this->currentToken) {
            $this->tokenSent = true;
            $this->remainingTime = max(0, now()->diffInSeconds($this->currentToken->created_at->addMinutes(2)));

            return;
        }

        $throttleKey = 'password-reset:'.request()->ip();
        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $this->remainingTime = RateLimiter::availableIn($throttleKey);
            $this->addError('mobile', "Too many attempts. Please try again in {$this->remainingTime} seconds.");

            return;
        }

        $token = mt_rand(10000, 99999);

        try {
            PasswordResetToken::where('user_id', $user->id)->delete();

            $this->currentToken = PasswordResetToken::create([
                'user_id' => $user->id,
                'type' => 2,
                'token' => $token,
                'created_at' => now(),
            ]);

            $this->sendSms(
                $user->mobile,
                "Your verification code: $token\nValid for 2 minutes."
            );

            $this->tokenSent = true;
            $this->remainingTime = 120;
            $this->dispatch('token-sent');
            RateLimiter::hit($throttleKey, 120);

        } catch (\Exception $e) {
            logger()->error('Password reset failed: '.$e->getMessage(), [
                'mobile' => $this->mobile,
                'ip' => request()->ip(),
            ]);
            $this->addError('mobile', 'Failed to send verification code. Please try again.');
        }
    }

    /**
     * Change number if user wants it
     */
    public function changeNumber(): void
    {
        $this->tokenSent = false;
        $this->reset('captcha');
    }

    /**
     * Verify entered code with sent token
     */
    public function verifyCode(): void
    {
        $this->validate(['verification_code' => 'required|integer|max_digits:5']);
        if ($this->currentToken->token != $this->verification_code) {
            $this->addError('verification_code', 'Invalid verification code.');
        }
        $this->showPasswordForm = true;
        $this->tokenSent = false;
        $this->user = User::where('mobile', $this->mobile)->first();
    }

    /**
     * Reset password if validation is true
     *
     * @return RedirectResponse
     */
    public function resetPassword()
    {
        $this->validate([
            'password' => [
                'required',
                'string',
                'confirmed',
                Password::min(8)
                    ->numbers(),
            ],
        ]);
        $this->user->password = Hash::make($this->password);
        $this->user->save();

        $this->currentToken->delete();
        session()->flash('success', 'Password reset successfully.');

        return redirect()->route('login');
    }
}
