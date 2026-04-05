<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OTPMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($otpCode, $name)
    {
        $this->otpCode = $otpCode;
        $this->name = $name;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Rxcel Clinic One-Time Passcode')
                    ->view('emails.otp_code'); // Siguraduhing may file sa resources/views/emails/otp_code.blade.php
    }
}