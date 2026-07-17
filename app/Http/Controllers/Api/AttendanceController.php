<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Visit;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Accept an ID/FaceID token, check if the member's subscription is valid, and record the entry.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id'
        ]);

        $client = Client::findOrFail($request->client_id);
        $now = Carbon::now();

        // Expired Subscription tekshiruvi
        if (!$client->end_date || Carbon::parse($client->end_date)->isPast()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Expired Subscription',
                'details' => 'Sizning abonementingiz muddati tugagan. Iltimos, to\'lovni amalga oshiring.'
            ], 403);
        }

        // Bugun allaqachon tashrif buyurganmi yoki yo'qmi tekshirish
        $alreadyVisited = Visit::where('client_id', $client->id)
            ->whereDate('date', $now->toDateString())
            ->exists();

        if ($alreadyVisited) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Siz bugun allaqachon tashrif buyurgansiz.'
            ], 422);
        }

        // Tashrifni qayd qilish
        $visit = Visit::create([
            'client_id' => $client->id,
            'date' => $now->toDateString(),
            'time' => $now->toTimeString()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Xush kelibsiz! Tashrif muvaffaqiyatli qayd etildi.',
            'data' => [
                'client_name' => $client->name,
                'visit_time' => $visit->time,
                'end_date' => $client->end_date
            ]
        ], 200);
    }
}
