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
        $role1 = Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
        ]);

        $role2 = Role::create([
            'name' => 'observador',
            'guard_name' => 'api',
        ]);

        $role3 = Role::create([
            'name' => 'analista',
            'guard_name' => 'api',
        ]);

        $role1->syncPermissions(config('permissions'));
        $role2->syncPermissions(config('permissions'));
    }
}
