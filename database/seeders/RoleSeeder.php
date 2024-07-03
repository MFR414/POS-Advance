<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        if(empty($adminRole)){
            $adminRole = Role::create([
                'name' => 'Admin',
            ]);
        }

        $superAdminRole = Role::where('name', 'Super Admin')->first();
        if(empty($superAdminRole)) {
            $superAdminRole = Role::create([
                'name' => 'Super Admin'
            ]);
        }
        
        $ownerRole = Role::where('name', 'Owner')->first();
        if(empty($ownerRole)) {
            $ownerRole = Role::create([
                'name' => 'Owner User',
            ]);
        }

        $cashierRole = Role::where('name', 'Cashier')->first();
        if(empty($cashierRole)){
            $cashierRole = Role::create([
                'name' => 'Cashier User',
            ]);
        }
    }
}
