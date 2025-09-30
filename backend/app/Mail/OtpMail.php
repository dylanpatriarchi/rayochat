<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $code, string $userName)
    {
        $this->code = $code;
        $this->userName = $userName;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Codice di Accesso RayoChat')
                    ->view('emails.otp');
    }
}
