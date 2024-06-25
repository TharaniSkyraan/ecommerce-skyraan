<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $email, $token;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email=null, $token=null)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        return $this->to($this->email)
            ->markdown('emails.reset-password')
            ->subject('Password Reset Link')
            ->with(['resetLink'=> route('ecommerce.reset.password', ['token' => $this->token]).'?email='.$this->email]);
    }
}
