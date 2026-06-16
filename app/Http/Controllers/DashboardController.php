<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockLog;
use App\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalSku = Product::count();
        $lowStockCount = Product::whereColumn('quantity', '<=', 'min_threshold')->count();
        // Mocking pending purchase orders to align with spec's dashboard requirements
        $pendingPurchaseOrders = 5;

        // Eager load relationships for the activity log list, ordered by creation time
        $activityLogs = StockLog::with(['product', 'user'])
            ->orderBy('id', 'desc')
            ->take(15)
            ->get();

        return view('dashboard', compact('totalSku', 'lowStockCount', 'pendingPurchaseOrders', 'activityLogs'));
    }
}
