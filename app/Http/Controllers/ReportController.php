<?php

namespace App\Http\Controllers;

use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function stockIn(Request $request): View
    {
        $search = $request->input('search');

        $logs = StockLog::with(['product', 'user'])
            ->where(function ($query) {
                $query->where('type', 'in')
                    ->orWhere('quantity_changed', '>', 0);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('reports.stock_in', compact('logs', 'search'));
    }

    public function stockOut(Request $request): View
    {
        $search = $request->input('search');

        $logs = StockLog::with(['product', 'user'])
            ->where(function ($query) {
                $query->where('type', 'out')
                    ->orWhere('quantity_changed', '<', 0);
            })
            ->when($search, function ($query, $search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('reports.stock_out', compact('logs', 'search'));
    }
}
