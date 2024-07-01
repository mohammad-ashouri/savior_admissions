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
        $admissionsOfficerRole=Role::where('name','Admissions Officer')->first();
        $admissionsOfficerRole->givePermissionTo([
            'change-users-password'
        ]);
        $interviewerRole=Role::where('name','Interviewer')->first();
        $interviewerRole->givePermissionTo([
            'change-users-password'
        ]);
        $superAdminRole=Role::where('name','Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'change-users-password'
        ]);
        $principalRole=Role::where('name','Principal')->first();
        $principalRole->givePermissionTo([
            'change-users-password'
        ]);
    }
}
