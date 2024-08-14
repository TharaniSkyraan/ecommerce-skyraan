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

        return $this->from(config('mail.receive_to.address'), config('mail.receive_to.name'))
                    ->to($data['email'], $data['name'])
                    ->subject('Receive Contact mail from '.$data['name'])
                    ->markdown('emails.contact_user')
                    ->with(['data'=>$data]);
    }
  
}
