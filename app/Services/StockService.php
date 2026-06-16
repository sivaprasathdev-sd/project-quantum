<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockLog;
use App\Events\StockLevelChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockService
{
    public function adjustStock(Product $product, int $quantityChange, string $type, string $reason): void
    {
        DB::transaction(function () use ($product, $quantityChange, $type, $reason) {
            $product->quantity += $quantityChange;
            $product->save();

            StockLog::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'quantity_changed' => $quantityChange,
                'type' => $type,
                'reason' => $reason,
            ]);

            // Dispatch Stock Check Event
            event(new StockLevelChanged($product));
        });
    }
}
