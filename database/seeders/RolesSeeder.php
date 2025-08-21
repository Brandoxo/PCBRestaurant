<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;



$user = User::find(1);


$user->assignRole('Admin');

class RolesSeeder extends Seeder
{
    
    public function run() {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Cajero']);
        Role::create(['name' => 'Mesero']);
        Role::create(['name' => 'Cocinero']);
    }
}
