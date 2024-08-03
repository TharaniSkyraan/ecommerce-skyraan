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
        $emailscc = array('ravindhiran@skyraan.com');
        array_push($emailscc, 'gnanamaruthu@skyraan.com');
        return $this->from($data['email'], $data['name'])
            ->replyTo($data['email'], $data['name'])
            ->cc($emailscc)
            ->to('supportadmin@skyraan.net', 'skyraa ecommerce')
            ->subject('Receive Contact mail from '.$data['name'])
            ->markdown('emails.contact_user')
            ->with(['data'=>$data]);
    }
  
}
