<?php

namespace App\Jobs;

use App\Mail\CourtesyMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
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

        try {
            Mail::to(['sistemas.ti@samitask.com', 'no-reply@sistemas-gv.com'])
                ->send(new CourtesyMail($this->code));

            Log::info("Correo de cortesía enviado correctamente.", [
                'destinatarios' => ['sistemas.ti@samitask.com', 'no-reply@sistemas-gv.com'],
                'code' => $this->code
            ]);
        } catch (\Exception $e) {
            Log::error("Error al enviar correo de cortesía: " . $e->getMessage(), [
                'code' => $this->code
            ]);
        }
    }
}
