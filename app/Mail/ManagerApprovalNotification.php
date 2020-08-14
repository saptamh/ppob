<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ManagerApprovalNotification extends Mailable
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
        $to = ["supriyadin.170845@gmail.com","zainpam@gmail.com","gideonrekakomindo@gmail.com","imah.rekakomindo@gmail.com"];

        if (strtolower(config('app.env')) == "local") {
            $to = ["supriyadin.170845@gmail.com"];
        }

        return $this->to($to)
                ->subject("REKAKOMINDO - Payment Approval")
                ->with([
                    'user' => $this->data->user,
                    'content' => $this->data->content,
                ])
                ->from(['address' => 'info@rekakomindo.com', 'name' => 'REKAKOMINDO NOTIFICATION'])
                ->view('mail.manager-approval-notification');
    }
}
