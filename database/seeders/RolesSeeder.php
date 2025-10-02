<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;




class RolesSeeder extends Seeder
{
    
    public function run() {
        if (Role::where('name', 'Admin')->exists()) {
            return; // Los roles ya existen, no hacer nada
        }
        $adminRole = Role::create(['name' => 'Admin']);
        $cashierRole = Role::create(['name' => 'Cajero']);
        $waiterRole = Role::create(['name' => 'Mesero']);
        $cookRole = Role::create(['name' => 'Cocinero']);

        Permission::create(['name' => 'manage sales']);

        $adminRole->givePermissionTo('manage sales');
    }
}
