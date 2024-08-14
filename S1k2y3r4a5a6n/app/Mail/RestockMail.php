<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestockMail extends Mailable
{
    use Queueable, SerializesModels;

    
    public $product, $name, $email;

    /**
     * Create a new message instance.
     */
    public function __construct($product, $name, $email)
    {
        $this->product = $product;
        $this->name = $name;
        $this->email = $email;
    }

    
    public function build()
    {
        return $this->from(config('mail.recieve_to.address'), config('mail.recieve_to.name'))
                    ->to($this->email, $this->name)
                    ->subject($this->product->name.' Back In Stock ')
                    ->markdown('emails.restack')
                    ->with([
                        'name' => $this->name,
                        'product' => $this->product,
                    ]);
    }
}
