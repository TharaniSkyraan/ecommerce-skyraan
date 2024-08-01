<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgetCart extends Mailable
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
        // dd(1);
        return $this->from(config('mail.recieve_to.address'), config('mail.recieve_to.name'))
            ->to($this->email, $this->name)
            ->subject('Forgotten Cart at ' . config('siteSetting.site_name'))
            ->markdown('emails.forgetcartemail')
            ->with([
                'name' => $this->name,
                'cart_products' => $this->cart_products,
            ]);
    }
}
