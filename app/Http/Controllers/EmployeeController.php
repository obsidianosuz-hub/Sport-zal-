<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = \App\Models\User::role(['trainer', 'manager', 'admin', 'cook'])->with('roles', 'permissions')->get();
        return \Inertia\Inertia::render('Employees/Index', [
            'employees' => $users
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string',
            'role'     => 'required|in:admin,manager,trainer,cook,cashier',
            'pin_code' => 'nullable|digits:4',
        ]);

        $user = \App\Models\User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => bcrypt($request->password),
            'pin_code' => $request->pin_code,
        ]);

        $user->assignRole($request->role);

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
        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:20',
            'password' => 'nullable|string',
            'role'     => 'required|in:admin,manager,trainer,cook,cashier',
            'pin_code' => 'nullable|digits:4',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->pin_code = $request->pin_code;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Update role
        $user->syncRoles([$request->role]);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->back();
    }

    public function updatePermissions(Request $request, string $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $request->validate([
            'permissions' => 'array',
        ]);

        $user->syncPermissions($request->permissions);

        return redirect()->back();
    }
}
