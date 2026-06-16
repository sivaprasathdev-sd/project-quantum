<?php

namespace App\Listeners;

use App\Events\StockLevelChanged;
use App\Jobs\SendLowStockAlertEmail;

class CheckStockThreshold
{
    public function handle(StockLevelChanged $event): void
    {
        $product = $event->product;
        if ($product->quantity <= $product->min_threshold) {
            // Queue the job to dispatch asynchronously
            SendLowStockAlertEmail::dispatch($product);
        }
    }
}
