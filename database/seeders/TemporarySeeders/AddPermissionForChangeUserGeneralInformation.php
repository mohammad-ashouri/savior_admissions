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
        $admissionsOfficerRole = Role::whereName('Admissions Officer')->first();
        $admissionsOfficerRole->givePermissionTo([
            'change_user_general_information',
        ]);
        $interviewerRole = Role::whereName('Interviewer')->first();
        $interviewerRole->givePermissionTo([
            'change_user_general_information',
        ]);
        $superAdminRole = Role::whereName('Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'change_user_general_information',
        ]);
        $principalRole = Role::whereName('Principal')->first();
        $principalRole->givePermissionTo([
            'change_user_general_information',
        ]);
    }
}
