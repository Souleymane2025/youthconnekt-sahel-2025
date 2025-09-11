<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PartnerConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $partnerData;
    public $type;

    /**
     * Create a new message instance.
     */
    public function __construct($partnerData, $type = 'partner')
    {
        $this->partnerData = $partnerData;
        $this->type = $type;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->type === 'partner' 
            ? 'Confirmation de candidature - Partenaire YouthConnekt Sahel 2025'
            : 'Confirmation de candidature - Sponsor YouthConnekt Sahel 2025';
            
        return new Envelope(
            subject: $subject,
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->type === 'partner' 
            ? 'emails.partner_confirmation'
            : 'emails.sponsor_confirmation';
            
        return new Content(
            view: $view,
            with: [
                'partnerData' => $this->partnerData,
                'type' => $this->type,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}


