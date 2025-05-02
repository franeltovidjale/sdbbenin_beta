<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class OtpVerification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * The OTP code
     *
     * @var string
     */
    public $otpCode;

    /**
     * The registration data
     *
     * @var array
     */
    public $registrationData;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otpCode, array $registrationData)
    {
        $this->otpCode = $otpCode;
        $this->registrationData = $registrationData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@kmjstore.com', 'KMJ Store'),
            subject: 'Vérification de votre compte'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-verification',
            with: [
                'otp' => $this->otpCode,
                'registration_data' => $this->registrationData
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments(): array
    {
        return [];
    }
}