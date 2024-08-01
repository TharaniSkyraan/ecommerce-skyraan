<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPassword extends Mailable
{
    use Queueable, SerializesModels;

    public $email, $token, $name;

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
        $email = $this->email;
        $name = User::where('email',$email)->first();
        return $this->to($this->email)
            ->markdown('emails.password-reset')
            ->subject('Password Reset Link')
            ->with(['name'=>[$name],'resetLink'=> route('ecommerce.reset.password', ['token' => $this->token]).'?email='.$this->email]);
    }
}
