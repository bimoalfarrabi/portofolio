<?php

namespace App\Mail;

use App\Models\PortfolioMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CollabMessageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public PortfolioMessage $portfolioMessage)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New collab message from ' . $this->portfolioMessage->name,
            replyTo: [new Address($this->portfolioMessage->email, $this->portfolioMessage->name)],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.collab-message',
        );
    }
}
