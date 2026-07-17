<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $notifications = [];

        if ($request->user()) {
            // Kam qolgan mahsulotlar
            $lowStockProducts = \App\Models\Product::where('stock', '<=', 5)->count();
            if ($lowStockProducts > 0) {
                $notifications[] = [
                    'id' => 'low_stock',
                    'title' => 'Kam qolgan mahsulotlar',
                    'message' => "Omborda $lowStockProducts ta mahsulot kam qoldi.",
                    'route' => 'inventory.index',
                    'icon' => 'cube',
                    'color' => 'text-yellow-400 bg-yellow-400/20'
                ];
            }

            // Obunasi tugayotgan mijozlar
            $expiringClients = \App\Models\Client::whereDate('subscription_expires_at', '<=', now()->addDays(3))->count();
            if ($expiringClients > 0) {
                $notifications[] = [
                    'id' => 'expiring_clients',
                    'title' => 'Muddati tugayotgan obunalar',
                    'message' => "$expiringClients ta mijozning obunasi tugamoqda yoki tugagan.",
                    'route' => 'clients.index',
                    'icon' => 'user',
                    'color' => 'text-red-400 bg-red-400/20'
                ];
            }
        }

        $userData = null;
        if ($request->user()) {
            $userData = $request->user()->toArray();
            $userData['roles'] = $request->user()->getRoleNames();
            $userData['permissions'] = $request->user()->getAllPermissions()->pluck('name');
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $userData,
            ],
            'notifications' => $notifications,
        ];
    }
}
