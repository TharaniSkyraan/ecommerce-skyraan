<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPlacedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function build()
    {
        $order = $this->order;

        return $this->from(config('mail.recieve_to.address'), config('mail.recieve_to.name'))
                    ->to($order->user->email, $order->user->name)
                    ->subject('Order Placed Successfully '. config('siteSetting.site_name'))
                    ->markdown('emails.order-placed')
                    ->with([
                        'order' => $this->order
                    ]);
    }

}
