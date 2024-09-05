<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundInitiated extends Mailable
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
        $email = $order->user->email;
        return $this->from(config('mail.from.address'), config('mail.from.name'))
                    ->to($email, $order->user->name)
                    ->subject('Refund Initiated Mail from '. config('siteSetting.site_name'))
                    ->markdown('emails.refund_initiated')
                    ->with([
                        'order' => $this->order,
                        'email' => $email
                    ]);
    }

}
