<?php

namespace App\Mail;

use App\Models\Auth\RegisterToken;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class SendRegisterToken extends Mailable
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
        $this->token = rand(14235, 64584);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('support@saviorschools.com', 'Savior Schools Support'),
            subject: 'Create Savior Schools Account',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        //Remove previous token
        RegisterToken::whereValue($this->email)->where('register_method', 'Email')->whereStatus(0)->delete();

        $tokenEntry = new RegisterToken();
        $tokenEntry->register_method = 'Email';
        $tokenEntry->value = $this->email;
        $tokenEntry->token = $this->token;
        $tokenEntry->status = 0;
        $tokenEntry->save();

        if ($tokenEntry) {
            return new Content(
                view: 'Auth.Signup.RegisterToken',
                with: [
                    'register_token' => $this->token,
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
