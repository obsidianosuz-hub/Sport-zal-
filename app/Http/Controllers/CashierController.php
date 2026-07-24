<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CashierController extends Controller
{
    public function index()
    {
        $clients = \App\Models\Client::latest()->get();
        $recent_histories = \App\Models\CashierHistory::with('client')
            ->whereDate('arrived_at', today())
            ->latest()
            ->get();
            
        return Inertia::render('Cashier/Index', [
            'clients' => $clients,
            'recent_histories' => $recent_histories
        ]);
    }

    public function history()
    {
        $histories = \App\Models\CashierHistory::with('client')->latest()->get();
        return Inertia::render('Cashier/History', [
            'histories' => $histories
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0'
        ]);

        \App\Models\CashierHistory::create([
            'client_id' => $request->client_id,
            'amount' => $request->amount,
            'arrived_at' => now(),
            // left_at can be updated later if needed
        ]);

        return redirect()->route('cashier.history')->with('success', 'To\'lov muvaffaqiyatli saqlandi');
    }

    public function destroyAll()
    {
        \App\Models\CashierHistory::truncate();
        return back()->with('success', 'Kassa tarixi tozalandi');
    }
}
