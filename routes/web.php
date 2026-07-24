<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/click-payments', function () {
        return \Inertia\Inertia::render('ClickPayments');
    })->name('click.payments');

    // Modullar uchun yo'nalishlar
    Route::resource('/employees', \App\Http\Controllers\EmployeeController::class);
    Route::post('/employees/{user}/permissions', [\App\Http\Controllers\EmployeeController::class, 'updatePermissions'])->name('employees.permissions');
    Route::resource('/salaries', \App\Http\Controllers\SalaryController::class);
    Route::post('/salaries/employee/{user}/salary', [\App\Http\Controllers\SalaryController::class, 'updateEmployeeSalary'])->name('salaries.employee.salary');
    Route::post('/salaries/employee/{user}/toggle-active', [\App\Http\Controllers\SalaryController::class, 'toggleEmployeeActive'])->name('salaries.employee.toggle-active');
    Route::get('/sales', [\App\Http\Controllers\SaleController::class, 'index'])->name('sales.index');
    Route::delete('/sales/all', [\App\Http\Controllers\SaleController::class, 'destroyAll'])->name('sales.destroyAll');
    Route::delete('/sales/{sale}', [\App\Http\Controllers\SaleController::class, 'destroy'])->name('sales.destroy');
    Route::resource('/clients', \App\Http\Controllers\ClientController::class);
    Route::get('/cashier', [\App\Http\Controllers\CashierController::class, 'index'])->name('cashier.index');
    Route::resource('/kitchen', \App\Http\Controllers\KitchenController::class);
    Route::post('/inventory/replenish', [\App\Http\Controllers\InventoryController::class, 'replenish'])->name('inventory.replenish');
    Route::delete('/inventory/all', [\App\Http\Controllers\InventoryController::class, 'destroyAll'])->name('inventory.destroyAll');
    Route::delete('/inventory/history/{id}', [\App\Http\Controllers\InventoryController::class, 'destroyHistory'])->name('inventory.history.destroy');
    Route::get('/inventory/history', [\App\Http\Controllers\InventoryController::class, 'history'])->name('inventory.history');
    Route::resource('/inventory', \App\Http\Controllers\InventoryController::class);
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/ui', [\App\Http\Controllers\SettingsController::class, 'updateUi'])->name('settings.ui');
    Route::put('/settings/pin', [\App\Http\Controllers\SettingsController::class, 'updatePin'])->name('settings.pin');
});

require __DIR__.'/auth.php';
