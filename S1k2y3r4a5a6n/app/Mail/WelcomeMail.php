<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $data = $this->data;
        $emailscc = array('ravindhiran@skyraan.com');
        array_push($emailscc, 'gnanamaruthu@skyraan.com');
        return $this->from(($data['email']), $data['name'])
            ->replyTo($data['email'], $data['name'])
            ->cc($emailscc)
            ->to(config('mail.support.address'), config('mail.support.name'))
            ->subject('Receive Contact mail from '.$data['name'])
            ->markdown('emails.welcomemail')
            ->with(['data'=>$data]);
    }   
}
