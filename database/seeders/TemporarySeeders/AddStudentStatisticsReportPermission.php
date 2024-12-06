<?php

namespace Database\Seeders\TemporarySeeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddStudentStatisticsReportPermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(['name' => 'student-statistics-report-menu-access']);
        $superAdminRole = Role::whereName('Super Admin')->first();
        $superAdminRole->givePermissionTo([
            'student-statistics-report-menu-access',
        ]);
        $principalRole = Role::whereName('Principal')->first();
        $principalRole->givePermissionTo([
            'student-statistics-report-menu-access',
        ]);
        $admissionsOfficerRole = Role::whereName('Admissions Officer')->first();
        $admissionsOfficerRole->givePermissionTo([
            'student-statistics-report-menu-access',
        ]);
    }
}
