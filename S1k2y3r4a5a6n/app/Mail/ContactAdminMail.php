<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    public function build()
    {
        $data = $this->data;
        // $emailscc = array('ravindhiran@skyraan.com');
        // array_push($emailscc, 'gnanamaruthu@skyraan.com');
        return $this->to(config('mail.support.address'), config('mail.support.name'))
        ->from(($data['email']), $data['name'])
        ->subject('Receive Contact mail from '.$data['name'])
        ->markdown('emails.contact_admin')
        ->with(['data'=>$data]);
    }
}
