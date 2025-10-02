<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $user;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     *
     * @var int
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Send order confirmation email
            Mail::send('emails.order-confirmation', [
                'order' => $this->order,
                'user' => $this->user,
                'orderDetails' => $this->order->orderDetails()->with('product')->get(),
            ], function ($message) {
                $message->to($this->user->email, $this->user->name)
                        ->subject('Order Confirmation - #' . $this->order->order_number);
            });

            Log::info('Order confirmation email sent successfully', [
                'order_id' => $this->order->id,
                'user_id' => $this->user->id,
                'email' => $this->user->email
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send order confirmation email', [
                'order_id' => $this->order->id,
                'user_id' => $this->user->id,
                'error' => $e->getMessage()
            ]);

            // Re-throw the exception to trigger job retry
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Order confirmation email job failed permanently', [
            'order_id' => $this->order->id,
            'user_id' => $this->user->id,
            'error' => $exception->getMessage()
        ]);
    }
}