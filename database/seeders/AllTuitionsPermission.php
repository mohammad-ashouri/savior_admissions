<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AllTuitionsPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'all-tuitions-index']);
        $role = Role::firstOrCreate(['name' => 'Financial Manager']);
        $role->givePermissionTo([
            'all-tuitions-index',
        ]);
        $role = Role::firstOrCreate(['name' => 'Super Admin']);
        $role->givePermissionTo([
            'all-tuitions-index',
        ]);
        $role = Role::firstOrCreate(['name' => 'Principal']);
        $role->givePermissionTo([
            'all-tuitions-index',
        ]);
    }
}
