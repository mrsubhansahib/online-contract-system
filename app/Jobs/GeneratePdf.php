<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPdfMail;

class GeneratePdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contract_name;
    protected $user_mails;
    protected $pdfPath;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contract_name, $user_mails, $pdfPath)
    {
        $this->contract_name = $contract_name;
        $this->user_mails = $user_mails;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $data = [
            'title' => 'Contract Completion',
            'body' => 'Contract with ID ' . $this->contract_name . ' is successfully completed.',
        ];
        foreach ($this->user_mails as $user_mail) {
            Mail::to($user_mail)->send(new SendPdfMail($data, $this->pdfPath));
            $this->release(1);
        }
    }
}
