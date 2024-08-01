<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name,$email;

    /**
     * Create a new message instance.
     */
    public function __construct($name,$email)
    {
        $this->name = $name;
        $this->email = $email;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }


    public function build()
    {
        $name = $this->name;
        $email = $this->email;

        return $this->from(config('mail.recieve_to.address'), config('mail.recieve_to.name'))
                    ->to($email, $name)
                    ->subject('Welcome Mail '. config('siteSetting.site_name'))
                    ->markdown('emails.welcomemail')
                    ->with([
                        'name' => $this->name,
                    ]);
    }
}
