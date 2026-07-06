<?php

namespace App\Jobs;

use App\Models\Order;
use App\Mail\OrderConfirmationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $backoff = 30; // retry after 30 seconds if failed

    protected int $orderId;

    public function __construct(Order $order)
    {
        $this->orderId = $order->id;
    }

    public function handle(): void
    {
        $order = Order::with('user', 'items.product.primaryImage')->find($this->orderId);

        if (!$order || !$order->user) {
            Log::warning("SendOrderConfirmationJob: Order #{$this->orderId} or user not found.");
            return;
        }

        Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
    }

    public function failed(\Throwable $exception): void
    {
        Log::error("Failed to send order confirmation for order #{$this->orderId}: " . $exception->getMessage());
    }
}