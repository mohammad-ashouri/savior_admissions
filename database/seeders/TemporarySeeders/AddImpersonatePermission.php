<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddImpersonatePermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'impersonate']);
        $admissionsOfficerRole = Role::whereName('Admissions Officer')->first();
        $admissionsOfficerRole->givePermissionTo([
            'impersonate',
        ]);
        $financialManagerRole = Role::whereName('Financial Manager')->first();
        $financialManagerRole->givePermissionTo([
            'impersonate',
        ]);
        $superAdminRole = Role::whereName('Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'impersonate',
        ]);
        $principalRole = Role::whereName('Principal')->first();
        $principalRole->givePermissionTo([
            'impersonate',
        ]);
    }
}
