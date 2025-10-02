<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\SendOrderConfirmationEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        try {
            // Dispatch job to send confirmation email
            SendOrderConfirmationEmail::dispatch($event->order, $event->order->customer)
                ->delay(now()->addSeconds(5)); // Delay by 5 seconds

            Log::info('Order confirmation email job dispatched', [
                'order_id' => $event->order->id,
                'customer_id' => $event->order->customer_id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to dispatch order confirmation email', [
                'order_id' => $event->order->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}