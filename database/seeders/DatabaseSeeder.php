<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder{

    public function run(): void{
        
        $this->call([
            SettingSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            SegmentSeeder::class,
            CustomerSeeder::class, // only for test
            SupplierSeeder::class, // only for test
            ProductSeeder::class, // only for test
        ]);

        $role = Role::first();

        \App\Models\Employee::create([
            'name' => 'المالك',
            'email' => 'owner@test.com',
            'phone' => '01234567890',
            'password' => Hash::make('123456'),
            'role_id' => $role->id
        ]);


        \App\Models\FundAccount::create([
            'balance' => 0
        ]);
    }
}