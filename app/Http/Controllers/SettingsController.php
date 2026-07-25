<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return \Inertia\Inertia::render('Settings/Index', [
            'users' => \App\Models\User::with(['roles', 'permissions'])->get(),
            'roles' => \Spatie\Permission\Models\Role::all(),
            'permissions' => \Spatie\Permission\Models\Permission::all(),
        ]);
    }

    public function updateUi(Request $request)
    {
        $request->validate([
            'language' => 'required|string',
            'theme' => 'required|string',
            'scale' => 'required|string',
        ]);

        $request->user()->update([
            'ui_settings' => [
                'language' => $request->language,
                'theme' => $request->theme,
                'scale' => $request->scale,
            ]
        ]);

        return back();
    }

    public function updatePin(Request $request)
    {
        $request->validate([
            'pin_code' => 'required|string'
        ]);

        $request->user()->update([
            'pin_code' => $request->pin_code,
            'password' => \Illuminate\Support\Facades\Hash::make($request->pin_code),
        ]);

        return back();
    }
}
