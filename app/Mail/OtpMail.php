<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public string $type
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'رمز التحقق الخاص بك - ' . config('app.name'),
        );
    }

    public function content(): Content
    {
        $typeTranslations = [
            'email' => 'البريد الإلكتروني',
            'phone' => 'رقم الهاتف'
        ];

        return new Content(
            view: 'emails.otp',
            with: [
                'otp' => $this->otp,
                'type' => $typeTranslations[$this->type] ?? $this->type,
            ],
        );
    }
}
