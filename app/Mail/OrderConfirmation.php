<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Attachment;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(

        public int $orderId,
        public string $mailBody,
        public ?array $files = null
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order Confirmation',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail-content',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    // public function attachments(): array
    // {
    //     if (empty($this->files)) {
    //         return [];
    //     }

    //     return collect($this->files)->map(function ($file) {
    //         return Attachment::fromStorage($file['path'])
    //             ->as($file['name'] ?? basename($file['path']));
    //     })->toArray();

    // }

    public function attachments(): array
    {

        if (empty($this->files)) {
            return [];
        }

        return collect($this->files)
            ->filter(fn($file) => !empty($file['path'])) // ✅ skip invalid
            ->map(function ($file) {

                $path = $file['path'];
                $name = $file['name'] ?? basename($path);

                // ✅ Create attachment
                $attachment = Attachment::fromStorage($path)
                    ->as($name);

                // ✅ AUTO DETECT MIME TYPE (BEST METHOD)
                $fullPath = storage_path('app/' . $path);

                if (file_exists($fullPath)) {
                    $mime = mime_content_type($fullPath);

                    if ($mime) {
                        $attachment->withMime($mime);
                    }
                }

                return $attachment;
            })
            ->toArray();
    }
}
