<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
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
        return $this->from(config('mail.recieve_to.address'), config('mail.recieve_to.name'))
                    ->to($order->user->email, $order->user->name)
                    ->subject('Order cancel Successfully'. config('siteSetting.site_name'))
                    ->markdown('emails.refund-cancel')
                    ->with([
                        'order' => $this->order
                    ]);
    }

}
