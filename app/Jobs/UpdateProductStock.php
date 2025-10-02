<?php

namespace App\Jobs;

use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UpdateProductStock implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderDetails;

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
    public $timeout = 30;

    /**
     * Create a new job instance.
     */
    public function __construct($orderDetails)
    {
        $this->orderDetails = $orderDetails;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            foreach ($this->orderDetails as $orderDetail) {
                $product = Product::find($orderDetail['product_id']);
                
                if ($product) {
                    $oldStock = $product->stock_quantity;
                    $newStock = $oldStock - $orderDetail['quantity'];
                    
                    // Update stock quantity
                    $product->update(['stock_quantity' => max(0, $newStock)]);
                    
                    // Log stock update
                    Log::info('Product stock updated', [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'old_stock' => $oldStock,
                        'new_stock' => $newStock,
                        'quantity_ordered' => $orderDetail['quantity']
                    ]);
                    
                    // Check for low stock and fire event
                    if ($newStock <= 10) {
                        event(new \App\Events\ProductLowStock($product, $newStock));
                    }
                    
                    // Clear product cache
                    Cache::forget("product_{$product->id}");
                    Cache::forget("products_featured");
                    Cache::forget("products_active");
                }
            }

            // Clear general caches
            Cache::forget('products_count');
            Cache::forget('low_stock_products');

            Log::info('Product stock update job completed successfully', [
                'order_details_count' => count($this->orderDetails)
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update product stock', [
                'order_details' => $this->orderDetails,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Product stock update job failed permanently', [
            'order_details' => $this->orderDetails,
            'error' => $exception->getMessage()
        ]);
    }
}