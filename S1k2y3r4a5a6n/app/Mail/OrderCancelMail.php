<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderCancelMail extends Mailable
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
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($order->user->email, $order->user->name)
                    ->subject('Order cancel Successfully from '. config('siteSetting.site_name'))
                    ->markdown('emails.cancel-order')
                    ->with([
                        'order' => $this->order
                    ]);
    }
}
