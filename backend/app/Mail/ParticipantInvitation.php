<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ParticipantInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public $participant;
    public $invitationData;

    /**
     * Create a new message instance.
     */
    public function __construct($participant, $invitationData = [])
    {
        $this->participant = $participant;
        $this->invitationData = $invitationData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Invitation Officielle - Forum YouthConnekt Sahel 2025',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.participant_invitation_official',
            with: [
                'participant' => $this->participant,
                'invitationData' => $this->invitationData,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        // Pour l'instant, pas de pièce jointe PDF
        // Le PDF sera généré et téléchargeable séparément
        return [];
    }
}
