<?php

namespace App\Mail;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;

class AttendanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $attendance;

    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Attendance Update - ' . $this->attendance->date->format('d M Y'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.attendance',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
