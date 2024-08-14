<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestockMail extends Mailable
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
        return $this->from(config('mail.recieve_to.address'), config('mail.recieve_to.name'))
                    ->to($this->email, $this->name)
                    ->subject('Forgotten Cart at ' . config('siteSetting.site_name'))
                    ->markdown('emails.restack')
                    ->with([
                        'name' => $this->name,
                        'product' => $this->product,
                    ]);
    }
}
