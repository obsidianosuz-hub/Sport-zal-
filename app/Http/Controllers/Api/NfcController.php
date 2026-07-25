<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NfcController extends Controller
{
    public function scan(Request $request)
    {
        $request->validate([
            'device_mac' => 'required|string',
            'card_uid' => 'required|string',
        ]);

        $device = \App\Models\NfcDevice::where('mac_address', $request->device_mac)->first();
        if (!$device) {
            return response()->json([
                'status' => 'error',
                'action' => 'DENY',
                'message' => 'Noma\'lum qurilma.'
            ], 403);
        }

        $card = \App\Models\NfcCard::where('uid', $request->card_uid)
            ->where('is_active', true)
            ->with('client')
            ->first();

        if (!$card || !$card->client_id) {
            return response()->json([
                'status' => 'error',
                'action' => 'DENY',
                'message' => 'Karta tizimda topilmadi yoki mijozga biriktirilmagan.'
            ], 403);
        }

        $client = $card->client;

        // Check if subscription is active
        if (!$client->subscription_expires_at || $client->subscription_expires_at->isPast()) {
            return response()->json([
                'status' => 'error',
                'action' => 'DENY',
                'message' => 'Abonement muddati tugagan.'
            ], 403);
        }

        // Check for active session
        $activeSession = \App\Models\TreadmillSession::where('client_id', $client->id)
            ->whereNull('ended_at')
            ->first();

        if ($activeSession) {
            // End session
            $duration = $activeSession->started_at->diffInMinutes(now());
            // Rough energy calculation: ~10 kcal per minute
            $energy = $duration * 10; 

            $activeSession->update([
                'ended_at' => now(),
                'duration_minutes' => $duration,
                'energy_kcal' => $energy
            ]);

            $device->update(['status' => 'online']);

            return response()->json([
                'status' => 'success',
                'action' => 'DISABLE_TREADMILL',
                'message' => "Seans yakunlandi. Vaqt: {$duration} daqiqa."
            ]);
        }

        // Start new session
        $session = \App\Models\TreadmillSession::create([
            'client_id' => $client->id,
            'nfc_device_id' => $device->id,
            'started_at' => now(),
        ]);

        $device->update(['status' => 'in_use']);

        return response()->json([
            'status' => 'success',
            'action' => 'ENABLE_TREADMILL',
            'session_id' => $session->id,
            'client_name' => $client->name,
            'message' => 'Xush kelibsiz! Yo\'lakcha faollashdi.'
        ]);
    }

    public function ping(Request $request)
    {
        $request->validate([
            'device_mac' => 'required|string',
        ]);

        $device = \App\Models\NfcDevice::where('mac_address', $request->device_mac)->first();
        
        if ($device) {
            $device->update(['last_ping_at' => now(), 'status' => $device->status === 'in_use' ? 'in_use' : 'online']);
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'Qurilma topilmadi'], 404);
    }
}
