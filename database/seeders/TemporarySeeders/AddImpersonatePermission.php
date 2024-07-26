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
        $admissionsOfficerRole = Role::where('name', 'Admissions Officer')->first();
        $admissionsOfficerRole->givePermissionTo([
            'impersonate',
        ]);
        $financialManagerRole = Role::where('name', 'Financial Manager')->first();
        $financialManagerRole->givePermissionTo([
            'impersonate',
        ]);
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'impersonate',
        ]);
        $principalRole = Role::where('name', 'Principal')->first();
        $principalRole->givePermissionTo([
            'impersonate',
        ]);
    }
}
