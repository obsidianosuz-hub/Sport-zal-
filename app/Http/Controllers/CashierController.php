<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class CashierController extends Controller
{
    public function index()
    {
        $clients = \App\Models\Client::latest()->get();
        return Inertia::render('Cashier/Index', [
            'clients' => $clients
        ]);
    }

    public function history()
    {
        return Inertia::render('Cashier/History');
    }
}
