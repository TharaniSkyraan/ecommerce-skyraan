<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundRequestedMail extends Mailable
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
                    ->subject('Refund Requested Mail from'. config('siteSetting.site_name'))
                    ->markdown('emails.refund-requested')
                    ->with([
                        'order' => $this->order
                    ]);
    }
}
