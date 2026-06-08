<?php

namespace App\Jobs;

use App\Mail\BirthdayMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailCouponBirthday implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $code;
    protected $company;

    private const COMPANY_EMAILS = [
        1 => ['controller.gs@lagranjavilla.com'],
        2 => ['sistemas.ti@samitask.com'],
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($code, $company)
    {
        $this->code = $code;
        $this->company = $company;
    }

    /** 
     *
     * @return void
     */
    public function handle()
    {
        $recipients = self::COMPANY_EMAILS[$this->company] ?? null;

        if (!$recipients) {
            Log::warning("Empresa no reconocida para envío de correo de cumpleaños.", [
                'company' => $this->company,
                'code'    => $this->code,
            ]);
            return;
        }

        try {
            Mail::to($recipients)->send(new BirthdayMail($this->code));

            Log::info("Correo de cumpleaños enviado correctamente.", [
                'destinatarios' => $recipients,
                'company'       => $this->company,
                'code'          => $this->code,
            ]);
        } catch (\Exception $e) {
            Log::error("Error al enviar correo de cumpleaños: " . $e->getMessage(), [
                'company' => $this->company,
                'code'    => $this->code,
            ]);
        }
    }
}
