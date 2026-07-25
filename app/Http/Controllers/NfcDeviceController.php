<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NfcDeviceController extends Controller
{
    public function index()
    {
        $devices = \App\Models\NfcDevice::latest()->get();
        
        $activeSessions = \App\Models\TreadmillSession::with(['client', 'device'])
            ->whereNull('ended_at')
            ->get()
            ->map(function ($session) {
                return [
                    'id' => $session->id,
                    'device_name' => $session->device->name,
                    'client_name' => $session->client->name,
                    'started_at' => $session->started_at->toIso8601String(),
                ];
            });

        return \Inertia\Inertia::render('NfcDevices/Index', [
            'devices' => $devices,
            'activeSessions' => $activeSessions
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mac_address' => 'required|string|max:255|unique:nfc_devices',
            'ip_address' => 'nullable|string|max:255',
            'device_type' => 'required|in:treadmill,turnstile,entry',
        ]);

        \App\Models\NfcDevice::create($validated);

        return back()->with('success', 'NFC Qurilma qo\'shildi.');
    }

    public function update(Request $request, \App\Models\NfcDevice $nfc_device)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'mac_address' => 'required|string|max:255|unique:nfc_devices,mac_address,' . $nfc_device->id,
            'ip_address' => 'nullable|string|max:255',
            'device_type' => 'required|in:treadmill,turnstile,entry',
        ]);

        $nfc_device->update($validated);

        return back()->with('success', 'NFC Qurilma yangilandi.');
    }

    public function destroy(\App\Models\NfcDevice $nfc_device)
    {
        $nfc_device->delete();
        return back()->with('success', 'NFC Qurilma o\'chirildi.');
    }
}
