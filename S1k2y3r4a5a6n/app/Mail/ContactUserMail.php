<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $data = $this->data;

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($data['email'], $data['name'])
                    ->subject('Received Contact mail')
                    ->markdown('emails.contact_user')
                    ->with(['data'=>$data]);
    }
  
}
