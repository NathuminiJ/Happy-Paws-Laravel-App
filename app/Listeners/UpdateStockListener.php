<?php

namespace App\Listeners;

use App\Events\ProductLowStock;
use App\Jobs\UpdateProductStock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class UpdateStockListener implements ShouldQueue
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
    public function handle(ProductLowStock $event): void
    {
        try {
            // Log low stock alert
            Log::warning('Product low stock alert', [
                'product_id' => $event->product->id,
                'product_name' => $event->product->name,
                'current_stock' => $event->currentStock,
                'priority' => $event->currentStock <= 5 ? 'high' : 'medium'
            ]);

            // Update low stock cache
            $lowStockProducts = Cache::get('low_stock_products', []);
            $lowStockProducts[$event->product->id] = [
                'product_id' => $event->product->id,
                'product_name' => $event->product->name,
                'current_stock' => $event->currentStock,
                'alerted_at' => now()->toISOString()
            ];
            Cache::put('low_stock_products', $lowStockProducts, 3600); // Cache for 1 hour

            // Send notification to admin (you could implement this)
            // Notification::send(Admin::all(), new LowStockNotification($event->product, $event->currentStock));

        } catch (\Exception $e) {
            Log::error('Failed to handle low stock event', [
                'product_id' => $event->product->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}