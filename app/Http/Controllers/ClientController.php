<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = \App\Models\Client::latest()->get();
        return \Inertia\Inertia::render('Clients/Index', [
            'clients' => $clients
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
            'phone' => 'nullable|string|max:20',
            'subscription_type' => 'required|in:oddiy,vip',
            'subscription_expires_at' => 'nullable|date',
        ]);

        \App\Models\Client::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'subscription_type' => $request->subscription_type,
            'subscription_expires_at' => $request->subscription_expires_at,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = \App\Models\Client::findOrFail($id);
        $client->delete();
        return redirect()->back();
    }
}
