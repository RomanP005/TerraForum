<?php

namespace App\Mail;

use App\Models\Service;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User    $user,
        public Service $service,
        public string  $orderNumber,
        public string  $paymentMethod,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Подтверждение заказа ' . $this->orderNumber . ' — TerraForum',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_confirmation',
        );
    }
}
