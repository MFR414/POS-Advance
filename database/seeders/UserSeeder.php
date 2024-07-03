<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function() {
            $superAdmin = User::where('name', 'Super Admin')->first();
            if(empty($superAdmin)){
                $superAdmin = User::create([
                    'name' => 'Super Admin',
                    'username' => 'SuperAdmin123',
                    'address' => 'testing street 123, testing, testing city',
                    'phone_number' => '123345689659',
                    'email' => 'super_admin.pos@mail.id',
                    'password' => bcrypt('password'),
                ]);

                $superAdmin->assignRole('Super Admin');
            }

            $admin = User::where('name', 'Admin')->first();
            if(empty($admin)){
                $admin = User::create([
                    'name' => 'Admin',
                    'username' => 'Admin123',
                    'address' => 'testing street 123, testing, testing city',
                    'phone_number' => '123345689852',
                    'email' => 'admin.pos@mail.id',
                    'password' => bcrypt('password')
                ]);

                $admin->assignRole('Admin');
            }

            $owner = User::where('name', 'Owner')->first();
            if(empty($owner)){
                $owner = User::create([
                    'name' => 'Owner',
                    'username' => 'Owner123',
                    'address' => 'testing street 123, testing, testing city',
                    'phone_number' => '123345896758',
                    'email' => 'owner.pos@mail.id',
                    'password' => bcrypt('password')
                ]);

                $owner->assignRole('Owner User');
            }

            $cashier = User::where('name', 'Cashier')->first();
            if(empty($cashier)){
                $cashier = User::create([
                    'name' => 'Cashier',
                    'username' => 'Cashier123',
                    'address' => 'testing street 123, testing, testing city',
                    'phone_number' => '123345812358',
                    'email' => 'Cashier.pos@mail.id',
                    'password' => bcrypt('password')
                ]);

                $cashier->assignRole('Cashier User');
            }
        });
    }
}
