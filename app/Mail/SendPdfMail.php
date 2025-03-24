<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendPdfMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdfPath;

    public function __construct($data, $pdfPath)
    {
        $this->data = $data;
        $this->pdfPath = $pdfPath; // Pass the PDF path to the Mailable
    }

    public function build()
    {
        return $this->view('mail.pdfView')
                    ->subject($this->data['title'])
                    ->attach(storage_path('app/' . $this->pdfPath)); // Attach the PDF to the email
    }
}
