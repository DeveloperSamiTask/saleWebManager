<?php

namespace App\Jobs;

use App\Mail\CourtesyMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailValidateCourtesy implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $code;

    /**
     * Create a new job instance.
     * @param string $code
     * @return void
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $encrypted = openssl_encrypt($this->code, 'AES-128-ECB', env('COURTESY_KEY'));
        $encoded = urlencode($encrypted);

        Mail::to('sistemas@lagranjavilla.com')->send(new CourtesyMail($encoded));
    }
}
