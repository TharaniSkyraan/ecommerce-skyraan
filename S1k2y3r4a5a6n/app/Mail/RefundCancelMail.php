<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundCancelMail extends Mailable
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
                    ->subject('Refund Cancel Mail from '. config('siteSetting.site_name'))
                    ->markdown('emails.refund-cancel')
                    ->with([
                        'order' => $this->order
                    ]);
    }

}
