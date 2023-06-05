<?php

namespace App\Jobs;

use App\Mail\OrderShipped;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct($details)
    {
        $this->details = $details;
        $this->details['email'] = env('MAIL_FROM_ADDRESS');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new OrderShipped($this->details);

        Mail::to($this->details['email'])->send($email);
    }
}
