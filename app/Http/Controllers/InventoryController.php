<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventory = \App\Models\Product::latest()->get();
        return \Inertia\Inertia::render('Inventory/Index', [
            'inventory' => $inventory
        ]);
    }

    public function history()
    {
        $histories = \App\Models\InventoryHistory::latest()->get();
        return \Inertia\Inertia::render('Inventory/History', [
            'histories' => $histories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'buy_price' => 'required|numeric',
        ]);

        $product = \App\Models\Product::create([
            'name' => $request->name,
            'category' => $request->category,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        \App\Models\InventoryPurchase::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'quantity' => $request->stock,
            'total_cost' => $request->buy_price * $request->stock,
            'date' => now(),
        ]);

        return redirect()->back();
    }

    /**
     * Replenish existing product from supplier.
     */
    public function replenish(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total_cost' => 'required|numeric|min:0',
        ]);

        $product = \App\Models\Product::findOrFail($request->product_id);
        
        // Increase stock
        $product->increment('stock', $request->quantity);

        // Record the purchase expense
        \App\Models\InventoryPurchase::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'quantity' => $request->quantity,
            'total_cost' => $request->total_cost,
            'date' => now(),
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroyAll()
    {
        // Delete all products and their history
        \App\Models\InventoryPurchase::truncate();
        \App\Models\Product::query()->delete();
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = \App\Models\Product::findOrFail($id);
        
        \App\Models\InventoryHistory::create([
            'product_name' => $product->name,
            'quantity' => $product->stock,
            'reason' => 'Tizimdan O\'chirildi'
        ]);

        // Delete related purchases first
        \App\Models\InventoryPurchase::where('product_id', $product->id)->delete();
        
        // Delete the product
        $product->delete();
        
        return redirect()->back();
    }
}
