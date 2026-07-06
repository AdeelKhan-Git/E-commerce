<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;
    public string $statusType;  
    public string $oldStatus;
    public string $newStatus;

    public function __construct(Order $order, string $statusType, string $oldStatus, string $newStatus)
    {
        $this->order = $order->load([
                'user',
                'items.product.primaryImage',
            ]);
        $this->statusType = $statusType;
        $this->oldStatus  = $oldStatus;
        $this->newStatus  = $newStatus;
    }

    public function envelope(): Envelope
    {
        $label = $this->statusType === 'order_status' ? 'Order Status' : 'Payment Status';
        return new Envelope(
            subject: "Order Update — {$this->order->order_number} ({$label}: " . ucfirst($this->newStatus) . ")",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.order-status-changed',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}