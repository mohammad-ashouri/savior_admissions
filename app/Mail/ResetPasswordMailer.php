<?php

namespace App\Mail;

use App\Models\Auth\PasswordResetToken;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Random\RandomException;

class ResetPasswordMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    public $token;

    /**
     * Create a new message instance.
     */
    public function __construct($email)
    {
        $this->email = $email;
        $this->token = rand(15424, 98546);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('support@saviorschools.com', 'Savior support'),
            subject: 'Reset Password',
        );
    }

    /**
     * Get the message content definition.
     *
     * @throws RandomException
     */
    public function content(): Content
    {

        $userInfo = User::where('email', $this->email)->first();

        PasswordResetToken::where('user_id', $userInfo->id)->update(['active' => 0]);

        $tokenEntry = new PasswordResetToken();
        $tokenEntry->user_id = $userInfo->id;
        $tokenEntry->type = 1;
        $tokenEntry->token = $this->token;
        $tokenEntry->save();

        if ($tokenEntry) {
            return new Content(
                view: 'Auth.ForgotPassword.showToken',
                with: [
                    'token' => $this->token,
                ]
            );
        }
        abort(403);
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
