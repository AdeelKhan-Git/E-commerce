<?php

namespace App\Jobs;
use App\Models\Cart;
use Illuminate\Foundation\Queue\Queueable;

class ClearExpiredCartsJob
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Cart::where('created_at', '<=',now()->subDays(7))->delete();
    }
}
