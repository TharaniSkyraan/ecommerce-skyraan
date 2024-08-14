<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetCartMail extends Mailable
{
    use SerializesModels;

    public $cart_products, $name, $email;

    /**
     * Create a new message instance.
     */
    public function __construct($cart_products, $name, $email)
    {
        $this->cart_products = $cart_products;
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($this->email, $this->name)
                    ->subject('Forgotten Cart at ' . config('siteSetting.site_name'))
                    ->markdown('emails.forgetcartemail')
                    ->with([
                        'name' => $this->name,
                        'cart_products' => $this->cart_products,
                    ]);
    }
}
