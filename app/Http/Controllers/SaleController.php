<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = \App\Models\Sale::with(['product', 'user'])->latest()->get();
        return \Inertia\Inertia::render('Sales/Index', [
            'sales' => $sales,
        ]);
    }

    public function destroy($id)
    {
        $sale = \App\Models\Sale::findOrFail($id);
        if ($sale->product_id) {
            $product = \App\Models\Product::find($sale->product_id);
            if ($product) {
                $product->stock += $sale->quantity;
                $product->save();
            }
        }
        $sale->delete();
        return redirect()->back();
    }

    public function destroyAll()
    {
        $sales = \App\Models\Sale::all();
        foreach ($sales as $sale) {
            if ($sale->product_id) {
                $product = \App\Models\Product::find($sale->product_id);
                if ($product) {
                    $product->stock += $sale->quantity;
                    $product->save();
                }
            }
            $sale->delete();
        }
        return redirect()->back();
    }
}
