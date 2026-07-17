<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Admin/Manager login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Noto\'g\'ri email yoki parol kiritildi.',
                'errors' => [
                    'email' => ['Kiritilgan ma\'lumotlar xato.']
                ]
            ], 422);
        }

        // Faqat admin va managerlar API orqali kira oladi deb faraz qilamiz
        if (!$user->hasRole('admin') && !$user->hasRole('manager')) {
            return response()->json([
                'message' => 'Sizda API orqali kirish huquqi yo\'q.'
            ], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Muvaffaqiyatli tizimga kirdingiz.',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames()
            ]
        ]);
    }
}
