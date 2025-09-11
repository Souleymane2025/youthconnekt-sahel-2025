<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParticipantConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;

    /**
     * Create a new message instance.
     */
    public function __construct($participant)
    {
        $this->participant = $participant;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Confirmation d\'inscription - YouthConnekt Sahel 2025')
                    ->view('emails.participant_confirmation')
                    ->with(['participant' => $this->participant]);
    }
}
