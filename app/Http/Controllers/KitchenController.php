<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KitchenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sales = \App\Models\Sale::with(['product', 'user'])->latest()->get();
        $products = \App\Models\Product::where('stock', '>', 0)->get();
        return \Inertia\Inertia::render('Kitchen/Index', [
            'sales' => $sales,
            'products' => $products,
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
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($request) {
            foreach ($request->items as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'items' => "{$product->name} dan omborda yetarli emas! (Qoldiq: {$product->stock})",
                    ]);
                }

                \App\Models\Sale::create([
                    'product_id'  => $product->id,
                    'user_id'     => auth()->id(),
                    'quantity'    => $item['quantity'],
                    'total_price' => $product->price * $item['quantity'],
                    'date'        => now(),
                ]);

                // Qoldiqni kamaytirish
                $product->decrement('stock', $item['quantity']);

                \App\Models\InventoryHistory::create([
                    'product_name' => $product->name,
                    'quantity' => $item['quantity'],
                    'reason' => 'Sotuv (Bar)'
                ]);
            }
        });

        return redirect()->back()->with('success', 'Savdo muvaffaqiyatli saqlandi!');
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
