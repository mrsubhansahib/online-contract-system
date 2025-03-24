<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotification extends Mailable
{
    
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $data = [];
    public function __construct( $dataFromMail )
    {
        $this->data = $dataFromMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->from("sample@gmail.com","Online Contract System")
        ->subject("This is a Sample Subject")
        ->view('emails.contract-notification')
        ->with("data",$this->data);

    }
}
