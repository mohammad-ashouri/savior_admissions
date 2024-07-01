<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermissionForChangeUserGeneralInformation extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'change_user_general_information']);
        $admissionsOfficerRole = Role::where('name', 'Admissions Officer')->first();
        $admissionsOfficerRole->givePermissionTo([
            'change_user_general_information',
        ]);
        $interviewerRole = Role::where('name', 'Interviewer')->first();
        $interviewerRole->givePermissionTo([
            'change_user_general_information',
        ]);
        $superAdminRole = Role::where('name', 'Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'change_user_general_information',
        ]);
        $principalRole = Role::where('name', 'Principal')->first();
        $principalRole->givePermissionTo([
            'change_user_general_information',
        ]);
    }
}
