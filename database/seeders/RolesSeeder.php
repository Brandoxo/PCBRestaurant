<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;




class RolesSeeder extends Seeder
{
    
    public function run() {
        
        $roles = [
            'Admin',
            'Cajero',
            'Mesero',
            'Cocinero',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Crear permisos
        $permissions = [
            'manage sales',
            'generate cutoff',
            'view cutoff',
            'create products',
            'edit products',
            'delete products',
            'create categories',
            'edit categories',
            'delete categories',
            'create tables',
            'edit tables',
            'delete tables',
            'create orders',
            'edit orders',
            'cancel orders',
            'hide orders',
            'create sales',

            'manage users',
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Asignar permisos a roles
        Role::findByName('Admin')->givePermissionTo(Permission::all());
        Role::findByName('Cajero')->givePermissionTo(['manage sales', 'generate cutoff', 'create orders', 'cancel orders', 'create sales', 'view cutoff']);
        Role::findByName('Mesero')->givePermissionTo(['create orders', 'cancel orders']);
        Role::findByName('Cocinero')->givePermissionTo(['create orders']);
    }
}
