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
        
        $emailscc = array('pavithra@skyraan.com');
        array_push($emailscc, 'tharani@skyraan.com');
        return $this->from($data['email'], $data['name'])
                        ->replyTo($data['email'], $data['name'])
                        ->cc($emailscc)
                        ->to(config('mail.support_receive_to.address'), config('mail.support_receive_to.name'))
                        ->subject('Feedback Mail from '. $data['name'])
                        ->markdown('emails.contact_admin')
                        ->with(['data'=>$data]);
    }
}
