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
            from: new Address('support@savioradmissions.com', 'Savior Schools Support'),
            subject: 'Create Savior Schools Account',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $token = preg_replace('/[\/\\.]/', '', Str::random(32));

        $tokenEntry = new RegisterToken();
        $tokenEntry->register_method = 'Email';
        $tokenEntry->value = $this->email;
        $tokenEntry->token = $token;
        $tokenEntry->status = 0;
        $tokenEntry->save();

        if ($tokenEntry) {
            return new Content(
                view: 'Auth.Signup.RegisterToken',
                with: [
                    'register_link' => env('APP_URL').'/new-account/'.$token,
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
