<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Carbon\Carbon;

class MemberController extends Controller
{
    /**
     * Fetch all members (with search/filter functionality).
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Client::query();

        // Qidiruv (ism, telefon yoki id bo'yicha)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('id', $search);
            });
        }

        // Filtrlash (holati bo'yicha: active, expired)
        if ($request->filled('status')) {
            $now = Carbon::now();
            if ($request->status === 'active') {
                $query->where('end_date', '>=', $now);
            } elseif ($request->status === 'expired') {
                $query->where('end_date', '<', $now);
            }
        }

        $members = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'message' => 'Mijozlar ro\'yxati',
            'data' => $members
        ]);
    }

    /**
     * Manually add or extend a membership for a member.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateSubscription(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'months' => 'required|integer|min:1'
        ]);

        $client = Client::findOrFail($request->client_id);
        
        $currentEndDate = $client->end_date ? Carbon::parse($client->end_date) : Carbon::now();
        
        // Agar muddati o'tib ketgan bo'lsa, bugundan boshlab qo'shamiz
        if ($currentEndDate->isPast()) {
            $currentEndDate = Carbon::now();
        }

        $newEndDate = $currentEndDate->addMonths($request->months);

        $client->update([
            'end_date' => $newEndDate,
            'monthly_fee' => $request->amount,
            'status' => 'active' // Optional status column if exists
        ]);

        return response()->json([
            'message' => 'Mijozning abonementi muvaffaqiyatli uzaytirildi.',
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'new_end_date' => $newEndDate->format('Y-m-d')
            ]
        ]);
    }
}
