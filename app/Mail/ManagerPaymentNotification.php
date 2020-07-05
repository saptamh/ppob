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
        return $this->to(["supriyadin.170845@gmail.com","zainpam@gmail.com","gideonrekakomindo@gmail.com"])
                ->subject("REKAKOMINDO - Payment Approval")
                ->with([
                    'type' => $this->data->type,
                    'url' => $this->data->url,
                    'content' => $this->data->content,
                ])
                ->from(['address' => 'info@rekakomindo.com', 'name' => 'REKAKOMINDO NOTIFICATION'])
                ->view('mail.manager-payment-notification');
    }
}
