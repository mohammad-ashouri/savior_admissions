<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddChangeUsersPasswordPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'change-users-password']);
        $admissionsOfficerRole=Role::whereName('Admissions Officer')->first();
        $admissionsOfficerRole->givePermissionTo([
            'change-users-password'
        ]);
        $interviewerRole=Role::whereName('Interviewer')->first();
        $interviewerRole->givePermissionTo([
            'change-users-password'
        ]);
        $superAdminRole=Role::whereName('Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'change-users-password'
        ]);
        $principalRole=Role::whereName('Principal')->first();
        $principalRole->givePermissionTo([
            'change-users-password'
        ]);
    }
}
