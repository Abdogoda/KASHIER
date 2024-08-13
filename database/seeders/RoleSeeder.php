<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder{
    
    public function run(): void{
        $role = Role::create(['name' => 'مالك']);
        Role::create(['name' => 'مدير']);
        Role::create(['name' => 'بائع']);

        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            DB::table('permission_role')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission->id
            ]);
        }
    }
}