<?php

namespace App\Livewire\Auth;

use App\Models\Auth\PasswordResetToken;
use App\Models\User;
use App\Traits\SMS;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Savior Schools | Password Recovery')]
#[Layout('layouts.login')]
class LostPassword extends Component
{
    use SMS;

    #[Validate([
        'required',
        'string',
        'regex:/^(\+98|0)?9\d{9}$/',
        'exists:users,mobile'
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
    public string $captcha;

    public int $remainingTime = 0;
    public bool $tokenSent = false;
    public ?PasswordResetToken $currentToken = null;

    public $verification_code;
    protected function messages(): array
    {
        return [
            'mobile.regex' => 'Please enter a valid Iranian mobile number.',
            'mobile.exists' => 'This mobile number is not registered.',
            'captcha.captcha' => 'Invalid security code. Please try again.',
        ];
    }

    public function requestResetToken()
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
                'created_at' => now()
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
                'ip' => request()->ip()
            ]);
            $this->addError('mobile', 'Failed to send verification code. Please try again.');
        }
    }

    public function changeNumber()
    {
        $this->tokenSent = false;
        $this->reset('captcha');
    }

    public function verifyCode()
    {
        $this->validate(['verification_code'=>'required|integer|max_digits:5']);
        if ($this->currentToken->token!=$this->verification_code) {
            $this->addError('verification_code', 'Invalid verification code.');
        }

    }
    public function render()
    {
        return view('livewire.auth.lost-password');
    }
}
