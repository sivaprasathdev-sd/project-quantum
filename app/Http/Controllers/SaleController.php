<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function __construct(protected StockService $stockService)
    {
    }

    public function create(): View
    {
        $products = Product::where('quantity', '>', 0)->get();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['nullable', 'string', 'max:255'],
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $qtyToSell = (int)$validated['quantity'];

        if ($product->quantity < $qtyToSell) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['quantity' => "Insufficient stock. Only {$product->quantity} items available."]);
        }

        $reason = $validated['reason'] ?: 'Product Sale';
        
        // Negative quantity change for subtraction
        $this->stockService->adjustStock($product, -$qtyToSell, 'out', $reason);

        return redirect()->route('products.index')->with('success', "Successfully recorded sale of {$qtyToSell} units of {$product->name}.");
    }
}
