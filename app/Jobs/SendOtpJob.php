<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $otp;
    protected $type;

    public function __construct($email, $otp, $type)
    {
        $this->email = $email;
        $this->otp = $otp;
        $this->type = $type;
    }

    public function handle()
    {
        Mail::to($this->email)->send(new \App\Mail\OtpMail($this->otp, $this->type));
    }
}
