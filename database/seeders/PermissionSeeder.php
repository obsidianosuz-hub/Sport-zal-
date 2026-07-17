<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        $routes = ['dashboard', 'employees.index', 'salaries.index', 'clients.index', 'kitchen.index', 'sales.index', 'inventory.index', 'settings.index'];
        foreach($routes as $route) {
            Permission::firstOrCreate(['name' => $route]);
        }
    }
}
