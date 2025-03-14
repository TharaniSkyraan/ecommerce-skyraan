<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use SerializesModels;

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
                    
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($this->email, $this->name)
                    ->subject('Welcome Mail '. config('siteSetting.site_name'))
                    ->markdown('emails.welcomemail')
                    ->with([
                        'name' => $this->name,
                        'email' => $this->email,
                    ]);
    }
}
