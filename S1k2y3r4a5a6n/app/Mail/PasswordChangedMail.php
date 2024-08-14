<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangedMail extends Mailable
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
  
    public function build()
    {
        $name = $this->name;
        $email = $this->email;

        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($email, $name)
                    ->subject('Password Changed Mail'. config('siteSetting.site_name'))
                    ->markdown('emails.password-change')
                    ->with([
                        'name' => $this->name,
                    ]);
    }
}
