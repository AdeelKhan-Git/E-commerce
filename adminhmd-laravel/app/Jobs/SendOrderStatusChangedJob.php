<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\OrderStatusChangedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderStatusChangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 30;

    protected int $orderId;
    protected string $statusType;
    protected string $oldStatus;
    protected string $newStatus;

    public function __construct(Order $order, string $statusType, string $oldStatus, string $newStatus)
    {
        $this->orderId    = $order->id;
        $this->statusType = $statusType;
        $this->oldStatus  = $oldStatus;
        $this->newStatus  = $newStatus;
    }

    public function handle(): void
    {
        $order = Order::with('user', 'items.product.primaryImage')->find($this->orderId);

        if (!$order || !$order->user) {
            Log::warning("SendOrderStatusChangedJob: Order #{$this->orderId} or user not found.");
            return;
        }

        Mail::to($order->user->email)->send(
            new OrderStatusChangedMail($order, $this->statusType, $this->oldStatus, $this->newStatus)
        );
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Failed to send status change email for order #{$this->orderId}: " . $exception->getMessage());
    }
}