<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPendingUserApprovalsPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'pending-user-approvals.view']);
        Permission::create(['name' => 'pending-user-approvals.approve']);

        $superAdminRole = Role::whereName('Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'pending-user-approvals.view',
            'pending-user-approvals.approve',
        ]);
        $admissionsOfficerRole = Role::whereName('Admissions Officer')->first();
        $admissionsOfficerRole->givePermissionTo([
            'pending-user-approvals.view',
            'pending-user-approvals.approve',
        ]);
    }
}
