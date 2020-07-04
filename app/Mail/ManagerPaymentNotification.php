<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManagerPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to(["supri170845@gmail.com","supriyadin.170845@gmail.com"])
                ->with([
                    'type' => $this->data->type,
                    'url' => $this->data->url,
                    'content' => $this->data->content,
                ])
                ->from('info@rekakomindo.com')
                ->view('mail.manager-payment-notification');
    }
}
