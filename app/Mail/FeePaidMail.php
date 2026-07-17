<?php

namespace App\Mail;

use App\Models\Fee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class FeePaidMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fee;

    public function __construct(Fee $fee)
    {
        $this->fee = $fee;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Fee Payment Received - ' . $this->fee->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.fee-paid',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
