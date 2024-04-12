<?php

namespace App\Mail;

use App\Models\Auth\PasswordResetToken;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $email;

    /**
     * Create a new message instance.
     */
    public function __construct($email)
    {
        $this->email = $email;
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
     */
    public function content(): Content
    {

        $token = str_replace(array('/', '\\', '.'), '', bcrypt(random_bytes(20)));
        $userInfo=User::where('email',$this->email)->first();

        $allUserTokens=PasswordResetToken::where('user_id',$userInfo->id)->update(['active' => 0]);

        if ($allUserTokens) {
            $tokenEntry = new PasswordResetToken();
            $tokenEntry->user_id = $userInfo->id;
            $tokenEntry->type = 1;
            $tokenEntry->token = $token;
            $tokenEntry->save();

            if ($tokenEntry) {
                return new Content(
                    view: 'Auth.showToken',
                    with: [
                        'resetLink' => env('APP_URL') . '/password/reset/' . $token,
                    ]
                );
            }
        }
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
