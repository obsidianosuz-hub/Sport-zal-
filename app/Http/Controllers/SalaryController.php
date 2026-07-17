<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalaryController extends Controller
{
    public function index()
    {
        $salaries = \App\Models\Salary::with('user:id,name,email')->orderBy('payment_date', 'desc')->get();
        // Get all employees (users with roles trainer, manager, cook) with their salaries and roles
        $currentMonth = date('Y-m');

        $employees = \App\Models\User::with(['employee', 'roles'])
            ->withSum(['salaries as paid_this_month' => function ($query) use ($currentMonth) {
                $query->where('month', $currentMonth);
            }], 'amount')
            ->role(['trainer', 'manager', 'cook'])
            ->select('id', 'name', 'is_active')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'is_active' => (bool) $user->is_active,
                    'role' => $user->roles->first() ? $user->roles->first()->name : 'Xodim',
                    'salary' => $user->employee ? $user->employee->salary : 0,
                    'paid_this_month' => $user->paid_this_month ?? 0,
                ];
            });
        
        return \Inertia\Inertia::render('Salaries/Index', [
            'salaries' => $salaries,
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'payment_date' => 'required|date',
            'note' => 'nullable|string'
        ]);

        if (auth()->user()->hasRole('manager') && $request->user_id == auth()->id()) {
            return back()->withErrors(['user_id' => 'Siz o\'zingizga oylik to\'lay olmaysiz!']);
        }

        \App\Models\Salary::create($request->all());

        return redirect()->back();
    }

    public function update(Request $request, string $id)
    {
        $salary = \App\Models\Salary::findOrFail($id);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'month' => 'required|string',
            'payment_date' => 'required|date',
            'note' => 'nullable|string'
        ]);

        if (auth()->user()->hasRole('manager') && $request->user_id == auth()->id()) {
            return back()->withErrors(['user_id' => 'Siz o\'zingizga oylik to\'lay olmaysiz!']);
        }

        $salary->update($request->all());

        return redirect()->back();
    }

    public function destroy(string $id)
    {
        $salary = \App\Models\Salary::findOrFail($id);
        $salary->delete();

        return redirect()->back();
    }

    public function updateEmployeeSalary(Request $request, \App\Models\User $user)
    {
        if (auth()->user()->hasRole('manager') && $user->id == auth()->id()) {
            return back()->withErrors(['salary' => 'Siz o\'zingizning oylik stavkangizni o\'zgartira olmaysiz!']);
        }

        $request->validate([
            'salary' => 'required|numeric|min:0',
        ]);

        $employee = \App\Models\Employee::firstOrCreate(
            ['user_id' => $user->id],
            ['salary' => 0, 'bonus' => 0]
        );

        $employee->salary = $request->salary;
        $employee->save();

        return redirect()->back();
    }

    public function toggleEmployeeActive(\App\Models\User $user)
    {
        if (auth()->user()->hasRole('manager') && $user->id == auth()->id()) {
            return back()->withErrors(['salary' => 'Siz o\'zingizni holatingizni o\'zgartira olmaysiz!']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return redirect()->back();
    }
}
