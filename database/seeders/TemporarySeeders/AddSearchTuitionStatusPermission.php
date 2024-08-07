<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddSearchTuitionStatusPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'search-tuition-status']);
        $financialManagerRole=Role::whereName('Financial Manager')->first();
        $financialManagerRole->givePermissionTo([
            'search-tuition-status'
        ]);
        $superAdminRole = Role::whereName('Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'search-tuition-status',
        ]);
        $principalRole = Role::whereName('Principal')->first();
        $principalRole->givePermissionTo([
            'search-tuition-status',
        ]);
    }
}
