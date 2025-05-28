<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddDropoutPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'dropout']);
        $superAdminRole = Role::whereName('Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'dropout'
        ]);
        $principalRole = Role::whereName('Principal')->first();
        $principalRole->givePermissionTo([
            'dropout'
        ]);
    }
}
