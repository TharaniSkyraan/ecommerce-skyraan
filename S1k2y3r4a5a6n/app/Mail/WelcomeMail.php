<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use SerializesModels;

    public $name,$email;

    /**
     * Create a new message instance.
     */
    public function __construct($name,$email)
    {
        $this->name = $name;
        $this->email = $email;

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
